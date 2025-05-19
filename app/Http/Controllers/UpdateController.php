<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 180);

use App\Models\Coupon;
use App\Models\Setting;
use App\Models\Restaurant;
use App\Models\DataSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Traits\ActivationClass;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\ProductLogic;
use App\Models\NotificationSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends Controller
{
    use ActivationClass;

    public function update_software_index(){
        return view('update.update-software');
    }

    public function update_software(Request $request)
    {
        Helpers::setEnvironmentValue('SOFTWARE_ID','MzM1NzE3NTA=');
        Helpers::setEnvironmentValue('BUYER_USERNAME',$request['username']);
        Helpers::setEnvironmentValue('PURCHASE_CODE',$request['purchase_key']);
        Helpers::setEnvironmentValue('APP_MODE','live');
        Helpers::setEnvironmentValue('SOFTWARE_VERSION','8.2');
        Helpers::setEnvironmentValue('APP_NAME','stackfood'.time());
        Helpers::setEnvironmentValue('REACT_APP_KEY','43218516');

        if (!$this->actch()) {
            return redirect(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'));
        }

        // version_7.9.1
        Artisan::call('cache:table');
        Helpers::setEnvironmentValue('CACHE_DRIVER', 'database');

        Artisan::call('migrate', ['--force' => true]);
        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        Helpers::insert_business_settings_key('free_delivery_over');
        Helpers::insert_business_settings_key('app_minimum_version_ios');
        Helpers::insert_business_settings_key('app_minimum_version_android');
        Helpers::insert_business_settings_key('app_url_ios');
        Helpers::insert_business_settings_key('app_url_android');
        Helpers::insert_business_settings_key('dm_maximum_orders',1);
        Helpers::insert_business_settings_key('order_confirmation_model','deliveryman');
        Helpers::insert_business_settings_key('popular_food',1);
        Helpers::insert_business_settings_key('popular_restaurant',1);
        Helpers::insert_business_settings_key('new_restaurant',1);
        Helpers::insert_business_settings_key('most_reviewed_foods',1);
        Helpers::insert_business_settings_key('flutterwave',
        json_encode([
            'status'        => 1,
            'public_key'     => '',
            'secret_key'     => '',
            'hash'    => '',
        ]));

        Helpers::insert_business_settings_key('mercadopago',
        json_encode([
            'status'        => 1,
            'public_key'     => '',
            'access_token'     => '',
        ]));

        Helpers::insert_business_settings_key('landing_page_text','{"header_title_1":"Food App","header_title_2":"Why stay hungry when you can order from StackFood","header_title_3":"Get 10% OFF on your first order","about_title":"StackFood is Best Delivery Service Near You","why_choose_us":"Why Choose Us?","why_choose_us_title":"Lorem ipsum dolor sit amet, consectetur adipiscing elit.","testimonial_title":"Trusted by Customer & Restaurant Owner","footer_article":"Suspendisse ultrices at diam lectus nullam. Nisl, sagittis viverra enim erat tortor ultricies massa turpis. Arcu pulvinar."}');
        Helpers::insert_business_settings_key('landing_page_links','{"app_url_android_status":"1","app_url_android":"https:\/\/play.google.com","app_url_ios_status":"1","app_url_ios":"https:\/\/www.apple.com\/app-store","web_app_url_status":null,"web_app_url":"front_end"}');
        Helpers::insert_business_settings_key('speciality','[{"img":"clean_&_cheap_icon.png","title":"Clean & Cheap Price"},{"img":"best_dishes_icon.png","title":"Best Dishes Near You"},{"img":"virtual_restaurant_icon.png","title":"Your Own Virtual Restaurant"}]');
        Helpers::insert_business_settings_key('testimonial','[{"img":"img-1.png","name":"Barry Allen","position":"Web Designer","detail":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. A\r\n                    aliquam amet animi blanditiis consequatur debitis dicta\r\n                    distinctio, enim error eum iste libero modi nam natus\r\n                    perferendis possimus quasi sint sit tempora voluptatem. Est,\r\n                    exercitationem id ipsa ipsum laboriosam perferendis temporibus!"},{"img":"img-2.png","name":"Sophia Martino","position":"Web Designer","detail":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquam amet animi blanditiis consequatur debitis dicta distinctio, enim error eum iste libero modi nam natus perferendis possimus quasi sint sit tempora voluptatem. Est, exercitationem id ipsa ipsum laboriosam perferendis temporibus!"},{"img":"img-3.png","name":"Alan Turing","position":"Web Designer","detail":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquam amet animi blanditiis consequatur debitis dicta distinctio, enim error eum iste libero modi nam natus perferendis possimus quasi sint sit tempora voluptatem. Est, exercitationem id ipsa ipsum laboriosam perferendis temporibus!"},{"img":"img-4.png","name":"Ann Marie","position":"Web Designer","detail":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquam amet animi blanditiis consequatur debitis dicta distinctio, enim error eum iste libero modi nam natus perferendis possimus quasi sint sit tempora voluptatem. Est, exercitationem id ipsa ipsum laboriosam perferendis temporibus!"}]');
        Helpers::insert_business_settings_key('landing_page_images','{"top_content_image":"double_screen_image.png","about_us_image":"about_us_image.png"}');
        Helpers::insert_business_settings_key('paymob_accept','{"status":"0","api_key":null,"iframe_id":null,"integration_id":null,"hmac":null}');

        //Version 5.0
        Helpers::insert_business_settings_key('show_dm_earning',0);
        Helpers::insert_business_settings_key('canceled_by_deliveryman',0);
        Helpers::insert_business_settings_key('canceled_by_restaurant',0);
        Helpers::insert_business_settings_key('timeformat','24');
        // Helpers::insert_business_settings_key('language','en');

        Helpers::insert_business_settings_key('toggle_veg_non_veg', 0);
        Helpers::insert_business_settings_key('toggle_dm_registration', 0);
        Helpers::insert_business_settings_key('toggle_restaurant_registration', 0);
        Helpers::insert_business_settings_key('recaptcha', '{"status":"0","site_key":null,"secret_key":null}');
        Helpers::insert_business_settings_key('schedule_order_slot_duration', 30);
        Helpers::insert_business_settings_key('digit_after_decimal_point', 2);
        Helpers::insert_business_settings_key('language', '["en"]');
        Helpers::insert_business_settings_key('icon', 'icon.png');

        Helpers::insert_business_settings_key('wallet_status', '0');
        Helpers::insert_business_settings_key('loyalty_point_minimum_point', '0');
        Helpers::insert_business_settings_key('loyalty_point_status', '0');
        Helpers::insert_business_settings_key('loyalty_point_item_purchase_point', '0');
        Helpers::insert_business_settings_key('loyalty_point_exchange_rate', '0');
        Helpers::insert_business_settings_key('wallet_add_refund', '0');
        Helpers::insert_business_settings_key('order_refunded_message', 'Order refunded successfully');
        Helpers::insert_business_settings_key('ref_earning_status', '0');
        Helpers::insert_business_settings_key('ref_earning_exchange_rate', '0');
        Helpers::insert_business_settings_key('dm_tips_status', '0');
        Helpers::insert_business_settings_key('theme', '1');
        Helpers::insert_business_settings_key('delivery_charge_comission', '0');
        Helpers::insert_business_settings_key('dm_max_cash_in_hand', '10000');
        if(is_numeric(env('SOFTWARE_VERSION')) && env('SOFTWARE_VERSION') <= '5.1'){
            ProductLogic::update_food_ratings();
        }

        Helpers::insert_business_settings_key('social_login','[{"login_medium":"google","client_id":"","client_secret":"","status":"0"},{"login_medium":"facebook","client_id":"","client_secret":"","status":""}]');
        //version 5.8
        Helpers::insert_business_settings_key('fcm_credentials',
            json_encode([
                'apiKey'=> '',
                'authDomain'=> '',
                'projectId'=> '',
                'storageBucket'=> '',
                'messagingSenderId'=> '',
                'appId'=> '',
                'measurementId'=> ''
            ])
        );
        //version 5.9
        Helpers::insert_business_settings_key('refund_active_status', '1');

        Helpers::insert_business_settings_key('business_model',
        json_encode([
            'commission'        =>  1,
            'subscription'     =>  0,
        ]));
        //version 6.1

        Helpers::insert_business_settings_key('tax_included', '0');
        Helpers::insert_business_settings_key('site_direction', 'ltr');
        // //version 6.2
        // Helpers::insert_business_settings_key('otp_interval_time', '30');
        // Helpers::insert_business_settings_key('max_otp_hit', '5');





        Helpers::insert_business_settings_key('take_away', '1');
        Helpers::insert_business_settings_key('repeat_order_option', '1');
        Helpers::insert_business_settings_key('home_delivery', '1');




            // 7.1
        $landing = BusinessSetting::where('key', 'landing_page')->exists();
        if(!$landing){
            Helpers::insert_business_settings_key('landing_page','1');
            Helpers::insert_business_settings_key('landing_integration_type','none');
        }

        Helpers::insert_business_settings_key('instant_order', '1');
        Helpers::insert_business_settings_key('manual_login_status', '1');
        Helpers::insert_business_settings_key('check_daily_stock_on', date('Y-m-d'));

        try {
           // 7.0

            if(DataSetting::count() == 0){
                $data_settings = file_get_contents('database/partial/data_settings.sql');
                DB::statement($data_settings);
            }
            Helpers::insert_data_settings_key('admin_login_url', 'login_admin' ,'admin');
            Helpers::insert_data_settings_key('admin_employee_login_url', 'login_admin_employee' ,'admin_employee');
            Helpers::insert_data_settings_key('restaurant_login_url', 'login_restaurant' ,'restaurant');
            Helpers::insert_data_settings_key('restaurant_employee_login_url', 'login_restaurant_employee' ,'restaurant_employee');

            if( EmailTemplate::count() == 0){
                $email_tempaltes = file_get_contents('database/partial/email_tempaltes.sql');
                DB::statement($email_tempaltes);
            }

            if (!Schema::hasTable('addon_settings')) {
                $sql = file_get_contents('database/partial/addon_settings.sql');
                DB::unprepared($sql);
                $this->set_data();
                $this->set_sms_data();
                }


                if (!Schema::hasTable('payment_requests')) {
                    $sql = file_get_contents('database/partial/payment_requests.sql');
                DB::unprepared($sql);
                }
                $storesToUpdate = Restaurant::whereNull('slug')->get(['id','name','slug']);
                foreach ($storesToUpdate as $store) {
                    $slug = Str::slug($store->name);
                    $store->slug = $store->slug? $store->slug :"{$slug}{$store->id}";
                    $store->save();
                }


                if (Schema::hasTable('addon_settings')) {
                    $data_values = Setting::whereIn('settings_type', ['payment_config'])
                        ->where('key_name', 'paystack')
                        ->first();


                    if ($data_values) {
                        $additional_data = $data_values->live_values;

                        if (array_key_exists("callback_url",$additional_data)) {
                            unset($additional_data['callback_url']);
                            $data_values->live_values = $additional_data;
                            $data_values->test_values = $additional_data;
                            $data_values->save();
                        }
                    }
                }

                Coupon::where('coupon_type', 'restaurant_wise')
                ->whereNull('restaurant_id')
                ->update([
                    'restaurant_id' => DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$[0]'))")
                ]);

            } catch (\Exception $exception) {
            Toastr::error('Database import failed! try again');
            return back();
            }

            if(NotificationSetting::count() == 0 ){
                Helpers::notificationDataSetup();
            }
            Helpers::insert_business_settings_key('country_picker_status', '1');
            Helpers::CheckOldSubscriptionSettings();
            Helpers::addNewAdminNotificationSetupDataSetup();

        $data = DataSetting::where('type', 'login_admin')->pluck('value')->first();
        return redirect('/login/'.$data);
    }



    private function set_data(){
        try{
            $gateway= ['ssl_commerz_payment',
            'razor_pay',
            'paypal',
            'stripe',
            'senang_pay',
            'paystack',
            'flutterwave',
            'mercadopago',
            'paymob_accept',
            'liqpay',
            'paytm',
            'bkash',
            'paytabs' ];

            $data= BusinessSetting::whereIn('key',$gateway)->pluck('value','key')->toArray();


            foreach($data as $key => $value){

            $gateway=$key;
            if($key == 'ssl_commerz_payment' ){
                $gateway='ssl_commerz';
            }

            $decoded_value= json_decode($value , true);
            $data= ['gateway' => $gateway ,
                'mode' =>  isset($decoded_value['status']) == 1  ?  'live': 'test'
                ];

                if ($gateway == 'ssl_commerz') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'store_id' => $decoded_value['store_id'],
                        'store_password' => $decoded_value['store_password'],
                    ];
                } elseif ($gateway == 'paypal') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'client_id' => $decoded_value['paypal_client_id'],
                        'client_secret' => $decoded_value['paypal_secret'],
                    ];
                } elseif ($gateway == 'stripe') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'api_key' => $decoded_value['api_key'],
                        'published_key' => $decoded_value['published_key'],
                    ];
                } elseif ($gateway == 'razor_pay') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'api_key' => $decoded_value['razor_key'],
                        'api_secret' => $decoded_value['razor_secret'],
                    ];
                } elseif ($gateway == 'senang_pay') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'callback_url' => null,
                        'secret_key' => $decoded_value['secret_key'],
                        'merchant_id' => $decoded_value['merchant_id'],
                    ];
                } elseif ($gateway == 'paytabs') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'profile_id' => $decoded_value['profile_id'],
                        'server_key' => $decoded_value['server_key'],
                        'base_url' => $decoded_value['base_url'],
                    ];
                } elseif ($gateway == 'paystack') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'public_key' => $decoded_value['publicKey'],
                        'secret_key' => $decoded_value['secretKey'],
                        'merchant_email' => $decoded_value['merchantEmail'],
                    ];
                } elseif ($gateway == 'paymob_accept') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'callback_url' => null,
                        'api_key' => $decoded_value['api_key'],
                        'iframe_id' => $decoded_value['iframe_id'],
                        'integration_id' => $decoded_value['integration_id'],
                        'hmac' => $decoded_value['hmac'],
                    ];
                } elseif ($gateway == 'mercadopago') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'access_token' => $decoded_value['access_token'],
                        'public_key' => $decoded_value['public_key'],
                    ];
                } elseif ($gateway == 'liqpay') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'private_key' => $decoded_value['public_key'],
                        'public_key' => $decoded_value['private_key'],
                    ];
                } elseif ($gateway == 'flutterwave') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'secret_key' => $decoded_value['secret_key'],
                        'public_key' => $decoded_value['public_key'],
                        'hash' => $decoded_value['hash'],
                    ];
                } elseif ($gateway == 'paytm') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'merchant_key' => $decoded_value['paytm_merchant_key'],
                        'merchant_id' => $decoded_value['paytm_merchant_mid'],
                        'merchant_website_link' => $decoded_value['paytm_merchant_website'],
                    ];
                } elseif ($gateway == 'bkash') {
                    $additional_data = [
                        'status' => $decoded_value['status'],
                        'app_key' => $decoded_value['api_key'],
                        'app_secret' => $decoded_value['api_secret'],
                        'username' => $decoded_value['username'],
                        'password' => $decoded_value['password'],
                    ];
                }

            $credentials= json_encode(array_merge($data, $additional_data));

            $payment_additional_data=['gateway_title' => ucfirst(str_replace('_',' ',$gateway)),
                                    'gateway_image' => null,'storage' => 'public'];

            DB::table('addon_settings')->updateOrInsert(['key_name' => $gateway, 'settings_type' => 'payment_config'], [
            'key_name' => $gateway,
            'live_values' => $credentials,
            'test_values' => $credentials,
            'settings_type' => 'payment_config',
            'mode' => isset($decoded_value['status']) == 1  ?  'live': 'test',
            'is_active' => isset($decoded_value['status']) == 1  ?  1: 0 ,
            'additional_data' => json_encode($payment_additional_data),
            ]);
            }
        } catch (\Exception $exception) {
            Toastr::error('Database import failed! try again');
            return true;
            }
        return true;
    }


    private function set_sms_data(){
        try{
            $sms_gateway= ['twilio_sms',
            'nexmo_sms',
            'msg91_sms',
            '2factor_sms'];

            $data= BusinessSetting::whereIn('key',$sms_gateway)->pluck('value','key')->toArray();
            foreach($data as $key => $value){
                    $decoded_value= json_decode($value , true);

                    if ($key == 'twilio_sms') {
                        $sms_gateway='twilio';
                        $additional_data = [
                            'status' => data_get($decoded_value,'status',null),
                            'sid' => data_get($decoded_value,'sid',null),
                            'messaging_service_sid' =>  data_get($decoded_value,'messaging_service_id',null),
                            'token' => data_get($decoded_value,'token',null),
                            'from' =>data_get($decoded_value,'from',null),
                            'otp_template' => data_get($decoded_value,'otp_template',null),
                        ];
                    } elseif ($key == 'nexmo_sms') {
                        $sms_gateway='nexmo';
                        $additional_data = [
                            'status' => data_get($decoded_value,'status',null),
                            'api_key' => data_get($decoded_value,'api_key',null),
                            'api_secret' =>  data_get($decoded_value,'api_secret',null),
                            'token' => data_get($decoded_value,'token',null),
                            'from' =>  data_get($decoded_value,'from',null),
                            'otp_template' =>  data_get($decoded_value,'otp_template',null),
                        ];
                    } elseif ($key == '2factor_sms') {
                        $sms_gateway='2factor';
                        $additional_data = [
                            'status' => data_get($decoded_value,'status',null),
                            'api_key' => data_get($decoded_value,'api_key',null),
                        ];
                    } elseif ($key == 'msg91_sms') {
                        $sms_gateway='msg91';
                        $additional_data = [
                            'status' => data_get($decoded_value,'status',null),
                            'template_id' =>  data_get($decoded_value,'template_id',null),
                            'auth_key' =>  data_get($decoded_value,'authkey',null),
                        ];
                    }
                    $data= ['gateway' => $sms_gateway ,
                    'mode' =>  isset($decoded_value['status']) == 1  ?  'live': 'test'
                ];
                    $credentials= json_encode(array_merge($data, $additional_data));

                    DB::table('addon_settings')->updateOrInsert(['key_name' => $sms_gateway, 'settings_type' => 'sms_config'], [
                        'key_name' => $sms_gateway,
                        'live_values' => $credentials,
                        'test_values' => $credentials,
                        'settings_type' => 'sms_config',
                        'mode' => isset($decoded_value['status']) == 1  ?  'live': 'test',
                        'is_active' => isset($decoded_value['status']) == 1  ?  1: 0 ,
                    ]);
                }
            } catch (\Exception $exception) {
                Toastr::error('Database import failed! try again');
                return true;
                }
            return true;
    }

}


