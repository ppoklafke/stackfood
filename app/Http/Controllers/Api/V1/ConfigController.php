<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Zone;
use App\Models\Vehicle;
use App\Models\Currency;
use App\Models\ReactFaq;
use App\Models\DataSetting;
use App\Models\SocialMedia;
use App\Traits\AddonHelper;
use App\Models\ReactService;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\ReactOpportunity;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\OfflinePaymentMethod;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\ReactPromotionalBanner;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ConfigController extends Controller
{
    private $map_api_key;
    use AddonHelper;

    function __construct()
    {
        $map_api_key_server = BusinessSetting::where(['key' => 'map_api_key_server'])->first()?->value ?? null;
        $this->map_api_key = $map_api_key_server;
    }

    public function configuration()
    {
        $key = [
            'cash_on_delivery', 'digital_payment', 'default_location', 'free_delivery_over', 'business_name', 'logo', 'address', 'phone', 'email_address', 'country', 'currency_symbol_position', 'app_minimum_version_android','app_url_android', 'app_minimum_version_ios', 'app_url_ios', 'customer_verification', 'order_delivery_verification', 'terms_and_conditions', 'privacy_policy', 'about_us', 'maintenance_mode', 'popular_food', 'popular_restaurant', 'new_restaurant', 'most_reviewed_foods', 'show_dm_earning', 'canceled_by_deliveryman', 'canceled_by_restaurant', 'timeformat', 'toggle_veg_non_veg', 'toggle_dm_registration', 'toggle_restaurant_registration', 'schedule_order_slot_duration','loyalty_point_exchange_rate', 'loyalty_point_item_purchase_point', 'loyalty_point_status', 'loyalty_point_minimum_point', 'wallet_status', 'schedule_order', 'dm_tips_status', 'ref_earning_status', 'ref_earning_exchange_rate', 'theme','business_model','admin_commission','footer_text' ,'icon','refund_active_status','refund_policy','shipping_policy','cancellation_policy','free_trial_period','app_minimum_version_android_restaurant','app_url_android_restaurant','app_minimum_version_ios_restaurant','app_url_ios_restaurant','app_minimum_version_android_deliveryman','tax_included','order_subscription','app_url_android_deliveryman','app_minimum_version_ios_deliveryman','app_url_ios_deliveryman', 'cookies_text','take_away','repeat_order_option','home_delivery','add_fund_status','partial_payment_status','partial_payment_method','additional_charge','additional_charge_status','additional_charge_name','dm_picture_upload_status','offline_payment_status','instant_order','customer_date_order_sratus' ,'customer_order_date','free_delivery_distance','guest_checkout_status','country_picker_status','disbursement_type','restaurant_disbursement_waiting_time','dm_disbursement_waiting_time', 'min_amount_to_pay_restaurant', 'extra_packaging_charge' ,'min_amount_to_pay_dm','restaurant_review_reply','manual_login_status','otp_login_status','social_login_status','google_login_status','facebook_login_status','apple_login_status','email_verification_status','firebase_otp_verification','phone_verification_status', 'subscription_deadline_warning_days', 'subscription_deadline_warning_message', 'subscription_free_trial_days', 'subscription_free_trial_type', 'subscription_free_trial_status','dine_in_order_option',
        ];


        $deliveryman_additional_join_us_page_data=Cache::rememberForever("data_settings_deliveryman_page_data", function () {
            return DataSetting::Where('type' , 'deliveryman')->where('key' , 'deliveryman_page_data')->first()?->value;
        });

        $deliveryman_additional_join_us_page_data =  $deliveryman_additional_join_us_page_data  && count(json_decode($deliveryman_additional_join_us_page_data ,true))  > 0? json_decode($deliveryman_additional_join_us_page_data ,true)  : null;

        $restaurant_additional_join_us_page_data=Cache::rememberForever("data_settings_restaurant_page_data", function () {
            return   DataSetting::Where('type' , 'restaurant')->where('key' , 'restaurant_page_data')->first()?->value;
        });
        $restaurant_additional_join_us_page_data =  $restaurant_additional_join_us_page_data && count(json_decode($restaurant_additional_join_us_page_data ,true))  > 0 ? json_decode($restaurant_additional_join_us_page_data ,true)  : null;

        $banner_data= Cache::rememberForever("data_settings_promotional_banner", function () {
            return DataSetting::where('type','promotional_banner')->whereIn('key' ,['promotional_banner_title' ,'promotional_banner_image'])->pluck('value','key')->toArray();
        });
        $banner_data_storage=Cache::rememberForever("data_settings_promotional_banner_storage", function () {
            return DataSetting::where('type','promotional_banner')->where('key' ,'promotional_banner_image')->first()?->storage[0]?->value ??'public';
        });
        $banner_data['promotional_banner_image_full_url'] = Helpers::get_full_url('banner',data_get($banner_data,'promotional_banner_image') ,$banner_data_storage);
        $social_login = [];
        $social_login_data=Helpers::get_business_settings('social_login') ?? [];
        foreach ($social_login_data as $social) {
            $config = [
                'login_medium' => $social['login_medium'],
                'status' => (boolean)$social['status']
            ];
            array_push($social_login, $config);
        }

        //addon settings publish status
        $published_status = 0; // Set a default value
        $payment_published_status = config('get_payment_publish_status');
        if (isset($payment_published_status[0]['is_published'])) {
            $published_status = $payment_published_status[0]['is_published'];
        }

        $active_addon_payment_lists = $published_status == 1 ? $this->getPaymentMethods() : $this->getDefaultPaymentMethods();

        $cacheKey = 'business_settings_keys';
        $settings = Cache::rememberForever($cacheKey, function () use ($key) {
            return array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');
        });

        $image_key = ['logo','icon'];
        $data = [];

        foreach ($image_key as $value){
            $data[$value . '_storage'] = Cache::rememberForever("business_settings_{$value}_storage", function () use ($value) {
                return BusinessSetting::where('key', $value)->first()?->storage[0]?->value ?? 'public';
            });
        }

        $currency_symbol = Cache::rememberForever("business_settings_currency_symbol", function () {
            return Currency::where(['currency_code' => Helpers::currency_code()])->first()?->currency_symbol;
        });
        $cod = json_decode($settings['cash_on_delivery'], true);
        $business_plan = isset($settings['business_model']) ? json_decode($settings['business_model'], true) : [
            'commission'        =>  1,
            'subscription'     =>  0,
        ];

        $digital_payment = json_decode($settings['digital_payment'], true);

        $digital_payment_infos = array(
            'digital_payment' => (boolean)($digital_payment['status'] == 1 ? true : false),
            'plugin_payment_gateways' =>  (boolean)($published_status ? true : false),
            'default_payment_gateways' =>  (boolean)($published_status ? false : true)
        );

        $default_location = isset($settings['default_location']) ? json_decode($settings['default_location'], true) : 0;
        $free_delivery_over = $settings['free_delivery_over'];
        $free_delivery_over = $free_delivery_over ? (float)$free_delivery_over : $free_delivery_over;

        $free_delivery_distance = isset($settings['free_delivery_distance']) ? (float)$settings['free_delivery_distance'] : 0;
        $languages = Helpers::get_business_settings('language');
        $lang_array = [];
        foreach ($languages as $language) {
            array_push($lang_array, [
                'key' => $language,
                'value' => Helpers::get_language_name($language)
            ]);
        }


        $apple_login = [];
        $apples = Helpers::get_business_settings('apple_login');
        if(isset($apples)){
            foreach (Helpers::get_business_settings('apple_login') as $apple) {
                $config = [
                    'login_medium' => $apple['login_medium'],
                    'status' => (boolean)$apple['status'],
                    'client_id' => $apple['client_id']
                ];
                array_push($apple_login, $config);
            }
        }


            $maintenance_mode_data=  Cache::rememberForever("data_settings_maintenance_mode", function () {
                return DataSetting::where('type','maintenance_mode')->whereIn('key' ,['maintenance_system_setup' ,'maintenance_duration_setup','maintenance_message_setup'])->pluck('value','key')
                ->map(function ($value) {
                    return json_decode($value, true);
                })
                ->toArray();
            });
            if (data_get($settings, 'subscription_free_trial_type') == 'year') {
                $trial_period = data_get($settings, 'subscription_free_trial_days') > 0 ? data_get($settings, 'subscription_free_trial_days') / 365 : 0;
            } else if (data_get($settings, 'subscription_free_trial_type') == 'month') {
                $trial_period = data_get($settings, 'subscription_free_trial_days') > 0 ? data_get($settings, 'subscription_free_trial_days') / 30 : 0;
            } else {
                $trial_period = data_get($settings, 'subscription_free_trial_days') > 0 ? data_get($settings, 'subscription_free_trial_days') : 0;
            }
        return response()->json([
            'business_name' => $settings['business_name'],
            'logo' => $settings['logo'],
            'logo_full_url' => Helpers::get_full_url('business',$settings['logo'],$data['logo_storage']??'public'),
            'address' => $settings['address'],
            'phone' => $settings['phone'],
            'email' => $settings['email_address'],
            'country' => $settings['country'],
            'default_location' => ['lat' => $default_location ? $default_location['lat'] : '23.757989', 'lng' => $default_location ? $default_location['lng'] : '90.360587'],
            'currency_symbol' => $currency_symbol,
            'currency_symbol_direction' => $settings['currency_symbol_position'],
            'app_minimum_version_android' => (float)$settings['app_minimum_version_android'],
            'app_url_android' => $settings['app_url_android'],
            'app_minimum_version_ios' => (float)$settings['app_minimum_version_ios'],
            'app_url_ios' => $settings['app_url_ios'],
            'customer_verification' => (bool)$settings['customer_verification'],
            'schedule_order' => (bool)$settings['schedule_order'],
            'order_delivery_verification' => (bool)$settings['order_delivery_verification'],
            'cash_on_delivery' => (bool)($cod['status'] == 1 ? true : false),
            'digital_payment' => (bool)($digital_payment['status'] == 1 ? true : false),

            'free_delivery_over' => $free_delivery_over,
            'free_delivery_distance' => $free_delivery_distance,
            'demo' => (bool)(env('APP_MODE') == 'demo' ? true : false),
            'maintenance_mode' => (bool)Helpers::get_business_settings('maintenance_mode') ?? 0,
            'order_confirmation_model' => config('order_confirmation_model'),
            'popular_food' => (float)$settings['popular_food'],
            'popular_restaurant' => (float)$settings['popular_restaurant'],
            'new_restaurant' => (float)$settings['new_restaurant'],
            'most_reviewed_foods' => (float)$settings['most_reviewed_foods'],
            'show_dm_earning' => (bool)$settings['show_dm_earning'],
            'canceled_by_deliveryman' => (bool)$settings['canceled_by_deliveryman'],
            'canceled_by_restaurant' => (bool)$settings['canceled_by_restaurant'],
            'timeformat' => (string)$settings['timeformat'],
            'language' => $lang_array,
            'toggle_veg_non_veg' => (bool)$settings['toggle_veg_non_veg'],
            'toggle_dm_registration' => (bool)$settings['toggle_dm_registration'],
            'toggle_restaurant_registration' => (bool)$settings['toggle_restaurant_registration'],
            'schedule_order_slot_duration' => (int)$settings['schedule_order_slot_duration'],
            'digit_after_decimal_point' => (int)config('round_up_to_digit'),
            'loyalty_point_exchange_rate' => (int)(isset($settings['loyalty_point_item_purchase_point']) ? $settings['loyalty_point_exchange_rate'] : 0),
            'loyalty_point_item_purchase_point' => (float)(isset($settings['loyalty_point_item_purchase_point']) ? $settings['loyalty_point_item_purchase_point'] : 0.0),
            'loyalty_point_status' => (int)(isset($settings['loyalty_point_status']) ? $settings['loyalty_point_status'] : 0),
            'minimum_point_to_transfer' => (int)(isset($settings['loyalty_point_minimum_point']) ? $settings['loyalty_point_minimum_point'] : 0),
            'customer_wallet_status' => (int)(isset($settings['wallet_status']) ? $settings['wallet_status'] : 0),
            'ref_earning_status' => (int)(isset($settings['ref_earning_status']) ? $settings['ref_earning_status'] : 0),
            'ref_earning_exchange_rate' => (double)(isset($settings['ref_earning_exchange_rate']) ? $settings['ref_earning_exchange_rate'] : 0),
            'dm_tips_status' => (int)(isset($settings['dm_tips_status']) ? $settings['dm_tips_status'] : 0),
            'theme' => (int)$settings['theme'],
            'social_media'=>SocialMedia::active()->get()->toArray(),
            'social_login' => $social_login,
            'business_plan' => $business_plan,
            'admin_commission' => (float)(isset($settings['admin_commission']) ? $settings['admin_commission'] : 0),
            'footer_text' => $settings['footer_text'],
            'fav_icon' => $settings['icon'],
            'fav_icon_full_url' => Helpers::get_full_url('business',$settings['icon'],$data['icon_storage']??'public'),
            'refund_active_status' => (bool)(isset($settings['refund_active_status']) ? $settings['refund_active_status'] : 0),

            'free_trial_period_status' => (int)(isset($settings['free_trial_period']) ? json_decode($settings['free_trial_period'], true)['status'] : 0),
            'free_trial_period_data' =>  (int)(isset($settings['free_trial_period']) ? json_decode($settings['free_trial_period'], true)['data'] : 0),

            'app_minimum_version_android_restaurant' => (float)(isset($settings['app_minimum_version_android_restaurant']) ? $settings['app_minimum_version_android_restaurant'] : 0),
            'app_url_android_restaurant' => (isset($settings['app_url_android_restaurant']) ? $settings['app_url_android_restaurant'] : null),
            'app_minimum_version_ios_restaurant' => (float)(isset($settings['app_minimum_version_ios_restaurant']) ? $settings['app_minimum_version_ios_restaurant'] : 0),
            'app_url_ios_restaurant' => (isset($settings['app_url_ios_restaurant']) ? $settings['app_url_ios_restaurant'] : null),
            'app_minimum_version_android_deliveryman' => (float)(isset($settings['app_minimum_version_android_deliveryman']) ? $settings['app_minimum_version_android_deliveryman'] : 0),
            'app_url_android_deliveryman' => (isset($settings['app_url_android_deliveryman']) ? $settings['app_url_android_deliveryman'] : null),
            'app_minimum_version_ios_deliveryman' => (isset($settings['app_minimum_version_ios_deliveryman']) ? $settings['app_minimum_version_ios_deliveryman'] : null),
            'app_url_ios_deliveryman' => (isset($settings['app_url_ios_deliveryman']) ? $settings['app_url_ios_deliveryman'] : null),
            'tax_included' => (int)(isset($settings['tax_included']) ? $settings['tax_included'] : 0),
            'apple_login' => $apple_login,
            'order_subscription' => (int)(isset($settings['order_subscription']) ? $settings['order_subscription'] : 0),
            'cookies_text'=>isset($settings['cookies_text'])?$settings['cookies_text']:'',

            'refund_policy_status' => (int)(self::get_settings_data('refund_policy_status')),
            'cancellation_policy_status' => (int)(self::get_settings_data('cancellation_policy_status')),
            'shipping_policy_status' => (int)(self::get_settings_data('shipping_policy_status')),

            'refund_policy_data' => (string) (self::get_settings_data('refund_policy')),
            'cancellation_policy_data' => (string)(self::get_settings_data('cancellation_policy')),
            'shipping_policy_data' => (string)(self::get_settings_data('shipping_policy')),
            'terms_and_conditions' => (string) (self::get_settings_data('terms_and_conditions')),
            'privacy_policy' => (string) (self::get_settings_data('privacy_policy')),
            'about_us' => (string) (self::get_settings_data('about_us')),

            'take_away' => (bool)(isset($settings['take_away']) ? $settings['take_away'] : false),
            'repeat_order_option' => (bool)(isset($settings['repeat_order_option']) ? $settings['repeat_order_option'] : false),
            'home_delivery' => (bool)(isset($settings['home_delivery']) ? $settings['home_delivery'] : false),
            'active_payment_method_list' => $active_addon_payment_lists ?? [],
            'add_fund_status' => (int)(isset($settings['add_fund_status']) ? $settings['add_fund_status'] : 0),
            'partial_payment_status' => (int)(isset($settings['partial_payment_status']) ? $settings['partial_payment_status'] : 0),
            'partial_payment_method' => (isset($settings['partial_payment_method']) ? $settings['partial_payment_method'] : ''),
            'additional_charge_status' => (int)(isset($settings['additional_charge_status']) ? $settings['additional_charge_status'] : 0),
            'additional_charge_name' => (isset($settings['additional_charge_name']) ? $settings['additional_charge_name'] : 'Service Charge'),
            'additional_charge'=>(float)(isset($settings['additional_charge'])?$settings['additional_charge']:0),
            'dm_picture_upload_status' => (int)(isset($settings['dm_picture_upload_status']) ? $settings['dm_picture_upload_status'] : 0),
            'digital_payment_info' => $digital_payment_infos,
            'banner_data' => count($banner_data) > 0 ? $banner_data :null,
            'offline_payment_status' => (int)(isset($settings['offline_payment_status']) ? $settings['offline_payment_status'] : 0),
            'guest_checkout_status' => (int)(isset($settings['guest_checkout_status']) ? $settings['guest_checkout_status'] : 0),
            'country_picker_status' => (int)(isset($settings['country_picker_status']) ? $settings['country_picker_status'] : 0),

            'instant_order' => (bool)(isset($settings['instant_order']) ? $settings['instant_order'] : 0),
            'extra_packaging_charge' => (bool)(isset($settings['extra_packaging_charge']) ? $settings['extra_packaging_charge'] : 0),
            'customer_date_order_sratus' => (bool)(isset($settings['customer_date_order_sratus']) ? $settings['customer_date_order_sratus'] : 0),
            'customer_order_date' => (int)(isset($settings['customer_order_date']) ? $settings['customer_order_date'] : 0),
            'deliveryman_additional_join_us_page_data' => $deliveryman_additional_join_us_page_data,
            'restaurant_additional_join_us_page_data' => $restaurant_additional_join_us_page_data,
            'disbursement_type' => (string)(isset($settings['disbursement_type']) ? $settings['disbursement_type'] : 'manual'),
            'restaurant_disbursement_waiting_time' => (int)(isset($settings['restaurant_disbursement_waiting_time']) ? $settings['restaurant_disbursement_waiting_time'] : 0),
            'dm_disbursement_waiting_time' => (int)(isset($settings['dm_disbursement_waiting_time']) ? $settings['dm_disbursement_waiting_time'] : 0),
            'min_amount_to_pay_restaurant' => (float)(isset($settings['min_amount_to_pay_restaurant']) ? $settings['min_amount_to_pay_restaurant'] : 0),
            'min_amount_to_pay_dm' => (float)(isset($settings['min_amount_to_pay_dm']) ? $settings['min_amount_to_pay_dm'] : 0),
            'restaurant_review_reply' => (bool)(isset($settings['restaurant_review_reply']) ? $settings['restaurant_review_reply'] : false),
            'maintenance_mode_data' => count($maintenance_mode_data)>0?$maintenance_mode_data:null,
            'firebase_otp_verification' => (int)(isset($settings['firebase_otp_verification']) ? $settings['firebase_otp_verification'] : 0),
            'centralize_login' => [
                'manual_login_status' => (int)(isset($settings['manual_login_status']) ? $settings['manual_login_status'] : 0),
                'otp_login_status' => (int)(isset($settings['otp_login_status']) ? $settings['otp_login_status'] : 0),
                'social_login_status' => (int)(isset($settings['social_login_status']) ? $settings['social_login_status'] : 0),
                'google_login_status' => (int)(isset($settings['google_login_status']) ? $settings['google_login_status'] : 0),
                'facebook_login_status' => (int)(isset($settings['facebook_login_status']) ? $settings['facebook_login_status'] : 0),
                'apple_login_status' => (int)(isset($settings['apple_login_status']) ? $settings['apple_login_status'] : 0),
                'email_verification_status' => (int)(isset($settings['email_verification_status']) ? $settings['email_verification_status'] : 0),
                'phone_verification_status' => (int)(isset($settings['phone_verification_status']) ? $settings['phone_verification_status'] : 0),
            ],

            'subscription_business_model' => (int)( Helpers::subscription_check()),
            'commission_business_model' => (int)(Helpers::commission_check() ?? 1),
            'subscription_deadline_warning_days' => (int)(isset($settings['subscription_deadline_warning_days']) ? $settings['subscription_deadline_warning_days'] : 1),
            'subscription_deadline_warning_message' => isset($settings['subscription_deadline_warning_message']) ? $settings['subscription_deadline_warning_message'] : null,
            'subscription_free_trial_days' => (int)$trial_period,
            'subscription_free_trial_type' => (isset($settings['subscription_free_trial_type']) ? $settings['subscription_free_trial_type'] : 'day'),
            'subscription_free_trial_status' => (int)(isset($settings['subscription_free_trial_status']) ? $settings['subscription_free_trial_status'] : 0),
            'dine_in_order_option' => (int)(isset($settings['dine_in_order_option']) ? $settings['dine_in_order_option'] : 0),
        ]);
    }


    public static function get_settings_data($name)
    {
        $data = Cache::rememberForever('data_settings_' . $name, function () use ($name) {
            return DataSetting::where(['key' => $name])->first()?->value;
        });

        return $data ?? 0;
    }

    public function get_zone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $zones = Zone::whereContains('coordinates', new Point($request->lat, $request->lng, POINT_SRID))->latest()->get(['id', 'status', 'minimum_shipping_charge',
        'increased_delivery_fee','increased_delivery_fee_status','increase_delivery_charge_message','per_km_shipping_charge','max_cod_order_amount','maximum_shipping_charge']);
        if (count($zones) < 1) {
            return response()->json([
                'errors' => [
                    ['code' => 'coordinates', 'message' => translate('messages.service_not_available_in_this_area')]
                ]
            ], 404);
        }
        $data = array_filter($zones->toArray(), function ($zone) {
            if ($zone['status'] == 1) {
                return $zone;
            }
        });

        if (count($data) > 0) {
            return response()->json(['zone_id' => json_encode(array_column($data, 'id')), 'zone_data'=>array_values($data)], 200);
        }

        return response()->json([
            'errors' => [
                ['code' => 'coordinates', 'message' => translate('messages.we_are_temporarily_unavailable_in_this_area')]
            ]
        ], 403);
    }

    public function place_api_autocomplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_text' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }


        $apiKey = $this->map_api_key;
        $url = "https://places.googleapis.com/v1/places:autocomplete";

        $data = [
            "input" => $request['search_text'],
            "languageCode" => app()->getLocale(),
        ];

        $headers = [
            "Content-Type: application/json",
            "X-Goog-Api-Key: $apiKey",

        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response,true);

        // old
        // $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . $request['search_text'] . '&key=' . $this->map_api_key);
        // return $response->json();
    }


    public function distance_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $apiKey = $this->map_api_key;
        $url = "https://routes.googleapis.com/distanceMatrix/v2:computeRouteMatrix";

        $data = [
            "origins" => [
                ["waypoint" => ["location" => ["latLng" => ["latitude" => $request['origin_lat'], "longitude" => $request['origin_lng']]]]]
            ],
            "destinations" => [
                ["waypoint" => ["location" => ["latLng" => ["latitude" => $request['destination_lat'], "longitude" => $request['destination_lng']]]]],
            ],
            "travelMode" => "WALK",
           // "routingPreference" => "TRAFFIC_AWARE"
        ];

        $headers = [
            "Content-Type: application/json",
            "X-Goog-Api-Key: $apiKey",
            "X-Goog-FieldMask: duration,distanceMeters,localizedValues"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true)[0];

        // old
        // $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $request['origin_lat'] . ',' . $request['origin_lng'] . '&destinations=' . $request['destination_lat'] . ',' . $request['destination_lng'] . '&key=' . $this->map_api_key . '&mode=walking');
        // return $response->json();
    }


    public function place_api_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'placeid' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }


        $apiKey = $this->map_api_key;
        $url = 'https://places.googleapis.com/v1/places/'.$request['placeid'];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Goog-Api-Key: ' . $apiKey,
            'X-Goog-FieldMask: id,displayName,formattedAddress,location',
        ]);

        // Execute the request and get the response
        $response = curl_exec($ch);
        curl_close($ch);

      return json_decode($response,true);

        // old
        // $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $request['placeid'] . '&key=' . $this->map_api_key);
        // return $response->json();
    }

    public function geocode_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request->lat . ',' . $request->lng . '&key=' . $this->map_api_key);
        return $response->json();
    }

    public function landing_page(){
        $key =['react_header_banner','banner_section_full','banner_section_half' ,'footer_logo','app_section_image',
        'react_feature' ,'discount_banner','landing_page_links','react_self_registration_restaurant','react_self_registration_delivery_man'];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        $image_key = ['react_header_banner','footer_logo'];
        $data = [];

        foreach ($image_key as $value){
            $data[$value.'_storage'] = BusinessSetting::where('key',$value)->first()?->storage[0]?->value ??'public';
        }


        $app_section_image = isset($settings['app_section_image']) ? json_decode($settings['app_section_image'], true) : [];

        $banner_section_full= (isset($settings['banner_section_full']) )  ? json_decode($settings['banner_section_full'], true) : null ;
        if($banner_section_full){
            $banner_section_full['banner_section_img_full_url']= Helpers::get_full_url('react_landing',$banner_section_full['banner_section_img_full']?? null,$banner_section_full['storage']??'public');
        }

        $banner_section_half_data = [];
        $banner_section_half=(isset($settings['banner_section_half']) )  ? json_decode($settings['banner_section_half'], true) : [];
        foreach ($banner_section_half as $value){
            $value['img_full_url'] = Helpers::get_full_url('react_landing',$value['img']?? null,$value['storage']??'public');
            array_push($banner_section_half_data, $value);
        }
        $banner_section_half = $banner_section_half_data;

        $react_feature_data = [];
        $react_feature= (isset($settings['react_feature'])) ? json_decode($settings['react_feature'], true) : [];
        foreach ($react_feature as $value){
            $value['img_full_url'] = Helpers::get_full_url('react_landing',$value['img']?? null,$value['storage']??'public');
            array_push($react_feature_data, $value);
        }
        $react_feature = $react_feature_data;

        $discount_banner= (isset($settings['discount_banner'])) ? json_decode($settings['discount_banner'], true) : null;
        if($discount_banner){
            $discount_banner['img_full_url']= Helpers::get_full_url('react_landing',$discount_banner['img']?? null,$discount_banner['storage']??'public');
        }

        $react_self_registration_restaurant= (isset($settings['react_self_registration_restaurant'])) ? json_decode($settings['react_self_registration_restaurant'], true) : null;
        if($react_self_registration_restaurant){
            $react_self_registration_restaurant['image_full_url']= Helpers::get_full_url('react_landing',$react_self_registration_restaurant['image']?? null,$react_self_registration_restaurant['storage']??'public');
        }

        $react_self_registration_delivery_man= (isset($settings['react_self_registration_delivery_man'])) ? json_decode($settings['react_self_registration_delivery_man'], true) : null;
        if($react_self_registration_delivery_man){
            $react_self_registration_delivery_man['image_full_url']= Helpers::get_full_url('react_landing',$react_self_registration_delivery_man['image']?? null,$react_self_registration_delivery_man['storage']??'public');
        }


        return  response()->json(
            [
                'react_header_banner'=>(isset($settings['react_header_banner']) )  ? $settings['react_header_banner'] : null ,
                'react_header_banner_full_url'=>Helpers::get_full_url('react_landing',$settings['react_header_banner']??null,$data['react_header_banner_storage']??'public'),
                'app_section_image'=> (isset($app_section_image['app_section_image'])) ?  $app_section_image['app_section_image'] : null,
                'app_section_image_full_url'=> Helpers::get_full_url('react_landing',$app_section_image['app_section_image']?? null,$app_section_image['app_section_image_storage']??'public'),
                'app_section_image_2'=> (isset($app_section_image['app_section_image_2'])) ?  $app_section_image['app_section_image_2'] : null,
                'app_section_image_2_full_url'=> Helpers::get_full_url('react_landing',$app_section_image['app_section_image_2']?? null,$app_section_image['app_section_image_2_storage']??'public'),
                'footer_logo'=> (isset($settings['footer_logo'])) ? $settings['footer_logo'] : null,
                'footer_logo_full_url'=> (isset($settings['footer_logo'])) ? Helpers::get_full_url('react_landing',$settings['footer_logo'],$data['footer_logo_storage']??'public') : null,
                'banner_section_full'=> $banner_section_full ,
                'banner_section_half'=>$banner_section_half,
                'react_feature'=> $react_feature,
                'discount_banner'=> $discount_banner,
                'landing_page_links'=> (isset($settings['landing_page_links'])) ? json_decode($settings['landing_page_links'], true) : null,
                'react_self_registration_restaurant'=> $react_self_registration_restaurant,
                'react_self_registration_delivery_man'=> $react_self_registration_delivery_man,
        ]);
    }


    public function extra_charge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'distance' => 'required',
        ]);
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $distance_data = $request->distance ?? 0;
        $data = Helpers::vehicle_extra_charge(distance_data:$distance_data);
        $extra_charges = (float) (isset($data) ? $data['extra_charge']  : 0);
        $vehicle_id= (isset($data) ? $data['vehicle_id']  : null);

        return response()->json($extra_charges,200);
    }

    public function get_vehicles(Request $request){
        $data = Vehicle::active()->get(['id','type']);
        return response()->json($data, 200);
    }

    public function react_landing_page()
    {
        // $settings =  DataSetting::where('type','react_landing_page')->pluck('value','key')->toArray();
        $datas =  DataSetting::with('translations')->where('type','react_landing_page')->get();
        $data = [];
        foreach ($datas as $key => $value) {
            if(count($value->translations)>0){
                $cred = [
                    $value->key => $value->translations[0]['value'],
                ];
                array_push($data,$cred);
            }else{
                $cred = [
                    $value->key => $value->value,
                ];
                array_push($data,$cred);
            }
            if (isset($value->storage)) {

                $cred = [
                    $value->key.'_storage' => $value?->storage[0]?->value ?? 'public',
                ];
                array_push($data, $cred);
            }
        }
        $settings = [];
        foreach($data as $single_data){
            foreach($single_data as $key=>$single_value){
                $settings[$key] = $single_value;
            }
        }

        $services=  ReactService::orderBy('id' , 'asc')->where('status',1)->get();
        $ReactPromotionalBanner=  ReactPromotionalBanner::orderBy('id' , 'asc')->where('status',1)->get();
        $zones= Zone::where('status',1)->get(['id','name','display_name']);

        $restaurant_section= [
            'react_restaurant_section_title'=>(isset($settings['react_restaurant_section_title']) )  ? $settings['react_restaurant_section_title'] : null ,
            'react_restaurant_section_sub_title'=>(isset($settings['react_restaurant_section_sub_title']) )  ? $settings['react_restaurant_section_sub_title'] : null ,
            'react_restaurant_section_button_name'=>(isset($settings['react_restaurant_section_button_name']) )  ? $settings['react_restaurant_section_button_name'] : null ,
            // 'react_restaurant_section_link_data'=> (isset($settings['react_restaurant_section_link_data']) )  ? json_decode($settings['react_restaurant_section_link_data'] , true) : [] ,
            'react_restaurant_section_link_data'=> [
                'react_restaurant_section_button_status'=>(int) (isset($settings['react_restaurant_section_button_status']) )  ? $settings['react_restaurant_section_button_status'] : 0 ,
                'react_restaurant_section_link' => isset($settings['react_restaurant_section_link_data'])   ? $settings['react_restaurant_section_link_data'] : null,
            ],
            'react_restaurant_section_image'=> (isset($settings['react_restaurant_section_image']) )  ? $settings['react_restaurant_section_image']  : null ,
            'react_restaurant_section_image_full_url'=>Helpers::get_full_url('react_restaurant_section_image',(isset($settings['react_restaurant_section_image']) )  ? $settings['react_restaurant_section_image'] : null,isset($settings['react_restaurant_section_image_storage'])   ? $settings['react_restaurant_section_image_storage'] : 'public') ,
        ];
        $delivery_section= [
            'react_delivery_section_title'=>(isset($settings['react_delivery_section_title']) )  ? $settings['react_delivery_section_title'] : null ,
            'react_delivery_section_sub_title'=>(isset($settings['react_delivery_section_sub_title']) )  ? $settings['react_delivery_section_sub_title'] : null ,
            'react_delivery_section_button_name'=>(isset($settings['react_delivery_section_button_name']) )  ? $settings['react_delivery_section_button_name'] : null ,
            // 'react_delivery_section_link_data'=> (isset($settings['react_delivery_section_link_data']) )  ? json_decode($settings['react_delivery_section_link_data'] , true) : [] ,
            'react_delivery_section_link_data'=> [
                'react_delivery_section_button_status'=>(int) (isset($settings['react_delivery_section_button_status']) )  ? $settings['react_delivery_section_button_status'] : 0 ,
                'react_delivery_section_link' =>  (isset($settings['react_delivery_section_link_data']) )  ? $settings['react_delivery_section_link_data'] : null,
                ],
            'react_delivery_section_image'=> (isset($settings['react_delivery_section_image']) )  ? $settings['react_delivery_section_image'] : null ,
            'react_delivery_section_image_full_url'=>Helpers::get_full_url('react_delivery_section_image',(isset($settings['react_delivery_section_image']) )  ? $settings['react_delivery_section_image'] : null,isset($settings['react_delivery_section_image_storage'])   ? $settings['react_delivery_section_image_storage'] : 'public') ,
            ];
        $download_app_section= [
            'react_download_apps_banner_image'=>(isset($settings['react_download_apps_banner_image']) )  ? $settings['react_download_apps_banner_image'] : null ,
            'react_download_apps_banner_image_full_url'=>Helpers::get_full_url('react_download_apps_image',(isset($settings['react_download_apps_banner_image']) )  ? $settings['react_download_apps_banner_image'] : null,isset($settings['react_download_apps_banner_image_storage'])   ? $settings['react_download_apps_banner_image_storage'] : 'public') ,
            'react_download_apps_image'=>(isset($settings['react_download_apps_image']) )  ? $settings['react_download_apps_image'] : null ,
            'react_download_apps_image_full_url'=>Helpers::get_full_url('react_download_apps_image',(isset($settings['react_download_apps_image']) )  ? $settings['react_download_apps_image'] : null,isset($settings['react_download_apps_image_storage'])   ? $settings['react_download_apps_image_storage'] : 'public') ,
            'react_download_apps_title'=>(isset($settings['react_download_apps_title']) )  ? $settings['react_download_apps_title'] : null ,
            'react_download_apps_tag'=>(isset($settings['react_download_apps_tag']) )  ? $settings['react_download_apps_tag'] : null ,
            'react_download_apps_sub_title'=>(isset($settings['react_download_apps_sub_title']) )  ? $settings['react_download_apps_sub_title'] : null ,
            'react_download_apps_app_store'=> (isset($settings['react_download_apps_link_data']) )  ? json_decode($settings['react_download_apps_link_data'] , true) : [] ,
                'react_download_apps_play_store' =>[
                    'react_download_apps_play_store_link'=>(isset($settings['react_download_apps_button_name']) )  ? $settings['react_download_apps_button_name'] : null ,
                    'react_download_apps_play_store_status'=>(int) (isset($settings['react_download_apps_button_status']) )  ? $settings['react_download_apps_button_status'] : 0 ,
                ],

        ];



        return  response()->json(
            [
                'base_urls' => [
                    'react_header_image_url' => dynamicStorage('storage/app/public/react_header'),
                    'react_services_image_url' => dynamicStorage('storage/app/public/react_service_image'),
                    'react_promotional_banner_image_url' => dynamicStorage('storage/app/public/react_promotional_banner'),
                    'react_delivery_section_image_url' => dynamicStorage('storage/app/public/react_delivery_section_image'),
                    'react_restaurant_section_image_url' => dynamicStorage('storage/app/public/react_restaurant_section_image'),
                    'react_download_apps_banner_image_url' => dynamicStorage('storage/app/public/react_download_apps_image'),
                    'react_download_apps_image_url' => dynamicStorage('storage/app/public/react_download_apps_image'),
                ],

                'react_header_title'=>(isset($settings['react_header_title']) )  ? $settings['react_header_title'] : null ,
                'react_header_sub_title'=>(isset($settings['react_header_sub_title']) )  ? $settings['react_header_sub_title'] : null ,
                'react_header_image'=>(isset($settings['react_header_image']) )  ? $settings['react_header_image'] : null ,
                'react_header_image_full_url'=>Helpers::get_full_url('react_header',(isset($settings['react_header_image']) )  ? $settings['react_header_image'] : null,isset($settings['react_header_image_storage'])   ? $settings['react_header_image_storage'] : 'public') ,

                'react_services' => $services ?? [],
                'react_promotional_banner' => $ReactPromotionalBanner ?? [],

                'restaurant_section' => $restaurant_section,
                'delivery_section' => $delivery_section,
                'download_app_section' => $download_app_section,

                'news_letter_sub_title'=>(isset($settings['news_letter_sub_title']) )  ? $settings['news_letter_sub_title'] : null ,
                'news_letter_title'=>(isset($settings['news_letter_title']) )  ? $settings['news_letter_title'] : null ,
                'footer_data'=>(isset($settings['footer_data']) )  ? $settings['footer_data'] : null ,

                'available_zone_status' => (int)((isset($settings['available_zone_status'])) ? $settings['available_zone_status'] : 0),
                'available_zone_title' => (isset($settings['available_zone_title'])) ? $settings['available_zone_title'] : null,
                'available_zone_short_description' => (isset($settings['available_zone_short_description'])) ? $settings['available_zone_short_description'] : null,
                'available_zone_image' => (isset($settings['available_zone_image'])) ? $settings['available_zone_image'] : null,
                'available_zone_image_full_url' => Helpers::get_full_url('available_zone_image', (isset($settings['available_zone_image'])) ? $settings['available_zone_image'] : null, (isset($settings['available_zone_image_storage'])) ? $settings['available_zone_image_storage'] : 'public'),
                'available_zone_list' => $zones,
        ]);
    }

    public function react_registration_page()
    {
        $settings = DataSetting::with('translations')
            ->where('type', 'react_registration_page')
            ->get();

        $data = [];

        foreach ($settings as $item) {
            $value = count($item->translations) > 0
                ? $item->translations[0]['value']
                : $item->value;

            $data[$item->key] = $value;

            if (isset($item->storage)) {
                $data[$item->key . '_storage'] = $item?->storage[0]?->value ?? 'public';
            }
        }

        $hero_image_content = json_decode($data['hero_image_content'] ?? null, true);
        $hero_image_content_full_url = Helpers::get_full_url(
            'hero_image',
            $hero_image_content['hero_image_content'] ?? 'double_screen_image.png',
            $hero_image_content['hero_image_content_storage'] ?? 'public'
        );

        $stepper = [
            'step1' => [
                'title' => $data['step1_title'] ?? null,
                'sub_title' => $data['step1_sub_title'] ?? null,
                'image' => $data['step1_image'] ?? null,
                'image_full_url' => Helpers::get_full_url(
                    'step_image',
                    $data['step1_image'] ?? null,
                    $data['step1_image_storage'] ?? 'public'
                ),
            ],
            'step2' => [
                'title' => $data['step2_title'] ?? null,
                'sub_title' => $data['step2_sub_title'] ?? null,
                'image' => $data['step2_image'] ?? null,
                'image_full_url' => Helpers::get_full_url(
                    'step_image',
                    $data['step2_image'] ?? null,
                    $data['step2_image_storage'] ?? 'public'
                ),
            ],
            'step3' => [
                'title' => $data['step3_title'] ?? null,
                'sub_title' => $data['step3_sub_title'] ?? null,
                'image' => $data['step3_image'] ?? null,
                'image_full_url' => Helpers::get_full_url(
                    'step_image',
                    $data['step3_image'] ?? null,
                    $data['step3_image_storage'] ?? 'public'
                ),
            ],
        ];

        $opportunities = ReactOpportunity::where('status',1)->latest()->get();
        $faqs = ReactFaq::where('status',1)->orderBy('priority')->get();

        return response()->json([
            'hero_image_content_full_url' => $hero_image_content_full_url,
            'stepper' => $stepper,
            'opportunities' => $opportunities,
            'faqs' => $faqs
        ]);
    }


    private function getPaymentMethods()
    {
        // Check if the addon_settings table exists
        if (!Schema::hasTable('addon_settings')) {
            return [];
        }

        $methods = DB::table('addon_settings')->where('is_active',1)->where('settings_type', 'payment_config')->get();
        $env = env('APP_ENV') == 'live' ? 'live' : 'test';
        $credentials = $env . '_values';

        $data = [];
        foreach ($methods as $method) {
            $credentialsData = json_decode($method->$credentials);
            $additional_data = json_decode($method->additional_data);
            if ($credentialsData->status == 1) {
                $data[] = [
                    'gateway' => $method->key_name,
                    'gateway_title' => $additional_data?->gateway_title,
                    'gateway_image' => $additional_data?->gateway_image,
                    'gateway_image_full_url' => Helpers::get_full_url('payment_modules/gateway_image',$additional_data?->gateway_image,$additional_data?->storage ?? 'public')
                ];
            }
        }
        return $data;
    }


    private function getDefaultPaymentMethods()
    {
        // Check if the addon_settings table exists
        if (!Schema::hasTable('addon_settings')) {
            return [];
        }

        $methods = DB::table('addon_settings')->where('is_active',1)->whereIn('settings_type', ['payment_config'])->whereIn('key_name', ['ssl_commerz','paypal','stripe','razor_pay','senang_pay','paytabs','paystack','paymob_accept','paytm','flutterwave','liqpay','bkash','mercadopago'])->get();
        $env = env('APP_ENV') == 'live' ? 'live' : 'test';
        $credentials = $env . '_values';

        $data = [];
        foreach ($methods as $method) {
            $credentialsData = json_decode($method->$credentials);
            $additional_data = json_decode($method->additional_data);
            if ($credentialsData->status == 1) {
                $data[] = [
                    'gateway' => $method->key_name,
                    'gateway_title' => $additional_data?->gateway_title,
                    'gateway_image' => $additional_data?->gateway_image,
                    'gateway_image_full_url' => Helpers::get_full_url('payment_modules/gateway_image',$additional_data?->gateway_image,$additional_data?->storage ?? 'public')
                ];
            }
        }
        return $data;
    }
    public function offline_payment_method_list()
    {
        $data = OfflinePaymentMethod::where('status', 1)->get();
        $data = $data->count() > 0 ? $data: null;
        return response()->json($data, 200);
    }
}
