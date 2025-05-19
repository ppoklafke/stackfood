<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Models\Category;
use App\Models\Characteristic;
use App\Models\Cuisine;
use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\RestaurantSchedule;
use App\Models\RestaurantConfig;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BusinessSettingsController extends Controller
{
    public function update_restaurant_basic_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_number' => 'required|string|max:20',
            'logo' => 'nullable|image|max:2048',
            'cover_photo' => 'nullable|image|max:2048',
            'meta_title' => 'max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $restaurant = $request['vendor']->restaurants[0];

        $data_trans = json_decode($request->translations, true);

        if (count($data_trans) < 1) {
            return response()->json([
                'errors' => [['code' => 'translations', 'message' => translate('messages.Name and address in english is required')]]
            ], 403);
        }

        $restaurant->name = $data_trans[0]['value'];
        $restaurant->address = $data_trans[1]['value'];
        $restaurant->phone = $request->contact_number;
        $restaurant->logo = $request->has('logo') ? Helpers::update(dir:'restaurant/', old_image:$restaurant->logo, format:'png', image:$request->file('logo')) : $restaurant->logo;
        $restaurant->cover_photo = $request->has('cover_photo') ? Helpers::update(dir:'restaurant/cover/', old_image:$restaurant->cover_photo,format: 'png', image:$request->file('cover_photo')) : $restaurant->cover_photo;

        $restaurant->meta_title = $data_trans[2]['value'];
        $restaurant->meta_description = $data_trans[3]['value'];
        $restaurant->meta_image = $request->has('meta_image') ? Helpers::update(dir:'restaurant/', old_image: $restaurant->meta_image, format: 'png', image: $request->file('meta_image')) : $restaurant->meta_image;

        $restaurant->save();

        foreach ($data_trans as $key => $i) {
            Translation::updateOrInsert(
                ['translationable_type' => 'App\Models\Restaurant',
                'translationable_id' => $restaurant->id,
                'locale' => $i['locale'],
                'key' => $i['key']],
                ['value' => $i['value']]
            );
        }

        if($restaurant?->vendor?->userinfo) {
            $userinfo = $restaurant->vendor->userinfo;
            $userinfo->f_name = $restaurant->name;
            $userinfo->image = $restaurant->logo;
            $userinfo->save();
        }

        return response()->json(['message' => translate('messages.restaurant_basic_info_updated')], 200);
    }


    public function update_restaurant_setup(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'delivery' => 'required|boolean',
            'take_away' => 'required|boolean',
            'schedule_order' => 'required|boolean',
            'veg' => 'required|boolean',
            'non_veg' => 'required|boolean',
            'order_subscription_active' => 'required|boolean',
            'minimum_order' => 'required|numeric',
            'gst' => 'required_if:gst_status,1',
            'free_delivery_distance' => 'required_if:free_delivery_distance_status,1',
            'customer_order_date' => 'required_if:customer_order_date_status,1',

            // 'minimum_delivery_time' => 'required|numeric',
            // 'maximum_delivery_time' => 'required|numeric',
            // 'delivery_time_type'=>'required|in:min,hours,days'

            // 'cuisine_ids' => 'required',
        ],[
            'gst.required_if' => translate('messages.gst_can_not_be_empty'),
            'free_delivery_distance.required_if' => translate('messages.free_delivery_distance_can_not_be_empty'),
            'meta_title.max'=>translate('Title_must_be_within_100_character'),

        ]);
        $restaurant = $request['vendor']->restaurants[0];
        $data =0;
        if(($restaurant->restaurant_model == 'subscription'  && $restaurant?->restaurant_sub?->self_delivery == 1)  || ($restaurant->restaurant_model == 'commission' &&  $restaurant->self_delivery_system == 1) ){
        $data =1;
        }

        $validator->sometimes('per_km_delivery_charge', 'required_with:minimum_delivery_charge', function ($request) use($data) {
            return ($data);
        });
        $validator->sometimes('minimum_delivery_charge', 'required_with:per_km_delivery_charge', function ($request) use($data) {
            return ($data);
        });


        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $home_delivery = BusinessSetting::where('key', 'home_delivery')->first()?->value ?? null;
        if ($request->delivery   && !$home_delivery) {
            return response()->json([
                'error'=>[
                    ['code'=>'delivery_or_take_way', 'message'=>translate('messages.Home_delivery_is_disabled_by_admin')]
                ]
            ],403);
        }
        $take_away = BusinessSetting::where('key', 'take_away')->first()?->value ?? null;
        if ($request->take_away && !$take_away) {
            return response()->json([
                'error'=>[
                    ['code'=>'delivery_or_take_way', 'message'=>translate('messages.Take_away_is_disabled_by_admin')]
                ]
            ],403);
        }

        $instant_order = BusinessSetting::where('key', 'instant_order')->first()?->value ?? null;

        if ($request->instant_order && !$instant_order) {
            return response()->json([
                'error'=>[
                    ['code'=>'instant_order', 'message'=>translate('messages.instant_order_is_disabled_by_admin')]
                ]
            ],403);
        }


        $dine_in_order_option = BusinessSetting::where('key', 'dine_in_order_option')->first()?->value ?? null;
        if ($request->is_dine_in_active && !$dine_in_order_option) {
            return response()->json([
                'error'=>[
                    ['code'=>'dine_in', 'message'=>translate('messages.dine_in_order_option_is_disabled_by_admin')]
                ]
            ],403);
        }

        if ($request->schedule_advance_dine_in_booking_duration_time_format == 'min' &&   $request->schedule_advance_dine_in_booking_duration > 60) {
            return response()->json([
                'error'=>[
                    ['code'=>'dine_in', 'message'=>translate('messages.Dine_in_dine_in_booking_duration_time_must_be_in_60_minute')]
                ]
            ],403);
        }
        elseif ($request->schedule_advance_dine_in_booking_duration_time_format == 'hour' &&   $request->schedule_advance_dine_in_booking_duration > 24) {
            return response()->json([
                'error'=>[
                    ['code'=>'dine_in', 'message'=>translate('messages.Dine_in_dine_in_booking_duration_time_must_be_in_24_hour')]
                ]
            ],403);
        }
        elseif ($request->schedule_advance_dine_in_booking_duration_time_format == 'day' &&   $request->schedule_advance_dine_in_booking_duration > 365) {
            return response()->json([
                'error'=>[
                    ['code'=>'dine_in', 'message'=>translate('messages.Dine_in_dine_in_booking_duration_time_must_be_in_356_days')]
                ]
            ],403);
        }

        if(!$request->take_away && !$request->delivery)
        {
            return response()->json([
                'error'=>[
                    ['code'=>'delivery_or_take_way', 'message'=>translate('messages.can_not_disable_both_take_away_and_delivery')]
                ]
            ],403);
        }

        if(!$request->veg && !$request->non_veg)
        {
            return response()->json([
                'error'=>[
                    ['code'=>'veg_non_veg', 'message'=>translate('messages.veg_non_veg_disable_warning')]
                ]
            ],403);
        }
        if(!$request->instant_order && $instant_order && !$request->schedule_order)
        {
            return response()->json([
                'error'=>[
                    ['code'=>'order', 'message'=>translate('messages.can_not_disable_both_instant_order_and_schedule_order')]
                ]
            ],403);
        }


        $tag_ids = [];
        if ($request->tags != null) {
            $tags = explode(",", $request->tags);
        }
        if(isset($tags)){
            foreach ($tags as $key => $value) {
                $tag = Tag::firstOrNew(
                    ['tag' => $value]
                );
                $tag->save();
                array_push($tag_ids,$tag->id);
            }
        }

        $characteristic_ids = [];
        if ($request->characteristics != null) {
            $characteristics = explode(",", $request->characteristics);
        }
        if(isset($characteristics)){
            foreach ($characteristics as $key => $value) {
                $characteristic = Characteristic::firstOrNew(
                    ['characteristic' => $value]
                );
                $characteristic->save();
                array_push($characteristic_ids,$characteristic->id);
            }
        }

        $restaurant->order_subscription_active = $request->order_subscription_active;
        $restaurant->delivery = $request->delivery;
        $restaurant->take_away = $request->take_away;
        $restaurant->schedule_order = $request->schedule_order;
        $restaurant->veg = $request->veg;
        $restaurant->non_veg = $request->non_veg;
        $restaurant->minimum_order = $request->minimum_order;
        $restaurant->opening_time = $request->opening_time;
        $restaurant->closeing_time = $request->closeing_time;
        $restaurant->off_day = $request->off_day??'';
        $restaurant->gst = json_encode(['status'=>$request->gst_status, 'code'=>$request->gst]);
        $restaurant->free_delivery_distance = json_encode(['status'=>$request->free_delivery_distance_status, 'value'=>$request->free_delivery_distance]);
        $restaurant->minimum_shipping_charge = $data?$request->minimum_delivery_charge??0: $restaurant->minimum_shipping_charge;
        $restaurant->per_km_shipping_charge = $data?$request->per_km_delivery_charge??0: $restaurant->per_km_shipping_charge;

        $restaurant->maximum_shipping_charge = $data?$request->maximum_delivery_charge??0: $restaurant->maximum_delivery_charge;
        $restaurant->logo = $request->has('logo') ? Helpers::update(dir:'restaurant/', old_image:$restaurant->logo, format:'png', image:$request->file('logo')) : $restaurant->logo;
        $restaurant->cover_photo = $request->has('cover_photo') ? Helpers::update(dir:'restaurant/cover/', old_image:$restaurant->cover_photo,format: 'png', image:$request->file('cover_photo')) : $restaurant->cover_photo;
        // $restaurant->delivery_time =$request->minimum_delivery_time .'-'. $request->maximum_delivery_time.'-'.$request->delivery_time_type;

        $restaurant->cutlery = $request->cutlery ?? 0;
        $restaurant->save();

        $restaurant->tags()->sync($tag_ids);
        $restaurant->characteristics()->sync($characteristic_ids);


        $conf = RestaurantConfig::firstOrNew(
            ['restaurant_id' =>  $restaurant->id]
        );
        $conf->instant_order = $request->instant_order ?? 0;
        $conf->customer_order_date = $request->customer_order_date ?? 0;
        $conf->customer_date_order_sratus = $request->customer_date_order_sratus ?? 0;
        $conf->halal_tag_status = $request->halal_tag_status ?? 0;
        $conf->extra_packaging_status = $request->extra_packaging_status ?? 0;
        $conf->is_extra_packaging_active = $request->is_extra_packaging_active ?? 0;
        $conf->extra_packaging_amount = $request->extra_packaging_amount;
        $conf->schedule_advance_dine_in_booking_duration = $request->schedule_advance_dine_in_booking_duration ?? 0;
        $conf->schedule_advance_dine_in_booking_duration_time_format = $request->schedule_advance_dine_in_booking_duration_time_format ?? 'min';
        $conf->dine_in = $request->is_dine_in_active ?? 0;
        $conf->save();

        $cuisine_ids = [];
        $cuisine_ids = json_decode($request->cuisine_ids, true);
        $restaurant->cuisine()->sync($cuisine_ids);

        return response()->json(['message'=>translate('messages.restaurant_settings_updated')], 200);
    }

    public function add_schedule(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'opening_time'=>'required|date_format:H:i:s',
            'closing_time'=>'required|date_format:H:i:s|after:opening_time',
            'day' => 'required',
        ],[
            'closing_time.after'=>translate('messages.End time must be after the start time')
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)],400);
        }
        $restaurant = $request['vendor']->restaurants[0];
        $temp = RestaurantSchedule::where('day', $request->day)->where('restaurant_id',$restaurant->id)
        ->where(function($q)use($request){
            return $q->where(function($query)use($request){
                return $query->where('opening_time', '<=' , $request->opening_time)->where('closing_time', '>=', $request->opening_time);
            })->orWhere(function($query)use($request){
                return $query->where('opening_time', '<=' , $request->closing_time)->where('closing_time', '>=', $request->closing_time);
            });
        })
        ->first();

        if(isset($temp))
        {
            return response()->json(['errors' => [
                ['code'=>'time', 'message'=>translate('messages.schedule_overlapping_warning')]
            ]], 400);
        }

        $restaurant_schedule = RestaurantSchedule::insertGetId(['restaurant_id'=>$restaurant->id,'day'=>$request->day,'opening_time'=>$request->opening_time,'closing_time'=>$request->closing_time]);
        return response()->json(['message'=>translate('messages.Schedule added successfully'), 'id'=>$restaurant_schedule], 200);
    }

    public function remove_schedule(Request $request, $restaurant_schedule)
    {
        $restaurant = $request['vendor']->restaurants[0];
        $schedule = RestaurantSchedule::where('restaurant_id', $restaurant->id)->find($restaurant_schedule);
        if(!$schedule)
        {
            return response()->json([
                'error'=>[
                    ['code'=>'not-fond', 'message'=>translate('messages.Schedule not found')]
                ]
            ],404);
        }
        $schedule->delete();
        return response()->json(['message'=>translate('messages.Schedule removed successfully')], 200);
    }

    function suggestion_list(Request $request)
    {
        $cuisineNames = Cuisine::pluck('name')->toArray();
        $categoryNames = Category::pluck('name')->toArray();
        $combinedNames = array_merge($cuisineNames, $categoryNames);

        return response()->json($combinedNames,200);
    }
}
