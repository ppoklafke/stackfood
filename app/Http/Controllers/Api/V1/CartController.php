<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cart;
use App\Models\Food;
use App\Models\Item;
use App\Models\AddOn;
use App\Models\ItemCampaign;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\VariationOption;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function get_carts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;
        $carts = Cart::where('user_id', $user_id)->where('is_guest',$is_guest)->get()
        ->map(function ($data) {
            $data->add_on_ids = json_decode($data->add_on_ids,true);
            $data->add_on_qtys = json_decode($data->add_on_qtys,true);
            $data->variations = json_decode($data->variations,true);
			$data->item = Helpers::cart_product_data_formatting($data->item, $data->variations,$data->add_on_ids,
            $data->add_on_qtys, false, app()->getLocale());
			return $data;
		});
        return response()->json($carts, 200);
    }

    public function add_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'item_id' => 'required|integer',
            'model' => 'required|string|in:Food,ItemCampaign',
            'price' => 'required|numeric',
            'variation_options' => 'nullable|array',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;
        $model = $request->model === 'Food' ? 'App\Models\Food' : 'App\Models\ItemCampaign';
        $item = $request->model === 'Food' ? Food::find($request->item_id) : ItemCampaign::find($request->item_id);

        $cart = Cart::where('item_id',$request->item_id)->where('item_type',$model)->where('variations',json_encode($request->variations))->where('user_id', $user_id)->where('is_guest',$is_guest)->first();

        if($cart){
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.Item_already_exists')]
                ]
            ], 403);
        }

        if($item?->maximum_cart_quantity && ($request->quantity>$item->maximum_cart_quantity)){
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item_limit', 'message' => translate('messages.maximum_cart_quantity_exceeded')]
                ]
            ], 403);
        }
        if($request->model === 'Food'){
            $addonAndVariationStock= Helpers::addonAndVariationStockCheck(product:$item,quantity: $request->quantity,add_on_qtys:$request->add_on_qtys, variation_options: $request?->variation_options,add_on_ids:$request->add_on_ids );

            if(data_get($addonAndVariationStock, 'out_of_stock') != null) {
                return response()->json([
                    'errors' => [
                        ['code' => 'stock_out', 'message' => data_get($addonAndVariationStock, 'out_of_stock') ],
                    ]
                ], 403);
            }
        }


        $cart = new Cart();
        $cart->user_id = $user_id;
        $cart->item_id = $request->item_id;
        $cart->is_guest = $is_guest;
        $cart->add_on_ids = json_encode($request->add_on_ids);
        $cart->add_on_qtys = json_encode($request->add_on_qtys);
        $cart->item_type = $request->model;
        $cart->price = $request->price;
        $cart->quantity = $request->quantity;
        $cart->variations = json_encode($request->variations);
        $cart->variation_options = json_encode($request?->variation_options ?? []);
        $cart->save();

        $item->carts()->save($cart);

        $carts = Cart::where('user_id', $user_id)->where('is_guest',$is_guest)->get()
        ->map(function ($data) {
            $data->add_on_ids = json_decode($data->add_on_ids,true);
            $data->add_on_qtys = json_decode($data->add_on_qtys,true);
            $data->variations = json_decode($data->variations,true);
			$data->item = Helpers::cart_product_data_formatting($data->item, $data->variations,$data->add_on_ids,
            $data->add_on_qtys, false, app()->getLocale());
            return $data;
		});
        return response()->json($carts, 200);
    }

    public function update_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
            'guest_id' => $request->user ? 'nullable' : 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;
        $cart = Cart::find($request->cart_id);
        $item = $cart->item_type === 'App\Models\Food' ? Food::find($cart->item_id) : ItemCampaign::find($cart->item_id);
        if($item->maximum_cart_quantity && ($request->quantity>$item->maximum_cart_quantity)){
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item_limit', 'message' => translate('messages.maximum_cart_quantity_exceeded')]
                ]
            ], 403);
        }

        if( $cart->item_type === 'App\Models\Food'){
            $addonAndVariationStock= Helpers::addonAndVariationStockCheck( product:$item, quantity: $request->quantity,add_on_qtys:$request->add_on_qtys, variation_options: $request?->variation_options,add_on_ids:$request->add_on_ids );

            if(data_get($addonAndVariationStock, 'out_of_stock') != null) {
                return response()->json([
                    'errors' => [
                        ['code' => 'stock_out', 'message' => data_get($addonAndVariationStock, 'out_of_stock') ],
                    ]
                ], 403);
            }
        }


        $cart->user_id = $user_id;
        $cart->is_guest = $is_guest;
        $cart->add_on_ids = $request->add_on_ids?json_encode($request->add_on_ids):$cart->add_on_ids;
        $cart->add_on_qtys = $request->add_on_qtys?json_encode($request->add_on_qtys):$cart->add_on_qtys;
        $cart->price = $request->price;
        $cart->quantity = $request->quantity;
        $cart->variations = $request->variations?json_encode($request->variations):$cart->variations;
        $cart->variation_options = json_encode($request?->variation_options ?? []);
        $cart->save();

        $carts = Cart::where('user_id', $user_id)->where('is_guest',$is_guest)->get()
        ->map(function ($data) {
            $data->add_on_ids = json_decode($data->add_on_ids,true);
            $data->add_on_qtys = json_decode($data->add_on_qtys,true);
            $data->variations = json_decode($data->variations,true);
			$data->item = Helpers::cart_product_data_formatting($data->item, $data->variations,$data->add_on_ids,
            $data->add_on_qtys, false, app()->getLocale());
            return $data;
		});
        return response()->json($carts, 200);
    }

    public function remove_cart_item(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $cart = Cart::find($request->cart_id);
        $cart->delete();

        $carts = Cart::where('user_id', $user_id)->where('is_guest',$is_guest)->get()
        ->map(function ($data) {
            $data->add_on_ids = json_decode($data->add_on_ids,true);
            $data->add_on_qtys = json_decode($data->add_on_qtys,true);
            $data->variations = json_decode($data->variations,true);
			$data->item = Helpers::cart_product_data_formatting($data->item, $data->variations,$data->add_on_ids,
            $data->add_on_qtys, false, app()->getLocale());
            return $data;
		});
        return response()->json($carts, 200);
    }

    public function remove_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $carts = Cart::where('user_id', $user_id)->where('is_guest',$is_guest)->get();

        foreach($carts as $cart){
            $cart->delete();
        }


        $carts = Cart::where('user_id', $user_id)->where('is_guest',$is_guest)->get()
        ->map(function ($data) {
            $data->add_on_ids = json_decode($data->add_on_ids,true);
            $data->add_on_qtys = json_decode($data->add_on_qtys,true);
            $data->variations = json_decode($data->variations,true);
			$data->item = Helpers::cart_product_data_formatting($data->item, $data->variations,$data->add_on_ids,
            $data->add_on_qtys, false, app()->getLocale());
            return $data;
		});
        return response()->json($carts, 200);
    }

    public function add_to_cart_multiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_list' => 'required',
        ]);

        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

            foreach($request->item_list as $single_item){


                $model = $single_item['model'] === 'Food' ? 'App\Models\Food' : 'App\Models\ItemCampaign';
                $item = $single_item['model'] === 'Food' ? Food::find($single_item['item_id']) : ItemCampaign::find($single_item['item_id']);

                $cart = Cart::where('item_id',$single_item['item_id'])->where('item_type',$model)->where('variations',json_encode($single_item['variations']))->where('user_id', $user_id)->where('is_guest',0)->first();

                if($cart){
                    return response()->json([
                        'errors' => [
                            ['code' => 'cart_item', 'message' => translate('messages.Item_already_exists')]
                        ]
                    ], 403);
                }

                if($item->maximum_cart_quantity && ($single_item['quantity']>$item->maximum_cart_quantity)){
                    return response()->json([
                        'errors' => [
                            ['code' => 'cart_item_limit', 'message' => translate('messages.maximum_cart_quantity_exceeded')]
                        ]
                    ], 403);
                }


                if($single_item['model'] === 'Food'){
                    $addonAndVariationStock= Helpers::addonAndVariationStockCheck(product:$item,quantity: $single_item['quantity'],add_on_qtys:$single_item['add_on_qtys'], variation_options: $single_item['variation_options'],add_on_ids:$single_item['add_on_ids'] );

                        if(data_get($addonAndVariationStock, 'out_of_stock') != null) {
                            return response()->json([
                                'errors' => [
                                    ['code' => 'stock_out', 'message' => data_get($addonAndVariationStock, 'out_of_stock') ],
                                ]
                            ], 403);
                        }
                }

                $cart = new Cart();
                $cart->user_id =$request->user->id;
                $cart->item_id = $single_item['item_id'];
                $cart->is_guest = 0;
                $cart->add_on_ids = json_encode($single_item['add_on_ids']);
                $cart->add_on_qtys = json_encode($single_item['add_on_qtys']);
                $cart->item_type = $single_item['model'];
                $cart->price = $single_item['price'];
                $cart->quantity = $single_item['quantity'];
                $cart->variations = json_encode($single_item['variations']);
                $cart->variation_options =  data_get($single_item,'variation_options',[] ) != null ? json_encode(data_get($single_item,'variation_options',[] )) : json_encode([]);

                $cart->save();

                $item->carts()->save($cart);
            }

        $carts = Cart::where('user_id', $user_id)->where('is_guest',0)->get()
        ->map(function ($data) {
            $data->add_on_ids = json_decode($data->add_on_ids,true);
            $data->add_on_qtys = json_decode($data->add_on_qtys,true);
            $data->variations = json_decode($data->variations,true);
			$data->item = Helpers::cart_product_data_formatting($data->item, $data->variations,$data->add_on_ids,
            $data->add_on_qtys, false, app()->getLocale());
            return $data;
		});
        return response()->json($carts, 200);
    }





}
