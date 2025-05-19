<?php

namespace App\Http\Controllers\Admin;

use App\Models\Translation;
use Illuminate\Http\Request;
use App\Models\OrderCancelReason;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class OrderCancelReasonController extends Controller
{
    public function index()
    {
        $reasons = OrderCancelReason::latest()->paginate(config('default_pagination'));
        return view('admin-views.order.cancelation-reason', compact('reasons'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'reason'=>'required|max:255',
            'user_type' =>'required|max:50',
        ]);

        if($request->reason[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_reason_is_required'));
            return back();
            }

        $cancelReason = new OrderCancelReason();
        $cancelReason->reason = $request->reason[array_search('default', $request->lang)];
        $cancelReason->user_type=$request->user_type;
        $cancelReason->is_default= OrderCancelReason::where('user_type' , $request->user_type)->where('is_default' , 1)->doesntExist() ? 1 : 0;
        $cancelReason->created_at = now();
        $cancelReason->updated_at = now();
        $cancelReason->save();
        $data = [];
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->reason[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\OrderCancelReason',
                        'translationable_id' => $cancelReason->id,
                        'locale' => $key,
                        'key' => 'reason',
                        'value' => $cancelReason->reason,
                    ));
                }
            }else{
                if ($request->reason[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\OrderCancelReason',
                        'translationable_id' => $cancelReason->id,
                        'locale' => $key,
                        'key' => 'reason',
                        'value' => $request->reason[$index],
                    ));
                }
            }

        }
        Translation::insert($data);
        Toastr::success(translate('messages.order_cancellation_reason_added_successfully'));
        return back();
    }
    public function destroy($cancelReason)
    {
        $cancelReason = OrderCancelReason::findOrFail($cancelReason);
        if( $cancelReason->is_default == 1 ){
            Toastr::warning(translate('messages.You_can_not_delete_the_default_Order_Cancel_Reason'));
            return back();
        }
        $cancelReason?->translations()?->delete();
        $cancelReason?->delete();
        Toastr::success(translate('messages.order_cancellation_reason_deleted_successfully'));
        return back();
    }

    public function status(Request $request)
    {
        $cancelReason = OrderCancelReason::findOrFail($request->id);
        if( $cancelReason->is_default == 1 && $request->status == 0){
            Toastr::warning(translate('messages.You_can_not_disable_the_default_Order_Cancel_Reason'));
            return back();
        }
        $cancelReason->status = $request->status;
        $cancelReason?->save();
        Toastr::success(translate('messages.status_updated'));
        return back();
    }
    public function setDefault(Request $request)
    {
        $cancelReason = OrderCancelReason::findOrFail($request->id);
        if( $cancelReason->is_default == 1 && $request->is_default == 0){
            Toastr::warning(translate('messages.You_can_not_change_the_default_status_of_this_Order_Cancel_Reason'));
            return back();
        }
        OrderCancelReason::where('user_type' , $cancelReason->user_type)->where('is_default',1)->update(['is_default' => 0]);
        $cancelReason->is_default = $request->is_default;
        $cancelReason->status = 1;
        $cancelReason?->save();
        Toastr::success(translate('messages.Dafault_status_updated'));
        return back();
    }
    public function update(Request $request)
    {
        $request->validate([
            'reason' => 'required|max:255',
            'user_type' =>'required|max:50',
        ]);

        if($request->reason[array_search('default', $request->lang1)] == '' ){
            Toastr::error(translate('default_reason_is_required'));
            return back();
            }
        $cancelReason = OrderCancelReason::findOrFail($request->reason_id);

        if($cancelReason->is_default == 1){
            OrderCancelReason::where('id','!=' ,$cancelReason->id)->where('user_type' , $request->user_type)->where('is_default',1)->update(['is_default' => 0]);
        }
        $cancelReason->reason = $request->reason[array_search('default', $request->lang1)];
        $cancelReason->user_type=$request->user_type;
        $cancelReason?->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang1 as $index => $key) {
            if($default_lang == $key && !($request->reason[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\OrderCancelReason',
                            'translationable_id' => $cancelReason->id,
                            'locale' => $key,
                            'key' => 'reason'
                        ],
                        ['value' => $cancelReason->reason]
                    );
                }
            }else{
                if ($request->reason[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\OrderCancelReason',
                            'translationable_id' => $cancelReason->id,
                            'locale' => $key,
                            'key' => 'reason'
                        ],
                        ['value' => $request->reason[$index]]
                    );
                }
            }
        }
        Toastr::success(translate('order_cancellation_reason_updated_successfully'));
        return back();
    }
}
