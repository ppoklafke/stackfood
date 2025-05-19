<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Models\Food;
use App\Models\Order;
use App\Library\Payer;
use App\Models\Vendor;
use App\Traits\Payment;
use App\Models\Campaign;
use App\Library\Receiver;
use App\Models\Restaurant;
use App\Models\Notification;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\WithdrawRequest;
use App\Models\RestaurantWallet;
use App\Models\UserNotification;
use App\Models\WithdrawalMethod;
use App\CentralLogics\OrderLogic;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\CentralLogics\RestaurantLogic;
use Illuminate\Support\Facades\Config;
use App\Library\Payment as PaymentInfo;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\SubscriptionBillingAndRefundHistory;

class VendorController extends Controller
{
    public function get_profile(Request $request)
    {
        $vendor = $request['vendor'];

        $min_amount_to_pay_restaurant = BusinessSetting::where('key' , 'min_amount_to_pay_restaurant')->first()->value ?? 0;
        $restaurant_review_reply = BusinessSetting::where('key' , 'restaurant_review_reply')->first()->value ?? 0;


        $restaurant = Helpers::restaurant_data_formatting(data:$vendor->restaurants[0], multi_data:false);
        $discount=Helpers::get_restaurant_discount(restaurant: $vendor->restaurants[0]);
        unset($restaurant['discount']);
        $restaurant['discount']=$discount;
        $restaurant['schedules']=$restaurant?->schedules()?->get();

        $vendor['order_count'] =$vendor?->orders?->count();
        $vendor['todays_order_count'] =$vendor?->todaysorders?->count();
        $vendor['this_week_order_count'] =$vendor?->this_week_orders?->count();
        $vendor['this_month_order_count'] =$vendor?->this_month_orders?->count();
        $vendor['member_since_days'] =$vendor?->created_at?->diffInDays();
        $vendor['cash_in_hands'] =(float)$vendor?->wallet?->collected_cash ?? 0;
        $vendor['balance'] = (float)$vendor?->wallet?->balance ?? 0;

        $vendor['total_earning']  = (float)$vendor?->wallet?->total_earning ?? 0;
        $vendor['todays_earning'] =(float)$vendor?->todays_earning()?->sum('restaurant_amount');
        $vendor['this_week_earning'] =(float)$vendor?->this_week_earning()?->sum('restaurant_amount');
        $vendor['this_month_earning'] =(float)$vendor?->this_month_earning()?->sum('restaurant_amount');

        if($vendor['balance']  < 0){
            $vendor['balance']  = 0 ;
        }

    $vendor['Payable_Balance'] =(float) ($vendor?->wallet?->balance  < 0 ? abs($vendor?->wallet?->balance): 0 );

    $wallet_earning =  round($vendor?->wallet?->total_earning -($vendor?->wallet?->total_withdrawn + $vendor?->wallet?->pending_withdraw) , 8);
    $vendor['withdraw_able_balance'] =(float) $wallet_earning ;

    if(($vendor?->wallet?->balance > 0 && $vendor?->wallet?->collected_cash > 0 ) || ($vendor?->wallet?->collected_cash != 0 && $wallet_earning !=  0)){
        $vendor['adjust_able'] = true;
    }
    elseif($vendor?->wallet?->balance ==  $wallet_earning  ){
        $vendor['adjust_able'] = false;
    }
    else{
        $vendor['adjust_able'] = false;
    }

    $vendor['review_reply_status'] = $restaurant_review_reply;
    $vendor['show_pay_now_button'] = false;
    $digital_payment = Helpers::get_business_settings('digital_payment');

    if ($min_amount_to_pay_restaurant <= $vendor?->wallet?->collected_cash && $digital_payment['status'] == 1 &&  $vendor?->wallet?->collected_cash  >  $vendor?->wallet?->balance ){
        $vendor['show_pay_now_button'] = true;
    }

    $vendor['pending_withdraw'] =(float)$vendor?->wallet?->pending_withdraw ?? 0;
    $vendor['total_withdrawn'] = (float)$vendor?->wallet?->total_withdrawn ?? 0;

    if($vendor['balance'] > 0 ){
        $vendor['dynamic_balance'] =  (float) abs($wallet_earning);
            if($vendor?->wallet?->balance ==  $wallet_earning){
                $vendor['dynamic_balance_type']  = translate('messages.Withdrawable_Balance') ;
            } else{
                $vendor['dynamic_balance_type']  = translate('messages.Balance').' '.(translate('Unadjusted')) ;
            }

    } else{
        $vendor['dynamic_balance']   =  (float) abs($vendor?->wallet?->collected_cash) ?? 0;
        $vendor['dynamic_balance_type']  = translate('messages.Payable_Balance') ;
    }

    $Payable_Balance = $vendor?->wallet?->collected_cash  > 0 ? 1: 0;

    $cash_in_hand_overflow=  BusinessSetting::where('key' ,'cash_in_hand_overflow_restaurant')->first()?->value;
    $cash_in_hand_overflow_restaurant_amount =  BusinessSetting::where('key' ,'cash_in_hand_overflow_restaurant_amount')->first()?->value;
    $val=  $cash_in_hand_overflow_restaurant_amount - (($cash_in_hand_overflow_restaurant_amount * 10)/100);

    $vendor['over_flow_warning'] = false;
    if($Payable_Balance == 1 &&  $cash_in_hand_overflow &&  $vendor?->wallet?->balance < 0 &&  $val <=  abs($vendor?->wallet?->collected_cash)  ){

        $vendor['over_flow_warning'] = true;
    }

    $vendor['over_flow_block_warning'] = false;
    if ($Payable_Balance == 1 &&  $cash_in_hand_overflow &&  $vendor?->wallet?->balance < 0 &&  $cash_in_hand_overflow_restaurant_amount < abs($vendor?->wallet?->collected_cash)){
        $vendor['over_flow_block_warning'] = true;
    }


        $vendor["restaurants"] = $restaurant;
        $vendor['userinfo'] = $vendor?->userinfo;

        $st = Restaurant::withoutGlobalScope('translate')->findOrFail($restaurant['id']);
        $vendor["translations"] = $st->translations;

        unset($vendor['orders']);
        unset($vendor['rating']);
        unset($vendor['todaysorders']);
        unset($vendor['this_week_orders']);
        unset($vendor['wallet']);
        unset($vendor['todaysorders']);
        unset($vendor['this_week_orders']);
        unset($vendor['this_month_orders']);


        $vendor['subscription_transactions']= (boolean) SubscriptionTransaction::where('restaurant_id',$restaurant->id)->count() > 0? true : false;
            if(isset($st?->restaurant_sub_update_application)){
                    $vendor['subscription'] =$st?->restaurant_sub_update_application;

                    if($vendor['subscription']->max_product== 'unlimited' ){
                        $max_product_uploads= -1;
                    }
                    else{
                        $max_product_uploads= $vendor['subscription']->max_product - $st?->foods?->count() > 0?  $vendor['subscription']->max_product - $st?->foods?->count() : 0 ;
                    }

                    $pending_bill= SubscriptionBillingAndRefundHistory::where(['restaurant_id'=>$restaurant->id,
                                        'transaction_type'=>'pending_bill', 'is_success' =>0])?->sum('amount') ?? 0;
                    $vendor['subscription_other_data'] =  [
                        'total_bill'=>  (float) $vendor['subscription']->package?->price * ($vendor['subscription']->total_package_renewed + 1),
                        'max_product_uploads' => (int) $max_product_uploads,
                        'pending_bill' => (float) $pending_bill,
                    ];
                }

        return response()->json($vendor, 200);
    }

    public function active_status(Request $request)
    {
        $restaurant = $request?->vendor?->restaurants[0];
        $restaurant->active = $restaurant->active?0:1;
        $restaurant?->save();
        return response()->json(['message' => $restaurant->active?translate('messages.restaurant_opened'):translate('messages.restaurant_temporarily_closed')], 200);
    }

    public function get_earning_data(Request $request)
    {
        $vendor = $request['vendor'];
        $data= RestaurantLogic::get_earning_data(vendor_id:$vendor->id);
        return response()->json($data, 200);
    }

    public function update_profile(Request $request)
    {
        $vendor = $request['vendor'];
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required|unique:vendors,phone,'.$vendor->id,
            'password' => ['nullable', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],

            'image' => 'nullable|max:2048',
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
            'l_name.required' => translate('messages.Last name is required!'),
            'password.min_length' => translate('The password must be at least :min characters long'),
            'password.mixed' => translate('The password must contain both uppercase and lowercase letters'),
            'password.letters' => translate('The password must contain letters'),
            'password.numbers' => translate('The password must contain numbers'),
            'password.symbols' => translate('The password must contain symbols'),
            'password.uncompromised' => translate('The password is compromised. Please choose a different one'),
            'password.custom' => translate('The password cannot contain white spaces.'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($request->has('image')) {
            $imageName = Helpers::update(dir:'vendor/', old_image: $vendor->image, format: 'png', image: $request->file('image'));
        } else {
            $imageName = $vendor->image;
        }
        if ($request['password'] != null) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $vendor->password;
        }
        $vendor->f_name = $request->f_name;
        $vendor->l_name = $request->l_name;
        $vendor->phone = $request->phone;
        $vendor->image = $imageName;
        $vendor->password = $pass;
        $vendor->updated_at = now();
        $vendor->save();





        return response()->json(['message' => translate('messages.profile_updated_successfully')], 200);
    }

    public function get_current_orders(Request $request)
    {
        $vendor = $request['vendor'];

        $restaurant=$vendor?->restaurants[0];
        $data =0;
        if (($restaurant?->restaurant_model == 'subscription' && $restaurant?->restaurant_sub?->self_delivery == 1)  || ($restaurant?->restaurant_model == 'commission' &&  $restaurant?->self_delivery_system == 1) ){
         $data =1;
        }

        $orders = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->with('customer')
        ->where(function($query)use($data){
            if(config('order_confirmation_model') == 'restaurant' || $data)
            {
                $query->whereIn('order_status', ['accepted','pending','confirmed', 'processing', 'handover','picked_up','canceled','failed' ])
                ->hasSubscriptionInStatus(['accepted','pending','confirmed', 'processing', 'handover','picked_up','canceled','failed' ]);
            }
            else
            {
                $query->whereIn('order_status', ['confirmed', 'processing', 'handover','picked_up','canceled','failed' ])
                ->hasSubscriptionInStatus(['accepted','pending','confirmed', 'processing', 'handover','picked_up','canceled','failed'])
                ->orWhere(function($query){
                    $query->where('payment_status','paid')->where('order_status', 'accepted');
                })
                ->orWhere(function($query){
                    $query->where('order_status','pending')->whereIn('order_type', ['take_away' , 'dine_in']);
                });
            }
        })
        ->NotDigitalOrder()
        ->Notpos()
        ->orderBy('schedule_at', 'desc')
        ->get();
        $orders= Helpers::order_data_formatting($orders, true);
        return response()->json($orders, 200);
    }

    public function get_completed_orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
            'status' => 'required' ,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $vendor = $request['vendor'];
        $paginator = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->with('customer','refund')
        ->when($request->status == 'all', function($query){
            return $query->whereIn('order_status', ['refunded','refund_requested','refund_request_canceled', 'delivered','canceled','failed' ]);
        })
        ->when($request->status != 'all', function($query)use($request){
            return $query->where('order_status', $request->status);
        })
        ->Notpos()
        ->latest()
        ->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $orders= Helpers::order_data_formatting($paginator->items(), true);
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return response()->json($data, 200);
    }

    public function update_order_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'reason' =>'required_if:status,canceled',
            'status' => 'required|in:confirmed,processing,handover,delivered,canceled',
            'order_proof' =>'nullable|array|max:5',

        ]);

        $validator->sometimes('otp', 'required', function ($request) {
            return (Config::get('order_delivery_verification')==1 && $request['status']=='delivered');
        });

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $vendor = $request['vendor'];
        $order = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->where('id', $request['order_id'])->with(['subscription_logs','details'])
        ->Notpos()
        ->first();

        if(!$order)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.Order_not_found')]
                ]
            ], 403);
        }

        if($request['order_status']=='canceled')
        {
            if(!config('canceled_by_restaurant'))
            {
                return response()->json([
                    'errors' => [
                        ['code' => 'status', 'message' => translate('messages.you_can_not_cancel_a_order')]
                    ]
                ], 403);
            }
            else if($order->confirmed)
            {
                return response()->json([
                    'errors' => [
                        ['code' => 'status', 'message' => translate('messages.you_can_not_cancel_after_confirm')]
                    ]
                ], 403);
            }
        }

        $restaurant=$vendor?->restaurants[0];
        $data =0;
        if (($restaurant?->restaurant_model == 'subscription' &&  $restaurant?->restaurant_sub?->self_delivery == 1)  || ($restaurant?->restaurant_model == 'commission' &&  $restaurant?->self_delivery_system == 1) ){
         $data =1;
        }

        if($request['status'] =="confirmed" && !$data && config('order_confirmation_model') == 'deliveryman' && !in_array($order['order_type'],['dine_in','take_away']) && $order->subscription_id == null)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order-confirmation-model', 'message' => translate('messages.order_confirmation_warning')]
                ]
            ], 403);
        }

        if($order->picked_up != null)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'status', 'message' => translate('messages.You_can_not_change_status_after_picked_up_by_delivery_man')]
                ]
            ], 403);
        }

        if($request['status']=='delivered' && !in_array($order['order_type'],['dine_in','take_away']) && !$data)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'status', 'message' => translate('messages.you_can_not_delivered_delivery_order')]
                ]
            ], 403);
        }
        if(Config::get('order_delivery_verification')==1 && $request['status']=='delivered' && $order->otp != $request['otp'])
        {
            return response()->json([
                'errors' => [
                    ['code' => 'otp', 'message' => 'Not matched']
                ]
            ], 403);
        }

        if ($request->status == 'delivered' && ($order->transaction == null || isset($order->subscription_id))) {

            if(isset($order->subscription_id) && count($order->subscription_logs) == 0 ){
                return response()->json([
                    'errors' => [
                        ['code' => 'order-subscription', 'message' => translate('messages.You_Can_Not_Delivered_This_Subscription_order_Before_Schedule')]
                    ]
                ], 403);
            }



            $unpaid_payment = OrderPayment::where('payment_status','unpaid')->where('order_id',$order->id)->first()?->payment_method;
            $unpaid_pay_method = 'digital_payment';
            if($unpaid_payment){
                $unpaid_pay_method = $unpaid_payment;
            }

            if($order->payment_method == 'cash_on_delivery'|| $unpaid_pay_method == 'cash_on_delivery')
            {
                $ol = OrderLogic::create_transaction( order:$order, received_by:'restaurant', status: null);
            }
            else
            {
                $ol = OrderLogic::create_transaction( order:$order, received_by:'admin', status: null);
            }

            if(!$ol){
                return response()->json([
                    'errors' => [
                        ['code' => 'error', 'message' => translate('messages.faield_to_create_order_transaction')]
                    ]
                ], 406);
            }

            $order->payment_status = 'paid';
            OrderLogic::update_unpaid_order_payment(order_id:$order->id, payment_method:$order->payment_method);
        }

        if($request->status == 'delivered')
        {
            $order?->details?->each(function($item, $key){
                $item?->food?->increment('order_count');
            });
            if($order->is_guest == 0){
                $order->customer->increment('order_count');
            }
            $order?->restaurant?->increment('order_count');

            if($order?->delivery_man)
            {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders>1?$dm->current_orders-1:0;
                $dm->save();
            }
            $img_names = [];
            $images = [];
            if (!empty($request->file('order_proof'))) {
                foreach ($request->order_proof as $img) {
                    $image_name = Helpers::upload('order/', 'png', $img);
                    array_push($img_names, ['img'=>$image_name, 'storage'=> Helpers::getDisk()]);
                }
                $images = $img_names;
            }
            $order->order_proof = json_encode($images);
        }


        if($request->status == 'canceled')
        {
            if($order?->delivery_man)
            {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders>1?$dm->current_orders-1:0;
                $dm->save();
            }
            if(!isset($order->confirmed) && isset($order->subscription_id)){
                $order?->subscription()?->update(['status' => 'canceled']);
                    if($order?->subscription?->log){
                        $order?->subscription?->log()?->update([
                            'order_status' => $request->status,
                            'canceled' => now(),
                            ]);
                    }
            }
            $order->cancellation_reason=$request->reason;
            $order->canceled_by='restaurant';

            Helpers::decreaseSellCount(order_details:$order->details);

        }

        if($request->status == 'processing') {
            $order->processing_time = isset($request->processing_time) ? $request->processing_time : explode('-', $order['restaurant']['delivery_time'])[0];
        }
        $order->order_status = $request['status'];
        $order[$request['status']] = now();
        $order->save();
        Helpers::send_order_notification($order);

        return response()->json(['message' => 'Status updated'], 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $vendor = $request['vendor'];

        $order = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->with(['customer','details','delivery_man','subscription','OrderReference'])
        ->where('id', $request['order_id'])
        ->Notpos()
        ->first();
        $details = $order?->details;
        $order['details'] = Helpers::order_details_data_formatting($details);
        return response()->json(['order' => $order],200);
    }

    public function get_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $vendor = $request['vendor'];

        $order = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->with(['customer','details','delivery_man','payments','OrderReference'])
        ->where('id', $request['order_id'])
        ->Notpos()
        ->first();

        return response()->json(Helpers::order_data_formatting($order),200);
    }

    public function get_all_orders(Request $request)
    {
        $vendor = $request['vendor'];
        $orders = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor?->id);
        })
        ->with('customer')
        ->Notpos()
        ->orderBy('schedule_at', 'desc')
        ->NotDigitalOrder()
        ->get();
        $orders= Helpers::order_data_formatting(data:$orders,multi_data: true);
        return response()->json($orders, 200);
    }

    public function update_fcm_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $vendor = $request['vendor'];

        Vendor::where(['id' => $vendor?->id])->update([
            'firebase_token' => $request['fcm_token']
        ]);

        return response()->json(['message'=>'successfully updated!'], 200);
    }

    public function get_notifications(Request $request){
        $vendor = $request['vendor'];

        $notifications = Notification::active()->where(function($q) use($vendor){
            $q->whereNull('zone_id')->orWhere('zone_id', $vendor->restaurants[0]->zone_id);
        })->where('tergat', 'restaurant')->where('created_at', '>=', \Carbon\Carbon::today()->subDays(7))->get();

        $notifications->append('data');

        $user_notifications = UserNotification::where('vendor_id', $vendor->id)->where('created_at', '>=', \Carbon\Carbon::today()->subDays(7))->get();

        $notifications =  $notifications->merge($user_notifications);

        try {
            return response()->json($notifications, 200);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()], 200);
        }
    }

    public function get_basic_campaigns(Request $request)
    {
        $vendor = $request['vendor'];
        $campaigns=Campaign::with('restaurants')->active()->running()->latest()->get();
        $data = [];
        $restaurant_id = $vendor?->restaurants[0]?->id;
        foreach ($campaigns as $item) {
            $restaurant_ids = count($item->restaurants)?$item->restaurants->pluck('id')->toArray():[];
            $restaurant_joining_status = count($item->restaurants)?$item->restaurants->pluck('pivot')->toArray():[];
            if($item->start_date)
            {
                $item['available_date_starts']=$item->start_date->format('Y-m-d');
                unset($item['start_date']);
            }
            if($item->end_date)
            {
                $item['available_date_ends']=$item->end_date->format('Y-m-d');
                unset($item['end_date']);
            }

            if (count($item['translations'])>0 ) {
                $translate = array_column($item['translations']->toArray(), 'value', 'key');
                $item['title'] = data_get($translate,'title',null);
                $item['description'] = data_get($translate,'description',null);
            }
            $item['vendor_status'] = null;
            foreach($restaurant_joining_status as $status){
                if($status['restaurant_id'] == $restaurant_id){
                    $item['vendor_status'] =  $status['campaign_status'];
                }

            }
            $item['is_joined'] = in_array($restaurant_id, $restaurant_ids)?true:false;
            unset($item['restaurants']);
            array_push($data, $item);
        }
        return response()->json($data, 200);
    }

    public function remove_restaurant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $campaign = Campaign::where('status', 1)->find($request->campaign_id);
        if(!$campaign)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'campaign', 'message'=>'Campaign not found or upavailable!']
                ]
            ]);
        }
        $restaurant = $request['vendor']?->restaurants[0];
        $campaign?->restaurants()?->detach($restaurant);
        $campaign?->save();
        return response()->json(['message'=>translate('messages.you_are_successfully_removed_from_the_campaign')], 200);
    }

    public function addrestaurant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $campaign = Campaign::where('status', 1)->find($request->campaign_id);
        if(!$campaign)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'campaign', 'message'=>'Campaign not found or upavailable!']
                ]
            ]);
        }
        $restaurant = $request['vendor']?->restaurants[0];
        $campaign?->restaurants()?->attach($restaurant);
        $campaign?->save();
        return response()->json(['message'=>translate('messages.you_are_successfully_joined_to_the_campaign')], 200);
    }

    public function get_products(Request $request)
    {
        $limit=$request->limit?$request->limit:25;
        $offset=$request->offset?$request->offset:1;
        $category_id=$request->category_id?$request->category_id:0;

        $type = $request->query('type', 'all');
        $stock = $request->query('stock', 'all');

        $paginator = Food::type($type);
        if($category_id != 0)
        {
            $paginator = $paginator->whereHas('category',function($q)use($category_id){
                return $q->whereId($category_id)->orWhere('parent_id', $category_id);
            });
        }
        if($stock == 'stock_out')
        {
            $paginator = $paginator->where('stock_type','!=' ,'unlimited' )->where(function($query){
                $query->whereRaw('item_stock - sell_count <= 0')->orWhereHas('newVariationOptions',function($query){
                    $query->whereRaw('total_stock - sell_count <= 0');
                });

            });
        }
        $paginator = $paginator->where('restaurant_id', $request['vendor']->restaurants[0]->id)->latest()->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => Helpers::product_data_formatting(data:$paginator->items(), multi_data: true, trans:true, local:app()->getLocale())
        ];

        return response()->json($data, 200);
    }

    public function update_bank_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|max:191',
            'branch' => 'required|max:191',
            'holder_name' => 'required|max:191',
            'account_no' => 'required|max:191'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $bank = $request['vendor'];
        $bank->bank_name = $request->bank_name;
        $bank->branch = $request->branch;
        $bank->holder_name = $request->holder_name;
        $bank->account_no = $request->account_no;
        $bank?->save();

        return response()->json(['message'=>translate('messages.bank_info_updated_successfully'),200]);
    }

    public function withdraw_list(Request $request)
    {
        $withdraw_req = WithdrawRequest::where('vendor_id', $request['vendor']->id)->latest()->get();
        $temp = [];
        $status = [
            0=>'Pending',
            1=>'Approved',
            2=>'Denied'
        ];
        foreach($withdraw_req as $item)
        {
            $item['status'] = $status[$item->approved];
            $item['requested_at'] = $item->created_at->format('Y-m-d H:i:s');

            if($item->type == 'disbursement'){
                $item['bank_name'] = $item->disbursementMethod ? $item->disbursementMethod->method_name : translate('Account');
            } else {
                $item['bank_name'] = $item->method ? $item->method->method_name : translate('Account');
            }

            $item['detail']=json_decode($item->withdrawal_method_fields,true);

            unset($item['created_at']);
            unset($item['approved']);
            $temp[] = $item;
        }
        return response()->json($temp, 200);
    }

    public function request_withdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'id'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $method = WithdrawalMethod::find($request['id']);
        $fields = array_column($method->method_fields, 'input_name');
        $values = $request->all();

        $method_data = [];
        foreach ($fields as $field) {
            if(key_exists($field, $values)) {
                $method_data[$field] = $values[$field];
            }
        }


        $w = $request['vendor']?->wallet;
        if ($w?->balance >= $request['amount']) {
            $data = [
                'vendor_id' => $w?->vendor_id,
                'amount' => $request['amount'],
                'transaction_note' => null,
                'withdrawal_method_id' => $request['id'],
                'withdrawal_method_fields' => json_encode($method_data),
                'approved' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
            try
            {
                DB::table('withdraw_requests')->insert($data);
                $w?->increment('pending_withdraw', $request['amount']);
                $notification_status= Helpers::getNotificationStatusData('admin','withdraw_request');
                if( $notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('withdraw_request_mail_status_admin') == '1') {
                    $wallet_transaction = WithdrawRequest::where('vendor_id',$w->vendor_id)->latest()->first();
                    $admin= \App\Models\Admin::where('role_id', 1)->first();

                    Mail::to($admin->email)->send(new \App\Mail\WithdrawRequestMail('admin_mail',$wallet_transaction));
                }
                return response()->json(['message'=>translate('messages.withdraw_request_placed_successfully')],200);
            }
            catch(\Exception $e)
            {
                info($e->getMessage());
                return response()->json($e);
            }
        }
        return response()->json([
            'errors'=>[
                ['code'=>'amount', 'message'=>translate('messages.insufficient_balance')]
            ]
        ],403);
    }

    public function remove_account(Request $request)
    {
        $vendor = $request['vendor'];

        if(Order::where('restaurant_id', $vendor?->restaurants[0]?->id)->whereIn('order_status', ['pending','accepted','confirmed','processing','handover','picked_up'])->count())
        {
            return response()->json(['errors'=>[['code'=>'on-going', 'message'=>translate('messages.user_account_delete_warning')]]],203);
        }

        if($vendor?->wallet && $vendor?->wallet?->collected_cash > 0)
        {
            return response()->json(['errors'=>[['code'=>'on-going', 'message'=>translate('messages.user_account_wallet_delete_warning')]]],203);
        }

        Helpers::check_and_delete('vendor/' , $vendor['image']);

        Helpers::check_and_delete('restaurant/' , $vendor?->restaurants[0]?->logo);

        Helpers::check_and_delete('restaurant/cover/' , $vendor?->restaurants[0]?->cover_photo);

        $vendor?->restaurants()?->delete();
        $vendor?->userinfo?->delete();
        $vendor?->delete();
        return response()->json([]);
    }

    public function withdraw_method_list(){
        $wi=WithdrawalMethod::where('is_active',1)->get();
        return response()->json($wi,200);
    }

    public function send_order_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $vendor = $request['vendor'];
        $restaurant=  $vendor->restaurants[0];

        $order = Order::where('id',$request->order_id)->whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->with('customer')

        ->where(function($query)use($restaurant){
            if(config('order_confirmation_model') == 'restaurant' ||   (($restaurant?->restaurant_model == 'subscription' && $restaurant?->restaurant_sub?->self_delivery == 1) || ($restaurant?->restaurant_model == 'commission' &&  $restaurant?->self_delivery_system == 1) ) )
            {
                $query->whereIn('order_status', ['accepted','pending','confirmed', 'processing', 'handover','picked_up']);
            }
            else
            {
                $query->whereIn('order_status', ['confirmed', 'processing', 'handover','picked_up'])
                ->orWhere(function($query){
                    $query->where('payment_status','paid')->where('order_status', 'accepted');
                })
                ->orWhere(function($query){
                    $query->where('order_status','pending')->where('order_type', 'take_away');
                });
            }
        })
        ->Notpos()
        ->NotDigitalOrder()
        ->first();
        if(!$order)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.not_found')]
                ]
            ], 404);
        }
        $value = translate('your_order_is_ready_to_be_delivered,_plesae_share_your_otp_with_delivery_man.').' '.translate('otp:').$order->otp.', '.translate('order_id:').$order->id;

        try {
            $customer_push_notification_status=Helpers::getNotificationStatusData('customer','customer_delivery_verification');

            $fcm_token= ($order->is_guest == 0 ? $order?->customer?->cm_firebase_token : $order?->guest?->fcm_token) ?? null ;
            if ($customer_push_notification_status?->push_notification_status  == 'active' && $value && $fcm_token) {
                $data = [
                    'title' => translate('messages.order_ready_to_be_delivered'),
                    'description' => $value,
                    'order_id' => $order->id,
                    'image' => '',
                    'type' => 'order_status',
                    'order_status' => $order->order_status,
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
                DB::table('user_notifications')->insert([
                    'data' => json_encode($data),
                    'user_id' => $order->user_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } catch (\Exception $e) {
            info($e->getMessage());
            return response()->json(['message' => translate('messages.push_notification_faild')], 403);
        }
        return response()->json([], 200);
    }




    Public function make_payment(Request $request){
        $validator = Validator::make($request->all(), [
            'payment_gateway' => 'required',
            'amount' => 'required|numeric|min:.001',
            'callback' => 'required'
        ]);

        $vendor = $request['vendor'];
        $restaurant=  $vendor->restaurants[0];


        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $restaurant =Restaurant::findOrfail($restaurant->id);

        $payer = new Payer(
            $restaurant->name ,
            $restaurant->email,
            $restaurant->phone,
            ''
        );
        $additional_data = [
            'business_name' => BusinessSetting::where(['key'=>'business_name'])->first()?->value,
            'business_logo' => dynamicStorage('storage/app/public/business') . '/' .BusinessSetting::where(['key' => 'logo'])->first()?->value
        ];
        $payment_info = new PaymentInfo(
            success_hook: 'collect_cash_success',
            failure_hook: 'collect_cash_fail',
            currency_code: Helpers::currency_code(),
            payment_method: $request->payment_gateway,
            payment_platform: 'app',
            payer_id: $restaurant->vendor_id,
            receiver_id: '100',
            additional_data:  $additional_data,
            payment_amount: $request->amount ,
            external_redirect_link: $request->has('callback')?$request['callback']:session('callback'),
            attribute: 'restaurant_collect_cash_payments',
            attribute_id: $restaurant->vendor_id,
        );

        $receiver_info = new Receiver('Admin','example.png');
        $redirect_link = Payment::generate_link($payer, $payment_info, $receiver_info);

        $data = [
            'redirect_link' => $redirect_link,
        ];
        return response()->json($data, 200);

    }


    public function make_wallet_adjustment(Request $request){
        $vendor = $request['vendor'];

        $wallet = RestaurantWallet::firstOrNew(
            ['vendor_id' =>$vendor->id]
        );

        $wallet_earning =  $wallet->total_earning -($wallet->total_withdrawn + $wallet->pending_withdraw);
        $adj_amount =  $wallet->collected_cash - $wallet_earning;


        if($wallet->collected_cash == 0 || $wallet_earning == 0  || ($wallet_earning  == $wallet->balance ) ){
            return response()->json(['message' => translate('messages.Already_Adjusted')], 201);
        }

        if($adj_amount > 0 ){
        $wallet->total_withdrawn =  $wallet->total_withdrawn + $wallet_earning ;
        $wallet->collected_cash =   $wallet->collected_cash - $wallet_earning ;

        $data = [
            'vendor_id' => $vendor->id,
            'amount' => $wallet_earning,
            'transaction_note' => "Restaurant_wallet_adjustment_partial",
            'withdrawal_method_id' => null,
            'withdrawal_method_fields' => null,
            'approved' => 1,
            'type' => 'adjustment',
            'created_at' => now(),
            'updated_at' => now()
        ];

    } else{
        $data = [
            'vendor_id' => $vendor->id,
            'amount' => $wallet->collected_cash ,
            'transaction_note' => "Restaurant_wallet_adjustment_full",
            'withdrawal_method_id' => null,
            'withdrawal_method_fields' => null,
            'approved' => 1,
            'type' => 'adjustment',
            'created_at' => now(),
            'updated_at' => now()
        ];
        $wallet->total_withdrawn =  $wallet->total_withdrawn + $wallet->collected_cash ;
        $wallet->collected_cash =   0;

    }

    $wallet->save();
    DB::table('withdraw_requests')->insert($data);

    return response()->json(['message' => translate('messages.restaurant_wallet_adjustment_successfull')], 200);
    }

    public function wallet_payment_list(Request $request)
    {
        $limit= $request['limit'] ?? 25;
        $offset = $request['offset'] ?? 1;
        $vendor = $request['vendor'];

        $key = isset($request['search']) ? explode(' ', $request['search']) : [];
        $paginator = AccountTransaction::
        when(isset($key), function ($query) use ($key) {
            return $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('ref', 'like', "%{$value}%");
                }
            });
        })
        ->where('type', 'collected')
        ->where('created_by' , 'restaurant')
        ->where('from_id', $vendor->id)
        ->where('from_type', 'restaurant')
        ->latest()

        ->paginate($limit, ['*'], 'page', $offset);

        $temp= [];

        foreach( $paginator->items() as $item)
        {
            $item['status'] = 'approved';
            $item['payment_time'] = \App\CentralLogics\Helpers::time_date_format($item->created_at);

            $temp[] = $item;
        }
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'transactions' => $temp,
        ];

        return response()->json($data, 200);
    }

    public function update_announcment(Request $request)
    {
        $vendor = $request['vendor']->restaurants[0];
        $validator = Validator::make($request->all(), [
            'announcement_status' => 'required',
            'announcement_message' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $vendor->announcement = $request->announcement_status;
        $vendor->announcement_message = $request->announcement_message;
        $vendor->save();

        return response()->json(['message' => translate('messages.announcement_updated_successfully')], 200);
    }


    public function add_dine_in_table_number(Order $order , Request $request)
    {

        $validator = Validator::make($request->all(), [
            'table_number' => 'nullable|max:255|required_without_all:token_number',
            'token_number' => 'nullable|max:255|required_without_all:table_number',
        ],[
            'table_number.required_without_all' => translate('you_must_set_a_table_or_token_number'),
            'token_number.required_without_all' => translate('you_must_set_a_table_or_token_number'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if($order?->order_type  == 'dine_in' ){
            $order->OrderReference()->update([
                'token_number' => $request->token_number ?? null,
                'table_number' =>$request->table_number ?? null
            ]);
        }
        $data = [
            // 'title' => $request->table_number  ? translate('Table_number_is_added') : translate('Token_number_is_added') ,
            // 'description' =>  $request->table_number  ? translate('Table_number_is') .' '.$request->token_number :  translate('Token_number_is') .' '.$request->token_number,
            'title' =>  $request->table_number &&  $request->token_number ?  translate('Here_is_your_Table_and_Token_Number') :($request->table_number  ? translate('Here_is_your_Table_Number') : translate('Here_is_your_Token_Number')) ,
            'description' => $request->table_number &&  $request->token_number ?  translate('Table No -') .' '.$request->table_number  .' '. translate('&_Token No -') .' '.$request->token_number : ($request->table_number  ? translate('Table No -') .' '.$request->table_number  :  translate('Token No -') .' '.$request->token_number ),
            'order_id' => $order->id,
            'image' => '',
            'type' => 'order_status',
            'order_status' => $order->order_status,
        ];
        $notification_status= Helpers::getNotificationStatusData('customer','customer_dine_in_table_or_token');
        $fcm= ($order->is_guest == 0 ? $order?->customer?->cm_firebase_token : $order?->guest?->fcm_token) ?? null ;
        if($notification_status?->push_notification_status  == 'active' && $fcm ){
            Helpers::send_push_notif_to_device($fcm, $data);
            DB::table('user_notifications')->insert([
                'data' => json_encode($data),
                'user_id' => $order?->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        return response()->json(['message' => $request->table_number ? translate('table_number_updated_successfully') : translate('token_number_updated_successfully')], 200);
    }


}
