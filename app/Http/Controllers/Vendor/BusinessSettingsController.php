<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Category;
use App\Models\Characteristic;
use App\Models\Cuisine;
use App\Models\Restaurant;
use App\Models\RestaurantNotificationSetting;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\RestaurantConfig;
use App\Models\RestaurantSchedule;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class BusinessSettingsController extends Controller
{

    private $restaurant;

    public function restaurant_index()
    {
        $restaurant =  Restaurant::withoutGlobalScope('translate')->with('translations')->findOrFail(Helpers::get_restaurant_id());
        $cuisineNames = Cuisine::pluck('name')->toArray();
        $categoryNames = Category::pluck('name')->toArray();
        $combinedNames = array_merge($cuisineNames, $categoryNames);
        $combinedNames = '[' . implode(', ', array_map(fn($name) => "'$name'", $combinedNames)) . ']';

        $language = getWebConfig('language');
        return view('vendor-views.business-settings.restaurant-index', compact('restaurant','combinedNames','language'));
    }

    public function notification_index()
    {
        if(RestaurantNotificationSetting::count() == 0 ){
            Helpers::restaurantNotificationDataSetup(Helpers::get_restaurant_id());
        }
        $data= RestaurantNotificationSetting::where('restaurant_id',Helpers::get_restaurant_id())->get();


        $business_name= BusinessSetting::where('key','business_name')->first()?->value;
        return view('vendor-views.business-settings.notification-index', compact('business_name' ,'data'));
    }

    public function notification_status_change($key, $type){
        $data= RestaurantNotificationSetting::where('restaurant_id',Helpers::get_restaurant_id())->where('key',$key)->first();
        if(!$data){
            Toastr::error(translate('messages.Notification_settings_not_found'));
            return back();
        }
        if($type == 'Mail' ) {
            $data->mail_status =  $data->mail_status == 'active' ? 'inactive' : 'active';
        }
        elseif($type == 'push_notification' ) {
            $data->push_notification_status =  $data->push_notification_status == 'active' ? 'inactive' : 'active';
        }
        elseif($type == 'SMS' ) {
            $data->sms_status =  $data->sms_status == 'active' ? 'inactive' : 'active';
        }
        $data?->save();

        Toastr::success(translate('messages.Notification_settings_updated'));
        return back();
    }

    public function restaurant_setup(Restaurant $restaurant, Request $request)
    {
        $request->validate([
            'gst' => 'required_if:gst_status,1',
            'free_delivery_distance' => 'required_if:free_delivery_distance_status,1',
            'per_km_delivery_charge'=>'required_with:minimum_delivery_charge|numeric|between:1,999999999999.99',
            'minimum_delivery_charge'=>'required_with:per_km_delivery_charge|numeric|between:1,999999999999.99',
            'maximum_shipping_charge'=>'nullable|gt:minimum_delivery_charge',
        ], [
            'gst.required_if' => translate('messages.gst_can_not_be_empty'),
        ]);

        if ($request->schedule_advance_dine_in_booking_duration_time_format == 'min' &&   $request->schedule_advance_dine_in_booking_duration > 60) {
            Toastr::error(translate('messages.Dine_in_dine_in_booking_duration_time_must_be_in_60_minute'));
            return back();
        }
        elseif ($request->schedule_advance_dine_in_booking_duration_time_format == 'hour' &&   $request->schedule_advance_dine_in_booking_duration > 24) {
            Toastr::error(translate('messages.Dine_in_dine_in_booking_duration_time_must_be_in_24_hour'));
            return back();
        }
        elseif ($request->schedule_advance_dine_in_booking_duration_time_format == 'day' &&   $request->schedule_advance_dine_in_booking_duration > 365) {
            Toastr::error(translate('messages.Dine_in_dine_in_booking_duration_time_must_be_in_356_days'));
            return back();
        }

        $data =0;
        if (($restaurant->restaurant_model == 'subscription' && $restaurant?->restaurant_sub?->self_delivery == 1)  || ($restaurant->restaurant_model == 'commission' &&  $restaurant->self_delivery_system == 1) ){
            $data =1;
        }
        $cuisine_ids = [];
        $cuisine_ids=$request->cuisine_ids;
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
        $off_day = $request->off_day?implode('',$request->off_day):'';
        $restaurant->minimum_order = $request->minimum_order;
        $restaurant->opening_time = $request->opening_time;
        $restaurant->closeing_time = $request->closeing_time;
        $restaurant->off_day = $off_day;
        $restaurant->gst = json_encode(['status'=>$request->gst_status, 'code'=>$request->gst]);
        $restaurant->free_delivery_distance = json_encode(['status'=>$request->free_delivery_distance_status, 'value'=>$request->free_delivery_distance]);
        $restaurant->minimum_shipping_charge = $data?$request->minimum_delivery_charge??0: $restaurant->minimum_shipping_charge;
        $restaurant->per_km_shipping_charge = $data?$request->per_km_delivery_charge??0: $restaurant->per_km_shipping_charge;
        $restaurant->maximum_shipping_charge = $request->maximum_shipping_charge ?? null;
        // $restaurant->delivery_time = $request?->minimum_delivery_time .'-'. $request?->maximum_delivery_time.'-'.$request?->delivery_time_type;

        $restaurant->save();
        $restaurant->cuisine()->sync($cuisine_ids);
        $restaurant->tags()->sync($tag_ids);
        $restaurant->characteristics()->sync($characteristic_ids);

        $conf = RestaurantConfig::firstOrNew(
            ['restaurant_id' =>  $restaurant->id]
        );
        $conf->customer_order_date = $request->customer_order_date ?? 0;
        $conf->extra_packaging_status = $request?->extra_packaging_status??0;
        $conf->extra_packaging_amount = $request->extra_packaging_amount;
        $conf->schedule_advance_dine_in_booking_duration = $request->schedule_advance_dine_in_booking_duration ?? 0;
        $conf->schedule_advance_dine_in_booking_duration_time_format = $request->schedule_advance_dine_in_booking_duration_time_format ?? 'min';
        $conf->save();

        Toastr::success(translate('messages.restaurant_settings_updated'));
        return back();
    }

    public function restaurant_status(Restaurant $restaurant, Request $request)
    {
        if($request->menu == "schedule_order" && !Helpers::schedule_order())
        {
            Toastr::warning(translate('messages.schedule_order_disabled_warning'));
            return back();
        }

        $home_delivery = BusinessSetting::where('key', 'home_delivery')->first()?->value ?? null;
        if ($request->menu == "delivery" && !$home_delivery) {
            Toastr::warning(translate('messages.Home_delivery_is_disabled_by_admin'));
            return back();
        }
        $take_away = BusinessSetting::where('key', 'take_away')->first()?->value ?? null;
        if ($request->menu == "take_away" && !$take_away) {
            Toastr::warning(translate('messages.Take_away_is_disabled_by_admin'));
            return back();
        }
        $dine_in_order_option = BusinessSetting::where('key', 'dine_in_order_option')->first()?->value ?? null;
        if ($request->menu == "dine_in" && !$dine_in_order_option && $request->status == 1 ) {
            Toastr::warning(translate('messages.dine_in_order_option_is_disabled_by_admin'));
            return back();
        }


        $instant_order = BusinessSetting::where('key', 'instant_order')->first()?->value ?? null;
        if ($request->menu == "instant_order" && !$instant_order && $request->status == 1 ) {
            Toastr::warning(translate('messages.instant_order_is_disabled_by_admin'));
            return back();
        }

        if((($request->menu == "delivery" && $restaurant->take_away==0) || ($request->menu == "take_away" && $restaurant->delivery==0)) &&  $request->status == 0 )
        {
            Toastr::warning(translate('messages.can_not_disable_both_take_away_and_delivery'));
            return back();
        }

        if((($request->menu == "instant_order" && $restaurant->schedule_order==0) || ( isset($restaurant->restaurant_config)   && ($request->menu == "schedule_order" && $restaurant?->restaurant_config?->instant_order ==0))) &&  $request->status == 0 && $instant_order )
        {
            Toastr::warning(translate('messages.can_not_disable_both_instant_order_and_schedule_order'));
            return back();
        }

        if((($request->menu == "veg" && $restaurant->non_veg==0) || ($request->menu == "non_veg" && $restaurant->veg==0)) &&  $request->status == 0 )
        {
            Toastr::warning(translate('messages.veg_non_veg_disable_warning'));
            return back();
        }

        if($request->menu == 'free_delivery' &&(($restaurant->restaurant_model == 'subscription' && $restaurant?->restaurant_sub?->self_delivery == 0) || ($restaurant->restaurant_model == 'unsubscribed'))){
            Toastr::error(translate('your_subscription_plane_does_not_have_this_feature'));
            return back();
        }

        if( in_array($request->menu,['instant_order','customer_date_order_sratus','halal_tag_status' ,'is_extra_packaging_active','dine_in'] ) ){

            $conf = RestaurantConfig::firstOrNew(
                ['restaurant_id' =>  $restaurant->id]
            );
            $conf[$request->menu] = $request->status;
            $conf->save();

            Toastr::success(translate('messages.Restaurant settings updated!'));
            return back();
        }



        $restaurant[$request->menu] = $request->status;
        $restaurant->save();
        Toastr::success(translate('messages.Restaurant settings updated!'));
        return back();
    }

    public function active_status(Request $request)
    {
        $restaurant = Helpers::get_restaurant_data();
        $restaurant->active = $restaurant->active?0:1;
        $restaurant->save();
        return response()->json(['message' => !$restaurant->active?translate('messages.restaurant_temporarily_closed'):translate('messages.restaurant_opened')], 200);
    }

    public function add_schedule(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'start_time'=>'required|date_format:H:i',
            'end_time'=>'required|date_format:H:i|after:start_time',
            'day'=>'required',
        ],[
            'end_time.after'=>translate('messages.End time must be after the start time')
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        $temp = RestaurantSchedule::where('day', $request->day)->where('restaurant_id',Helpers::get_restaurant_id())
        ->where(function($q)use($request){
            return $q->where(function($query)use($request){
                return $query->where('opening_time', '<=' , $request->start_time)->where('closing_time', '>=', $request->start_time);
            })->orWhere(function($query)use($request){
                return $query->where('opening_time', '<=' , $request->end_time)->where('closing_time', '>=', $request->end_time);
            });
        })
        ->first();

        if(isset($temp))
        {
            return response()->json(['errors' => [
                ['code'=>'time', 'message'=>translate('messages.schedule_overlapping_warning')]
            ]]);
        }

        $restaurant = Helpers::get_restaurant_data();
        $restaurant_schedule = RestaurantSchedule::insert(['restaurant_id'=>Helpers::get_restaurant_id(),'day'=>$request->day,'opening_time'=>$request->start_time,'closing_time'=>$request->end_time]);
        return response()->json([
            'view' => view('vendor-views.business-settings.partials._schedule', compact('restaurant'))->render(),
        ]);
    }

    public function remove_schedule($restaurant_schedule)
    {
        $restaurant = Helpers::get_restaurant_data();
        $schedule = RestaurantSchedule::where('restaurant_id', $restaurant->id)->find($restaurant_schedule);
        if(!$schedule)
        {
            return response()->json([],404);
        }
        $schedule->delete();
        return response()->json([
            'view' => view('vendor-views.business-settings.partials._schedule', compact('restaurant'))->render(),
        ]);
    }

    public function site_direction_vendor(Request $request){
        session()->put('site_direction_vendor', ($request->status == 1?'ltr':'rtl'));
        return response()->json();
    }

    public function updateStoreMetaData(Restaurant $restaurant, Request $request)
    {
        $request->validate([
            'meta_title.0' => 'required',
            'meta_description.0' => 'required',
            'meta_title.*' => 'max:100',

        ],[
            'meta_title.0.required'=>translate('default_meta_title_is_required'),
            'meta_title.max'=>translate('Title_must_be_within_100_character'),
            'meta_description.0.required'=>translate('default_meta_description_is_required'),
        ]);

        $restaurant->meta_image = $request->has('meta_image') ? Helpers::update('restaurant/', $restaurant->meta_image, 'png', $request->file('meta_image')) : $restaurant->meta_image;
        $restaurant->meta_title = $request->meta_title[array_search('default', $request->lang)];
        $restaurant->meta_description = $request->meta_description[array_search('default', $request->lang)];
        $restaurant->save();

        Helpers::add_or_update_translations(request:$request,key_data: 'meta_title', name_field:'meta_title' , model_name:'Restaurant' ,data_id:$restaurant->id,data_value:$restaurant->meta_title);
        Helpers::add_or_update_translations(request:$request,key_data: 'meta_description', name_field:'meta_description' , model_name:'Restaurant' ,data_id:$restaurant->id,data_value:$restaurant->meta_description);

        Toastr::success(translate('messages.meta_data_updated'));
        return back();
    }
}
