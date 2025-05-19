<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Exports\DisbursementExport;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Restaurant;
use App\Models\Disbursement;
use App\Models\DisbursementDetails;
use App\Models\RestaurantWallet;
use App\Models\WithdrawRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class RestaurantDisbursementController extends Controller
{
    public function list(Request $request)
    {
        $status = $request->status??'all';
        $disbursements = Disbursement::
        when($status!='all', function($q) use($status){
                return $q->where('status',$status);
        })
        ->where('created_for','restaurant')
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.restaurant-disbursement.index', compact('disbursements','status'));
    }

    public function view(Request $request,$id)
    {
        $key = explode(' ', $request['search']);
        $restaurant_id = $request->query('restaurant_id', 'all');
        $payment_method_id = $request->query('payment_method_id', 'all');
        $disbursement = Disbursement::findOrFail($id);
        $disbursements=DisbursementDetails::with('restaurant','withdraw_method')->where(['disbursement_id'=>$id])
            ->when(isset($key) , function($q) use($key){
                $q->whereHas('restaurant', function ($q) use($key){
                    $q->where(function($query)use ($key){
                        $query->orWhereHas('vendor', function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('f_name', 'like', "%{$value}%")
                                    ->orWhere('l_name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%")
                                    ->orWhere('phone', 'like', "%{$value}%");
                            }
                        })
                            ->where(function ($q) use ($key) {
                                foreach ($key as $value) {
                                    $q->orWhere('name', 'like', "%{$value}%")
                                        ->orWhere('email', 'like', "%{$value}%")
                                        ->orWhere('phone', 'like', "%{$value}%");
                                }
                            });
                    });
                });
            })
            ->when((isset($restaurant_id) && is_numeric($restaurant_id)), function ($query) use ($restaurant_id){
                $query->where('restaurant_id', $restaurant_id);
            })
            ->when((isset($payment_method_id) && is_numeric($payment_method_id)), function ($query) use ($payment_method_id){
                $query->whereHas('withdraw_method', function ($q) use($payment_method_id){
                    return $q->where('withdrawal_method_id', $payment_method_id);
                });
            })
            ->latest();
        $restaurant_ids = json_encode($disbursements->pluck('restaurant_id')->toArray());
        $disbursement_restaurants = $disbursements->paginate(config('default_pagination'));
        return view('admin-views.restaurant-disbursement.view', compact('disbursement','disbursement_restaurants','restaurant_ids','restaurant_id','payment_method_id'));
    }
    public function export(Request $request,$id, $type = 'excel')
    {
        $key = explode(' ', $request['search']);
        $restaurant_id = $request->query('restaurant_id', 'all');
        $payment_method_id = $request->query('payment_method_id', 'all');
        $disbursement = Disbursement::findOrFail($id);
        $disbursements=DisbursementDetails::where(['disbursement_id'=>$id])
            ->when(isset($key) , function($q) use($key){
                $q->whereHas('restaurant', function ($q) use($key){
                    $q->where(function($query)use ($key){
                        $query->orWhereHas('vendor', function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('f_name', 'like', "%{$value}%")
                                    ->orWhere('l_name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%")
                                    ->orWhere('phone', 'like', "%{$value}%");
                            }
                        })
                            ->where(function ($q) use ($key) {
                                foreach ($key as $value) {
                                    $q->orWhere('name', 'like', "%{$value}%")
                                        ->orWhere('email', 'like', "%{$value}%")
                                        ->orWhere('phone', 'like', "%{$value}%");
                                }
                            });
                    });
                });
            })
            ->when((isset($restaurant_id) && is_numeric($restaurant_id)), function ($query) use ($restaurant_id){
                $query->where('restaurant_id', $restaurant_id);
            })
            ->when((isset($payment_method_id) && is_numeric($payment_method_id)), function ($query) use ($payment_method_id){
                $query->whereHas('withdraw_method', function ($q) use($payment_method_id){
                    return $q->where('withdrawal_method_id', $payment_method_id);
                });
            })
            ->latest()->get();
        $data=[
            'type'=>'restaurant',
            'disbursement' =>$disbursement,
            'disbursements' =>$disbursements,
        ];
        if($type == 'pdf'){
            $mpdf_view = View::make('admin-views.restaurant-disbursement.pdf', compact('disbursement','disbursements')
            );
            Helpers::gen_mpdf(view: $mpdf_view,file_prefix: 'Disbursement',file_postfix: $id);
        }elseif($type == 'csv'){
            return Excel::download(new DisbursementExport($data), 'Disbursement.csv');
        }
        return Excel::download(new DisbursementExport($data), 'Disbursement.xlsx');
    }

    public function status(Request $request)
    {
        $disbursements=DisbursementDetails::where(['disbursement_id'=>$request->disbursement_id])->whereIn('restaurant_id',$request->restaurant_ids)->get();

        foreach ($disbursements as $disbursement){
            $wallet=  RestaurantWallet::where('vendor_id',$disbursement->restaurant->vendor_id)->first();

            if ( (string) $wallet->total_earning <  (string) ($wallet->total_withdrawn + $wallet->pending_withdraw) ) {
                return response()->json([
                    'status' => 'error',
                    'message'=> translate('messages.Blalnce_mismatched_total_earning_is_too_low_for').' '.$disbursement->restaurant?->name,
                ]);
            }

            if($request->status == 'completed'){
                if($disbursement->status != 'completed') {
                    $withdraw = new WithdrawRequest();
                    $withdraw->vendor_id = $disbursement->restaurant?->vendor?->id;
                    $withdraw->amount = $disbursement['disbursement_amount'];
                    $withdraw->withdrawal_method_id = $disbursement['payment_method'];
                    $withdraw->withdrawal_method_fields = $disbursement?->withdraw_method?->method_fields;
                    $withdraw->approved = 1;
                    $withdraw->transaction_note =$disbursement->id;
                    $withdraw->type = 'disbursement';

                    if($disbursement->status== 'canceled'){
                        $wallet->increment('total_withdrawn', $disbursement['disbursement_amount']);
                        } else{
                            $wallet->decrement('pending_withdraw', $disbursement['disbursement_amount']);
                            $wallet->increment('total_withdrawn', $disbursement['disbursement_amount']);
                        }
                    $withdraw->save();
                }
            }elseif ($request->status == 'canceled'){
                if($disbursement->status == 'completed'){
                    return response()->json([
                        'status' => 'error',
                        'message'=> translate('messages.can_not_cancel_completed_disbursement_,_uncheck_completed_disbursements')
                    ]);
                }

                $wallet->decrement('pending_withdraw', $disbursement['disbursement_amount']);
            }elseif ($request->status == 'pending'){
                if($disbursement->status == 'canceled'){
                    return response()->json([
                        'status' => 'error',
                        'message'=> translate('messages.can_not_revert_canceled_disbursement_,_uncheck_canceled_disbursements')
                    ]);
                }
                if($disbursement->status == 'completed'){
                    $withdraw = WithdrawRequest::where('transaction_note',$disbursement->id)->where('vendor_id', $disbursement->restaurant?->vendor?->id)->first();
                    if ($withdraw){
                        $withdraw->delete();
                    }
                }
                $wallet->decrement('total_withdrawn', $disbursement['disbursement_amount']);
                $wallet->increment('pending_withdraw', $disbursement['disbursement_amount']);
            }
            $disbursement->status = $request->status;
            $disbursement->save();
        }

        self::check_status($request->disbursement_id);

        return response()->json([
            'status' => 'success',
            'message'=> translate('messages.status_updated')
        ]);
    }

    public function statusById($id,$status)
    {
        $disbursement=DisbursementDetails::find($id);
        $wallet=  RestaurantWallet::where('vendor_id',$disbursement->restaurant->vendor_id)->first();
        if ((string) $wallet->total_earning <  (string) ($wallet->total_withdrawn + $wallet->pending_withdraw) ) {
            Toastr::error(translate('messages.Blalnce_mismatched_total_earning_is_too_low'));
            return back();

        }

        if($status == 'completed'){
            $withdraw = new WithdrawRequest();
            $withdraw->vendor_id = $disbursement->restaurant?->vendor?->id;
            $withdraw->amount = $disbursement['disbursement_amount'];
            $withdraw->withdrawal_method_id = $disbursement['payment_method'];
            $withdraw->withdrawal_method_fields = $disbursement->withdraw_method?->method_fields;
            $withdraw->approved = 1;
            $withdraw->transaction_note = $id;
            $withdraw->type = 'disbursement';


            if($disbursement->status== 'canceled'){
                $wallet->increment('total_withdrawn', $disbursement['disbursement_amount']);
                } else{
                    $wallet->decrement('pending_withdraw', $disbursement['disbursement_amount']);
                    $wallet->increment('total_withdrawn', $disbursement['disbursement_amount']);
                }

            $withdraw->save();
        }elseif ($status == 'canceled'){
            if($disbursement->status == 'completed'){
                Toastr::error(translate('messages.can_not_cancel_completed_disbursement_,_uncheck_completed_disbursements'));
                return back();
            }
            $wallet->decrement('pending_withdraw', $disbursement['disbursement_amount']);
        }elseif ($status == 'pending'){
            if($disbursement->status == 'completed'){
                $withdraw = WithdrawRequest::where('transaction_note',$id)->where('vendor_id', $disbursement->restaurant->vendor_id)->first();
                if ($withdraw){
                    $withdraw->delete();
                }
            }
            $wallet->decrement('total_withdrawn', $disbursement['disbursement_amount']);
            $wallet->increment('pending_withdraw', $disbursement['disbursement_amount']);
        }

        $disbursement->status = $status;
        $disbursement->save();

        self::check_status($disbursement->disbursement_id);

        Toastr::success(translate('messages.status_updated'));
        return back();
    }
    public function generate_disbursement()
    {
        $restaurants = Restaurant::all();
        $disbursement_details = [];
        $total_amount = 0;

        $disbursement = new Disbursement();
        $disbursement->id = 1000 + Disbursement::count() + 1;
        if (Disbursement::find($disbursement->id)) {
            $disbursement->id = Disbursement::orderBy('id', 'desc')->first()->id + 1;
        }
        $disbursement->title = 'Disbursement # '.$disbursement->id;
        $minimum_amount = BusinessSetting::where(['key' => 'restaurant_disbursement_min_amount'])->first()?->value;
        foreach ($restaurants as $restaurant){
            if(isset($restaurant->vendor->wallet)){

                $total_earning = $restaurant->vendor->wallet->total_earning ?? 0;
                $total_withdraw = ($restaurant->vendor->wallet->total_withdrawn ?? 0) + ($restaurant->vendor->wallet->pending_withdraw ?? 0);
                $total_cash_in_hand = $restaurant->vendor->wallet->collected_cash ?? 0;

                $disbursement_amount = ((string) $total_earning> (string) ($total_withdraw+$total_cash_in_hand))?(  ($total_earning - ($total_withdraw+$total_cash_in_hand))):0;

                if ($disbursement_amount > $minimum_amount && isset($restaurant->disbursement_method)){

                    $res_d = [
                        'disbursement_id' => $disbursement->id,
                        'restaurant_id' => $restaurant->id,
                        'disbursement_amount' => $disbursement_amount,
                        'payment_method' => $restaurant->disbursement_method->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $disbursement_details[] = $res_d;
                    $total_amount += $res_d['disbursement_amount'];

                    $restaurant->vendor->wallet->pending_withdraw = $restaurant->vendor->wallet->pending_withdraw + $disbursement_amount;
                    $restaurant->vendor->wallet->save();
                }
            }
        }

        if ($total_amount > 0){
            $disbursement->total_amount = $total_amount;
            $disbursement->created_for = 'restaurant';
            $disbursement->save();

            DisbursementDetails::insert($disbursement_details);
        }
        info("Restaurant-----Disbursement");
        return true;

    }

    public function check_status($id) {
        $disbursements = DisbursementDetails::where(['disbursement_id' => $id])->get();
        $statusCounts = $disbursements->countBy('status');

        $disbursement = Disbursement::find($id);

        if (isset($statusCounts['pending']) && ($statusCounts['pending'] == count($disbursements))) {
            $disbursement->status = 'pending';
        } elseif (isset($statusCounts['canceled']) && ($statusCounts['canceled'] == count($disbursements))) {
            $disbursement->status = 'canceled';
        } elseif (isset($statusCounts['completed']) && ($statusCounts['completed'] == count($disbursements))) {
            $disbursement->status = 'completed';
        } else {
            $disbursement->status = 'partially_completed';
        }

        return $disbursement->save();
    }
}
