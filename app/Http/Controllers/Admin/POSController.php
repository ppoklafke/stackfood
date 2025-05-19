<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Food;
use App\Models\User;
use App\Models\Zone;
use App\Models\AddOn;
use App\Models\Order;
use App\Mail\PlaceOrder;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Scopes\RestaurantScope;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\CustomerLogic;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class POSController extends Controller
{
    public function index(Request $request)
    {
        $time = Carbon::now()->toTimeString();
        $zone_id = $request->query('zone_id', null);
        $restaurant_id = $request->query('restaurant_id', null);
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_data = Restaurant::active()->with('restaurant_sub')->where('zone_id', $zone_id)->find($restaurant_id);
        $category = $request->query('category_id', 0);
        $categories = Category::active()->get(['id','name']);
        $keyword = $request->query('keyword', false);
        $key = explode(' ', $keyword);

        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            if (!isset($cart['restaurant_id']) || $cart['restaurant_id'] != $restaurant_id) {
                session()->forget('cart');
            }
        }
        session()->forget('address');

        $products = Food::withoutGlobalScope(RestaurantScope::class)->active()->when($category != 'all', function ($query) use ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                return $q->whereId($category)->orWhere('parent_id', $category);
            });
        })
            ->where(['restaurant_id' => $restaurant_id])
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('restaurant_id', $zone->restaurants->pluck('id'));
            })
            ->when($keyword, function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->orderByRaw("FIELD(name, ?) DESC", [$request->name])
            ->available($time)
            ->latest()->paginate(12);

        return view('admin-views.pos.index', compact('categories', 'products', 'category', 'keyword', 'restaurant_data', 'zone'));
    }

    public function quick_view(Request $request)
    {
        $product = Food::withoutGlobalScope(RestaurantScope::class)->with('restaurant')->findOrFail($request->product_id);
        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos._quick-view-data', compact('product'))->render(),
        ]);
    }


    public function quick_view_card_item(Request $request)
    {
        $product = Food::withoutGlobalScope(RestaurantScope::class)->findOrFail($request->product_id);
        //dd($product);
        $item_key = $request->item_key;
        $cart_item = session()->get('cart')[$item_key];

        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos._quick-view-cart-item', compact('product', 'cart_item', 'item_key'))->render(),
        ]);
    }

    public function variant_price(Request $request)
    {


        $old_selected_addons=[];
        $old_selected_variations=[];
        $old_selected_without_variation = $request?->old_selected_without_variation ?? 0;

        if($request?->old_selected_variations){
            $old_selected_variations= json_decode($request->old_selected_variations,true)?? [];
        }
        if($request?->old_selected_addons){
            $old_selected_addons= json_decode($request->old_selected_addons,true)?? [];
        }


        $product = Food::withoutGlobalScope(RestaurantScope::class)->with('restaurant')->where(['id' => $request->id])->first();

        $price = $product->price;
        $addon_price = 0;
        $add_on_ids=[];
        $add_on_qtys=[];
        if ($request['addon_id']) {
            foreach ($request['addon_id'] as $id) {
                $add_on_ids[]= $id;
                $add_on_qtys[]= $request['addon-quantity' . $id];

                $addon_price += $request['addon-price' . $id] * $request['addon-quantity' . $id];
            }
        }

        $addonAndVariationStock= Helpers::addonAndVariationStockCheck(product:$product,quantity: $request->quantity,add_on_qtys:$add_on_qtys, variation_options:explode(',',$request?->option_ids),add_on_ids:$add_on_ids, old_selected_variations:$old_selected_variations ,old_selected_without_variation:$old_selected_without_variation ,old_selected_addons:$old_selected_addons  );
        if(data_get($addonAndVariationStock, 'out_of_stock') != null) {
            return response()->json([
                'error' => 'stock_out',  'message' => data_get($addonAndVariationStock, 'out_of_stock'),
                'current_stock' => data_get($addonAndVariationStock, 'current_stock'),
                'id'=> data_get($addonAndVariationStock, 'id'),
                'type'=> data_get($addonAndVariationStock, 'type'),
            ],203);
        }


        $product_variations = json_decode($product->variations, true);
        if ($request->variations && count($product_variations)) {

            $price_total =  $price + Helpers::variation_price(product:$product_variations, variations: $request->variations);
            $price= $price_total - Helpers::product_discount_calculate(product:$product, price:$price_total, restaurant:$product->restaurant);
        } else {
            $price = $product->price - Helpers::product_discount_calculate(product:$product, price:$product->price,restaurant: $product->restaurant);
        }
        return array('price' => Helpers::format_currency(($price * $request->quantity) + $addon_price));
    }

    public function addToCart(Request $request)
    {
        $product = Food::with('restaurant')->withoutGlobalScope(RestaurantScope::class)->where(['id' => $request->id])->first();
        $data = array();
        $data['id'] = $product->id;
        $str = '';
        $variations = [];
        $price = 0;
        $addon_price = 0;
        $variation_price=0;
        $add_on_ids=[];
        $add_on_qtys=[];

        $product_variations = json_decode($product->variations, true);
        if ($request->variations && count($product_variations)) {
            foreach($request->variations  as $key=> $value ){

                if($value['required'] == 'on' &&  isset($value['values']) == false){
                    return response()->json([
                        'data' => 'variation_error',
                        'message' => translate('Please select items from') . ' ' . $value['name'],
                    ]);
                }
                if(isset($value['values'])  && $value['min'] != 0 && $value['min'] > count($value['values']['label'])){
                    return response()->json([
                        'data' => 'variation_error',
                        'message' => translate('Please select minimum ').$value['min'].translate(' For ').$value['name'].'.',
                    ]);
                }
                if(isset($value['values']) && $value['max'] != 0 && $value['max'] < count($value['values']['label'])){
                    return response()->json([
                        'data' => 'variation_error',
                        'message' => translate('Please select maximum ').$value['max'].translate(' For ').$value['name'].'.',
                    ]);
                }
            }
            $variation_data = Helpers::get_varient(product_variations: $product_variations, variations: $request->variations);
            $variation_price = $variation_data['price'];
            $variations = $request->variations;
        }
        $data['variations'] = $variations;
        $data['variant'] = $str;

        $price = $product->price + $variation_price;
        $data['variation_price'] = $variation_price;
        $data['quantity'] = $request['quantity'];
        $data['price'] = $price;
        $data['name'] = $product->name;
        $data['discount'] = Helpers::product_discount_calculate(product:$product,price: $price, restaurant: $product->restaurant);
        $data['image'] = $product->image;
        $data['image_full_url'] = $product->image_full_url;
        $data['add_ons'] = [];
        $data['add_on_qtys'] = [];
        $data['maximum_cart_quantity'] = $product->maximum_cart_quantity;
        $data['variation_option_ids'] = $request?->option_ids ?? null;


        if ($request['addon_id']) {
            foreach ($request['addon_id'] as $id) {
                $add_on_ids[]= $id;
                $add_on_qtys[]= $request['addon-quantity' . $id];

                $addon_price += $request['addon-price' . $id] * $request['addon-quantity' . $id];
                $data['add_on_qtys'][] = $request['addon-quantity' . $id];
            }
            $data['add_ons'] = $request['addon_id'];
        }



        $addonAndVariationStock= Helpers::addonAndVariationStockCheck(product:$product,quantity: $request->quantity,add_on_qtys:$add_on_qtys, variation_options:explode(',',$request?->option_ids),add_on_ids:$add_on_ids );
        if(data_get($addonAndVariationStock, 'out_of_stock') != null) {
            return response()->json([
                'data' => 'stock_out',
                'message' => data_get($addonAndVariationStock, 'out_of_stock'),
                'current_stock' => data_get($addonAndVariationStock, 'current_stock'),
                'id'=> data_get($addonAndVariationStock, 'id'),
                'type'=> data_get($addonAndVariationStock, 'type'),
            ],203);
        }




        $data['addon_price'] = $addon_price;
        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            if(!isset($request->cart_item_key)){

                foreach($cart as $key=> $cartItems){
                    if($key != 'restaurant_id'  &&  $cartItems['id'] == $request->id && (strcmp(json_encode($cartItems['variations']) , json_encode($request['variations'])) === 0 || !isset($request['variations']) ) ){

                        if($cartItems['maximum_cart_quantity'] >= $cartItems['quantity'] +  $request->quantity ){
                            $cart = $cart->map(function ($object, $cartkey) use ($key,$request) {
                                if ($cartkey == $key  ) {
                                    $object['quantity'] = $object['quantity'] + $request->quantity ;
                                }
                                return $object;
                            });
                            $request->session()->put('cart', $cart);

                            return response()->json([
                                'data' => 'cart_readded'
                            ]);
                        }

                        return response()->json([
                            'data' => 1
                        ]);
                    }
                }
            }

            if (isset($request->cart_item_key)) {
                $cart[$request->cart_item_key] = $data;
                $data = 2;
            } else {
                $cart->push($data);
            }
        } else {
            $cart = collect([$data,'restaurant_id'=>$product->restaurant_id]);
            $request->session()->put('cart', $cart);
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function addDeliveryInfo(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'contact_person_name' => 'required|max:254',
            'contact_person_number' => 'required|max:254',
            'floor' => 'nullable|max:254',
            'road' => 'nullable|max:254',
            'house' => 'nullable|max:254',
            'delivery_fee' => 'required|max:254',
            'longitude' => 'required|max:254',
            'latitude' => 'required|max:254',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $address = [
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => 'delivery',
            'address' => $request?->address,
            'floor' => $request?->floor,
            'road' => $request?->road,
            'house' => $request?->house,
            'delivery_fee' => $request->delivery_fee,
            'distance' => $request->distance,
            'longitude' => (string)$request->longitude,
            'latitude' => (string)$request->latitude,
        ];

        $request->session()->put('address', $address);

        return response()->json([
            'data' => $address,
            'view' => view('admin-views.pos._address', compact('address'))->render(),
        ]);
    }

    public function cart_items(Request $request)
    {
        $restaurant_data = Restaurant::find($request->restaurant_id);
        return view('admin-views.pos._cart', compact('restaurant_data'));
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
        }

        return response()->json([], 200);
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
       $product= Food::withoutGlobalScope(RestaurantScope::class)->find($request->food_id);
        if($request->option_ids){
            $addonAndVariationStock= Helpers::addonAndVariationStockCheck(product:$product,quantity: $request->quantity, variation_options:explode(',',$request?->option_ids));
            if(data_get($addonAndVariationStock, 'out_of_stock') != null) {
                return response()->json([
                    'data' => 'stock_out',
                    'message' => data_get($addonAndVariationStock, 'out_of_stock'),
                    'current_stock' => data_get($addonAndVariationStock, 'current_stock'),
                    'id'=> data_get($addonAndVariationStock, 'id'),
                    'type'=> data_get($addonAndVariationStock, 'type'),
                ],203);
            }

        }
        $cart = $request->session()->get('cart', collect([]));
        $cart = $cart->map(function ($object, $key) use ($request) {
            if ($key == $request->key) {
                $object['quantity'] = $request->quantity;
            }
            return $object;
        });
        $request->session()->put('cart', $cart);
        return response()->json([], 200);
    }

    //empty Cart
    public function emptyCart(Request $request)
    {
        session()->forget('cart');
        session()->forget('address');
        return response()->json([], 200);
    }

    public function update_tax(Request $request)
    {
        $cart = $request->session()->get('cart', collect([]));
        $cart['tax'] = $request->tax;
        $request->session()->put('cart', $cart);
        return back();
    }

    public function update_paid(Request $request)
    {
        $cart = $request->session()->get('cart', collect([]));
        $cart['paid'] = $request->paid;
        $request->session()->put('cart', $cart);
        return back();
    }

    public function update_discount(Request $request)
    {
        $cart = $request->session()->get('cart', collect([]));
        $cart['discount'] = $request->discount;
        $cart['discount_type'] = $request->type;
        $request->session()->put('cart', $cart);
        return back();
    }

    public function get_customers(Request $request)
    {
        $key = explode(' ', $request['q']);
        $data = User::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%")
                    ->orWhere('l_name', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%");
            }
        })
        ->where('status', 1)
            ->limit(8)
            ->get([DB::raw('id, CONCAT(f_name, " ", l_name, " (", phone ,")") as text')]);

        return response()->json($data);
    }

    public function place_order(Request $request)
    {
        if(!$request->user_id){
            Toastr::error(translate('messages.no_customer_selected'));
            return back();
        }
        $customer = User::find($request->user_id);
        if(!$request->type){
            Toastr::error(translate('No payment method selected'));
            return back();
        }
        if ($request->session()->has('cart')) {
            if (count($request->session()->get('cart')) < 2) {
                Toastr::error(translate('messages.cart_is_empty'));
                return back();
            }
        } else {
            Toastr::error(translate('messages.cart_is_empty'));
            return back();
        }
        if ($request->session()->has('address')) {
            $address = $request->session()->get('address');
        }else {
            if(!isset($address['delivery_fee'])){
                Toastr::error(translate('messages.please_select_a_valid_delivery_location_on_the_map'));
                return back();
            }
            Toastr::error(translate('messages.delivery_information_is_missing'));
            return back();
        }
        if($request->type == 'wallet' && Helpers::get_business_settings('wallet_status', false) != 1)
        {
            Toastr::error(translate('messages.customer_wallet_is_disable'));
            return back()->withInput()->with('customer', $customer);
        }
        $restaurant = Restaurant::find($request->restaurant_id);
        if(!$restaurant){
            Toastr::error(translate('messages.Sorry_the_restaurant_is_not_available'));
            return back()->withInput()->with('customer', $customer);
        }


        $self_delivery_status = $restaurant->self_delivery_system;

        $rest_sub=$restaurant?->restaurant_sub;
        if ($restaurant->restaurant_model == 'subscription' && isset($rest_sub)) {

            $self_delivery_status = $rest_sub->self_delivery;

            if($rest_sub->max_order != "unlimited" && $rest_sub->max_order <= 0){
                Toastr::error(translate('messages.The_restaurant_has_reached_the_maximum_number_of_orders'));
                return back()->withInput()->with('customer', $customer);
            }
        } elseif($restaurant->restaurant_model == 'unsubscribed'){
            Toastr::error(translate('messages.The_restaurant_is_not_subscribed_or_subscription_has_expired'));
            return back()->withInput()->with('customer', $customer);
        }

        $cart = $request->session()->get('cart');
        $total_addon_price = 0;
        $product_price = 0;
        $restaurant_discount_amount = 0;

        $order_details = [];
        $order = new Order();

        $order->id = 100000 + Order::count() + 1;
        if (Order::find($order->id)) {
            $order->id = Order::latest()->first()->id + 1;
        }
        $order->distance = isset($address) ? $address['distance'] : 0;


        $distance_data = $order->distance ?? 1;

        $extra_charges = 0;
        if($self_delivery_status != 1){
            $data = Helpers::vehicle_extra_charge(distance_data:$distance_data);
            $vehicle_id= (isset($data) ? $data['vehicle_id']  : null);
            $extra_charges = (float) (isset($data) ? $data['extra_charge']  : 0);
        }

        $additional_charge_status = BusinessSetting::where('key', 'additional_charge_status')->first()?->value;
        $additional_charge = BusinessSetting::where('key', 'additional_charge')->first()?->value;
        if ($additional_charge_status == 1) {
            $order->additional_charge = $additional_charge ?? 0;
        } else {
            $order->additional_charge = 0;
        }

        $order->vehicle_id =  $vehicle_id ?? null;
        $order->payment_status = $request->type == 'wallet'?'paid':'unpaid';
        $order->order_status = $request->type == 'wallet'?'confirmed':'pending';
        $order->order_type = 'delivery';
        $order->restaurant_id = $restaurant->id;
        $order->zone_id = $restaurant->zone_id;
        $order->user_id = $request->user_id;
        $order->delivery_charge = isset($address)?$address['delivery_fee']:0;
        $order->original_delivery_charge = isset($address)?$address['delivery_fee']:0;
        $order->delivery_address = isset($address)?json_encode($address):null;
        $order->checked = 1;
        $order->schedule_at = now();
        $order->created_at = now();
        $order->updated_at = now();
        $order->otp = rand(1000, 9999);
        DB::beginTransaction();

        foreach ($cart as $c) {
            if (is_array($c)) {
                $product = Food::withoutGlobalScope(RestaurantScope::class)->with('restaurant')->find($c['id']);
                if ($product) {

                    $price = $c['price'];
                    $product->tax = $product?->restaurant?->tax;
                    $product = Helpers::product_data_formatting($product);
                    $addon_data = Helpers::calculate_addon_price(addons:AddOn::withoutGlobalScope(RestaurantScope::class)->whereIn('id', $c['add_ons'])->get(), add_on_qtys:$c['add_on_qtys']);

                        $addonAndVariationStock= Helpers::addonAndVariationStockCheck(product:$product,quantity: $c['quantity'],add_on_qtys:$c['add_on_qtys'], variation_options:explode(',',data_get($c,'variation_option_ids')),add_on_ids:$c['add_ons'],incrementCount: true );
                        if(data_get($addonAndVariationStock, 'out_of_stock') != null) {
                            Toastr::error(data_get($addonAndVariationStock, 'out_of_stock'));
                            return back()->withInput();
                        }



                    $variation_data = Helpers::get_varient(product_variations:$product->variations, variations: $c['variations']);
                    $variations = $variation_data['variations'];
                    $or_d = [
                        'food_id' => $c['id'],
                        'item_campaign_id' => null,
                        'food_details' => json_encode($product),
                        'quantity' => $c['quantity'],
                        'price' => $price,
                        'tax_amount' => Helpers::tax_calculate(food:$product,price: $price),
                        'discount_on_food' => Helpers::product_discount_calculate(product:$product,price: $price, restaurant:$product->restaurant),
                        'discount_type' => 'discount_on_product',
                        'variation' => json_encode($variations),
                        'add_ons' => json_encode($addon_data['addons']),
                        'total_add_on_price' => $addon_data['total_add_on_price'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $total_addon_price += $or_d['total_add_on_price'];
                    $product_price += $price * $or_d['quantity'];
                    $restaurant_discount_amount += $or_d['discount_on_food'] * $or_d['quantity'];
                    $order_details[] = $or_d;
                }
            }
        }


        $order->discount_on_product_by = 'vendor';
        $restaurant_discount = Helpers::get_restaurant_discount($restaurant);
        if(isset($restaurant_discount)){
            $order->discount_on_product_by = 'admin';
        }

        if (isset($cart['discount'])) {
            $restaurant_discount_amount += $cart['discount_type'] == 'percent' && $cart['discount'] > 0 ? ((($product_price + $total_addon_price - $restaurant_discount_amount) * $cart['discount']) / 100) : $cart['discount'];
        }

        $total_price = $product_price + $total_addon_price - $restaurant_discount_amount;
        $tax = isset($cart['tax']) ? $cart['tax'] : $restaurant->tax;


        $order->tax_status = 'excluded';

        $tax_included = BusinessSetting::where(['key'=>'tax_included'])->first()?->value;
        if ($tax_included ==  1){
            $order->tax_status = 'included';
        }

        $total_tax_amount=Helpers::product_tax(price:$total_price, tax:$tax, is_include: $order->tax_status =='included');
        $tax_a=$order->tax_status =='included'?0:$total_tax_amount;

        try {
            $order->restaurant_discount_amount = $restaurant_discount_amount;
            $order->total_tax_amount = $total_tax_amount;

            $order->order_amount = $total_price + $tax_a + $order->delivery_charge + $order->additional_charge;

            $order->payment_method = $request->type == 'wallet'?'wallet':'cash_on_delivery';
            $order->adjusment = $order->order_amount;


            $max_cod_order_amount_value= BusinessSetting::where('key', 'max_cod_order_amount')->first()?->value ?? 0;
            if( $max_cod_order_amount_value > 0 && $order->payment_method == 'cash_on_delivery' && $order->order_amount > $max_cod_order_amount_value){
            Toastr::error(translate('messages.You can not Order more then ').$max_cod_order_amount_value .Helpers::currency_symbol().' '. translate('messages.on COD order.')  );
            return back()->withInput()->with('customer', $customer);
            }

            if($request->type == 'wallet'){
                if($request->user_id){
                    if($customer->wallet_balance < $order->order_amount){
                        Toastr::error(translate('messages.insufficient_wallet_balance'));
                        return back()->withInput()->with('customer', $customer);
                    }else{
                        CustomerLogic::create_wallet_transaction(user_id:$order->user_id, amount:$order->order_amount,transaction_type: 'order_place', referance: $order->id);
                    }
                }else{
                    Toastr::error(translate('messages.no_customer_selected'));
                    return back()->withInput()->with('customer', $customer);
                }
            };
            $order->save();
            foreach ($order_details as $key => $item) {
                $order_details[$key]['order_id'] = $order->id;
            }
            OrderDetail::insert($order_details);
            session()->forget('cart');
            session()->forget('address');
            session(['last_order' => $order->id]);

            if ($restaurant->restaurant_model == 'subscription' && isset($rest_sub)) {
                if ($rest_sub->max_order != "unlimited" && $rest_sub->max_order > 0 ) {
                    $rest_sub->decrement('max_order' , 1);
                    }
            }
        DB::commit();
            //PlaceOrderMail
                try{
                    if ($order?->customer) {
                        Helpers::send_order_notification($order);
                    }
                    $notification_status= Helpers::getNotificationStatusData('customer','customer_order_notification');

                    if($notification_status?->mail_status == 'active' && $order->order_status == 'pending' && config('mail.status') && Helpers::get_mail_status('place_order_mail_status_user') == '1' && $order?->customer?->email)
                    {
                        Mail::to($order->customer->email)->send(new PlaceOrder($order->id));
                    }
                    // if ($order->order_status == 'pending' &&  config('mail.status') && config('order_delivery_verification') == 1 && $order?->customer?->email && Helpers::get_mail_status('order_verification_mail_status_user') == '1') {
                    //     Mail::to($order->customer->email)->send(new OrderVerificationMail($order->otp,$order->customer->f_name));
                    // }

                }catch (\Exception $exception) {
                    info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
                }
                //PlaceOrderMail end

            Toastr::success(translate('messages.order_placed_successfully'));
            return back();
        } catch (\Exception $exception) {
            DB::rollBack();
            info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
        }
        Toastr::warning(translate('messages.failed_to_place_order'));
        return back()->withInput()->with('customer', $customer);
    }


    public function customer_store(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
        ]);
        $customer= new User();
        $customer->f_name = $request['f_name'];
        $customer->l_name = $request['l_name'];
        $customer->email = $request['email'];
        $customer->phone = $request['phone'];
        $customer->password = bcrypt('password');
        $customer->save();

        try {
            $notification_status= Helpers::getNotificationStatusData('customer','customer_pos_registration');

            if ($notification_status?->mail_status == 'active' && config('mail.status') && $request->email && Helpers::get_mail_status('pos_registration_mail_status_user') == '1') {
                Mail::to($request->email)->send(new \App\Mail\CustomerRegistrationPOS($request->f_name . ' ' . $request->l_name,$request['email'],'password'));
                Toastr::success(translate('mail_sent_to_the_user'));
            }
        } catch (\Exception $ex) {
            info($ex->getMessage());
        }
        Toastr::success(translate('customer_added_successfully'));
        return back()->with('customer', $customer);
    }


    public function extra_charge(Request $request)
    {
        $distance_data = $request->distancMileResult ?? 1;
        $self_delivery_status = $request->self_delivery_status;
        $extra_charges = 0;
        if($self_delivery_status != 1){
            $data = Helpers::vehicle_extra_charge(distance_data:$distance_data);
            $vehicle_id= (isset($data) ? $data['vehicle_id']  : null);
            $extra_charges = (float) (isset($data) ? $data['extra_charge']  : 0);
        }
            return response()->json($extra_charges,200);
    }

    public function getUserData(Request $request){
            if($request->customer_id){
                $user= User::where('id', $request->customer_id)->first();
                if ($user) {
                    $user = [
                        'id' => $user->id,
                        'customer_name' => $user->f_name . ' ' . $user->l_name,
                        'customer_phone' => $user->phone,
                        'customer_wallet' => Helpers::format_currency($user->wallet_balance),
                        'customer_image' => $user->image_full_url,
                    ];
                }
            return response()->json($user,200);
            }
        return response()->json([],200);
    }
}
