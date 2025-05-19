<?php

namespace App\Http\Controllers\Api\V1\Vendor;


use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Mail\SubscriptionCancel;
use App\Models\RestaurantWallet;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\RestaurantSubscription;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\Validator;
use App\Models\SubscriptionBillingAndRefundHistory;

class SubscriptionController extends Controller
{
    public function package_view()
    {
        $packages = SubscriptionPackage::where('status', 1)->latest()->get();
        return response()->json(['packages' => $packages], 200);
    }


    public function business_plan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
            'payment' => 'nullable',
            'business_plan' => 'required|in:subscription,commission',
            'package_id' => 'nullable|required_if:business_plan,subscription',
            'payment_gateway' => 'nullable|required_if:business_plan,subscription',
            'payment_platform' => 'nullable|in:app,web'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $restaurant = Restaurant::Where('id', $request->restaurant_id)->first();
        if ($request->business_plan == 'subscription' && $request->package_id != null) {

            $package = SubscriptionPackage::withoutGlobalScope('translate')->find($request->package_id);
            $pending_bill = SubscriptionBillingAndRefundHistory::where([
                'restaurant_id' => $restaurant->id,
                'transaction_type' => 'pending_bill',
                'is_success' => 0
            ])?->sum('amount') ?? 0;
            if (!in_array($request->payment_gateway, ['wallet', 'free_trial'])) {
                $url = $request->has('callback') ? $request['callback'] : session('callback');
                $data = [
                    'redirect_link' => Helpers::subscriptionPayment(restaurant_id: $restaurant->id, package_id: $package->id, payment_gateway: $request->payment_gateway, payment_platform: $request->payment_platform ?? 'web', url: $url, pending_bill: $pending_bill, type: $request?->type),
                ];

                return response()->json($data, 200);
            }

            if ($request->payment_gateway == 'wallet') {
                $wallet = RestaurantWallet::firstOrNew(['vendor_id' => $restaurant->vendor_id]);
                $balance = BusinessSetting::where('key', 'wallet_status')->first()?->value == 1 ? $wallet?->balance ?? 0 : 0;

                if ($balance > $package?->price) {
                    $reference = 'wallet_payment_by_vendor';
                    $plan_data =   Helpers::subscription_plan_chosen(restaurant_id: $restaurant->id, package_id: $package->id, payment_method: 'wallet', discount: 0, pending_bill: $pending_bill, reference: $reference, type: $request?->type);
                    if ($plan_data != false) {
                        $wallet->total_withdrawn = $wallet?->total_withdrawn + $package->price;
                        $wallet?->save();
                    }
                } else {
                    return response()->json([
                        'errors' => ['message' => translate('messages.Insufficient_balance_in_wallet')]
                    ], 403);
                }
            }

            if ($request->payment_gateway == 'free_trial') {
                $plan_data =   Helpers::subscription_plan_chosen(restaurant_id: $restaurant->id, package_id: $package->id, payment_method: 'free_trial', discount: 0, pending_bill: $pending_bill, reference: 'free_trial', type: 'new_join');
            }

            $data = [
                'restaurant_model' => 'subscription',
                'logo' => $restaurant->logo,
                'message' => translate('messages.application_placed_successfully')
            ];
            return response()->json($data, 200);
        } elseif ($request->business_plan == 'commission') {
            $restaurant->restaurant_model = 'commission';
            $restaurant->save();
            RestaurantSubscription::where(['restaurant_id' => $restaurant->id])->update([
                'status' => 0,
            ]);
            $data = [
                'restaurant_model' => 'commission',
                'logo' => $restaurant->logo,
                'message' => translate('messages.application_placed_successfully')
            ];
            return response()->json($data, 200);
        }
        return response()->json([], 403);
    }

    public function transaction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

        $key = explode(' ', $request['search']);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limite']??25;
        $offset = $request['offset']??1;
        $from = $request->from;
        $to = $request->to;
        $restaurant_id = $request->vendor->restaurants[0]->id;

        $transactions=  SubscriptionTransaction::where('restaurant_id', $restaurant_id)->latest()
        ->with('restaurant:id,name','package:id,package_name')
        ->when(isset($key), function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('id', 'like', "%{$value}%");
                }
                $q->orWhereHas('restaurant' , function ($q) use ($key) {
                    foreach ($key as $value) {
                    $q->where('name', 'like', "%{$value}%");
                }
                });
            });
        })
        ->when(isset($from) &&  isset($to) ,function($query) use($from,$to){
            $query->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:29']);
        })

        ->paginate($limit, ['*'], 'page', $offset);

            $data = [
                'total_size' => $transactions->total(),
                'limit' => $limit,
                'offset' => $offset,
                'transactions' => $transactions->items()
            ];
            return response()->json($data,200);
    }


    public function cancelSubscription(Request $request){

        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
            'subscription_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        RestaurantSubscription::where([ 'id'=>$request->subscription_id , 'restaurant_id' => $request->restaurant_id])->update([
            'is_canceled' => 1,
            'canceled_by' => 'restaurant',
        ]);

        try {
            $restaurant=Restaurant::where('id',$request->restaurant_id)->first();
            $notification_status=Helpers::getNotificationStatusData('restaurant','restaurant_subscription_cancel');
            $reataurant_notification_status=Helpers::getRestaurantNotificationStatusData($restaurant?->id,'restaurant_subscription_cancel');

            if(  $notification_status?->push_notification_status  == 'active' && $reataurant_notification_status?->push_notification_status  == 'active'   &&  $restaurant?->vendor?->firebase_token){
                $data = [
                    'title' => translate('subscription_canceled'),
                    'description' => translate('Your_subscription_has_been_canceled'),
                    'order_id' => '',
                    'image' => '',
                    'type' => 'subscription',
                    'order_status' => '',
                ];
                Helpers::send_push_notif_to_device($restaurant?->vendor?->firebase_token, $data);
                DB::table('user_notifications')->insert([
                    'data' => json_encode($data),
                    'vendor_id' => $restaurant?->vendor_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            if (config('mail.status') && Helpers::get_mail_status('subscription_cancel_mail_status_restaurant') == '1' && $notification_status?->mail_status  == 'active' && $reataurant_notification_status?->mail_status  == 'active') {
                Mail::to($restaurant->email)->send(new SubscriptionCancel($restaurant->name));
            }
        } catch (\Exception $ex) {
            info($ex->getMessage());
        }

        return response()->json(['success'],200);

    }


    public function checkProductLimits(Request $request){

        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
            'package_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $disable_item_count=0;
        if(data_get(Helpers::subscriptionConditionsCheck(restaurant_id:$request->restaurant_id,package_id:$request->package_id) , 'disable_item_count') > 0){
            $disable_item_count = (int) (data_get(Helpers::subscriptionConditionsCheck(restaurant_id:$request->restaurant_id,package_id:$request->package_id) , 'disable_item_count',0));
        }

        $restaurant = Restaurant::where('id',$request->restaurant_id)->with('restaurant_sub_update_application')->first();
        $restaurant_subscription= $restaurant->restaurant_sub_update_application;
        $cash_backs=[];

        if($restaurant->restaurant_model == 'subscription' &&  $restaurant_subscription->status == 1 && $restaurant_subscription->is_canceled == 0 && $restaurant_subscription->is_trial == 0  && $restaurant_subscription->package_id !=  $request->package_id){
            $cash_backs= Helpers::calculateSubscriptionRefundAmount(restaurant:$restaurant, return_data:true);
        }

        return  response()->json(['disable_item_count'=> $disable_item_count,
                                    'back_amount'=> (float)data_get($cash_backs,'back_amount',0),
                                    'days'=> (int) data_get($cash_backs,'days',0) ],200);
    }

}
