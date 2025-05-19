<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Traits\Processor;
use App\Models\Restaurant;
use App\Models\DataSetting;
use App\Models\Translation;
use App\Models\PriorityList;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\RestaurantConfig;
use App\Models\OrderCancelReason;
use Illuminate\Support\Facades\DB;
use App\Models\NotificationMessage;
use App\Models\NotificationSetting;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Models\RestaurantSubscription;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessSettingsController extends Controller
{
    use Processor;


    public function business_index($tab = 'business')
    {
        if (!Helpers::module_permission_check('settings')) {
            Toastr::error(translate('messages.access_denied'));
            return back();
        }
        if ($tab == 'business') {
            return view('admin-views.business-settings.business-index');
        } else if ($tab == 'customer') {
            $data = BusinessSetting::where('key', 'like', 'wallet_%')
                ->orWhere('key', 'like', 'loyalty_%')
                ->orWhere('key', 'like', 'ref_earning_%')
                ->orWhere('key', 'like', 'add_fund_status%')
                ->orWhere('key', 'like', 'customer_%')
                ->orWhere('key', 'like', 'new_customer_discount_%')->get();
            $data = array_column($data->toArray(), 'value', 'key');
            return view('admin-views.business-settings.customer-index', compact('data'));
        } else if ($tab == 'deliveryman') {
            return view('admin-views.business-settings.deliveryman-index');
        } else if ($tab == 'order') {
            $key = ['order_delivery_verification','home_delivery','take_away','instant_order','repeat_order_option','order_subscription','schedule_order','customer_date_order_sratus','canceled_by_restaurant','canceled_by_deliveryman','order_confirmation_model','schedule_order_slot_duration','schedule_order_slot_duration_time_formate','customer_order_date','dine_in_order_option'];
            $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');
            $language = getWebConfig('language');
            $reasons = OrderCancelReason::latest()->paginate(config('default_pagination'));
            return view('admin-views.business-settings.order-index', compact('settings','reasons','language'));
        } else if ($tab == 'restaurant') {
            return view('admin-views.business-settings.restaurant-index');
        } else if ($tab == 'landing-page') {
            return view('admin-views.business-settings.landing-index');
        } else if ($tab == 'disbursement') {
            return view('admin-views.business-settings.disbursement-index');
        } else if ($tab == 'priority') {
            return view('admin-views.business-settings.priority-index');
        }
    }


    public function update_restaurant(Request $request)
    {
        Helpers::businessUpdateOrInsert(['key' => 'cash_in_hand_overflow_restaurant'], [
            'value' => $request['cash_in_hand_overflow_restaurant'] ?? 0
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'cash_in_hand_overflow_restaurant_amount'], [
            'value' => $request['cash_in_hand_overflow_restaurant_amount']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'min_amount_to_pay_restaurant'], [
            'value' => $request['min_amount_to_pay_restaurant']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'canceled_by_restaurant'], [
            'value' => $request['canceled_by_restaurant']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'toggle_restaurant_registration'], [
            'value' => $request['restaurant_self_registration']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'restaurant_review_reply'], [
            'value' => $request['restaurant_review_reply']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'extra_packaging_charge'], [
            'value' => $request['extra_packaging_charge'] ?? 0
        ]);

        Toastr::success(translate('messages.successfully_updated_to_changes_restart_app'));
        return back();
    }
    public function update_dm(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        Helpers::businessUpdateOrInsert(['key' => 'min_amount_to_pay_dm'], [
            'value' => $request['min_amount_to_pay_dm']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'cash_in_hand_overflow_delivery_man'], [
            'value' => $request['cash_in_hand_overflow_delivery_man'] ?? 0
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'dm_tips_status'], [
            'value' => $request['dm_tips_status']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'dm_tips_status'], [
            'value' => $request['dm_tips_status']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'dm_maximum_orders'], [
            'value' => $request['dm_maximum_orders']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'canceled_by_deliveryman'], [
            'value' => $request['canceled_by_deliveryman']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'show_dm_earning'], [
            'value' => $request['show_dm_earning']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'toggle_dm_registration'], [
            'value' => $request['dm_self_registration']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'dm_max_cash_in_hand'], [
            'value' => $request['dm_max_cash_in_hand']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'dm_picture_upload_status'], [
            'value' => $request['dm_picture_upload_status']
        ]);

        Toastr::success(translate('messages.successfully_updated_to_changes_restart_app'));
        return back();
    }

    public function update_disbursement(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        Helpers::businessUpdateOrInsert(['key' => 'disbursement_type'], [
            'value' => $request['disbursement_type']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'restaurant_disbursement_time_period'], [
            'value' => $request['restaurant_disbursement_time_period']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'restaurant_disbursement_week_start'], [
            'value' => $request['restaurant_disbursement_week_start']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'restaurant_disbursement_waiting_time'], [
            'value' => $request['restaurant_disbursement_waiting_time']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'restaurant_disbursement_create_time'], [
            'value' => $request['restaurant_disbursement_create_time']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'restaurant_disbursement_min_amount'], [
            'value' => $request['restaurant_disbursement_min_amount']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'dm_disbursement_time_period'], [
            'value' => $request['dm_disbursement_time_period']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'dm_disbursement_week_start'], [
            'value' => $request['dm_disbursement_week_start']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'dm_disbursement_waiting_time'], [
            'value' => $request['dm_disbursement_waiting_time']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'dm_disbursement_create_time'], [
            'value' => $request['dm_disbursement_create_time']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'dm_disbursement_min_amount'], [
            'value' => $request['dm_disbursement_min_amount']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'system_php_path'], [
            'value' => $request['system_php_path']
        ]);

        if(function_exists('exec')){
            $data = self::generateCronCommand(disbursement_type: $request['disbursement_type']);
            $scriptPath = 'script.sh';
            exec('sh '. $scriptPath);
            Helpers::businessUpdateOrInsert(['key' => 'restaurant_disbursement_command'], [
                'value' => $data['restaurantCronCommand']
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'dm_disbursement_command'], [
                'value' => $data['dmCronCommand']
            ]);
            Toastr::success(translate('messages.successfully_updated_disbursement_functionality'));
            return back();
        }else{
            $data = self::generateCronCommand(disbursement_type: $request['disbursement_type']);
            Helpers::businessUpdateOrInsert(['key' => 'restaurant_disbursement_command'], [
                'value' => $data['restaurantCronCommand']
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'dm_disbursement_command'], [
                'value' => $data['dmCronCommand']
            ]);
            if($request['disbursement_type'] == 'automated'){
                Session::flash('disbursement_exec', true);
                Toastr::warning(translate('messages.Servers_PHP_exec_function_is_disabled_check_dependencies_&_start_cron_job_manualy_in_server'));
            }
            Toastr::success(translate('messages.successfully_updated_disbursement_functionality'));
            return back();
        }

    }

    private function dmSchedule(){
        $key = [
            'dm_disbursement_time_period','dm_disbursement_week_start','dm_disbursement_create_time'
        ];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        $scheduleFrequency = $settings['dm_disbursement_time_period'] ?? 'daily';
        $weekDay = $settings['dm_disbursement_week_start'] ?? 'sunday';
        $time =$settings['dm_disbursement_create_time'] ?? '12:00';


        $time= explode(":",$time);

        $hour= $time[0] ;
        $min= $time[1] ;

        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $day = array_search($weekDay,$days);
        $schedule = "* * * * *";
        if($scheduleFrequency == 'daily' ){
            $schedule =  $min. " ".$hour." "."* * *";

        }
        elseif($scheduleFrequency == 'weekly' ){

            $schedule =  $min. " ".$hour." "."* * " .$day;
        }
        elseif($scheduleFrequency == 'monthly' ){
            $schedule =  $min. " ".$hour." "."28-31 * *";

        }
        return $schedule;
    }

    private function restaurantSchedule(){
        $key = [
            'restaurant_disbursement_time_period','restaurant_disbursement_week_start','restaurant_disbursement_create_time'
        ];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        $scheduleFrequency = $settings['restaurant_disbursement_time_period'] ?? 'daily';
        $weekDay = $settings['restaurant_disbursement_week_start'] ?? 'sunday';
        $time =$settings['restaurant_disbursement_create_time'] ?? '12:00';


        $time= explode(":",$time);

        $hour= $time[0] ;
        $min= $time[1] ;

        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $day = array_search($weekDay,$days);
        $schedule = "* * * * *";
        if($scheduleFrequency == 'daily' ){
            $schedule =  $min. " ".$hour." "."* * *";

        }
        elseif($scheduleFrequency == 'weekly' ){

            $schedule =  $min. " ".$hour." "."* * " .$day;
        }
        elseif($scheduleFrequency == 'monthly' ){
            $schedule =  $min. " ".$hour." "."28-31 * *";

        }
        return $schedule;
    }

    private function generateCronCommand($disbursement_type = 'automated') {
        $system_php_path = BusinessSetting::where('key', 'system_php_path')->first();
        $system_php_path = $system_php_path ? $system_php_path->value : "/usr/bin/php";
        $dmSchedule = self::dmSchedule();
        $restaurantSchedule = self::restaurantSchedule();
        $scriptFilename = $_SERVER['SCRIPT_FILENAME'];
        $rootPath = dirname($scriptFilename);
        $phpCommand = $system_php_path;
        $dmScriptPath = $rootPath . "/artisan dm:disbursement";
        $restaurantScriptPath = $rootPath . "/artisan restaurant:disbursement";
        $dmClearCronCommand = "(crontab -l | grep -v \"$phpCommand $dmScriptPath\") | crontab -";
        $dmCronCommand = $disbursement_type == 'automated'?"(crontab -l ; echo \"$dmSchedule $phpCommand $dmScriptPath\") | crontab -":"";
        $restaurantClearCronCommand = "(crontab -l | grep -v \"$phpCommand $restaurantScriptPath\") | crontab -";
        $restaurantCronCommand = $disbursement_type == 'automated'?"(crontab -l ; echo \"$restaurantSchedule $phpCommand $restaurantScriptPath\") | crontab -":"";
        $scriptContent = "#!/bin/bash\n";
        $scriptContent .= $dmClearCronCommand."\n";
        $scriptContent .= $dmCronCommand."\n";
        $scriptContent .= $restaurantClearCronCommand."\n";
        $scriptContent .= $restaurantCronCommand."\n";
        $scriptFilePath = $rootPath . "/script.sh";
        file_put_contents($scriptFilePath, $scriptContent);

        return [
            'dmCronCommand' => $dmCronCommand,
            'restaurantCronCommand' =>  $restaurantCronCommand
        ];
    }

    public function update_order(Request $request)
    {

        if ($request?->home_delivery == null && $request?->take_away == null)  {
            Toastr::warning(translate('messages.can_not_disable_both_take_away_and_delivery'));
            return back();
        }
        if ($request?->instant_order == null && $request?->schedule_order == null)  {
            Toastr::warning(translate('messages.can_not_disable_both_schedule_order_and_instant_order'));
            return back();
        }

        Helpers::businessUpdateOrInsert(['key' => 'instant_order'], [
            'value' => $request['instant_order'] ?? 0
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'customer_date_order_sratus'], [
            'value' => $request['customer_date_order_sratus'] ?? 0
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'customer_order_date'], [
            'value' => $request['customer_order_date'] ?? 0
        ]);


        Helpers::businessUpdateOrInsert(['key' => 'order_delivery_verification'], [
            'value' => $request['odc']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'schedule_order'], [
            'value' => $request['schedule_order']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'home_delivery'], [
            'value' => $request['home_delivery']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'take_away'], [
            'value' => $request['take_away']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'repeat_order_option'], [
            'value' => $request['repeat_order_option']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'order_subscription'], [
            'value' => $request['order_subscription']
        ]);


        $time=  $request['schedule_order_slot_duration'];
        if($request['schedule_order_slot_duration_time_formate'] == 'hour'){
            $time=  $request['schedule_order_slot_duration']*60;
        }
        Helpers::businessUpdateOrInsert(['key' => 'schedule_order_slot_duration'], [
            'value' => $time
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'schedule_order_slot_duration_time_formate'], [
            'value' => $request['schedule_order_slot_duration_time_formate']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'canceled_by_restaurant'], [
            'value' => $request['canceled_by_restaurant']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'canceled_by_deliveryman'], [
            'value' => $request['canceled_by_deliveryman']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'order_confirmation_model'], [
            'value' => $request['order_confirmation_model']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'dine_in_order_option'], [
            'value' => $request['dine_in_order_option']
        ]);

        if($request['take_away'] != 1){
            Restaurant::where('take_away',1)->update(['take_away'=> 0]);
        }
        if($request['dine_in_order_option'] != 1){
            RestaurantConfig::where('dine_in', true)->update(['dine_in' => 0]);
        }
        if($request['home_delivery'] != 1){
            Restaurant::where('delivery',1)->update(['delivery'=> 0]);
        }

        Toastr::success(translate('messages.successfully_updated_to_changes_restart_app'));
        return back();
    }
    public function update_priority(Request $request)
    {
        $list = ['popular_food','popular_restaurant','new_restaurant','all_restaurant','campaign_food','best_reviewed_food' ,'category_list','cuisine_list','category_food','search_bar'];
        foreach ($list as $item){
            Helpers::businessUpdateOrInsert(['key' => $item.'_default_status'], [
                'value' => $request[$item.'_default_status'] ?? 0
            ]);

            if($request[$item.'_default_status'] == '0'){


                if (! $request[$item.'_sort_by_general']    &&  $item != 'search_bar'){
                    Toastr::error(translate('you_must_selcet_an_option_for').' '.translate($item) );
                    return back();
                }


                if($request[$item.'_sort_by_general']){
                    PriorityList::query()->updateOrInsert(['name' => $item.'_sort_by_general','type' => 'general'], [
                        'value' => $request[$item.'_sort_by_general']
                    ]);
                }
                if($request[$item.'_sort_by_unavailable']){
                    PriorityList::query()->updateOrInsert(['name' => $item.'_sort_by_unavailable','type' => 'unavailable'], [
                        'value' => $request[$item.'_sort_by_unavailable']
                    ]);
                }
                if($request[$item.'_sort_by_temp_closed']){
                    PriorityList::query()->updateOrInsert(['name' => $item.'_sort_by_temp_closed','type' => 'temp_closed'], [
                        'value' => $request[$item.'_sort_by_temp_closed']
                    ]);
                }
                if($request[$item.'_sort_by_rating']){
                    PriorityList::query()->updateOrInsert(['name' => $item.'_sort_by_rating','type' => 'rating'], [
                        'value' => $request[$item.'_sort_by_rating']
                    ]);
                }
            }
        }

        Toastr::success(translate('messages.successfully_updated_to_changes_restart_app'));
        return back();
    }




    public function business_setup(Request $request)

    {

        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
            }


        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|max:2048',
            'icon' => 'nullable|max:2048',
        ]);

        if ($validator->fails()) {
        Toastr::error( translate('Image size must be within 2mb'));
        return back();
        }

        $key =['logo','icon',];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');


        Helpers::businessUpdateOrInsert(['key' => 'guest_checkout_status'], [
            'value' => $request['guest_checkout_status'] ? $request['guest_checkout_status'] : 0
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'country_picker_status'], [
            'value' => $request['country_picker_status'] ? $request['country_picker_status'] : 0
        ]);


        Helpers::businessUpdateOrInsert(['key' => 'order_notification_type'], [
            'value' => $request['order_notification_type']
        ]);

         Helpers::businessUpdateOrInsert(['key' => 'additional_charge_status'], [
            'value' => $request['additional_charge_status'] ? $request['additional_charge_status'] : null
        ]);

         Helpers::businessUpdateOrInsert(['key' => 'additional_charge_name'], [
            'value' => $request['additional_charge_name'] ? $request['additional_charge_name'] : null
        ]);

         Helpers::businessUpdateOrInsert(['key' => 'additional_charge'], [
            'value' => $request['additional_charge'] ? $request['additional_charge'] : null
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'tax_included'], [
            'value' => $request['tax_included']
        ]);

        if($request['order_subscription']  == null ){
            Restaurant::query()->update([
                'order_subscription_active' => 0,
            ]);
        }
        Helpers::businessUpdateOrInsert(['key' => 'business_name'], [
            'value' => $request['restaurant_name']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'currency'], [
            'value' => $request['currency']
        ]);

        Config::set('currency', $request['currency']);
        Config::set('currency_symbol_position', $request['currency_symbol_position']);
        Config::set('currency_symbol', $request['currency_symbol']);


        Helpers::businessUpdateOrInsert(['key' => 'timezone'], [
            'value' => $request['timezone']
        ]);

        if ($request->has('logo')) {

            $image_name = Helpers::update( dir: 'business/', old_image:$settings['logo'],format: 'png',image: $request->file('logo'));
        } else {
            $image_name = $settings['logo'];
        }

        Helpers::businessUpdateOrInsert(['key' => 'logo'], [
            'value' => $image_name
        ]);

        if ($request->has('icon')) {

            $image_name = Helpers::update( dir: 'business/', old_image:$settings['icon'], format:'png', image: $request->file('icon'));
        } else {
            $image_name = $settings['icon'];
        }

        Helpers::businessUpdateOrInsert(['key' => 'icon'], [
            'value' => $image_name
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'phone'], [
            'value' => $request['phone']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'email_address'], [
            'value' => $request['email']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'address'], [
            'value' => $request['address']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'cookies_text'], [
            'value' => $request['cookies_text']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'currency_symbol_position'], [
            'value' => $request['currency_symbol_position']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'tax'], [
            'value' => $request['tax']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'admin_commission'], [
            'value' => $request['admin_commission']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'country'], [
            'value' => $request['country']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'default_location'], [
            'value' => json_encode(['lat' => $request['latitude'], 'lng' => $request['longitude']])
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'admin_order_notification'], [
            'value' => $request['admin_order_notification']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'free_delivery_over'], [
            'value' => $request['free_delivery_over_status'] ? $request['free_delivery_over'] : null
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'free_delivery_distance'], [
            'value' => $request['free_delivery_distance_status'] ? $request['free_delivery_distance'] : null
        ]);


        Helpers::businessUpdateOrInsert(['key' => 'timeformat'], [
            'value' => $request['time_format']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'toggle_veg_non_veg'], [
            'value' => $request['vnv']
        ]);


        Helpers::businessUpdateOrInsert(['key' => 'digit_after_decimal_point'], [
            'value' => $request['digit_after_decimal_point']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'delivery_charge_comission'], [
            'value' => $request['admin_comission_in_delivery_charge']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'partial_payment_status'], [
            'value' => $request['partial_payment_status']
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'partial_payment_method'], [
            'value' => $request['partial_payment_method']
        ]);

        if(!isset($request->commission) && !isset($request->subscription)){
            Toastr::error( translate('You_must_select_at_least_one_business_model_between_commission_and_subscription'));
            return back();
        }

        // For commission Model
        if (isset($request->commission) && !isset($request->subscription)) {

            if (RestaurantSubscription::where('status', 1)->count() > 0) {
                Toastr::warning(translate('You_need_to_switch_your_subscribers_to_commission_first'));
                return back();
            }

            Helpers::businessUpdateOrInsert(['key' => 'business_model'], [
                    'value' => json_encode(['commission' => 1, 'subscription' => 0 ])
                ]);
                $business_model= BusinessSetting::where('key', 'business_model')->first()?->value;
                $business_model = json_decode($business_model, true) ?? [];

            if ($business_model && $business_model['subscription'] == 0 ){
                Restaurant::query()->update(['restaurant_model' => 'commission']);
            }
        }
        // For subscription model
            elseif(isset($request->subscription) && !isset($request->commission)) {
            Helpers::businessUpdateOrInsert(['key' => 'business_model'], [
                'value' => json_encode(['commission' =>  0, 'subscription' => 1 ])
            ]);
            $business_model= BusinessSetting::where('key', 'business_model')->first()?->value;
            $business_model = json_decode($business_model, true) ?? [];

            if ( $business_model && $business_model['commission'] == 0 ){
                Restaurant::where('restaurant_model','commission')
                ->update(['restaurant_model' => 'unsubscribed',
                'status' => 0,]);
            }
        } else {
            Helpers::businessUpdateOrInsert(['key' => 'business_model'], [
                'value' => json_encode(['commission' =>  1, 'subscription' => 1 ])
            ]);
        }
        Toastr::success( translate('Successfully updated. To see the changes in app restart the app.'));
        return back();
    }

    public function storage_connection_index(Request $request)
    {
        return view('admin-views.business-settings.storage-connection-index');
    }

    public function storage_connection_update(Request $request, $name)
    {
        if($name == 'local_storage'){
            Helpers::businessUpdateOrInsert(['key' => 'local_storage'], [
                'key' => 'local_storage',
                'value' => $request->status??0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            Helpers::businessUpdateOrInsert(['key' => '3rd_party_storage'], [
                'key' => '3rd_party_storage',
                'value' => $request->status=='1'?0:1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if($name == '3rd_party_storage'){
            Helpers::businessUpdateOrInsert(['key' => '3rd_party_storage'], [
                'key' => '3rd_party_storage',
                'value' => $request->status??0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'local_storage'], [
                'key' => 'local_storage',
                'value' => $request->status=='1'?0:1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if($name == 'storage_connection') {
            Helpers::businessUpdateOrInsert(['key' => 's3_credential'], [
                'key' => 's3_credential',
                'value' => json_encode([
                    'key' => $request['key'],
                    'secret' => $request['secret'],
                    'region' => $request['region'],
                    'bucket' => $request['bucket'],
                    'url' => $request['url'],
                    'end_point' => $request['end_point']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

//        $credentials=\App\CentralLogics\Helpers::get_business_settings('s3_credential');
//        $config=\App\CentralLogics\Helpers::get_business_data('local_storage');
//
//        $s3Credentials = [
//            'FILESYSTEM_DRIVER' => isset($config)?($config==0?'s3':'local'):'local',
//            'AWS_ACCESS_KEY_ID' => $credentials['key'],
//            'AWS_SECRET_ACCESS_KEY' => $credentials['secret'],
//            'AWS_DEFAULT_REGION' => $credentials['region'],
//            'AWS_BUCKET' => $credentials['bucket'],
//            'AWS_URL' => $credentials['url'],
//            'AWS_ENDPOINT' => $credentials['end_point']
//        ];

//        // Load existing environment file into an array
//        $envFile = file(base_path('.env'), FILE_IGNORE_NEW_LINES);
//        $data = [];
//        foreach ($envFile as $line) {
//            if (!empty(trim($line))) {
//                list($key, $value) = explode('=', $line, 2);
//                $data[$key] = $value;
//            } else {
//                // Preserve empty lines
//                $data[] = '';
//            }
//        }
//
//        // Update existing keys
//        foreach ($s3Credentials as $key => $value) {
//            if (isset($data[$key])) {
//                // Update the value
//                $data[$key] = $value;
//            }
//        }
//
//        // Append any new keys that were not present in the original file
//        foreach ($s3Credentials as $key => $value) {
//            if (!isset($data[$key])) {
//                $data[$key] = $value;
//            }
//        }
//
//        // Write the updated environment file
//        $lines = [];
//        foreach ($data as $key => $value) {
//            if (is_numeric($key)) {
//                // Preserve empty lines
//                $lines[] = '';
//            } else {
//                $lines[] = $key . '=' . $value;
//            }
//        }
//
//        file_put_contents(base_path('.env'), implode(PHP_EOL, $lines) . PHP_EOL);


        Toastr::success(translate('messages.updated_successfully'));
        return back();
    }

    public function mail_index()
    {
        return view('admin-views.business-settings.mail-index');
    }

    public function mail_config(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        Helpers::businessUpdateOrInsert(
            ['key' => 'mail_config'],
            [
                'value' => json_encode([
                    "status" => $request['status'],
                    "name" => $request['name'],
                    "host" => $request['host'],
                    "driver" => $request['driver'],
                    "port" => $request['port'],
                    "username" => $request['username'],
                    "email_id" => $request['email'],
                    "encryption" => $request['encryption'],
                    "password" => $request['password']
                ]),
                'updated_at' => now()
            ]
        );
        Toastr::success(translate('messages.configuration_updated_successfully'));
        return back();
    }
    public function mail_config_status(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $config = BusinessSetting::where(['key' => 'mail_config'])->first();

        $data = $config ? json_decode($config['value'], true) : null;

        Helpers::businessUpdateOrInsert(
            ['key' => 'mail_config'],
            [
                'value' => json_encode([
                    "status" => $request['status'] ?? 0,
                    "name" => $data['name'] ?? '',
                    "host" => $data['host'] ?? '',
                    "driver" => $data['driver'] ?? '',
                    "port" => $data['port'] ?? '',
                    "username" => $data['username'] ?? '',
                    "email_id" => $data['email_id'] ?? '',
                    "encryption" => $data['encryption'] ?? '',
                    "password" => $data['password'] ?? ''
                ]),
                'updated_at' => now()
            ]
        );
        Toastr::success(translate('messages.configuration_updated_successfully'));
        return back();
    }

    public function payment_index()
    {
        $published_status = addon_published_status('Gateways');

        $routes = config('addon_admin_routes');
        $desiredName = 'payment_setup';
        $payment_url = '';
        foreach ($routes as $routeArray) {
            foreach ($routeArray as $route) {
                if ($route['name'] === $desiredName) {
                    $payment_url = $route['url'];
                    break 2;
                }
            }
        }
        $data_values = Setting::whereIn('settings_type', ['payment_config'])->whereIn('key_name', ['ssl_commerz','paypal','stripe','razor_pay','senang_pay','paytabs','paystack','paymob_accept','paytm','flutterwave','liqpay','bkash','mercadopago'])->get();

        return view('admin-views.business-settings.payment-index', compact('published_status', 'payment_url','data_values'));
    }

    public function payment_config_update(Request $request)
    {
        if ($request->toggle_type) {
            Helpers::businessUpdateOrInsert(['key' => $request->toggle_type], [
                'value' =>  $request->toggle_type == 'offline_payment_status' ? $request?->status : json_encode(['status' => $request?->status]),
                'updated_at' => now()
            ]);
            Toastr::success(translate('messages.payment_settings_updated'));
            return back();
        }
        $request['status'] = $request->status ?? 0;

        $validation = [
            'gateway' => 'required|in:ssl_commerz,paypal,stripe,razor_pay,senang_pay,paytabs,paystack,paymob_accept,paytm,flutterwave,liqpay,bkash,mercadopago',
            'mode' => 'required|in:live,test'
        ];



        $currency_check=Helpers::checkCurrency($request['gateway'],'payment_gateway' ) ;

        if(  $request['status'] == 1 && $currency_check !== true ){
            Toastr::warning(translate($currency_check).' ' . translate('does_not_support_your_current_currency') );
        }



        $additional_data = [];

        if ($request['gateway'] == 'ssl_commerz') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'store_id' => 'required_if:status,1',
                'store_password' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'paypal') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'client_id' => 'required_if:status,1',
                'client_secret' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'stripe') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required_if:status,1',
                'published_key' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'razor_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required_if:status,1',
                'api_secret' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'senang_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'callback_url' => 'required_if:status,1',
                'secret_key' => 'required_if:status,1',
                'merchant_id' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'paytabs') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'profile_id' => 'required_if:status,1',
                'server_key' => 'required_if:status,1',
                'base_url' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'paystack') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'public_key' => 'required_if:status,1',
                'secret_key' => 'required_if:status,1',
                'merchant_email' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'paymob_accept') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'callback_url' => 'required_if:status,1',
                'api_key' => 'required_if:status,1',
                'iframe_id' => 'required_if:status,1',
                'integration_id' => 'required_if:status,1',
                'hmac' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'mercadopago') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'access_token' => 'required_if:status,1',
                'public_key' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'liqpay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'private_key' => 'required_if:status,1',
                'public_key' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'flutterwave') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'secret_key' => 'required_if:status,1',
                'public_key' => 'required_if:status,1',
                'hash' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'paytm') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchant_key' => 'required_if:status,1',
                'merchant_id' => 'required_if:status,1',
                'merchant_website_link' => 'required_if:status,1'
            ];
        } elseif ($request['gateway'] == 'bkash') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'app_key' => 'required_if:status,1',
                'app_secret' => 'required_if:status,1',
                'username' => 'required_if:status,1',
                'password' => 'required_if:status,1',
            ];
        }

        $request->validate(array_merge($validation, $additional_data));

        $settings = Setting::where('key_name', $request['gateway'])->where('settings_type', 'payment_config')->first();

        $additional_data_image = $settings['additional_data'] != null ? json_decode($settings['additional_data']) : null;
        $storage =$additional_data_image?->storage ?? 'public';

        if ($request->has('gateway_image')) {
            $gateway_image = $this->file_uploader('payment_modules/gateway_image/', 'png', $request['gateway_image'], $additional_data_image != null ? $additional_data_image->gateway_image : '');
            $storage = Helpers::getDisk();
        } else {
            $gateway_image = $additional_data_image != null ? $additional_data_image->gateway_image : '';

        }

        $payment_additional_data = [
            'gateway_title' => $request['gateway_title'],
            'gateway_image' => $gateway_image,
            'storage' => $storage,
        ];

        $validator = Validator::make($request->all(), array_merge($validation, $additional_data));

        Setting::updateOrCreate(['key_name' => $request['gateway'], 'settings_type' => 'payment_config'], [
            'key_name' => $request['gateway'],
            'live_values' => $validator->validate(),
            'test_values' => $validator->validate(),
            'settings_type' => 'payment_config',
            'mode' => $request['mode'],
            'is_active' => $request['status'],
            'additional_data' => json_encode($payment_additional_data),
        ]);

        Toastr::success(GATEWAYS_DEFAULT_UPDATE_200['message']);
        return back();
    }
    public function theme_settings()
    {
        return view('admin-views.business-settings.theme-settings');
    }
    public function update_theme_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        Helpers::businessUpdateOrInsert(['key' => 'theme'], [
            'value' => $request['theme']
        ]);
        Toastr::success(translate('theme_settings_updated'));
        return back();
    }

    public function app_settings()
    {
        return view('admin-views.business-settings.app-settings');
    }

    public function update_app_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        if($request->type == 'user_app'){
            Helpers::businessUpdateOrInsert(['key' => 'app_minimum_version_android'], [
                'value' => $request['app_minimum_version_android']
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'app_url_android'], [
                'value' => $request['app_url_android']
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'app_minimum_version_ios'], [
                'value' => $request['app_minimum_version_ios']
            ]);

            Helpers::businessUpdateOrInsert(['key' => 'app_url_ios'], [
                'value' => $request['app_url_ios']
            ]);
            Toastr::success(translate('messages.User_app_settings_updated'));
            return back();
        }
        if($request->type == 'restaurant_app'){
            Helpers::businessUpdateOrInsert(['key' => 'app_minimum_version_android_restaurant'], [
                'value' => $request['app_minimum_version_android_restaurant']
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'app_url_android_restaurant'], [
                'value' => $request['app_url_android_restaurant']
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'app_minimum_version_ios_restaurant'], [
                'value' => $request['app_minimum_version_ios_restaurant']
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'app_url_ios_restaurant'], [
                'value' => $request['app_url_ios_restaurant']
            ]);
            Toastr::success(translate('messages.Restaurant_app_settings_updated'));
            return back();
        }
        if($request->type == 'delivery_app'){
            Helpers::businessUpdateOrInsert(['key' => 'app_minimum_version_android_deliveryman'], [
                'value' => $request['app_minimum_version_android_deliveryman']
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'app_url_android_deliveryman'], [
                'value' => $request['app_url_android_deliveryman']
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'app_minimum_version_ios_deliveryman'], [
                'value' => $request['app_minimum_version_ios_deliveryman']
            ]);
            Helpers::businessUpdateOrInsert(['key' => 'app_url_ios_deliveryman'], [
                'value' => $request['app_url_ios_deliveryman']
            ]);

            Toastr::success(translate('messages.Delivery_app_settings_updated'));
            return back();
        }

        return back();
    }

    public function landing_page_settings($tab)
    {
        abort(404);
        // if ($tab == 'index') {
        //     return view('admin-views.business-settings.landing-page-settings.index');
        // } else if ($tab == 'links') {
        //     return view('admin-views.business-settings.landing-page-settings.links');
        // } else if ($tab == 'speciality') {
        //     return view('admin-views.business-settings.landing-page-settings.speciality');
        // } else if ($tab == 'testimonial') {
        //     return view('admin-views.business-settings.landing-page-settings.testimonial');
        // } else if ($tab == 'feature') {
        //     return view('admin-views.business-settings.landing-page-settings.feature');
        // } else if ($tab == 'image') {
        //     return view('admin-views.business-settings.landing-page-settings.image');
        // } else if ($tab == 'backgroundChange') {
        //     return view('admin-views.business-settings.landing-page-settings.backgroundChange');
        // }  else if ($tab == 'react') {
        //     return view('admin-views.business-settings.landing-page-settings.react');
        // } else if ($tab == 'react-feature') {
        //     return view('admin-views.business-settings.landing-page-settings.react_feature');
        // } else if ($tab == 'platform-order') {
        //     return view('admin-views.business-settings.landing-page-settings.our_platform');
        // } else if ($tab == 'platform-restaurant') {
        //     return view('admin-views.business-settings.landing-page-settings.restaurant_platform');
        // } else if ($tab == 'platform-delivery') {
        //     return view('admin-views.business-settings.landing-page-settings.delivery_platform');
        // } else if ($tab == 'react-half-banner') {
        //     return view('admin-views.business-settings.landing-page-settings.react_half_banner');
        // } else if ($tab == 'react-self-registration') {
        //     return view('admin-views.business-settings.landing-page-settings.react_self_reg');
        // }
    }

    public function update_landing_page_settings(Request $request, $tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'text') {
            Helpers::businessUpdateOrInsert(['key' => 'landing_page_text'], [
                'value' => json_encode([
                    'header_title_1' => $request['header_title_1'],
                    'header_title_2' => $request['header_title_2'],
                    'header_title_3' => $request['header_title_3'],
                    'about_title' => $request['about_title'],
                    'why_choose_us' => $request['why_choose_us'],
                    'why_choose_us_title' => $request['why_choose_us_title'],
                    'testimonial_title' => $request['testimonial_title'],
                    'mobile_app_section_heading' => $request['mobile_app_section_heading'],
                    'mobile_app_section_text' => $request['mobile_app_section_text'],
                    'feature_section_description' => $request['feature_section_description'],
                    'feature_section_title' => $request['feature_section_title'],
                    'footer_article' => $request['footer_article'],

                    'join_us_title' => $request['join_us_title'],
                    'join_us_sub_title' => $request['join_us_sub_title'],
                    'join_us_article' => $request['join_us_article'],
                    'our_platform_title' => $request['our_platform_title'],
                    'our_platform_article' => $request['our_platform_article'],
                    'newsletter_title' => $request['newsletter_title'],
                    'newsletter_article' => $request['newsletter_article'],
                ])
            ]);
            Toastr::success(translate('messages.landing_page_text_updated'));
        } else if ($tab == 'links') {
            Helpers::businessUpdateOrInsert(['key' => 'landing_page_links'], [
                'value' => json_encode([
                    'app_url_android_status' => $request['app_url_android_status'],
                    'app_url_android' => $request['app_url_android'],
                    'app_url_ios_status' => $request['app_url_ios_status'],
                    'app_url_ios' => $request['app_url_ios'],
                    'web_app_url_status' => $request['web_app_url_status'],
                    'web_app_url' => $request['web_app_url'],
                    'order_now_url_status' => $request['order_now_url_status'],
                    'order_now_url' => $request['order_now_url']
                ])
            ]);
            Toastr::success(translate('messages.landing_page_links_updated'));
        } else if ($tab == 'speciality') {
            $data = [];
            $imageName = null;
            $storage = 'public';
            $speciality = BusinessSetting::where('key', 'speciality')->first();
            if ($speciality) {
                $data = json_decode($speciality?->value, true);
                $storage =$data['storage'] ?? 'public';
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = Helpers::upload('landing/image/', 'png', $request->file('image'));
                $storage =Helpers::getDisk();
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->speciality_title,
                'storage' => $storage
            ]);

            Helpers::businessUpdateOrInsert(['key' => 'speciality'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_speciality_updated'));
        } else if ($tab == 'feature') {
            $data = [];
            $imageName = null;
            $storage = 'public';
            $feature = BusinessSetting::where('key', 'feature')->first();
            if ($feature) {
                $data = json_decode($feature?->value, true);
                $storage =$data['storage'] ?? 'public';
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = Helpers::upload('landing/image/', 'png', $request->file('image'));
                $storage =Helpers::getDisk();
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->feature_title,
                'feature_description' => $request->feature_description,
                'storage' => $storage
            ]);

            Helpers::businessUpdateOrInsert(['key' => 'feature'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_feature_updated'));
        }
        else if ($tab == 'testimonial') {
            $data = [];
            $imageName = null;
            $storage ='public';
            $speciality = BusinessSetting::where('key', 'testimonial')->first();
            if ($speciality) {
                $data = json_decode($speciality?->value, true);
                $storage =$data['storage'] ?? 'public';
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = Helpers::upload('landing/image/', 'png', $request->file('image'));
                $storage =Helpers::getDisk();
            }
            array_push($data, [
                'img' => $imageName,
                'name' => $request->reviewer_name,
                'position' => $request->reviewer_designation,
                'detail' => $request->review,
                'storage' => $storage
            ]);

            Helpers::businessUpdateOrInsert(['key' => 'testimonial'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_testimonial_updated'));
        }
        else if ($tab == 'image') {
            $data = [];
            $images = BusinessSetting::where('key', 'landing_page_images')->first();
            $top_content_image_storage = 'public';
            $about_us_image_storage = 'public';
            $feature_section_image_storage = 'public';
            $mobile_app_section_image_storage = 'public';
            if ($images) {
                $data = json_decode($images?->value, true);
                $top_content_image_storage =$data['top_content_image_storage'] ?? 'public';
                $about_us_image_storage =$data['about_us_image_storage'] ?? 'public';
                $feature_section_image_storage =$data['feature_section_image_storage'] ?? 'public';
                $mobile_app_section_image_storage =$data['mobile_app_section_image_storage'] ?? 'public';
            }
            if ($request->has('top_content_image')) {
                $validator = Validator::make($request->all(), [
                    'top_content_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = Helpers::upload('landing/image/', 'png', $request->file('top_content_image'));
                $top_content_image_storage =Helpers::getDisk();
                $data['top_content_image'] = $imageName;
                $data['top_content_image_storage'] = $top_content_image_storage;
            }
            if ($request->has('about_us_image')) {
                $validator = Validator::make($request->all(), [
                    'about_us_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                 }
                $imageName = Helpers::upload('landing/image/', 'png', $request->file('about_us_image'));
                $about_us_image_storage =Helpers::getDisk();
                $data['about_us_image'] = $imageName;
                $data['about_us_image_storage'] = $about_us_image_storage;
            }

            if ($request->has('feature_section_image')) {
                $validator = Validator::make($request->all(), [
                    'feature_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                    }
                $imageName = Helpers::upload('landing/image/', 'png', $request->file('feature_section_image'));
                $feature_section_image_storage =Helpers::getDisk();
                $data['feature_section_image'] = $imageName;
                $data['feature_section_image_storage'] = $feature_section_image_storage;
            }
            if ($request->has('mobile_app_section_image')) {
                $validator = Validator::make($request->all(), [
                    'mobile_app_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = Helpers::upload('landing/image/', 'png', $request->file('mobile_app_section_image'));
                $mobile_app_section_image_storage =Helpers::getDisk();
                $data['mobile_app_section_image'] = $imageName;
                $data['mobile_app_section_image_storage'] = $mobile_app_section_image_storage;
            }
            Helpers::businessUpdateOrInsert(['key' => 'landing_page_images'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_image_updated'));
        } else if ($tab == 'background-change') {
            Helpers::businessUpdateOrInsert(['key' => 'backgroundChange'], [
                'value' => json_encode([
                    'primary_1_hex' => $request['header-bg'],
                    'primary_1_rgb' => Helpers::hex_to_rbg($request['header-bg']),
                    'primary_2_hex' => $request['footer-bg'],
                    'primary_2_rgb' => Helpers::hex_to_rbg($request['footer-bg']),
                ])
            ]);
            Toastr::success(translate('messages.background_updated'));
        } else if ($tab == 'react_header') {
            $data = null;
            $image = BusinessSetting::firstOrNew(['key' => 'react_header_banner']);
            if ($image) {
                $data = $image?->value;
            }
            $image_name =$data ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
            if ($request->has('react_header_banner')) {
                // $image_name = ;
                $validator = Validator::make($request->all(), [
                    'react_header_banner' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $data = Helpers::update( dir: 'react_landing/', old_image:$image_name, format:'png',image: $request->file('react_header_banner')) ?? null;
            }
            $image->value = $data;
            $image->save();
            Toastr::success(translate('Landing page header banner updated'));
        } else if ($tab == 'full-banner') {

            $request->validate([
                'banner_section_img_full' => 'nullable|max:2048',
                'full_banner_section_title' => 'required|max:30',
                'full_banner_section_sub_title' => 'required|max:55',
            ]);

            $data = [];
            $banner_section_full = BusinessSetting::firstOrNew(['key'=>'banner_section_full']);
            $imageName = null;
            $storage = 'public';
            if($banner_section_full){
                $data = json_decode($banner_section_full?->value, true);
                $imageName =$data['banner_section_img_full'] ?? null;
                $storage =$data['storage'] ?? 'public';
            }
            if ($request->has('banner_section_img_full'))   {
                if (empty($imageName)) {
                    $imageName = Helpers::upload( dir:'react_landing/',format: 'png',image: $request->file('banner_section_img_full'));
                    $storage = Helpers::getDisk();
                }  else{
                    $imageName= Helpers::update( dir: 'react_landing/',old_image: $data['banner_section_img_full'],format: 'png', image:$request->file('banner_section_img_full')) ;
                    $storage = Helpers::getDisk();
                }
            }
            $data = [
                'banner_section_img_full' => $imageName,
                'full_banner_section_title' => $request->full_banner_section_title ?? $banner_section_full['full_banner_section_title'] ,
                'full_banner_section_sub_title' => $request->full_banner_section_sub_title ?? $banner_section_full['full_banner_section_sub_title'],
                'storage' => $storage,
            ];
            $banner_section_full->value = json_encode($data);

            $banner_section_full->save();
            Toastr::success(translate('messages.landing_page_banner_section_updated'));
        } else if ($tab == 'discount-banner') {

            $request->validate([
                'img' => 'nullable|max:2048',
                'title' => 'required|max:30',
                'sub_title' => 'required|max:55',
                ]);

            $data = [];
            $discount_banner = BusinessSetting::firstOrNew(['key' => 'discount_banner']);
            $imageName = null;
            if($discount_banner){
                $data = json_decode($discount_banner?->value, true);
                $imageName =$data['img'] ?? null;
            }
            if ($request->has('img'))   {
                if (empty($imageName)) {
                    $imageName = Helpers::upload( dir:'react_landing/', format:'png',image: $request->file('img'));
                    }  else{
                    $imageName= Helpers::update( dir: 'react_landing/', old_image: $data['img'],format: 'png',image: $request->file('img')) ;
                    }
            }
            $data = [
                'img' => $imageName,
                'title' => $request->title ?? $discount_banner['title'] ,
                'sub_title' => $request->sub_title ?? $discount_banner['sub_title'],
            ];
            $discount_banner->value = json_encode($data);

            $discount_banner->save();
            Toastr::success(translate('messages.landing_page_discount_banner_section_updated'));
        } else if ($tab == 'banner-section-half') {

            $request->validate([
                'image' => 'nullable|max:2048',
                'title' => 'nullable|max:20',
                'sub_title' => 'nullable|max:30',
            ]);
            $data = [];
            $imageName = null;
            $storage = 'public';
            $banner_section_half = BusinessSetting::firstOrNew(['key'=>'banner_section_half']);
            if ($banner_section_half) {
                $data = json_decode($banner_section_half?->value, true);
                $storage =$data['storage'] ?? 'public';
            }

            if ($request->has('image')) {
                $imageName=Helpers::upload( dir:'react_landing/',format:'png', image:$request->file('image')) ;
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->title ?? null,
                'sub_title' => $request->sub_title ?? null,
                'storage' => $storage
            ]);

            $banner_section_half->value = json_encode($data);

            $banner_section_half->save();
            Toastr::success(translate('messages.landing_page_banner_section_updated'));
        }
        else if ($tab == 'app_section_image') {
            $data = [];
            $app_section_image_storage ='public';
            $app_section_image_2_storage ='public';
            $images = BusinessSetting::firstOrNew(['key' => 'app_section_image']);
            if ($images) {
                $data = json_decode($images?->value, true);
                $app_section_image_storage =$data['app_section_image_storage'] ?? 'public';
                $app_section_image_2_storage =$data['app_section_image_2_storage'] ?? 'public';
            }
            if ($request->has('app_section_image')) {
                $validator = Validator::make($request->all(), [
                    'app_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }

                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $imageName = Helpers::update( dir: 'react_landing/', old_image:$imageName, format:'png', image:$request->file('app_section_image'));
                $app_section_image_storage = Helpers::getDisk();
                $data['app_section_image'] = $imageName;
                $data['app_section_image_storage'] = $app_section_image_storage;
            }
            if ($request->has('app_section_image_2')) {
                $validator = Validator::make($request->all(), [
                    'app_section_image_2' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                 }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $imageName = Helpers::update( dir: 'react_landing/',  old_image:$imageName, format:'png',image: $request->file('app_section_image_2'));
                $app_section_image_2_storage = Helpers::getDisk();
                $data['app_section_image_2'] = $imageName;
                $data['app_section_image_2_storage'] = $app_section_image_2_storage;
            }

            $images->value = json_encode($data);

            $images->save();
            Toastr::success(translate('messages.App section image updated'));
        }

        else if ($tab == 'footer_logo') {
            $data = null;
            $image = BusinessSetting::firstOrNew(['key' => 'footer_logo']);
            if ($image) {
                $data = $image?->value;
            }
            $image_name =$data ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
            if ($request->has('footer_logo')) {
                $validator = Validator::make($request->all(), [
                    'footer_logo' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $data = Helpers::update( dir: 'react_landing/', old_image: $image_name, format:'png', image:$request->file('footer_logo')) ?? null;
            }
            $image->value = json_encode($data);

            $image->save();
            Toastr::success(translate('Footer logo updated'));
        }  else if ($tab == 'react-feature') {

            $request->validate([
                'image' => 'nullable|max:2048',
                'feature_title' => 'required|max:20',
                'feature_description' => 'required',
            ]);

            $data = [];
            $imageName = null;
            $storage = 'public';
            $feature = BusinessSetting::firstOrNew(['key'=>'react_feature']);
            if ($feature) {
                $data = json_decode($feature?->value, true);
                $storage =$data['storage'] ?? 'public';
            }
            if ($request->has('image')) {
                $imageName=Helpers::upload( dir:'react_landing/feature/',format:'png',image: $request->file('image')) ;
                $storage = Helpers::getDisk();
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->feature_title,
                'feature_description' => $request->feature_description,
                'storage' => $storage
            ]);

            $feature->value = json_encode($data);

            $feature->save();
            Toastr::success(translate('messages.landing_page_feature_updated'));
        } else if ($tab == 'platform-main') {

            if($request->button == 'restaurant_platform'){
                $data = [];
                $imageName = null;
                $storage = 'public';
                $restaurant_platform = BusinessSetting::firstOrNew(['key' => 'restaurant_platform']);
                if ($restaurant_platform) {
                    $data = json_decode($restaurant_platform?->value, true);
                    $imageName = $data['image'] ?? null;
                    $storage =$data['storage'] ?? 'public';
                }

                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {
                    Toastr::error( translate('Image size must be within 2mb'));
                    return back();
                    }

                    $imageName  = Helpers::update( dir: 'landing/', old_image:$image_name,format:'png', image:$request->file('image')) ?? null;
                    $storage = Helpers::getDisk();
                }

                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                    'storage' => $storage
                ];
                $restaurant_platform->value = json_encode($data);

                $restaurant_platform->save();
            }
            if($request->button == 'order_platform'){

                $data = [];
                $imageName = null;
                $storage = 'public';
                $order_platform = BusinessSetting::firstOrNew(['key' => 'order_platform']);
                if ($order_platform) {
                    $data = json_decode($order_platform?->value, true);
                    $imageName = $data['image'] ?? null;
                    $storage =$data['storage'] ?? 'public';
                }
                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {
                    Toastr::error( translate('Image size must be within 2mb'));
                    return back();
                    }
                    $imageName  = Helpers::update( dir: 'landing/', old_image:$image_name, format:'png',image: $request->file('image')) ?? null;
                    $storage = Helpers::getDisk();
                }
                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                    'storage' => $storage
                ];
                $order_platform->value = json_encode($data);

                $order_platform->save();
            }
            if($request->button == 'delivery_platform'){
                // dd($request->all());
                $data = [];
                $imageName = null;
                $storage = 'public';
                $delivery_platform = BusinessSetting::firstOrNew(['key' => 'delivery_platform']);
                if ($delivery_platform) {
                    $data = json_decode($delivery_platform?->value, true);
                    $imageName = $data['image'] ?? null;
                    $storage =$data['storage'] ?? 'public';
                }
                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {
                    Toastr::error( translate('Image size must be within 2mb'));
                    return back();
                    }
                    $imageName  = Helpers::update( dir: 'landing/', old_image: $image_name, format:'png', image:$request->file('image')) ?? null;
                    $storage = Helpers::getDisk();
                }
                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    // 'sub_title' => $request->sub_title,
                    // 'detail' => $request->detail,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                    'storage' => $storage
                ];

                $delivery_platform->value = json_encode($data);

                $delivery_platform->save();
            }

            Toastr::success(translate('messages.landing_page_our_platform_updated'));
        }


        else if ($tab == 'platform-data') {
            if($request->button == 'platform_order_data'){
                $data = [];
                $imageName = null;
                $platform_order_data = BusinessSetting::where('key', 'platform_order_data')->first();
                if ($platform_order_data) {
                    $data = json_decode($platform_order_data?->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                Helpers::businessUpdateOrInsert(['key' => 'platform_order_data'], [
                    'value' => json_encode($data)
                ]);
                Toastr::success(translate('messages.landing_page_order_platform_data_added'));
            }
            if($request->button == 'platform_restaurant_data'){
                $data = [];
                $imageName = null;
                $platform_restaurant_data = BusinessSetting::where('key', 'platform_restaurant_data')->first();
                if ($platform_restaurant_data) {
                    $data = json_decode($platform_restaurant_data?->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                Helpers::businessUpdateOrInsert(['key' => 'platform_restaurant_data'], [
                    'value' => json_encode($data)
                ]);
                Toastr::success(translate('messages.landing_page_restaurant_platform_data_added'));
            }
            if($request->button == 'platform_delivery_data'){
                $data = [];
                $imageName = null;
                $platform_delivery_data = BusinessSetting::where('key', 'platform_delivery_data')->first();
                if ($platform_delivery_data) {
                    $data = json_decode($platform_delivery_data?->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                Helpers::businessUpdateOrInsert(['key' => 'platform_delivery_data'], [
                    'value' => json_encode($data)
                ]);
                Toastr::success(translate('messages.landing_page_delivary_platform_data_updated'));
            }

        }
        else if ($tab == 'react-self-registration-delivery-man') {

            $request->validate([
                'image' => 'nullable|max:2048',
                'title' => 'required|max:24',
                'sub_title' => 'required|max:55',
                'button_name' => 'nullable|max:254',
                'button_status' => 'nullable|max:2',
                'button_link' => 'nullable|max:254',
            ]);
            $data = [];
            $react_self_registration_delivery_man = BusinessSetting::firstOrNew(['key'=>'react_self_registration_delivery_man']);
            $imageName = null;
            $storage ='public';
            if($react_self_registration_delivery_man){
                $data = json_decode($react_self_registration_delivery_man?->value, true);
                $imageName =$data['image'] ?? null;
                $storage =$data['storage'] ?? 'public';
            }
            if ($request->has('image'))   {

                if (empty($imageName)) {
                    $imageName = Helpers::upload( dir:'react_landing/', format:'png', image:$request->file('image'));
                    $storage = Helpers::getDisk();
                    }  else{
                    $imageName= Helpers::update( dir: 'react_landing/',old_image: $data['image'],format: 'png',image: $request->file('image')) ;
                    $storage = Helpers::getDisk();
                    }
            }
            $data = [
                'image' => $imageName,
                'title' => $request->title ?? $react_self_registration_delivery_man['title'] ,
                'sub_title' => $request->sub_title ?? $react_self_registration_delivery_man['sub_title'],
                'button_name' => $request->button_name ?? $react_self_registration_delivery_man['button_name'],
                'button_status' => $request->button_status ?? $react_self_registration_delivery_man['button_status'],
                'button_link' => $request->button_link ?? $react_self_registration_delivery_man['button_link'],
                'storage' => $storage
            ];

            $react_self_registration_delivery_man->value = json_encode($data);

            $react_self_registration_delivery_man->save();
            Toastr::success(translate('messages.Delivery_man_self_registration_section_updated'));
        }
        else if ($tab == 'react-self-registration-restaurant') {
            $request->validate([
                'image' => 'nullable|max:2048',
                'title' => 'required|max:24',
                'sub_title' => 'required|max:55',
                'button_name' => 'nullable|max:254',
                'button_status' => 'nullable|max:2',
                'button_link' => 'nullable|max:254'
            ]);
            $data = [];
            $react_self_registration_restaurant = BusinessSetting::firstOrNew(['key'=>'react_self_registration_restaurant']);
            $imageName = null;
            $storage ='public';
            if($react_self_registration_restaurant){
                $data = json_decode($react_self_registration_restaurant?->value, true);
                $imageName =$data['image'] ?? null;
                $storage =$data['storage'] ?? 'public';
            }
            if ($request->has('image'))   {
                if (empty($imageName)) {
                    $imageName = Helpers::upload( dir:'react_landing/', format:'png',image: $request->file('image'));
                    $storage = Helpers::getDisk();
                    }  else{
                    $imageName= Helpers::update( dir: 'react_landing/',old_image: $data['image'],format: 'png',image: $request->file('image')) ;
                    $storage = Helpers::getDisk();
                    }
            }
            $data = [
                'image' => $imageName,
                'title' => $request->title ?? $react_self_registration_restaurant['title'] ,
                'sub_title' => $request->sub_title ?? $react_self_registration_restaurant['sub_title'],
                'button_name' => $request->button_name ?? $react_self_registration_restaurant['button_name'],
                'button_status' => $request->button_status ?? $react_self_registration_restaurant['button_status'],
                'button_link' => $request->button_link ?? $react_self_registration_restaurant['button_link'],
                'storage'=> $storage
            ];
            $react_self_registration_restaurant->value = json_encode($data);

            $react_self_registration_restaurant->save();
            Toastr::success(translate('messages.Restaurant_self_registration_section_updated'));
        }

        return back();
    }

    public function delete_landing_page_settings($tab, $key)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $item = BusinessSetting::where('key', $tab)->first();
        $data = $item ? json_decode($item?->value, true) : null;
        if ($data && array_key_exists($key, $data)) {
            if($tab == 'react_feature' && isset($data[$key]['img'])){
                Helpers::check_and_delete('react_landing/feature/' , $data[$key]['img']);
            }
            if ( $tab != 'react_feature' && isset($data[$key]['img']) && file_exists(public_path('assets/landing/image') . $data[$key]['img'])) {
                unlink(public_path('assets/landing/image') . $data[$key]['img']);
            }

            array_splice($data, $key, 1);

            $item->value = json_encode($data);
            $item->save();
            Toastr::success(translate('messages.' . $tab) . ' ' . translate('messages.deleted'));
            return back();
        }
        Toastr::error(translate('messages.not_found'));
        return back();

    }

    // public function currency_index()
    // {
    //     return view('admin-views.business-settings.currency-index');
    // }

    // public function currency_store(Request $request)
    // {
    //     $request->validate([
    //         'currency_code' => 'required|unique:currencies',
    //     ]);

    //     Currency::create([
    //         "country" => $request['country'],
    //         "currency_code" => $request['currency_code'],
    //         "currency_symbol" => $request['symbol'],
    //         "exchange_rate" => $request['exchange_rate'],
    //     ]);
    //     Toastr::success(translate('messages.currency_added_successfully'));
    //     return back();
    // }

    // public function currency_edit($id)
    // {
    //     $currency = Currency::find($id);
    //     return view('admin-views.business-settings.currency-update', compact('currency'));
    // }

    // public function currency_update(Request $request, $id)
    // {
    //     Currency::where(['id' => $id])->update([
    //         "country" => $request['country'],
    //         "currency_code" => $request['currency_code'],
    //         "currency_symbol" => $request['symbol'],
    //         "exchange_rate" => $request['exchange_rate'],
    //     ]);
    //     Toastr::success(translate('messages.currency_updated_successfully'));
    //     return redirect('restaurant-panel/business-settings/currency-add');
    // }

    // public function currency_delete($id)
    // {
    //     Currency::where(['id' => $id])->delete();
    //     Toastr::success(translate('messages.currency_deleted_successfully'));
    //     return back();
    // }


    private function update_data($request, $key_data){
        $data = DataSetting::firstOrNew(
            ['key' =>  $key_data,
            'type' =>  'admin_landing_page'],
        );

        $data->value = $request->{$key_data}[array_search('default', $request->lang)];
        $data->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->{$key_data}[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\DataSetting',
                            'translationable_id' => $data->id,
                            'locale' => $key,
                            'key' => $key_data
                        ],
                        ['value' => $data->value]
                    );
                }
            } else {
                if ($request->{$key_data}[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\DataSetting',
                            'translationable_id' => $data->id,
                            'locale' => $key,
                            'key' => $key_data
                        ],
                        ['value' => $request->{$key_data}[$index]]
                    );
                }
            }
        }

        return true;
    }


    private function policy_status_update($key_data , $status){
        $data = DataSetting::firstOrNew(
            ['key' =>  $key_data,
            'type' =>  'admin_landing_page'],
        );
        $data->value = $status;
        $data->save();

        return true;
    }


    public function terms_and_conditions()
    {
        $terms_and_conditions =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'terms_and_conditions')->first();
        return view('admin-views.business-settings.terms-and-conditions', compact('terms_and_conditions'));
    }

    public function terms_and_conditions_update(Request $request)
    {
        $this->update_data($request , 'terms_and_conditions');
        Toastr::success(translate('messages.terms_and_condition_updated'));
        return back();
    }

    public function privacy_policy()
    {
        $privacy_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'privacy_policy')->first();
        return view('admin-views.business-settings.privacy-policy', compact('privacy_policy'));
    }

    public function privacy_policy_update(Request $request)
    {
        $this->update_data($request , 'privacy_policy');
        Toastr::success(translate('messages.privacy_policy_updated'));
        return back();
    }

    public function refund_policy()
    {
        $refund_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'refund_policy')->first();
        $refund_policy_status =DataSetting::where('type', 'admin_landing_page')->where('key','refund_policy_status')->first();
        return view('admin-views.business-settings.refund_policy', compact('refund_policy','refund_policy_status'));
    }

    public function refund_policy_update(Request $request)
    {
        $this->update_data($request , 'refund_policy');
        Toastr::success(translate('messages.refund_policy_updated'));
        return back();
    }
    public function refund_policy_status($status)
    {
        $this->policy_status_update('refund_policy_status' , $status);
        return response()->json(['status'=>"changed"]);
    }

    public function shipping_policy()
    {

        $shipping_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'shipping_policy')->first();
        $shipping_policy_status =DataSetting::where('type', 'admin_landing_page')->where('key','shipping_policy_status')->first();
        return view('admin-views.business-settings.shipping_policy', compact('shipping_policy','shipping_policy_status'));
    }

    public function shipping_policy_update(Request $request)
    {
        $this->update_data($request , 'shipping_policy');
        Toastr::success(translate('messages.shipping_policy_updated'));
        return back();
    }


    public function shipping_policy_status($status)
    {
        $this->policy_status_update('shipping_policy_status' , $status);
        return response()->json(['status'=>"changed"]);
    }

    public function cancellation_policy()
    {
        $cancellation_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'cancellation_policy')->first();
        $cancellation_policy_status =DataSetting::where('type', 'admin_landing_page')->where('key','cancellation_policy_status')->first();
        return view('admin-views.business-settings.cancellation_policy',compact('cancellation_policy','cancellation_policy_status'));
    }

    public function cancellation_policy_update(Request $request)
    {
        $this->update_data($request , 'cancellation_policy');
        Toastr::success(translate('messages.cancellation_policy_updated'));
        return back();
    }

    public function cancellation_policy_status($status)
    {
        $this->policy_status_update('cancellation_policy_status' , $status);
        return response()->json(['status'=>"changed"]);
    }

    public function about_us()
    {
        $about_us =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'about_us')->first();
        return view('admin-views.business-settings.about-us', compact('about_us'));
    }

    public function about_us_update(Request $request)
    {
        $this->update_data($request , 'about_us');
        Toastr::success(translate('messages.about_us_updated'));
        return back();
    }

    public function fcm_index()
    {
        $fcm_credentials = Helpers::get_business_settings('fcm_credentials');
        return view('admin-views.business-settings.fcm-index', compact('fcm_credentials'));
    }

    public function update_fcm(Request $request)
    {
        Helpers::businessUpdateOrInsert(['key' => 'push_notification_service_file_content'], [
            'value' => $request['push_notification_service_file_content'],
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'fcm_project_id'], [
            'value' => $request['projectId']
        ]);
//
//        Helpers::businessUpdateOrInsert(['key' => 'push_notification_key'], [
//            'value' => $request['push_notification_key']
//        ]);
//
        Helpers::businessUpdateOrInsert(['key' => 'fcm_credentials'], [
            'value' => json_encode([
                'apiKey'=> $request->apiKey,
                'authDomain'=> $request->authDomain,
                'projectId'=> $request->projectId,
                'storageBucket'=> $request->storageBucket,
                'messagingSenderId'=> $request->messagingSenderId,
                'appId'=> $request->appId,
                'measurementId'=> $request->measurementId
            ])
        ]);
        Toastr::success(translate('messages.settings_updated'));
        session()->put('fcm_updated',1);
        return redirect()->back();
    }
    public function fcm_config()
    {
        $fcm_credentials = Helpers::get_business_settings('fcm_credentials');
        return view('admin-views.business-settings.fcm-config', compact('fcm_credentials'));
    }


    public function update_fcm_messages(Request $request)
    {
        $notification = NotificationMessage::where('key','order_pending_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_pending_message';

        $notification->message = $request->pending_message[array_search('default', $request->lang)];
        $notification->status = $request['pending_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->pending_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->pending_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key','order_confirmation_msg')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_confirmation_msg';

        $notification->message = $request->confirm_message[array_search('default', $request->lang)];
        $notification->status = $request['confirm_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->confirm_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->confirm_message[$index]]
                );
            }
        }

            $notification = NotificationMessage::where('key','order_processing_message')->first();
            if($notification == null){
                $notification = new NotificationMessage();
            }

            $notification->key = 'order_processing_message';

            $notification->message = $request->processing_message[array_search('default', $request->lang)];
            $notification->status = $request['processing_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach($request->lang as $index=>$key)
            {
                if($request->processing_message[$index] && $key != 'default' )
                {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key],
                        ['value'                 => $request->processing_message[$index]]
                    );
                }
            }

            $notification = NotificationMessage::where('key','order_handover_message')->first();
            if($notification == null){
                $notification = new NotificationMessage();
            }

            $notification->key = 'order_handover_message';

            $notification->message = $request->order_handover_message[array_search('default', $request->lang)];
            $notification->status = $request['order_handover_message_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach($request->lang as $index=>$key)
            {
                if($request->order_handover_message[$index] && $key != 'default' )
                {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key],
                        ['value'                 => $request->order_handover_message[$index]]
                    );
                }
            }

            $notification = NotificationMessage::where('key','order_refunded_message')->first();
            if($notification == null){
                $notification = new NotificationMessage();
            }

            $notification->key = 'order_refunded_message';

            $notification->message = $request->order_refunded_message[array_search('default', $request->lang)];
            $notification->status = $request['order_refunded_message_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach($request->lang as $index=>$key)
            {
                if($request->order_refunded_message[$index] && $key != 'default' )
                {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key],
                        ['value'                 => $request->order_refunded_message[$index]]
                    );
                }
            }

            $notification = NotificationMessage::where('key','refund_request_canceled')->first();

            if($notification == null){
                $notification = new NotificationMessage();
            }

            $notification->key = 'refund_request_canceled';

            $notification->message = $request->refund_request_canceled[array_search('default', $request->lang)];
            $notification->status = $request['refund_request_canceled_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach($request->lang as $index=>$key)
            {
                if($request->refund_request_canceled[$index] && $key != 'default' )
                {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key],
                        ['value'                 => $request->refund_request_canceled[$index]]
                    );
                }
            }



        $notification = NotificationMessage::where('key','out_for_delivery_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'out_for_delivery_message';

        $notification->message = $request->out_for_delivery_message[array_search('default', $request->lang)];
        $notification->status = $request['out_for_delivery_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->out_for_delivery_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->out_for_delivery_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key','order_delivered_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_delivered_message';

        $notification->message = $request->delivered_message[array_search('default', $request->lang)];
        $notification->status = $request['delivered_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->delivered_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->delivered_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key','delivery_boy_assign_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'delivery_boy_assign_message';

        $notification->message = $request->delivery_boy_assign_message[array_search('default', $request->lang)];
        $notification->status = $request['delivery_boy_assign_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->delivery_boy_assign_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->delivery_boy_assign_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key','delivery_boy_delivered_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'delivery_boy_delivered_message';

        $notification->message = $request->delivery_boy_delivered_message[array_search('default', $request->lang)];
        $notification->status = $request['delivery_boy_delivered_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->delivery_boy_delivered_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->delivery_boy_delivered_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key','order_cancled_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_cancled_message';

        $notification->message = $request->order_cancled_message[array_search('default', $request->lang)];
        $notification->status = $request['order_cancled_message_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->order_cancled_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->order_cancled_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key', 'offline_order_accept_message')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'offline_order_accept_message';
        $notification->message = $request->offline_order_accept_message[array_search('default', $request->lang)];
        $notification->status = $request['offline_order_accept_message_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->offline_order_accept_message[$index] && $key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->offline_order_accept_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key', 'offline_order_deny_message')->first();
        if ($notification == null) {
            $notification = new NotificationMessage();
        }

        $notification->key = 'offline_order_deny_message';
        $notification->message = $request->offline_order_deny_message[array_search('default', $request->lang)];
        $notification->status = $request['offline_order_deny_message_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach ($request->lang as $index => $key) {
            if ($request->offline_order_deny_message[$index]) {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key
                    ],
                    ['value'                 => $request->offline_order_deny_message[$index]]
                );
            }
        }

        Toastr::success(translate('messages.message_updated'));
        return back();
    }
    // public function location_index()
    // {
    //     return view('admin-views.business-settings.location-index');
    // }

    public function location_setup(Request $request)
    {
        $restaurant = Helpers::get_restaurant_id();
        $restaurant->latitude = $request['latitude'];
        $restaurant->longitude = $request['longitude'];
        $restaurant->save();

        Toastr::success(translate('messages.settings_updated'));
        return back();
    }

    public function config_setup()
    {
        return view('admin-views.business-settings.config');
    }

    public function config_update(Request $request)
    {
        Helpers::businessUpdateOrInsert(['key' => 'map_api_key'], [
            'value' => $request['map_api_key']
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'map_api_key_server'], [
            'value' => $request['map_api_key_server']
        ]);

        Toastr::success(translate('messages.config_data_updated'));
        return back();
    }

    public function toggle_settings($key, $value)
    {
        Helpers::businessUpdateOrInsert(['key' => $key], [
            'value' => $value
        ]);

        Toastr::success(translate('messages.app_settings_updated'));
        return back();
    }

    public function viewSocialLogin()
    {
        $data = BusinessSetting::where('key', 'social_login')->first();
        if(! $data){
            Helpers::insert_business_settings_key('social_login','[{"login_medium":"google","client_id":"","client_secret":"","status":"0"},{"login_medium":"facebook","client_id":"","client_secret":"","status":""}]');
            $data = BusinessSetting::where('key', 'social_login')->first();
        }
        $apple = BusinessSetting::where('key', 'apple_login')->first();
        if (!$apple) {
            Helpers::insert_business_settings_key('apple_login', '[{"login_medium":"apple","client_id":"","client_secret":"","team_id":"","key_id":"","service_file":"","redirect_url":"","status":""}]');
            $apple = BusinessSetting::where('key', 'apple_login')->first();
        }
        $appleLoginServices = json_decode($apple?->value, true);
        $socialLoginServices = json_decode($data?->value, true);
        return view('admin-views.business-settings.social-login.view', compact('socialLoginServices','appleLoginServices'));
    }

    public function updateSocialLogin($service, Request $request)
    {
        $login_setup_status = Helpers::get_business_settings($service.'_login_status')??0;
        if($login_setup_status && ($request['status']==0)){
            Toastr::warning(translate($service.'_login_status_is_enabled_in_login_setup._First_disable_from_login_setup.'));
            return redirect()->back();
        }
        $socialLogin = BusinessSetting::where('key', 'social_login')->first();
        $credential_array = [];
        foreach (json_decode($socialLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        Helpers::businessUpdateOrInsert(['key' => 'social_login'], [
            'value' =>$credential_array
        ]);

        Toastr::success(translate('messages.credential_updated', ['service' => $service]));
        return redirect()->back();
    }
    public function updateAppleLogin($service, Request $request)
    {
        $login_setup_status = Helpers::get_business_settings($service.'_login_status')??0;
        if($login_setup_status && ($request['status']==0)){
            Toastr::warning(translate($service.'_login_status_is_enabled_in_login_setup._First_disable_from_login_setup.'));
            return redirect()->back();
        }
        $appleLogin = BusinessSetting::where('key', 'apple_login')->first();
        $credential_array = [];
        if($request->hasfile('service_file')){
            $fileName = Helpers::upload( dir:'apple-login/', format:'p8',image: $request->file('service_file'));
        }
        foreach (json_decode($appleLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                    'team_id' => $request['team_id'],
                    'key_id' => $request['key_id'],
                    'service_file' => isset($fileName)?$fileName:$data['service_file'],
                    'redirect_url' => $request['redirect_url'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        Helpers::businessUpdateOrInsert(['key' => 'apple_login'], [
            'value' =>$credential_array
        ]);
        Toastr::success(translate('messages.credential_updated', ['service' => $service]));
        return redirect()->back();
    }

    //recaptcha
    public function recaptcha_index(Request $request)
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    public function recaptcha_update(Request $request)
    {
        // dd( $request['status']);
        Helpers::businessUpdateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('messages.updated_successfully'));
        return back();
    }

    public function send_mail(Request $request)
    {
        $response_flag = 0;
        $message = 'success';
        try {

            Mail::to($request->email)->send(new \App\Mail\TestEmailSender());
            $response_flag = 1;
        } catch (\Exception $exception) {
            info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
            $response_flag = 2;
            $message = $exception->getMessage();
        }
        return response()->json(['success' => $response_flag , 'message' => $message]);
    }

    public function react_setup()
    {
        Helpers::react_domain_status_check();
        return view('admin-views.business-settings.react-setup');
    }

    public function react_update(Request $request)
    {
        $request->validate([
            'react_license_code'=>'required',
            'react_domain'=>'required'
        ],[
            'react_license_code.required'=>translate('messages.license_code_is_required'),
            'react_domain.required'=>translate('messages.doamain_is_required'),
        ]);
        if(Helpers::activation_submit($request['react_license_code'])){
            Helpers::businessUpdateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode([
                    'status'=>1,
                    'react_license_code'=>$request['react_license_code'],
                    'react_domain'=>$request['react_domain'],
                    'react_platform' => 'codecanyon'
                ])
            ]);

            Toastr::success(translate('messages.react_data_updated'));
            return back();
        }
        elseif(Helpers::react_activation_check(react_domain:$request->react_domain,react_license_code: $request->react_license_code)){

            Helpers::businessUpdateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode([
                    'status'=>1,
                    'react_license_code'=>$request['react_license_code'],
                    'react_domain'=>$request['react_domain'],
                    'react_platform' => 'iss'
                ])
            ]);

            Toastr::success(translate('messages.react_data_updated'));
            return back();
        }
        Toastr::error(translate('messages.Invalid_license_code_or_unregistered_domain'));
        return back()->withInput(['invalid-data'=>true]);
    }


    public function site_direction(Request $request){
        if (env('APP_MODE') == 'demo') {
            session()->put('site_direction', ($request->status == 1?'ltr':'rtl'));
            return response()->json();
        }
        if($request->status == 1){
            Helpers::businessUpdateOrInsert(['key' => 'site_direction'], [
                'value' => 'ltr'
            ]);
        } else
        {
            Helpers::businessUpdateOrInsert(['key' => 'site_direction'], [
                'value' => 'rtl'
            ]);
        }
        return ;
    }





    public function email_index(Request $request,$type,$tab)
    {
        $template = $request->query('template',null);
        if ($tab == 'new-order') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.place-order-format',compact('template'));
        } else if ($tab == 'forgot-password') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.forgot-pass-format',compact('template'));
        } else if ($tab == 'restaurant-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.restaurant-registration-format',compact('template'));
        } else if ($tab == 'dm-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.dm-registration-format',compact('template'));
        } else if ($tab == 'registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.registration-format',compact('template'));
        } else if ($tab == 'approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.approve-format',compact('template'));
        } else if ($tab == 'deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.deny-format',compact('template'));
        } else if ($tab == 'withdraw-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-request-format',compact('template'));
        } else if ($tab == 'withdraw-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-approve-format',compact('template'));
        } else if ($tab == 'withdraw-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-deny-format',compact('template'));
        } else if ($tab == 'campaign-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-request-format',compact('template'));
        } else if ($tab == 'campaign-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-approve-format',compact('template'));
        } else if ($tab == 'campaign-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-deny-format',compact('template'));
        } else if ($tab == 'refund-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-request-format',compact('template'));
        } else if ($tab == 'login') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.login-format',compact('template'));
        } else if ($tab == 'suspend') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.suspend-format',compact('template'));
        } else if ($tab == 'unsuspend') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.unsuspend-format',compact('template'));
        } else if ($tab == 'cash-collect') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.cash-collect-format',compact('template'));
        } else if ($tab == 'registration-otp') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.registration-otp-format',compact('template'));
        } else if ($tab == 'login-otp') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.login-otp-format',compact('template'));
        } else if ($tab == 'order-verification') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.order-verification-format',compact('template'));
        } else if ($tab == 'refund-request-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-request-deny-format',compact('template'));
        } else if ($tab == 'add-fund') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.add-fund-format',compact('template'));
        } else if ($tab == 'refund-order') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-order-format',compact('template'));
        } else if ($tab == 'offline-payment-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.offline-approved-format',compact('template'));
        } else if ($tab == 'offline-payment-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.offline-deny-format',compact('template'));
        } else if ($tab == 'pos-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.pos-registration-format',compact('template'));
        } else if ($tab == 'new-advertisement') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.new-advertisement-format',compact('template'));
        } else if ($tab == 'update-advertisement') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.update-advertisement-format',compact('template'));
        } else if ($tab == 'advertisement-create') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.advertisement-create-format',compact('template'));
        } else if ($tab == 'advertisement-approved') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.advertisement-approved-format',compact('template'));
        } else if ($tab == 'advertisement-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.advertisement-deny-format',compact('template'));
        } else if ($tab == 'advertisement-resume') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.advertisement-resume-format',compact('template'));
        } else if ($tab == 'advertisement-pause') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.advertisement-pause-format',compact('template'));
        } else if ($tab == 'subscription-successful') {
            return view('admin-views.business-settings.email-format-setting.' . $type . '-email-formats.subscription-successful-format', compact('template'));
        } else if ($tab == 'subscription-renew') {
            return view('admin-views.business-settings.email-format-setting.' . $type . '-email-formats.subscription-renew-format', compact('template'));
        } else if ($tab == 'subscription-shift') {
            return view('admin-views.business-settings.email-format-setting.' . $type . '-email-formats.subscription-shift-format', compact('template'));
        } else if ($tab == 'subscription-cancel') {
            return view('admin-views.business-settings.email-format-setting.' . $type . '-email-formats.subscription-cancel-format', compact('template'));
        } else if ($tab == 'subscription-deadline') {
            return view('admin-views.business-settings.email-format-setting.' . $type . '-email-formats.subscription-deadline-format', compact('template'));
        } else if ($tab == 'subscription-plan_upadte') {
            return view('admin-views.business-settings.email-format-setting.' . $type . '-email-formats.subscription-plan_upadte-format', compact('template'));
        } else if ($tab == 'profile-verification') {
            return view('admin-views.business-settings.email-format-setting.' . $type . '-email-formats.profile-verification-format', compact('template'));

        }

    }

    public function update_email_index(Request $request,$type,$tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        if ($tab == 'new-order') {
            $email_type = 'new_order';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'new_order')->first();
        }elseif($tab == 'forget-password'){
            $email_type = 'forget_password';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'forget_password')->first();
        }elseif($tab == 'restaurant-registration'){
            $email_type = 'restaurant_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'restaurant_registration')->first();
        }elseif($tab == 'dm-registration'){
            $email_type = 'dm_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'dm_registration')->first();
        }elseif($tab == 'registration'){
            $email_type = 'registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'registration')->first();
        }elseif($tab == 'approve'){
            $email_type = 'approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'approve')->first();
        }elseif($tab == 'deny'){
            $email_type = 'deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'deny')->first();
        }elseif($tab == 'withdraw-request'){
            $email_type = 'withdraw_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_request')->first();
        }elseif($tab == 'withdraw-approve'){
            $email_type = 'withdraw_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_approve')->first();
        }elseif($tab == 'withdraw-deny'){
            $email_type = 'withdraw_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_deny')->first();
        }elseif($tab == 'campaign-request'){
            $email_type = 'campaign_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_request')->first();
        }elseif($tab == 'campaign-approve'){
            $email_type = 'campaign_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_approve')->first();
        }elseif($tab == 'campaign-deny'){
            $email_type = 'campaign_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_deny')->first();
        }elseif($tab == 'refund-request'){
            $email_type = 'refund_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_request')->first();
        }elseif($tab == 'login'){
            $email_type = 'login';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'login')->first();
        }elseif($tab == 'suspend'){
            $email_type = 'suspend';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'suspend')->first();
        }elseif($tab == 'unsuspend'){
            $email_type = 'unsuspend';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'unsuspend')->first();
        }elseif($tab == 'cash-collect'){
            $email_type = 'cash_collect';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'cash_collect')->first();
        }elseif($tab == 'registration-otp'){
            $email_type = 'registration_otp';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'registration_otp')->first();
        }elseif($tab == 'login-otp'){
            $email_type = 'login_otp';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'login_otp')->first();
        }elseif($tab == 'order-verification'){
            $email_type = 'order_verification';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'order_verification')->first();
        }elseif($tab == 'refund-request-deny'){
            $email_type = 'refund_request_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_request_deny')->first();
        }elseif($tab == 'add-fund'){
            $email_type = 'add_fund';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'add_fund')->first();
        }elseif($tab == 'refund-order'){
            $email_type = 'refund_order';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_order')->first();
        }elseif($tab == 'offline-payment-deny'){
            $email_type = 'offline_payment_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'offline_payment_deny')->first();
        }elseif($tab == 'offline-payment-approve'){
            $email_type = 'offline_payment_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'offline_payment_approve')->first();
        }elseif($tab == 'pos-registration'){
            $email_type = 'pos_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'pos_registration')->first();
        }elseif($tab == 'new-advertisement'){
            $email_type = 'new_advertisement';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'new_advertisement')->first();
        }elseif($tab == 'update-advertisement'){
            $email_type = 'update_advertisement';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'update_advertisement')->first();
        }elseif($tab == 'advertisement-pause'){
            $email_type = 'advertisement_pause';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'advertisement_pause')->first();
        }elseif($tab == 'advertisement-approved'){
            $email_type = 'advertisement_approved';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'advertisement_approved')->first();
        }elseif($tab == 'advertisement-create'){
            $email_type = 'advertisement_create';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'advertisement_create')->first();
        }elseif($tab == 'advertisement-deny'){
            $email_type = 'advertisement_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'advertisement_deny')->first();
        }elseif($tab == 'advertisement-resume'){
            $email_type = 'advertisement_resume';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'advertisement_resume')->first();
        } elseif ($tab == 'subscription-successful') {
            $email_type = 'subscription-successful';
            $template = EmailTemplate::where('type', $type)->where('email_type', 'subscription-successful')->first();
        } elseif ($tab == 'subscription-renew') {
            $email_type = 'subscription-renew';
            $template = EmailTemplate::where('type', $type)->where('email_type', 'subscription-renew')->first();
        } elseif ($tab == 'subscription-shift') {
            $email_type = 'subscription-shift';
            $template = EmailTemplate::where('type', $type)->where('email_type', 'subscription-shift')->first();
        } elseif ($tab == 'subscription-cancel') {
            $email_type = 'subscription-cancel';
            $template = EmailTemplate::where('type', $type)->where('email_type', 'subscription-cancel')->first();
        } elseif ($tab == 'subscription-deadline') {
            $email_type = 'subscription-deadline';
            $template = EmailTemplate::where('type', $type)->where('email_type', 'subscription-deadline')->first();
        } elseif ($tab == 'subscription-plan_upadte') {
            $email_type = 'subscription-plan_upadte';
            $template = EmailTemplate::where('type', $type)->where('email_type', 'subscription-plan_upadte')->first();
        } elseif ($tab == 'profile-verification') {
            $email_type = 'profile_verification';
            $template = EmailTemplate::where('type', $type)->where('email_type', 'profile_verification')->first();
        }
        if ($template == null) {
            $template = new EmailTemplate();
        }

        // dd($type,$tab,$template);
        $template->title = $request->title[array_search('default', $request->lang)];
        $template->body = $request->body[array_search('default', $request->lang)];

        $template->body_2 = $request?->body_2 ? $request->body_2[array_search('default', $request->lang)] : null;
        $template->button_name = $request->button_name?$request->button_name[array_search('default', $request->lang)]:'';
        $template->footer_text = $request->footer_text[array_search('default', $request->lang)];
        $template->copyright_text = $request->copyright_text[array_search('default', $request->lang)];
        $template->background_image = $request->has('background_image') ? Helpers::update('email_template/', $template->background_image, 'png', $request->file('background_image')) : $template->background_image;
        $template->image = $request->has('image') ? Helpers::update('email_template/', $template->image, 'png', $request->file('image')) : $template->image;
        $template->logo = $request->has('logo') ? Helpers::update('email_template/', $template->logo, 'png', $request->file('logo')) : $template->logo;
        $template->icon = $request->has('icon') ? Helpers::update('email_template/', $template->icon, 'png', $request->file('icon')) : $template->icon;
        $template->email_type = $email_type;
        $template->type = $type;
        $template->button_url = $request->button_url??'';
        $template->email_template = $request->email_template;
        $template->privacy = $request->privacy?'1':0;
        $template->refund = $request->refund?'1':0;
        $template->cancelation = $request->cancelation?'1':0;
        $template->contact = $request->contact?'1':0;
        $template->facebook = $request->facebook?'1':0;
        $template->instagram = $request->instagram?'1':0;
        $template->twitter = $request->twitter?'1':0;
        $template->linkedin = $request->linkedin?'1':0;
        $template->pinterest = $request->pinterest?'1':0;
        $template->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $template->title]
                    );
                }
            } else {

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $request->title[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->body[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'body'
                        ],
                        ['value'                 => $template->body]
                    );
                }
            } else {

                if ($request->body[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'body'
                        ],
                        ['value'                 => $request->body[$index]]
                    );
                }
            }

            if ($request?->body_2 && $default_lang == $key && !($request->body_2[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'body_2'
                        ],
                        ['value'                 => $template->body_2]
                    );
                }
            } else {

                if ($request?->body_2 && $request->body_2[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'body_2'
                        ],
                        ['value'                 => $request->body_2[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->button_name && $request->button_name[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'button_name'
                        ],
                        ['value'                 => $template->button_name]
                    );
                }
            } else {

                if ($request->button_name && $request->button_name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'button_name'
                        ],
                        ['value'                 => $request->button_name[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->footer_text[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'footer_text'
                        ],
                        ['value'                 => $template->footer_text]
                    );
                }
            } else {

                if ($request->footer_text[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'footer_text'
                        ],
                        ['value'                 => $request->footer_text[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->copyright_text[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'copyright_text'
                        ],
                        ['value'                 => $template->copyright_text]
                    );
                }
            } else {

                if ($request->copyright_text[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'copyright_text'
                        ],
                        ['value'                 => $request->copyright_text[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('messages.template_added_successfully'));
        return back();
    }

    public function update_email_status(Request $request,$type,$tab,$status)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'place-order') {
            Helpers::businessUpdateOrInsert(['key' => 'place_order_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'forgot-password') {
            Helpers::businessUpdateOrInsert(['key' => 'forget_password_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'restaurant-registration') {
            Helpers::businessUpdateOrInsert(['key' => 'restaurant_registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'dm-registration') {
            Helpers::businessUpdateOrInsert(['key' => 'dm_registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'registration') {
            Helpers::businessUpdateOrInsert(['key' => 'registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'approve') {
            Helpers::businessUpdateOrInsert(['key' => 'approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'deny') {
            Helpers::businessUpdateOrInsert(['key' => 'deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-request') {
            Helpers::businessUpdateOrInsert(['key' => 'withdraw_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-approve') {
            Helpers::businessUpdateOrInsert(['key' => 'withdraw_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-deny') {
            Helpers::businessUpdateOrInsert(['key' => 'withdraw_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-request') {
            Helpers::businessUpdateOrInsert(['key' => 'campaign_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-approve') {
            Helpers::businessUpdateOrInsert(['key' => 'campaign_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-deny') {
            Helpers::businessUpdateOrInsert(['key' => 'campaign_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-request') {
            Helpers::businessUpdateOrInsert(['key' => 'refund_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'login') {
            Helpers::businessUpdateOrInsert(['key' => 'login_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'suspend') {
            Helpers::businessUpdateOrInsert(['key' => 'suspend_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'unsuspend') {
            Helpers::businessUpdateOrInsert(['key' => 'unsuspend_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'cash-collect') {
            Helpers::businessUpdateOrInsert(['key' => 'cash_collect_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'registration-otp') {
            Helpers::businessUpdateOrInsert(['key' => 'registration_otp_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'login-otp') {
            Helpers::businessUpdateOrInsert(['key' => 'login_otp_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'order-verification') {
            Helpers::businessUpdateOrInsert(['key' => 'order_verification_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-request-deny') {
            Helpers::businessUpdateOrInsert(['key' => 'refund_request_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'add-fund') {
            Helpers::businessUpdateOrInsert(['key' => 'add_fund_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-order') {
            Helpers::businessUpdateOrInsert(['key' => 'refund_order_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'offline-payment-deny') {
            Helpers::businessUpdateOrInsert(['key' => 'offline_payment_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'offline-payment-approve') {
            Helpers::businessUpdateOrInsert(['key' => 'offline_payment_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'pos-registration') {
            Helpers::businessUpdateOrInsert(['key' => 'pos_registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'new-advertisement') {
            Helpers::businessUpdateOrInsert(['key' => 'new_advertisement_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'update-advertisement') {
            Helpers::businessUpdateOrInsert(['key' => 'update_advertisement_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'advertisement-resume') {
            Helpers::businessUpdateOrInsert(['key' => 'advertisement_resume_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'advertisement-approved') {
            Helpers::businessUpdateOrInsert(['key' => 'advertisement_approved_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'advertisement-create') {
            Helpers::businessUpdateOrInsert(['key' => 'advertisement_create_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'advertisement-pause') {
            Helpers::businessUpdateOrInsert(['key' => 'advertisement_pause_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'advertisement-deny') {
            Helpers::businessUpdateOrInsert(['key' => 'advertisement_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-successful') {
            Helpers::businessUpdateOrInsert(['key' => 'subscription_successful_mail_status_' . $type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-renew') {
            Helpers::businessUpdateOrInsert(['key' => 'subscription_renew_mail_status_' . $type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-shift') {
            Helpers::businessUpdateOrInsert(['key' => 'subscription_shift_mail_status_' . $type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-cancel') {
            Helpers::businessUpdateOrInsert(['key' => 'subscription_cancel_mail_status_' . $type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-deadline') {
            Helpers::businessUpdateOrInsert(['key' => 'subscription_deadline_mail_status_' . $type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-plan_upadte') {
            Helpers::businessUpdateOrInsert(['key' => 'subscription_plan_upadte_mail_status_' . $type], [
                'value' => $status
            ]);
        } else if ($tab == 'profile-verification') {
            Helpers::businessUpdateOrInsert(['key' => 'profile_verification_mail_status_' . $type], [
                'value' => $status
            ]);
        }
        Toastr::success(translate('messages.email_status_updated'));
        return back();

    }

    public function login_settings(){
        $data = array_column(BusinessSetting::whereIn('key',['manual_login_status','otp_login_status','social_login_status','google_login_status','facebook_login_status','apple_login_status','email_verification_status','phone_verification_status'
                ])->get(['key','value'])->toArray(), 'value', 'key');

        return view('admin-views.login-setup.login_page',compact('data'));
    }

    public function login_settings_update(Request $request)
    {
        $social_login = [];
        $social_login_data=Helpers::get_business_settings('social_login') ?? [];
        foreach ($social_login_data as $social) {
            $social_login[$social['login_medium']] = (boolean)$social['status'];
        }
        $social_login_data=Helpers::get_business_settings('apple_login') ?? [];
        foreach ($social_login_data as $social) {
            $social_login[$social['login_medium']] = (boolean)$social['status'];
        }

        $is_firebase_active=Helpers::get_business_settings('firebase_otp_verification') ?? 0;

        $is_sms_active= Setting::where('is_active',1)->whereJsonContains('live_values->status','1')->where('settings_type', 'sms_config')->exists();

        $is_mail_active= config('mail.status');

        if(!$request['manual_login_status'] && !$request['otp_login_status'] && !$request['social_login_status']){
            Session::flash('select-one-method', true);
            return back();
        }

        if($request['otp_login_status'] && !$is_sms_active && !$is_firebase_active){
            Session::flash('sms-config', true);
            return back();
        }

        if(!$request['manual_login_status'] && !$request['otp_login_status'] && $request['social_login_status']){
            if(!$request['google_login_status'] && !$request['facebook_login_status']){
                Session::flash('select-one-method-android', true);
                return back();
            }
        }
        if( $request['social_login_status'] &&  !$request['google_login_status'] && !$request['facebook_login_status'] && !$request['apple_login_status']){
            Session::flash('select-one-method-social-login', true);
            return back();
        }

        if(($request['social_login_status'] && $request['google_login_status'] && !isset($social_login['google'])) || ($request['social_login_status'] && ($request['google_login_status'] && isset($social_login['google'])) && !$social_login['google'])){
            Session::flash('setup-google', true);
            return back();
        }

        if(($request['social_login_status'] && $request['facebook_login_status'] && !isset($social_login['facebook'])) || ($request['social_login_status'] && ($request['facebook_login_status'] && isset($social_login['facebook'])) && !$social_login['facebook'])){
            Session::flash('setup-facebook', true);
            return back();
        }

        if(($request['social_login_status'] && $request['apple_login_status'] && !isset($social_login['apple'])) || ($request['social_login_status'] && ($request['apple_login_status'] && isset($social_login['apple'])) && !$social_login['apple'])){
            Session::flash('setup-apple', true);
            return back();
        }

        if($request['phone_verification_status'] && !$is_sms_active && !$is_firebase_active){
            Session::flash('sms-config-verification', true);
            return back();
        }

        if($request['email_verification_status'] && !$is_mail_active){
            Session::flash('mail-config-verification', true);
            return back();
        }


        Helpers::businessUpdateOrInsert(['key' => 'manual_login_status'], [
            'value' => $request['manual_login_status'] ? 1 : 0
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'otp_login_status'], [
            'value' => $request['otp_login_status'] ? 1 : 0
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'social_login_status'], [
            'value' => $request['social_login_status'] ? 1 : 0
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'google_login_status'], [
            'value' => $request['social_login_status']?($request['google_login_status'] ? 1 : 0):0
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'facebook_login_status'], [
            'value' => $request['social_login_status']?($request['facebook_login_status'] ? 1 : 0):0
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'apple_login_status'], [
            'value' => $request['social_login_status']?($request['apple_login_status'] ? 1 : 0):0
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'email_verification_status'], [
            'value' => $request['email_verification_status'] ? 1 : 0
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'phone_verification_status'], [
            'value' => $request['phone_verification_status'] ? 1 : 0
        ]);

        Toastr::success(translate('messages.login_settings_data_updated_successfully'));
        return back();
    }


    public function firebase_otp_index(Request $request)
    {
        $is_sms_active= Setting::where('is_active',1)->whereJsonContains('live_values->status','1')->where('settings_type', 'sms_config')
            ->exists();
        $is_mail_active= config('mail.status');
        return view('admin-views.business-settings.firebase-otp-index',compact('is_sms_active','is_mail_active'));
    }

    public function firebase_otp_update(Request $request)
    {
        $login_setup_status = Helpers::get_business_settings('otp_login_status')??0;
        $phone_verification_status = Helpers::get_business_settings('phone_verification_status')??0;
        $is_sms_active= Setting::where('is_active',1)->whereJsonContains('live_values->status','1')->where('settings_type', 'sms_config')
            ->exists();
        if(!$is_sms_active && $login_setup_status && ($request['firebase_otp_verification']==0)){
            Toastr::warning(translate('otp_login_status_is_enabled_in_login_setup._First_disable_from_login_setup.'));
            return redirect()->back();
        }
        if(!$is_sms_active && $phone_verification_status && ($request['firebase_otp_verification']==0)){
            Toastr::warning(translate('phone_verification_status_is_enabled_in_login_setup._First_disable_from_login_setup.'));
            return redirect()->back();
        }
        Helpers::businessUpdateOrInsert(['key' => 'firebase_otp_verification'], [
            'value' => $request['firebase_otp_verification'] ?? 0
        ]);
        Helpers::businessUpdateOrInsert(['key' => 'firebase_web_api_key'], [
            'value' => $request['firebase_web_api_key']
        ]);

        Toastr::success(translate('messages.updated_successfully'));
        return back();
    }

    public function login_url_page(){
        $data=array_column(DataSetting::whereIn('key',['restaurant_employee_login_url','restaurant_login_url','admin_employee_login_url','admin_login_url'
                ])->get(['key','value'])->toArray(), 'value', 'key');

        return view('admin-views.login-setup.login_setup',compact('data'));
    }
    public function login_url_page_update(Request $request){

        $request->validate([
            'type' => 'required',
            'admin_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
            'admin_employee_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
            'restaurant_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
            'restaurant_employee_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
        ]);

        if($request->type == 'admin') {
            Helpers::dataUpdateOrInsert(['key' => 'admin_login_url','type' => 'login_admin'], [
                'value' => $request->admin_login_url
            ]);
            // Config::set('admin_login_url', $request->admin_login_url);
        }
        elseif($request->type == 'admin_employee') {
            Helpers::dataUpdateOrInsert(['key' => 'admin_employee_login_url','type' => 'login_admin_employee'], [
                'value' => $request->admin_employee_login_url
            ]);
        }
        elseif($request->type == 'restaurant') {
            Helpers::dataUpdateOrInsert(['key' => 'restaurant_login_url','type' => 'login_restaurant'], [
                'value' => $request->restaurant_login_url
            ]);
        }
        elseif($request->type == 'restaurant_employee') {
            Helpers::dataUpdateOrInsert(['key' => 'restaurant_employee_login_url','type' => 'login_restaurant_employee'], [
                'value' => $request->restaurant_employee_login_url
            ]);
        }
        Toastr::success(translate('messages.update_successfull'));
        return back();
    }


    public function remove_image(Request $request){

        $request->validate([
            'model_name' => 'required',
            'id' => 'required',
            'image_path' => 'required',
            'field_name' => 'required',
        ]);
    try {

        $model_name = $request->model_name;
        $model = app("\\App\\Models\\{$model_name}");
        $data=  $model->where('id', $request->id)->first();
        // dd($request->image_path);

        $data_value = $data?->{$request->field_name};
        if (!$data_value){
            $data_value = json_decode($data?->value, true);
        }

//         dd($data_value);

                if($request?->json == 1){
                    Helpers::check_and_delete($request->image_path.'/' , $data_value[$request->field_name]);
                    $data_value[$request->field_name] = null;
                    $data->value = json_encode($data_value);
                }
                else{
                    Helpers::check_and_delete($request->image_path.'/' , $data_value);
                    $data->{$request->field_name} = null;
                }

        $data?->save();

    } catch (\Throwable $th) {
        Toastr::error($th->getMessage(). 'Line....'.$th->getLine());
        return back();
    }
        Toastr::success(translate('messages.Image_removed_successfully'));
        return back();
    }



    public function landing_page_settings_update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'landing_integration_via' => 'required',
            'redirect_url' => 'required_if:landing_integration_via,url',
            'file_upload' => 'mimes:zip'
        ]);

        if(!File::exists('resources/views/layouts/landing/custom/index.blade.php') && ($request->landing_integration_via == 'file_upload') && (!$request->file('file_upload'))){
            $validator->getMessageBag()->add('file_upload', translate('messages.zip_file_is_required'));
        }

        if ($validator->errors()->count() > 0) {
            $error = Helpers::error_processor($validator);
            return response()->json(['status' => 'error', 'message' => $error[0]['message']]);
        }

        Helpers::businessUpdateOrInsert(['key' => 'landing_integration_type'], [
            'value' => $request['landing_integration_via']
        ]);
        $status = 'success';
        $message = translate('updated_successfully!');

        if($request->landing_integration_via == 'file_upload'){

            $file = $request->file('file_upload');
            if($file){

                $filename = $file->getClientOriginalName();
                $tempPath = $file->storeAs('temp', $filename);
                $zip = new \ZipArchive();
                if ($zip->open(storage_path('app/' . $tempPath)) === TRUE) {
                    // Extract the contents to a directory
                    $extractPath = base_path('resources/views/layouts/landing/custom');
                    $zip->extractTo($extractPath);
                    $zip->close();
                    // dd(File::exists($extractPath.'/index.blade.php'));
                    if(File::exists($extractPath.'/index.blade.php')){
                        Toastr::success(translate('file_upload_successfully!'));
                        $status = 'success';
                        $message = translate('file_upload_successfully!');
                    }else{
                        File::deleteDirectory($extractPath);
                        $status = 'error';
                        $message = translate('invalid_file!');
                    }
                }else{
                    $status = 'error';
                    $message = translate('file_upload_fail!');
                }

                Storage::delete($tempPath);
            }
        }

        if($request->landing_integration_via == 'url'){
            Helpers::businessUpdateOrInsert(['key' => 'landing_page_custom_url'], [
                'value' => $request['redirect_url']
            ]);

            $status = 'success';
            $message = translate('url_saved_successfully!');
        }

        return response()->json([
            'status' => $status,
            'message'=> $message
        ]);
    }

    public function delete_custom_landing_page()
    {
        $filePath = 'resources/views/layouts/landing/custom/index.blade.php';

        if (File::exists($filePath)) {
            File::delete($filePath);
            Toastr::success(translate('messages.File_deleted_successfully'));
            return back();
        } else {
            Toastr::error(translate('messages.File_not_found'));
            return back();
        }
    }


    public function notification_setup(Request $request){


        if(NotificationSetting::count() == 0 ){
            Helpers::notificationDataSetup();
        }
        Helpers::addNewAdminNotificationSetupDataSetup();
        $data= NotificationSetting::
            when( $request?->type == null ||  $request?->type == 'admin'  , function($query){
            $query->where('type','admin');
        })
        ->when($request?->type == 'restaurant'  , function($query){
            $query->where('type','restaurant');
        })
        ->when($request?->type == 'customers'  , function($query){
            $query->where('type','customer');
        })
        ->when($request?->type == 'deliveryman'  , function($query){
            $query->where('type','deliveryman');
        })->get();


        $business_name= BusinessSetting::where('key','business_name')->first()?->value;
        return view('admin-views.business-settings.notification_setup',compact('business_name' ,'data'));

    }
    public function notification_status_change($key,$user_type, $type){
        $data= NotificationSetting::where('type',$user_type)->where('key',$key)->first();
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


}
