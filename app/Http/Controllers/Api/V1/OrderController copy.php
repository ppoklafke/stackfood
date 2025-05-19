<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cart;
use App\Models\Food;
use App\Models\User;
use App\Models\Zone;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Refund;
use App\Models\Review;
use App\Mail\PlaceOrder;
use App\Models\DMReview;
use App\Models\Restaurant;
use App\Mail\RefundRequest;
use App\Models\DeliveryMan;
use App\Models\OrderDetail;
use App\Models\ItemCampaign;
use App\Models\RefundReason;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\CashBackHistory;
use App\Models\OfflinePayments;
use App\CentralLogics\OrderLogic;
use App\Models\OrderCancelReason;
use App\CentralLogics\CouponLogic;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderVerificationMail;
use App\CentralLogics\CustomerLogic;
use App\Http\Controllers\Controller;
use App\Models\OfflinePaymentMethod;
use App\Models\SubscriptionSchedule;
use App\Models\VariationOption;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;

class OrderController extends Controller
{
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'guest_id' => $request->user ? 'nullable' : 'required',
            'contact_number' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];

        $order = Order::with(['restaurant','restaurant.restaurant_sub', 'refund', 'delivery_man', 'delivery_man.rating','subscription','payments'])->withCount('details')->where(['id' => $request['order_id'], 'user_id' => $user_id])
        ->when(!$request->user, function ($query) use ($request) {
            return $query->whereJsonContains('delivery_address->contact_person_number', $request['contact_number']);
        })
        ->Notpos()->first();

        if($order){
            $order['restaurant'] = $order['restaurant'] ? Helpers::restaurant_data_formatting($order['restaurant']): $order['restaurant'];
            $order['delivery_address'] = $order['delivery_address']?json_decode($order['delivery_address'],true):$order['delivery_address'];
            $order['delivery_man'] = $order['delivery_man']?Helpers::deliverymen_data_formatting([$order['delivery_man']]):$order['delivery_man'];
            $order['offline_payment'] =  isset($order->offline_payments) ? Helpers::offline_payment_formater($order->offline_payments) : null;
            $order['is_reviewed'] =   $order->details_count >  Review::whereOrderId($request->order_id)->count() ? False :True ;
            $order['is_dm_reviewed'] =  $order?->delivery_man ? DMReview::whereOrderId($order->id)->exists()  : True ;

            if($order->subscription){
                $order->subscription['delivered_count']= (int) $order->subscription->logs()->whereOrderStatus('delivered')->count();
                $order->subscription['canceled_count']= (int) $order->subscription->logs()->whereOrderStatus('canceled')->count();
            }

            unset($order['offline_payments']);
            unset($order['details']);
        } else{
            return response()->json([
                'errors' => [
                    ['code' => 'order_not_found', 'message' => translate('messages.Order_not_found')]
                ]
            ], 404);
        }
        return response()->json($order, 200);
    }

    public function place_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_amount' => 'required',
            'payment_method'=>'required|in:cash_on_delivery,digital_payment,wallet,offline_payment',
            'order_type' => 'required|in:take_away,delivery',
            'restaurant_id' => 'required',
            'distance' => 'required_if:order_type,delivery',
            'address' => 'required_if:order_type,delivery',
            'longitude' => 'required_if:order_type,delivery',
            'latitude' => 'required_if:order_type,delivery',
            'dm_tips' => 'nullable|numeric',
            'guest_id' => $request->user ? 'nullable' : 'required',
            'contact_person_name' => $request->user ? 'nullable' : 'required',
            'contact_person_number' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        if($request->payment_method == 'wallet' && Helpers::get_business_settings('wallet_status', false) != 1)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'payment_method', 'message' => translate('messages.customer_wallet_disable_warning')]
                ]
            ], 203);
        }

        if($request->partial_payment && Helpers::get_mail_status('partial_payment_status') == 0){
            return response()->json([
                'errors' => [
                    ['code' => 'order_method', 'message' => translate('messages.partial_payment_is_not_active')]
                ]
            ], 403);
        }

        if ($request->payment_method == 'offline_payment' &&  Helpers::get_mail_status('offline_payment_status') == 0) {
            return response()->json([
                'errors' => [
                    ['code' => 'offline_payment_status', 'message' => translate('messages.offline_payment_for_the_order_not_available_at_this_time')]
                ]
            ], 403);
        }
        $digital_payment = Helpers::get_business_settings('digital_payment');
        if($digital_payment['status'] == 0 && $request->payment_method == 'digital_payment'){
            return response()->json([
                'errors' => [
                    ['code' => 'digital_payment', 'message' => translate('messages.digital_payment_for_the_order_not_available_at_this_time')]
                ]
            ], 403);
        }

        if($request->is_guest && !Helpers::get_mail_status('guest_checkout_status')){
            return response()->json([
                'errors' => [
                    ['code' => 'is_guest', 'message' => translate('messages.Guest_order_is_not_active')]
                ]
            ], 403);
        }

        $coupon = null;
        $delivery_charge = null;
        $free_delivery_by = null;
        $coupon_created_by = null;
        $schedule_at = $request->schedule_at?\Carbon\Carbon::parse($request->schedule_at):now();
        $per_km_shipping_charge = 0;
        $minimum_shipping_charge = 0;
        $maximum_shipping_charge =  0;
        $max_cod_order_amount_value=  0;
        $increased=0;
        $distance_data = $request->distance ?? 0;


        $home_delivery = BusinessSetting::where('key', 'home_delivery')->first()?->value ?? null;
        if ($home_delivery == null && $request->order_type == 'delivery') {
            return response()->json([
                'errors' => [
                    ['code' => 'order_type', 'message' => translate('messages.Home_delivery_is_disabled')]
                ]
            ], 403);
        }

        $take_away = BusinessSetting::where('key', 'take_away')->first()?->value ?? null;
        if ($take_away == null && $request->order_type == 'take_away') {
            return response()->json([
                'errors' => [
                    ['code' => 'order_type', 'message' => translate('messages.Take_away_is_disabled')]
                ]
            ], 403);
        }

        $settings =  BusinessSetting::where('key', 'cash_on_delivery')->first();
        $cod = json_decode($settings?->value, true);
        if(isset($cod['status']) &&  $cod['status'] != 1 && $request->payment_method == 'cash_on_delivery'){
            return response()->json([
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.Cash_on_delivery_is_not_active')]
                ]
            ], 403);

        }

        if($request->schedule_at && $schedule_at < now())
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.you_can_not_schedule_a_order_in_past')]
                ]
            ], 406);
        }
        $restaurant = Restaurant::with(['discount', 'restaurant_sub'])->selectRaw('*, IF(((select count(*) from `restaurant_schedule` where `restaurants`.`id` = `restaurant_schedule`.`restaurant_id` and `restaurant_schedule`.`day` = '.$schedule_at->format('w').' and `restaurant_schedule`.`opening_time` < "'.$schedule_at->format('H:i:s').'" and `restaurant_schedule`.`closing_time` >"'.$schedule_at->format('H:i:s').'") > 0), true, false) as open')->where('id', $request->restaurant_id)->first();

        if(!$restaurant) {
            return response()->json([
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.restaurant_not_found')]
                ]
            ], 404);
        }


        $rest_sub=$restaurant?->restaurant_sub;
        if ($restaurant->restaurant_model == 'subscription' && isset($rest_sub)) {
            if($rest_sub->max_order != "unlimited" && $rest_sub->max_order <= 0){
                return response()->json([
                    'errors' => [
                        ['code' => 'order-confirmation-error', 'message' => translate('messages.Sorry_the_restaurant_is_unable_to_take_any_order_!')]
                    ]
                ], 403);
            }
        }
        elseif( $restaurant->restaurant_model == 'unsubscribed'){
            return response()->json([
                'errors' => [
                    ['code' => 'order-confirmation-model', 'message' => translate('messages.Sorry_the_restaurant_is_unable_to_take_any_order_!')]
                ]
            ], 403);
        }


        if($request->schedule_at && !$restaurant->schedule_order){
            return response()->json([
                'errors' => [
                    ['code' => 'schedule_at', 'message' => translate('messages.schedule_order_not_available')]
                ]
            ], 406);
        }

        if($restaurant->open == false && !$request->subscription_order){
            return response()->json([
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.restaurant_is_closed_at_order_time')]
                ]
            ], 406);
        }

        $instant_order = BusinessSetting::where('key', 'instant_order')->first()?->value;
        if(($instant_order != 1 || $restaurant->restaurant_config?->instant_order != 1) && !$request->schedule_at && !$request->subscription_order){
            return response()->json([
                'errors' => [
                    ['code' => 'instant_order', 'message' => translate('messages.instant_order_is_not_available_for_now!')]
                ]
            ], 403);
        }



        DB::beginTransaction();



        if ($request['coupon_code']) {
            $coupon = Coupon::active()->where(['code' => $request['coupon_code']])->first();
            if (isset($coupon)) {
                if($request->is_guest){
                    $staus = CouponLogic::is_valid_for_guest(coupon: $coupon, restaurant_id: $request['restaurant_id']);
                }else{
                    $staus = CouponLogic::is_valide(coupon: $coupon, user_id: $request->user->id ,restaurant_id: $request['restaurant_id']);
                }

                $message= match($staus){
                    407 => translate('messages.coupon_expire'),
                    408 => translate('messages.You_are_not_eligible_for_this_coupon'),
                    406 => translate('messages.coupon_usage_limit_over'),
                    404 => translate('messages.not_found'),
                    default => null ,
                };
                if ($message != null) {
                    return response()->json([
                        'errors' => [
                            ['code' => 'coupon', 'message' => $message]
                        ]
                    ], $staus);
                }
                $coupon->increment('total_uses');

                $coupon_created_by =$coupon->created_by;
                if($coupon->coupon_type == 'free_delivery'){
                    $delivery_charge = 0;
                    $free_delivery_by =  $coupon_created_by;
                    $coupon_created_by = null;
                    $coupon = null;
                }

            } else {
                return response()->json([
                    'errors' => [
                        ['code' => 'coupon', 'message' => translate('messages.not_found')]
                    ]
                ], 404);
            }
        }


        $data = Helpers::vehicle_extra_charge(distance_data:$distance_data);
        $extra_charges = (float) (isset($data) ? $data['extra_charge']  : 0);
        $vehicle_id= (isset($data) ? (int) $data['vehicle_id']  : null);

        if($request->latitude && $request->longitude){
            $zone = Zone::where('id', $restaurant->zone_id)->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))->first();            if(!$zone)
            {
                $errors = [];
                array_push($errors, ['code' => 'coordinates', 'message' => translate('messages.out_of_coverage')]);
                return response()->json([
                    'errors' => $errors
                ], 403);
            }
            if( $zone->per_km_shipping_charge && $zone->minimum_shipping_charge ) {
                $per_km_shipping_charge = $zone->per_km_shipping_charge;
                $minimum_shipping_charge = $zone->minimum_shipping_charge;
                $maximum_shipping_charge = $zone->maximum_shipping_charge;
                $max_cod_order_amount_value= $zone->max_cod_order_amount;
                if($zone->increased_delivery_fee_status == 1){
                    $increased=$zone->increased_delivery_fee ?? 0;
                }
            }
        }


        if($request['order_type'] != 'take_away' && !$restaurant->free_delivery &&  !isset($delivery_charge) && ($restaurant->restaurant_model == 'subscription' && isset($restaurant->restaurant_sub) && $restaurant->restaurant_sub->self_delivery == 1  || $restaurant->restaurant_model == 'commission' &&  $restaurant->self_delivery_system == 1 )){
                $per_km_shipping_charge = $restaurant->per_km_shipping_charge;
                $minimum_shipping_charge = $restaurant->minimum_shipping_charge;
                $maximum_shipping_charge = $restaurant->maximum_shipping_charge;
                $extra_charges= 0;
                $vehicle_id=null;
                $increased=0;
        }

        if($restaurant->free_delivery || $free_delivery_by == 'vendor' ){
            $per_km_shipping_charge = $restaurant->per_km_shipping_charge;
            $minimum_shipping_charge = $restaurant->minimum_shipping_charge;
            $maximum_shipping_charge = $restaurant->maximum_shipping_charge;
            $extra_charges= 0;
            $vehicle_id=null;
            $increased=0;
        }

        $original_delivery_charge = ($request->distance * $per_km_shipping_charge > $minimum_shipping_charge) ? $request->distance * $per_km_shipping_charge + $extra_charges  : $minimum_shipping_charge + $extra_charges;

        if($request['order_type'] == 'take_away')
        {
            $per_km_shipping_charge = 0;
            $minimum_shipping_charge = 0;
            $maximum_shipping_charge = 0;
            $extra_charges= 0;
            $distance_data = 0;
            $vehicle_id=null;
            $increased=0;
            $original_delivery_charge =0;
        }

        if ( $maximum_shipping_charge  > $minimum_shipping_charge  && $original_delivery_charge >  $maximum_shipping_charge ){
            $original_delivery_charge = $maximum_shipping_charge;
        }
        else{
            $original_delivery_charge = $original_delivery_charge;
        }

        if(!isset($delivery_charge)){
            $delivery_charge = ($request->distance * $per_km_shipping_charge > $minimum_shipping_charge) ? $request->distance * $per_km_shipping_charge : $minimum_shipping_charge;
            if ( $maximum_shipping_charge  > $minimum_shipping_charge  && $delivery_charge + $extra_charges >  $maximum_shipping_charge ){
                $delivery_charge =$maximum_shipping_charge;
            }
            else{
                $delivery_charge =$extra_charges + $delivery_charge;
            }
        }


        if($increased > 0 ){
            if($delivery_charge > 0){
                $increased_fee = ($delivery_charge * $increased) / 100;
                $delivery_charge = $delivery_charge + $increased_fee;
            }
            if($original_delivery_charge > 0){
                $increased_fee = ($original_delivery_charge * $increased) / 100;
                $original_delivery_charge = $original_delivery_charge + $increased_fee;
            }
        }
        $address = [
            'contact_person_name' => $request->contact_person_name ? $request->contact_person_name : ($request->user?$request->user->f_name . ' ' . $request->user->f_name:''),
            'contact_person_number' => $request->contact_person_number ? ($request->user ? $request->contact_person_number :str_replace('+', '', $request->contact_person_number)) : ($request->user?$request->user->phone:''),
            'contact_person_email' => $request->contact_person_email ? $request->contact_person_email : ($request->user?$request->user->email:''),
            'address_type' => $request->address_type?$request->address_type:'Delivery',
            'address' => $request->address,
            'floor' => $request->floor,
            'road' => $request->road,
            'house' => $request->house,
            'longitude' => (string)$request->longitude,
            'latitude' => (string)$request->latitude,
        ];

        $total_addon_price = 0;
        $product_price = 0;
        $restaurant_discount_amount = 0;

        $order_details = [];
        $order = new Order();
        $order->id = 100000 + Order::count() + 1;
        if (Order::find($order->id)) {
            $order->id = Order::orderBy('id', 'desc')->first()->id + 1;
        }


        $order_status ='pending';
        if(($request->partial_payment && $request->payment_method != 'offline_payment') || $request->payment_method == 'wallet' ){
            $order_status ='confirmed';
        }


        $order->distance = $distance_data;
        $order->user_id = $request->user ? $request->user->id : $request['guest_id'];
        $order->order_amount = $request['order_amount'];
        $order->payment_status = ($request->partial_payment ? 'partially_paid' : ($request['payment_method'] == 'wallet' ? 'paid' : 'unpaid'));
        $order->order_status = $order_status;
        $order->coupon_code = $request['coupon_code'];
        $order->payment_method = $request->partial_payment? 'partial_payment' :$request->payment_method;
        $order->transaction_reference = null;
        $order->order_note = $request['order_note'];
        $order->order_type = $request['order_type'];
        $order->restaurant_id = $request['restaurant_id'];
        $order->delivery_charge = round($delivery_charge, config('round_up_to_digit'))??0;
        $order->original_delivery_charge = round($original_delivery_charge, config('round_up_to_digit'));
        $order->delivery_address = json_encode($address);
        $order->schedule_at = $schedule_at;
        $order->scheduled = $request->schedule_at?1:0;
        $order->is_guest = $request->user ? 0 : 1;
        $order->otp = rand(1000, 9999);
        $order->zone_id = $restaurant->zone_id;
        $dm_tips_manage_status = BusinessSetting::where('key', 'dm_tips_status')->first()->value;
        if ($dm_tips_manage_status == 1) {
            $order->dm_tips = $request->dm_tips ?? 0;
        } else {
            $order->dm_tips = 0;
        }
        $order->vehicle_id = $vehicle_id;
        $order->pending = now();

        if ($order_status == 'confirmed') {
            $order->confirmed = now();
        }

        $order->created_at = now();
        $order->updated_at = now();

        $order->cutlery = $request->cutlery ? 1 : 0;
        $order->unavailable_item_note = $request->unavailable_item_note ?? null ;
        $order->delivery_instruction = $request->delivery_instruction ?? null ;
        $order->tax_percentage = $restaurant->tax ;


        $carts = Cart::where('user_id', $order->user_id)->where('is_guest',$order->is_guest)
        ->when(isset($request->is_buy_now) && $request->is_buy_now == 1 && $request->cart_id, function ($query) use ($request) {
            return $query->where('id',$request->cart_id);
        })
        ->get()->map(function ($data) {
            $data->add_on_ids = json_decode($data->add_on_ids,true);
            $data->add_on_qtys = json_decode($data->add_on_qtys,true);
            $data->variations = json_decode($data->variations,true);
			return $data;
		});

        if(isset($request->is_buy_now) && $request->is_buy_now == 1){
            $carts = $request['cart'];
        }

        foreach ($carts as $c) {

            if ($c['item_type'] === 'App\Models\ItemCampaign' || $c['item_type'] === 'AppModelsItemCampaign')  {
                $product = ItemCampaign::active()->find($c['item_id']);
                $campaign_id = $c['item_id'];
                $code = 'campaign';
            } else{
                $product = Food::active()->find($c['item_id']);
                $food_id = $c['item_id'];
                $code = 'food';
            }

            if($product->restaurant_id != $request['restaurant_id']){
                return response()->json([
                    'errors' => [
                        ['code' => 'restaurant', 'message' => translate('messages.you_need_to_order_food_from_single_restaurant')],
                    ]
                ], 406);
            }

            if ($product) {
                if($product->maximum_cart_quantity && ($c['quantity'] > $product->maximum_cart_quantity)){
                    return response()->json([
                        'errors' => [
                            ['code' => 'quantity', 'message' =>$product?->name ?? $product?->title ?? $code.' '.translate('messages.has_reached_the_maximum_cart_quantity_limit')]
                        ]
                    ], 406);
                }

                $addon_data = Helpers::calculate_addon_price(addons: \App\Models\AddOn::whereIn('id',$c['add_on_ids'])->get(), add_on_qtys: $c['add_on_qtys']);

                    if($code == 'food'){
                        $variation_options =  is_string(data_get($c,'variation_options')) ? json_decode(data_get($c,'variation_options') ,true) : [];
                        $addonAndVariationStock= Helpers::addonAndVariationStockCheck(product:$product,quantity: $c['quantity'],add_on_qtys:$c['add_on_qtys'], variation_options:$variation_options,add_on_ids:$c['add_on_ids'],incrementCount: true );
                            if(data_get($addonAndVariationStock, 'out_of_stock') != null) {
                                return response()->json([
                                    'errors' => [
                                        ['code' => data_get($addonAndVariationStock, 'type') ?? 'food', 'message' =>data_get($addonAndVariationStock, 'out_of_stock') ],
                                    ]
                                ], 406);
                            }
                        }

                $product_variations = json_decode($product->variations, true);
                $variations=[];
                if (count($product_variations)) {
                    $variation_data = Helpers::get_varient($product_variations, $c['variations']);
                    $price = $product['price'] + $variation_data['price'];
                    $variations = $variation_data['variations'];
                } else {
                    $price = $product['price'];
                }

                $product->tax = $restaurant->tax;

                $product = Helpers::product_data_formatting($product, false, false, app()->getLocale());

                $or_d = [
                    'food_id' => $food_id ??  null,
                    'item_campaign_id' => $campaign_id ?? null,
                    'food_details' => json_encode($product),
                    'quantity' => $c['quantity'],
                    'price' => round($price, config('round_up_to_digit')),
                    'tax_amount' => Helpers::tax_calculate(food:$product, price:$price),
                    'discount_on_food' => Helpers::product_discount_calculate(product:$product, price:$price, restaurant:$restaurant),
                    'discount_type' => 'discount_on_product',
                    'variation' => json_encode($variations),
                    'add_ons' => json_encode($addon_data['addons']),
                    'total_add_on_price' => $addon_data['total_add_on_price'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $order_details[] = $or_d;
                $total_addon_price += $or_d['total_add_on_price'];
                $product_price += $price*$or_d['quantity'];
                $restaurant_discount_amount += $or_d['discount_on_food']*$or_d['quantity'];

            } else {
                return response()->json([
                    'errors' => [
                        ['code' => $code ?? null, 'message' => translate('messages.product_unavailable_warning')]
                    ]
                ], 404);
            }
        }


        $order->discount_on_product_by = 'vendor';

        $restaurant_discount = Helpers::get_restaurant_discount(restaurant:$restaurant);
        if(isset($restaurant_discount)){
            $order->discount_on_product_by = 'admin';

            if($product_price + $total_addon_price < $restaurant_discount['min_purchase']){
                $restaurant_discount_amount = 0;
            }

            if($restaurant_discount_amount > $restaurant_discount['max_discount']){
                $restaurant_discount_amount = $restaurant_discount['max_discount'];
            }
        }

        $coupon_discount_amount = $coupon ? CouponLogic::get_discount(coupon:$coupon, order_amount: $product_price + $total_addon_price - $restaurant_discount_amount) : 0;
        $total_price = $product_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount ;

        if($order->is_guest  == 0 && $order->user_id  && !($request->subscription_order && $request->subscription_quantity) ){
            $user= User::withcount('orders')->find($order->user_id);
            $discount_data= Helpers::getCusromerFirstOrderDiscount(order_count:$user->orders_count ,user_creation_date:$user->created_at,  refby:$user->ref_by, price: $total_price);
                if(data_get($discount_data,'is_valid') == true &&  data_get($discount_data,'calculated_amount') > 0){
                    $total_price = $total_price - data_get($discount_data,'calculated_amount');
                    $order->ref_bonus_amount = data_get($discount_data,'calculated_amount');
                }
        }

        $tax = ($restaurant->tax > 0)?$restaurant->tax:0;
        $order->tax_status = 'excluded';

        $tax_included =BusinessSetting::where(['key'=>'tax_included'])->first() ?  BusinessSetting::where(['key'=>'tax_included'])->first()->value : 0;
        if ($tax_included ==  1){
            $order->tax_status = 'included';
        }

        $total_tax_amount=Helpers::product_tax(price:$total_price, tax:$tax, is_include:$order->tax_status =='included');

        $tax_a=$order->tax_status =='included'?0:$total_tax_amount;

        if($restaurant->minimum_order > $product_price + $total_addon_price )
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order_amount', 'message' => translate('messages.you_need_to_order_at_least').' '. $restaurant->minimum_order.' '.Helpers::currency_code()],
                ]
            ], 406);
        }

        $free_delivery_over = BusinessSetting::where('key', 'free_delivery_over')->first()->value;
        if(isset($free_delivery_over))
        {
            if($free_delivery_over <= $product_price + $total_addon_price - $coupon_discount_amount - $restaurant_discount_amount)
            {
                $order->delivery_charge = 0;
                $free_delivery_by = 'admin';
            }
        }

        $free_delivery_distance = BusinessSetting::where('key', 'free_delivery_distance')->first()->value;
        if($restaurant->self_delivery_system == 0 && isset($free_delivery_distance))
        {
            if($request->distance <= $free_delivery_distance)
            {
                $order->delivery_charge = 0;
                $free_delivery_by = 'admin';
            }
        }

        if($restaurant->free_delivery){
            $order->delivery_charge = 0;
            $free_delivery_by = 'vendor';
        }

        if($restaurant->self_delivery_system == 1 && $restaurant->free_delivery_distance_status == 1 && $restaurant->free_delivery_distance_value && ($request->distance <= $restaurant->free_delivery_distance_value)){
            $order->delivery_charge = 0;
            $free_delivery_by = 'vendor';
        }

        $order->coupon_created_by = $coupon_created_by;
                //Added service charge
                $additional_charge_status = BusinessSetting::where('key', 'additional_charge_status')->first()?->value;
                $additional_charge = BusinessSetting::where('key', 'additional_charge')->first()?->value;
                if ($additional_charge_status == 1) {
                    $order->additional_charge = $additional_charge ?? 0;
                } else {
                    $order->additional_charge = 0;
                }

        //Extra packaging charge
        $extra_packaging_data = BusinessSetting::where('key', 'extra_packaging_charge')->first()?->value ?? 0;
        $order->extra_packaging_amount =  ($extra_packaging_data == 1 && $restaurant?->restaurant_config?->is_extra_packaging_active == 1  && $request?->extra_packaging_amount > 0)?$restaurant?->restaurant_config?->extra_packaging_amount:0;

        $order_amount = round($total_price + $tax_a + $order->delivery_charge + $order->additional_charge + $order->extra_packaging_amount, config('round_up_to_digit'));
        if($request->payment_method == 'wallet' && $request->user->wallet_balance < $order_amount)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order_amount', 'message' => translate('messages.insufficient_balance')]
                ]
            ], 203);
        }
        if ($request->partial_payment && $request->user->wallet_balance > $order->order_amount) {
            return response()->json([
                'errors' => [
                    ['code' => 'partial_payment', 'message' => translate('messages.order_amount_must_be_greater_than_wallet_amount')]
                ]
            ], 203);
        }
        try {
            $order->coupon_discount_amount = round($coupon_discount_amount, config('round_up_to_digit'));
            $order->coupon_discount_title = $coupon ? $coupon->title : '';
            $order->free_delivery_by = $free_delivery_by;
            $order->restaurant_discount_amount= round($restaurant_discount_amount, config('round_up_to_digit'));
            $order->total_tax_amount= round($total_tax_amount, config('round_up_to_digit'));
            $order->order_amount = $order_amount + $order->dm_tips;


            if( $max_cod_order_amount_value > 0 && $order->payment_method == 'cash_on_delivery' && $order->order_amount > $max_cod_order_amount_value){
            return response()->json([
                'errors' => [
                    ['code' => 'order_amount', 'message' => translate('messages.You can not Order more then ').$max_cod_order_amount_value .Helpers::currency_symbol().' '. translate('messages.on COD order.')]
                ]
            ], 203);
            }

            // DB::beginTransaction();

            // new Order Subscription create
            if($request->subscription_order && $request->subscription_quantity){
                $subscription = new Subscription();
                $subscription->status = 'active';
                $subscription->start_at = $request->subscription_start_at;
                $subscription->end_at = $request->subscription_end_at;
                $subscription->type = $request->subscription_type;
                $subscription->quantity = $request->subscription_quantity;
                $subscription->user_id = $request->user->id;
                $subscription->restaurant_id = $restaurant->id;
                $subscription->save();
                $order->subscription_id = $subscription->id;
                // $subscription_schedules =  Helpers::get_subscription_schedules($request->subscription_type, $request->subscription_start_at, $request->subscription_end_at, json_decode($request->days, true));

                $days = array_map(function($day)use($subscription){
                    $day['subscription_id'] = $subscription->id;
                    $day['type'] = $subscription->type;
                    $day['created_at'] = now();
                    $day['updated_at'] = now();
                    return $day;
                },json_decode($request->subscription_days, true));
                // info(['SubscriptionSchedule_____', $days]);
                SubscriptionSchedule::insert($days);

                // $order->checked = 1;
            }

            $order->save();
            // new Order Subscription logs create for the order
            OrderLogic::create_subscription_log(id:$order->id);
            // End Order Subscription.

            foreach ($order_details as $key => $item) {
                $order_details[$key]['order_id'] = $order->id;

                if($restaurant_discount_amount <= 0 ){
                    $order_details[$key]['discount_on_food'] = 0;
                }
            }
            OrderDetail::insert($order_details);

            if(!isset($request->is_buy_now) || (isset($request->is_buy_now) && $request->is_buy_now == 0 )){
                foreach ($carts as $cart) {
                    $cart->delete();
                }
            }



            $restaurant->increment('total_order');

            if($request->user){
                $customer = $request->user;
                $customer->zone_id = $restaurant->zone_id;
                $customer->save();

            Helpers::visitor_log(model: 'restaurant', user_id:$customer->id, visitor_log_id:$restaurant->id, order_count:true);
            }
            if($request->payment_method == 'wallet') CustomerLogic::create_wallet_transaction(user_id:$order->user_id, amount:$order->order_amount, transaction_type:'order_place', referance:$order->id);

            if ($request->partial_payment) {
                if ($request->user->wallet_balance<=0) {
                    return response()->json([
                        'errors' => [
                            ['code' => 'order_amount', 'message' => translate('messages.insufficient_balance_for_partial_amount')]
                        ]
                    ], 203);
                }
                $p_amount = min($request->user->wallet_balance, $order->order_amount);
                $unpaid_amount = $order->order_amount - $p_amount;
                $order->partially_paid_amount = $p_amount;
                $order->save();
                CustomerLogic::create_wallet_transaction($order->user_id, $p_amount, 'partial_payment', $order->id);
                OrderLogic::create_order_payment(order_id:$order->id, amount:$p_amount, payment_status:'paid', payment_method:'wallet');
                OrderLogic::create_order_payment(order_id:$order->id, amount:$unpaid_amount, payment_status:'unpaid',payment_method:$request->payment_method);
            }


            if($order->is_guest  == 0 && $order->user_id && !($request->subscription_order && $request->subscription_quantity) ){
                $this->createCashBackHistory($order->order_amount, $order->user_id,$order->id);
            }

            DB::commit();
            //PlaceOrderMail
            $order_mail_status = Helpers::get_mail_status('place_order_mail_status_user');
            $order_verification_mail_status = Helpers::get_mail_status('order_verification_mail_status_user');
            try {
                if($request->payment_method != 'digital_payment' && config('mail.status')){

                    $notification_status= Helpers::getNotificationStatusData('customer','customer_order_notification');

                    if($notification_status?->mail_status == 'active' && $order->order_status == 'pending' && $order_mail_status == '1'&& $request->user) {
                        Mail::to($request->user->email)->send(new PlaceOrder($order->id));
                        }
                    if($notification_status?->mail_status == 'active' && $order->is_guest == 1 && $order->order_status == 'pending' && $order_mail_status == '1' && isset($request->contact_person_email)) {
                        Mail::to($request->contact_person_email)->send(new PlaceOrder($order->id));
                        }

                    $notification_status=null ;
                    $notification_status= Helpers::getNotificationStatusData('customer','customer_delivery_verification');

                    if($notification_status?->mail_status == 'active' && $order->order_status == 'pending' && config('order_delivery_verification') == 1 && $order_verification_mail_status == '1'&& $request->user) {
                        Mail::to($request->user->email)->send(new OrderVerificationMail($order->otp,$request->user->f_name));
                    }

                    if($notification_status?->mail_status == 'active' && $order->is_guest == 1 && $order->order_status == 'pending' && config('order_delivery_verification') == 1 && $order_verification_mail_status == '1' && isset($request->contact_person_email)) {
                        Mail::to($request->contact_person_email)->send(new OrderVerificationMail($order->otp,$request->contact_person_name));
                    }
                }


            }catch (\Exception $ex) {
                info($ex);
            }
            return response()->json([
                'message' => translate('messages.order_placed_successfully'),
                'order_id' => $order->id,
                'total_ammount' => $total_price+$order->delivery_charge+$tax_a
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return response()->json([$e->getMessage()], 403);
        }

        return response()->json([
            'errors' => [
                ['code' => 'order_time', 'message' => translate('messages.failed_to_place_order')]
            ]
        ], 403);
    }

    public function get_order_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];

        $paginator = Order::with(['restaurant', 'delivery_man.rating'])->withCount('details')->where(['user_id' => $user_id])->
        whereIn('order_status', ['delivered','canceled','refund_requested','refund_request_canceled','refunded','failed'])->Notpos()
        ->whereNull('subscription_id')
        ->when(!isset($request->user) , function($query){
            $query->where('is_guest' , 1);
        })

        ->when(isset($request->user)  , function($query){
            $query->where('is_guest' , 0);
        })

        ->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $orders = array_map(function ($data) {
            $data['delivery_address'] = $data['delivery_address']?json_decode($data['delivery_address']):$data['delivery_address'];
            $data['restaurant'] = $data['restaurant']?Helpers::restaurant_data_formatting($data['restaurant']):$data['restaurant'];
            $data['delivery_man'] = $data['delivery_man']?Helpers::deliverymen_data_formatting([$data['delivery_man']]):$data['delivery_man'];
            $data['is_reviewed'] =   $data['details_count'] >  Review::whereOrderId($data->id)->count() ? False :True ;
            $data['is_dm_reviewed'] = $data['delivery_man'] ? DMReview::whereOrderId($data->id)->exists()  : True ;
            return $data;
        }, $paginator->items());
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return response()->json($data, 200);
    }
    public function get_order_subscription_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $paginator = Order::with(['restaurant', 'delivery_man.rating'])->withCount('details')->where(['user_id' => $user_id])
        ->Notpos()
        ->whereNotNull('subscription_id')
        ->when(!isset($request->user) , function($query){
            $query->where('is_guest' , 1);
        })

        ->when(isset($request->user)  , function($query){
            $query->where('is_guest' , 0);
        })
        ->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $orders = array_map(function ($data) {
            $data['delivery_address'] = $data['delivery_address']?json_decode($data['delivery_address']):$data['delivery_address'];
            $data['restaurant'] = $data['restaurant']?Helpers::restaurant_data_formatting($data['restaurant']):$data['restaurant'];
            $data['delivery_man'] = $data['delivery_man']?Helpers::deliverymen_data_formatting([$data['delivery_man']]):$data['delivery_man'];
            $data['is_reviewed'] =   $data['details_count'] >  Review::whereOrderId($data->id)->count() ? False :True ;
            $data['is_dm_reviewed'] =  $data['delivery_man'] ? DMReview::whereOrderId($data->id)->exists()  : True ;

            return $data;
        }, $paginator->items());
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return response()->json($data, 200);
    }


    public function get_running_orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];

        $paginator = Order::with(['restaurant', 'delivery_man.rating'])->withCount('details')->where(['user_id' => $user_id])
        ->whereNull('subscription_id')
        ->whereNotIn('order_status', ['delivered','canceled','refund_requested','refund_request_canceled','refunded','failed'])
        ->when(!isset($request->user) , function($query){
            $query->where('is_guest' , 1);
        })

        ->when(isset($request->user)  , function($query){
            $query->where('is_guest' , 0);
        })
        ->Notpos()->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $orders = array_map(function ($data) {
            $data['delivery_address'] = $data['delivery_address']?json_decode($data['delivery_address']):$data['delivery_address'];
            $data['restaurant'] = $data['restaurant']?Helpers::restaurant_data_formatting($data['restaurant']):$data['restaurant'];
            $data['delivery_man'] = $data['delivery_man']?Helpers::deliverymen_data_formatting([$data['delivery_man']]):$data['delivery_man'];
            return $data;
        }, $paginator->items());
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return response()->json($data, 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $order = Order::with('details','offline_payments','subscription.schedules')->where('user_id', $user_id)

        ->when(!isset($request->user) , function($query){
            $query->where('is_guest' , 1);
        })

        ->when(isset($request->user)  , function($query){
            $query->where('is_guest' , 0);
        })
        ->find($request->order_id);
        $details = $order?->details;

        if ($details != null && $details->count() > 0) {
            $storage = [];
            foreach ($details as $item) {
                $item['add_ons'] = json_decode($item['add_ons']);
                $item['variation'] = json_decode($item['variation']);
                $item['food_details'] = json_decode($item['food_details'], true);
                $item['zone_id'] = (int) (isset($order->zone_id) ? $order->zone_id :  $order->restaurant->zone_id);
                array_push($storage, $item);
            }
            $data = $storage;
            $subscription_schedules =  $order?->subscription?->schedules;
            $offline_payment = isset($order->offline_payments) ? Helpers::offline_payment_formater($order->offline_payments) : null;

            return response()->json(['details'=>$data, 'subscription_schedules'=> $subscription_schedules, 'offline_payment' => $offline_payment
            ], 200);
        }

        else {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.not_found')]
                ]
            ], 200);
        }
    }

    public function cancel_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|max:255',
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $order = Order::where(['user_id' => $user_id, 'id' => $request['order_id']])

        ->when(!isset($request->user) , function($query){
            $query->where('is_guest' , 1);
        })

        ->when(isset($request->user)  , function($query){
            $query->where('is_guest' , 0);
        })
        ->with('details')
        ->Notpos()->first();
        if(!$order){
                return response()->json([
                    'errors' => [
                        ['code' => 'order', 'message' => translate('messages.not_found')]
                    ]
                ], 404);
        }
        else if ($order->order_status == 'pending' || $order->order_status == 'failed' || $order->order_status == 'canceled'  ) {
            $order->order_status = 'canceled';
            $order->canceled = now();
            $order->cancellation_reason = $request->reason;
            $order->canceled_by = 'customer';
            $order->save();

            Helpers::decreaseSellCount(order_details:$order->details);
            Helpers::send_order_notification($order);
            Helpers::increment_order_count($order->restaurant); //for subscription package order increase


            $wallet_status= BusinessSetting::where('key','wallet_status')->first()?->value;
            $refund_to_wallet= BusinessSetting::where('key', 'wallet_add_refund')->first()?->value;

            if($order?->payments && $order?->is_guest == 0){
                $refund_amount =$order->payments()->where('payment_status','paid')->sum('amount');
                if($wallet_status &&  $refund_to_wallet && $refund_amount > 0){
                    CustomerLogic::create_wallet_transaction(user_id:$order->user_id, amount:$refund_amount,transaction_type: 'order_refund',referance: $order->id);

                    return response()->json(['message' => translate('messages.order_canceled_successfully_and_refunded_to_wallet')], 200);
                } else {
                    return response()->json(['message' => translate('messages.order_canceled_successfully_and_for_refund_amount_contact_admin')], 200);
                }
            }


            return response()->json(['message' => translate('messages.order_canceled_successfully')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('messages.you_can_not_cancel_after_confirm')]
            ]
        ], 403);
    }

    public function refund_reasons(){
        $refund_reasons=RefundReason::where('status',1)->get();
        return response()->json([
            'refund_reasons' => $refund_reasons
        ], 200);
    }

    public function refund_request(Request $request)
    {
        if(BusinessSetting::where(['key'=>'refund_active_status'])->first()->value == false){
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('You can not request for a refund')]
                ]
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'customer_reason' => 'required|string|max:254',
            'refund_method'=>'nullable|string|max:100',
            'customer_note'=>'nullable|string|max:65535',
            'image.*' => 'nullable|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $order = Order::where(['user_id' => $user_id, 'id' => $request['order_id']])


        ->when(!isset($request->user) , function($query){
            $query->where('is_guest' , 1);
        })

        ->when(isset($request->user)  , function($query){
            $query->where('is_guest' , 0);
        })
        ->Notpos()->first();
        if(!$order){
                return response()->json([
                    'errors' => [
                        ['code' => 'order', 'message' => translate('messages.not_found')]
                    ]
                ], 404);
        }

        else if ($order->order_status == 'delivered' && $order->payment_status == 'paid') {

            $id_img_names = [];
            if ($request->has('image')) {
                foreach ($request->file('image') as $img) {
                    $image_name = Helpers::upload(dir:'refund/', format:'png', image:$img);
                    array_push($id_img_names, $image_name);
                }
                $images = json_encode($id_img_names);
            } else {
                $images = json_encode([]);
                // return response()->json(['message' => 'no_image'], 200);
            }

            $refund_amount = round($order->order_amount - $order->delivery_charge- $order->dm_tips , config('round_up_to_digit'));

            $refund = new Refund();
            $refund->order_id = $order->id;
            $refund->user_id = $order->user_id;
            $refund->order_status= $order->order_status;
            $refund->refund_status= 'pending';
            $refund->refund_method= $request->refund_method ?? 'wallet';
            $refund->customer_reason= $request->customer_reason;
            $refund->customer_note= $request->customer_note;
            $refund->refund_amount= $refund_amount;
            $refund->image = $images;
            $refund->save();

            $order->order_status = 'refund_requested';
            $order->refund_requested = now();
            $order->save();
            // Helpers::send_order_notification($order);

            $admin = Admin::where('role_id',1)->first();
            try {
                $notification_status= Helpers::getNotificationStatusData('admin','order_refund_request');

                if($notification_status?->mail_status == 'active' && config('mail.status') && $admin['email'] && Helpers::get_mail_status('refund_request_mail_status_admin') == '1') {
                    Mail::to($admin['email'])->send(new RefundRequest($order->id));
                }
            } catch (\Exception $ex) {
                info($ex->getMessage());
            }
            return response()->json(['message' => translate('messages.refund_request_placed_successfully')], 200);
        }

        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('messages.you_can_not_request_for_refund_after_delivery')]
            ]
        ], 403);
    }

    public function update_payment_method(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $config=Helpers::get_business_settings('cash_on_delivery');
        if($config['status']==0)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'cod', 'message' => translate('messages.Cash on delivery order not available at this time')]
                ]
            ], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $order = Order::where(['user_id' => $user_id, 'id' => $request['order_id']])->Notpos()->first();
        if ($order) {
            Order::where(['user_id' =>$user_id, 'id' => $request['order_id']])->update([
                'payment_method' => 'cash_on_delivery', 'order_status'=>'pending', 'pending'=> now()
            ]);
            $order_mail_status = Helpers::get_mail_status('place_order_mail_status_user');
            $order_verification_mail_status = Helpers::get_mail_status('order_verification_mail_status_user');
            $address = json_decode($order->delivery_address, true);
            try {

        Helpers::send_order_notification($order);
        $notification_status= Helpers::getNotificationStatusData('customer','customer_order_notification');

                if($notification_status?->mail_status == 'active' && $order->is_guest == 0 && config('mail.status') && $order_mail_status == '1'&& $order->customer) {
                    Mail::to($order->customer->email)->send(new PlaceOrder($order->id));
                }
                if($notification_status?->mail_status == 'active' && $order->is_guest == 1 && config('mail.status') && $order_mail_status == '1' && isset($address['contact_person_email'])) {
                    Mail::to($address['contact_person_email'])->send(new PlaceOrder($order->id));
                }

            } catch (\Exception $e) {
                info($e->getMessage());
            }
            return response()->json(['message' => translate('messages.payment_method_updated_successfully')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('messages.not_found')]
            ]
        ], 404);
    }

    public function cancellation_reason(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $limit = $request->query('limit', 1);
        $offset = $request->query('offset', 1);

        $reasons = OrderCancelReason::where('status', 1)->when($request->type,function($query) use($request){
        return $query->where('user_type',$request->type);
        })
        ->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total_size' => $reasons->total(),
            'limit' => $limit,
            'offset' => $offset,
            'reasons' => $reasons->items(),
        ];
        return response()->json($data, 200);
    }


    public function food_list(Request $request){

        $validator = Validator::make($request->all(), [
            'food_id' => 'required',
        ]);

        $food_ids= json_decode($request['food_id'], true);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $product = Food::active()->whereIn('id',$food_ids)->get();
        return response()->json(Helpers::product_data_formatting($product, true, false, app()->getLocale()), 200);
    }


    public function order_notification(Request $request,$order_id){
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $order = Order::where('user_id', $user_id)->where('id',$order_id)->with('restaurant')->first();
        $payments = $order->payments()->where('payment_method','cash_on_delivery')->exists();
        $reload_home= false;
        if( $order && $order->restaurant){
            $restaurant= $order->restaurant;
            $rest_sub=$restaurant?->restaurant_sub;

            if ($restaurant->restaurant_model == 'subscription' && isset($rest_sub) && (!in_array($order->payment_method, ['digital_payment', 'partial_payment', 'offline_payment']) || $payments) ){
                if ($rest_sub->max_order != "unlimited" && $rest_sub->max_order > 0 ) {
                    $rest_sub->decrement('max_order' , 1);
                    $reload_home=$rest_sub->max_order <= 0 ?  true : false;
                    }
            }
        }
        if($order && (!in_array($order->payment_method, ['digital_payment', 'partial_payment', 'offline_payment']) || $payments ) ){
            Helpers::send_order_notification($order);
        }

        return response()->json([
            'reload_home' => $reload_home
        ], 200);
    }

    public function most_tips()
    {
        $data = Order::whereNot('dm_tips',0)->get()->mode('dm_tips');
        $data = ($data && (count($data)>0))?$data[0]:null;
        return response()->json([
            'most_tips_amount' => $data
        ], 200);
    }
    public function order_again(Request $request){
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $longitude= $request->header('longitude') ?? 0;
        $latitude= $request->header('latitude') ?? 0;
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $zone_id= json_decode($request->header('zoneId'), true);
        $data = Restaurant::withOpen($longitude,$latitude)->
        wherehas('orders' ,function($q) use($user_id){
            $q->where('user_id', $user_id)->where('is_guest' , 0)->latest();
        })

        ->withcount('foods')
        ->with(['foods_for_reorder'])
        ->Active()
        ->whereIn('zone_id', $zone_id)
        ->take(20)
        ->orderBy('open', 'desc')
        ->get()
		->map(function ($data) {
			$data->foods = $data->foods_for_reorder->take(5);
            unset($data->foods_for_reorder);
			return $data;
		});
        return response()->json(Helpers::restaurant_data_formatting($data, true), 200);
    }

    public function offline_payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'method_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $config = Helpers::get_mail_status('offline_payment_status');
        if ($config == 0) {
            return response()->json([
                'errors' => [
                    ['code' => 'offline_payment_status', 'message' => translate('messages.offline_payment_for_the_order_not_available_at_this_time')]
                ]
            ], 403);
        }
        $order = Order::find($request->order_id);

        $offline_payment_info = [];
        $method = OfflinePaymentMethod::where(['id'=>$request->method_id,'status'=>1])->first();

        if(!$method || !$order ) {
            return response()->json([
                'errors' => [
                    ['code' => 'offline_payment_order_or_method', 'message' => translate('messages.offline_payment_order_or_method_not_found')]
                ]
            ], 403);
        }

        try{
            $fields = array_column($method->method_informations, 'customer_input');
            $values = $request->all();

            $offline_payment_info['method_id'] = $request->method_id;
            $offline_payment_info['method_name'] = $method->method_name;
            foreach ($fields as $field) {
                if(key_exists($field, $values)) {
                    $offline_payment_info[$field] = $values[$field];
                }
            }

            $OfflinePayments= OfflinePayments::firstOrNew(['order_id' => $order->id]);
            $OfflinePayments->payment_info =json_encode($offline_payment_info);
            $OfflinePayments->customer_note = $request->customer_note;
            $OfflinePayments->method_fields = json_encode($method?->method_fields);
            DB::beginTransaction();
            $OfflinePayments->save();
            $order->save();
            DB::commit();


            $data = [
                'title' => translate('messages.order_push_title'),
                'description' => translate('messages.new_order_push_description'),
                'order_id' => $order->id,
                'image' => '',
                'order_type' => $order->order_type,
                'zone_id' => $order->zone_id,
                'type' => 'new_order',
            ];
            Helpers::send_push_notif_to_topic($data, 'admin_message', 'order_request', url('/').'/admin/order/list/all');

            return response()->json([
                'payment' => 'success'
            ], 200);


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([ 'payment' => $e->getMessage()], 403);
        }
    }


    public function update_offline_payment_info(Request $request){
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
            $info= OfflinePayments::where('order_id' , $request->order_id)->first();
            $order= Order::find($request->order_id);

            if(!$info || !$order ) {
                return response()->json([
                    'errors' => [
                        ['code' => 'offline_payment_order_or_method', 'message' => translate('messages.offline_payment_order_or_method_not_found')]
                    ]
                ], 403);
            }
            $old_data =   json_decode($info->payment_info , true) ;
            $method_id= data_get($old_data,'method_id',null);
            $method = OfflinePaymentMethod::where('id', $method_id)->first();

            if(!$method ) {
            return response()->json([
                'errors' => [
                    ['code' => 'offline_payment_order_or_method', 'message' => translate('messages.offline_payment_method_not_found')]
                ]
            ], 403);
        }
                $offline_payment_info = [];
                    $fields = array_column($method->method_informations, 'customer_input');
                    $values = $request->all();
                    $offline_payment_info['method_id'] =$method->id;
                    $offline_payment_info['method_name'] = $method->method_name;
                    foreach ($fields as $field) {
                        if(key_exists($field, $values)) {
                            $offline_payment_info[$field] = $values[$field];
                        }
                    }

            $info->customer_note = $request->customer_note;
            $info->payment_info =json_encode($offline_payment_info);
            $info->status = 'pending';
            $info->save();

        return response()->json([ 'payment' => 'Payment_Info_Updated_successfully'], 200);
    }

    public function getPendingReviews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $foodIds=[];
        $itemIds=[];

        $orderDetails= OrderDetail::whereOrderId($request->order_id)->get(['id','food_id', 'item_campaign_id','food_details']);
        foreach($orderDetails as $detail){
                $foodIds[]=$detail->food_id;
                $itemIds[]=$detail->item_campaign_id;
        }
        $reviews =   Review::whereOrderId($request->order_id)->where(function($query) use($foodIds ,$itemIds) {
            $query->whereIn('food_id',$foodIds)->orWhereIn('item_campaign_id',$itemIds);
        })->get(['id','food_id','item_campaign_id'])->toArray();

        $reviewedFoodIds = array_column($reviews, 'food_id');
        $reviewedItemIds = array_column($reviews, 'item_campaign_id');
        $storage = [];
        foreach($orderDetails as $detail){
            if(!in_array($detail->food_id, $reviewedFoodIds) || !in_array($detail->item_campaign_id, $reviewedItemIds)){
                $detail['food_details'] = json_decode($detail['food_details'], true);
                $storage[] = $detail;
            }
        }
            return response()->json(['details'=>$storage], 200);
    }


    private function createCashBackHistory($order_amount, $user_id,$order_id){
        $cashBack =  Helpers::getCalculatedCashBackAmount(amount:$order_amount, customer_id:$user_id);
        if(data_get($cashBack,'calculated_amount') > 0){
            $CashBackHistory = new CashBackHistory();
            $CashBackHistory->user_id = $user_id;
            $CashBackHistory->order_id = $order_id;
            $CashBackHistory->calculated_amount = data_get($cashBack,'calculated_amount');
            $CashBackHistory->cashback_amount = data_get($cashBack,'cashback_amount');
            $CashBackHistory->cash_back_id = data_get($cashBack,'id');
            $CashBackHistory->cashback_type = data_get($cashBack,'cashback_type');
            $CashBackHistory->min_purchase = data_get($cashBack,'min_purchase');
            $CashBackHistory->max_discount = data_get($cashBack,'max_discount');
            $CashBackHistory->save();

            $CashBackHistory?->order()->update([
                'cash_back_id'=> $CashBackHistory->id
            ]);
        }
        return true;
    }

}
