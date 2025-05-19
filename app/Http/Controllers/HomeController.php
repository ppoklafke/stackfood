<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Mail\ContactMail;
use App\Models\DataSetting;
use App\Models\AdminFeature;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\ContactMessage;
use Illuminate\Support\Carbon;
use App\Models\BusinessSetting;
use App\Models\AdminTestimonial;
use Gregwar\Captcha\CaptchaBuilder;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $datas =  DataSetting::with('translations')->where('type','admin_landing_page')->get();
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
            if(count($value->storage)>0){
                $cred = [
                    $value->key.'_storage' => $value->storage[0]['value'],
                ];
                array_push($data,$cred);
            }else{
                $cred = [
                    $value->key.'_storage' => 'public',
                ];
                array_push($data,$cred);
            }
        }
        $settings = [];
        foreach($data as $single_data){
            foreach($single_data as $key=>$single_value){
                $settings[$key] = $single_value;
            }
        }


        $key=['business_name'];
        $business_settings =  BusinessSetting::whereIn('key', $key)->pluck('value','key')->toArray();

        $features = AdminFeature::latest()->where('status',1)->get()->toArray();
        $testimonials = AdminTestimonial::latest()->where('status',1)->get()->toArray();

        $header_floating_content= json_decode($settings['header_floating_content'] ?? null, true);
        $header_image_content= json_decode($settings['header_image_content'] ?? null, true);

        $zones= Zone::where('status',1)->get(['id','name','display_name']);

        $landing_data = [
            'header_title'=>  $settings['header_title'] ??  'Why Stay Hungry !' ,
            'header_sub_title'=> $settings['header_sub_title'] ?? 'When you can order from' ,
            'header_tag_line'=> $settings['header_tag_line'] ?? 'Get Offers' ,
            'header_app_button_name'=> $settings['header_app_button_name'] ?? 'Order now' ,
            'header_app_button_status'=> $settings['header_app_button_status'] ?? 0 ,
            'header_button_redirect_link'=>   $settings['header_button_content'] ?? null ,

            'header_floating_total_order'=>   $header_floating_content['header_floating_total_order'] ?? null ,
            'header_floating_total_user'=>   $header_floating_content['header_floating_total_user'] ?? null ,
            'header_floating_total_reviews'=>   $header_floating_content['header_floating_total_reviews'] ?? null ,

            'header_content_image'=>   $header_image_content['header_content_image'] ?? 'double_screen_image.png' ,
            'header_content_image_full_url'=>   Helpers::get_full_url('header_image',$header_image_content['header_content_image'] ?? 'double_screen_image.png',$header_image_content['header_content_image_storage']??'public') ,
            'header_bg_image'=>   $header_image_content['header_bg_image'] ?? null ,
            'header_bg_image_full_url'=>   Helpers::get_full_url('header_image',$header_image_content['header_bg_image'] ?? null,$header_image_content['header_bg_image_storage']??'public') ,

            'about_us_title'=>   $settings['about_us_title'] ?? null ,
            'about_us_sub_title'=>   $settings['about_us_sub_title'] ?? null ,
            'about_us_text'=>   $settings['about_us_text'] ?? null ,
            'about_us_app_button_name'=>   $settings['about_us_app_button_name'] ?? 'More' ,
            'about_us_app_button_status'=>   $settings['about_us_app_button_status'] ?? 0 ,

            'about_us_redirect_link'=>   $settings['about_us_button_content'] ?? null ,
            'about_us_image_content'=>   $settings['about_us_image_content'] ??  null ,
            'about_us_image_content_full_url'=>   Helpers::get_full_url('about_us_image',$settings['about_us_image_content'] ??  null,$settings['about_us_image_content_storage']) ,

            'why_choose_us_title'=>   $settings['why_choose_us_title']?? null ,
            'why_choose_us_sub_title'=>   $settings['why_choose_us_sub_title'] ??  null ,
            'why_choose_us_image_1'=>   $settings['why_choose_us_image_1'] ??  null ,
            'why_choose_us_image_1_full_url'=>   Helpers::get_full_url('why_choose_us_image',$settings['why_choose_us_image_1'] ??  null,$settings['why_choose_us_image_1_storage']) ,
            'why_choose_us_title_1'=>   $settings['why_choose_us_title_1'] ??  null ,
            'why_choose_us_title_2'=>   $settings['why_choose_us_title_2'] ??  null ,
            'why_choose_us_image_2'=>   $settings['why_choose_us_image_2'] ??  null ,
            'why_choose_us_image_2_full_url'=>   Helpers::get_full_url('why_choose_us_image',$settings['why_choose_us_image_2'] ??  null,$settings['why_choose_us_image_2_storage']) ,
            'why_choose_us_title_3'=>   $settings['why_choose_us_title_3'] ??  null ,
            'why_choose_us_image_3'=>   $settings['why_choose_us_image_3'] ??  null ,
            'why_choose_us_image_3_full_url'=>   Helpers::get_full_url('why_choose_us_image',$settings['why_choose_us_image_3'] ??  null,$settings['why_choose_us_image_3_storage']) ,
            'why_choose_us_title_4'=>   $settings['why_choose_us_title_4'] ??  null ,
            'why_choose_us_image_4'=>   $settings['why_choose_us_image_4'] ??  null ,
            'why_choose_us_image_4_full_url'=>   Helpers::get_full_url('why_choose_us_image',$settings['why_choose_us_image_4'] ??  null,$settings['why_choose_us_image_4_storage']) ,


            'feature_title'=>   $settings['feature_title'] ??  null ,
            'feature_sub_title'=>   $settings['feature_sub_title'] ??  null ,
            'features'=> $features ?? [] ,

            'services_title'=>   $settings['services_title'] ??  null ,
            'services_sub_title'=>   $settings['services_sub_title'] ??  null ,
            'services_order_title_1'=>   $settings['services_order_title_1'] ??  null ,
            'services_order_title_2'=>   $settings['services_order_title_2'] ??  null ,
            'services_order_description_1'=>   $settings['services_order_description_1'] ??  null ,
            'services_order_description_2'=>   $settings['services_order_description_2'] ??  null ,
            'services_order_button_name'=>   $settings['services_order_button_name'] ??  null ,
            'services_order_button_status'=>   $settings['services_order_button_status'] ??  null ,
            'services_order_button_link'=>   $settings['services_order_button_link'] ??  null ,


            'services_manage_restaurant_title_1'=>   $settings['services_manage_restaurant_title_1'] ??  null ,
            'services_manage_restaurant_title_2'=>   $settings['services_manage_restaurant_title_2'] ??  null ,
            'services_manage_restaurant_description_1'=>   $settings['services_manage_restaurant_description_1'] ??  null ,
            'services_manage_restaurant_description_2'=>   $settings['services_manage_restaurant_description_2'] ??  null ,
            'services_manage_restaurant_button_name'=>   $settings['services_manage_restaurant_button_name'] ??  null ,
            'services_manage_restaurant_button_status'=>   $settings['services_manage_restaurant_button_status'] ??  null ,
            'services_manage_restaurant_button_link'=>   $settings['services_manage_restaurant_button_link'] ??  null ,


            'services_manage_delivery_title_1'=>   $settings['services_manage_delivery_title_1'] ??  null ,
            'services_manage_delivery_title_2'=>   $settings['services_manage_delivery_title_2'] ??  null ,
            'services_manage_delivery_description_1'=>   $settings['services_manage_delivery_description_1'] ??  null ,
            'services_manage_delivery_description_2'=>   $settings['services_manage_delivery_description_2'] ??  null ,
            'services_manage_delivery_button_name'=>   $settings['services_manage_delivery_button_name'] ??  null ,
            'services_manage_delivery_button_status'=>   $settings['services_manage_delivery_button_status'] ??  null ,
            'services_manage_delivery_button_link'=>   $settings['services_manage_delivery_button_link'] ??  null ,

            'testimonial_title'=> $settings['testimonial_title'] ??  null ,
            'testimonials'=> $testimonials ?? [] ,

            'earn_money_title'=>   $settings['earn_money_title'] ??  null ,
            'earn_money_sub_title'=>   $settings['earn_money_sub_title'] ??  null ,
            'earn_money_reg_title'=>   $settings['earn_money_reg_title'] ??  null ,
            'earn_money_restaurant_req_button_name'=>   $settings['earn_money_restaurant_req_button_name'] ??  null ,
            'earn_money_restaurant_req_button_status'=>   $settings['earn_money_restaurant_req_button_status'] ??  null ,
            'earn_money_delivety_man_req_button_name'=>   $settings['earn_money_delivety_man_req_button_name'] ??  null ,
            'earn_money_delivery_man_req_button_status'=>   $settings['earn_money_delivery_man_req_button_status'] ??  0 ,
            'earn_money_reg_image'=>   $settings['earn_money_reg_image'] ??  null ,
            'earn_money_reg_image_full_url'=>   Helpers::get_full_url('earn_money',$settings['earn_money_reg_image'] ??  null,$settings['earn_money_reg_image_storage']) ,

            'earn_money_delivery_req_button_link'=>   $settings['earn_money_delivery_man_req_button_link']??  null ,
            'earn_money_restaurant_req_button_link'=>   $settings['earn_money_restaurant_req_button_link'] ??  null ,

            'business_name' =>  $business_settings['business_name'] ?? 'Stackfood',

            'available_zone_status' => (int)((isset($settings['available_zone_status'])) ? $settings['available_zone_status'] : 0),
            'available_zone_title' => (isset($settings['available_zone_title'])) ? $settings['available_zone_title'] : null,
            'available_zone_short_description' => (isset($settings['available_zone_short_description'])) ? $settings['available_zone_short_description'] : null,
            'available_zone_image' => (isset($settings['available_zone_image'])) ? $settings['available_zone_image'] : null,
            'available_zone_image_full_url' => Helpers::get_full_url('available_zone_image', (isset($settings['available_zone_image'])) ? $settings['available_zone_image'] : null, (isset($settings['available_zone_image_storage'])) ? $settings['available_zone_image_storage'] : 'public'),
            'available_zone_list' => $zones ?? [],

        ];

        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        if(isset($config) && $config){
            $new_user= request()?->new_user ?? null ;
            return view('home',compact('landing_data','new_user'));
        }elseif($landing_integration_type == 'file_upload' && File::exists('resources/views/layouts/landing/custom/index.blade.php')){
            return view('layouts.landing.custom.index');
        }elseif($landing_integration_type == 'url'){
            return redirect($redirect_url);
        }else{
            abort(404);
        }
    }

    public function terms_and_conditions(Request $request)
    {
        $data = self::get_settings('terms_and_conditions');
        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('terms_and_conditions',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }

        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        if(isset($config) && $config){
            return view('terms-and-conditions', compact('data'));
        }elseif($landing_integration_type == 'file_upload'){
            return view('layouts.landing.custom.index');
        }elseif($landing_integration_type == 'url'){
            return redirect($redirect_url);
        }else{
            abort(404);
        }
    }

    public function about_us(Request $request)
    {
        $data = self::get_settings('about_us');
        // $data_title = self::get_settings('about_title');

        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('about_us',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }

        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        if(isset($config) && $config){
            return view('about-us', compact('data'));
        }elseif($landing_integration_type == 'file_upload'){
            return view('layouts.landing.custom.index');
        }elseif($landing_integration_type == 'url'){
            return redirect($redirect_url);
        }else{
            abort(404);
        }
    }

    public function contact_us(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email:filter',
                'message' => 'required',
            ],[
                'name.required' => translate('messages.Name is required!'),
                'email.required' => translate('messages.Email is required!'),
                'email.filter' => translate('messages.Must ba a valid email!'),
                'message.required' => translate('messages.Message is required!'),
            ]);

            $recaptcha = Helpers::get_business_settings('recaptcha');
            if (isset($recaptcha) && $recaptcha['status'] == 1 && !$request?->set_default_captcha) {
                $request->validate([
                    'g-recaptcha-response' => [
                        function ($attribute, $value, $fail) {
                            $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                            $gResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                                'secret' => $secret_key,
                                'response' => $value,
                                'remoteip' => \request()->ip(),
                            ]);

                            if (!$gResponse->successful()) {
                                $fail(translate('ReCaptcha Failed'));
                            }
                        },
                    ],
                ]);
            } else if (strtolower(session('six_captcha')) != strtolower($request->custome_recaptcha)) {
                Toastr::error(translate('messages.ReCAPTCHA Failed'));
                return back();
            }

            $email = Helpers::get_settings('email_address');
            $messageData = [
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
            ];
            ContactMessage::create($messageData);

            $business_name=Helpers::get_settings('business_name') ?? 'Stackfood';
            $subject='Enquiry from '.$business_name;
            try{
                if(config('mail.status')) {
                    Mail::to($email)->send(new ContactMail($messageData,$subject));
                    Toastr::success(translate('messages.Thanks_for_your_enquiry._We_will_get_back_to_you_soon.'));
                }
            }catch(\Exception $exception)
            {
                dd([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
            }
            return back();
        }


        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        $custome_recaptcha = new CaptchaBuilder;
        $custome_recaptcha->build();
        Session::put('six_captcha', $custome_recaptcha->getPhrase());

        if(isset($config) && $config){
            return view('contact-us',compact('custome_recaptcha'));
        }elseif($landing_integration_type == 'file_upload'){
            return view('layouts.landing.custom.index');
        }elseif($landing_integration_type == 'url'){
            return redirect($redirect_url);
        }else{
            abort(404);
        }
    }

    public function privacy_policy(Request $request)
    {
        $data = self::get_settings('privacy_policy');
        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('privacy_policy',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }
        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        if(isset($config) && $config){
            return view('privacy-policy',compact('data'));
        }elseif($landing_integration_type == 'file_upload'){
            return view('layouts.landing.custom.index');
        }elseif($landing_integration_type == 'url'){
            return redirect($redirect_url);
        }else{
            abort(404);
        }
    }

    public function refund_policy(Request $request)
    {
        $data = self::get_settings('refund_policy');
        $status = self::get_settings('refund_policy_status');
        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('refund_policy',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }
        abort_if($status == 0 ,404);

        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        if(isset($config) && $config){
            return view('refund_policy',compact('data'));
        }elseif($landing_integration_type == 'file_upload'){
            return view('layouts.landing.custom.index');
        }elseif($landing_integration_type == 'url'){
            return redirect($redirect_url);
        }else{
            abort(404);
        }
    }

    public function shipping_policy(Request $request)
    {
        $data = self::get_settings('shipping_policy');
        $status = self::get_settings('shipping_policy_status');
        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('shipping_policy',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }
        abort_if($status == 0 ,404);

        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        if(isset($config) && $config){
            return view('shipping_policy',compact('data'));
        }elseif($landing_integration_type == 'file_upload'){
            return view('layouts.landing.custom.index');
        }elseif($landing_integration_type == 'url'){
            return redirect($redirect_url);
        }else{
            abort(404);
        }
    }

    public function cancellation_policy(Request $request)
    {
        $data = self::get_settings('cancellation_policy');
        $status = self::get_settings('cancellation_policy_status');
        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('cancellation_policy',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }
        abort_if($status == 0 ,404);

        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        if(isset($config) && $config){
            return view('cancellation_policy',compact('data'));
        }elseif($landing_integration_type == 'file_upload'){
            return view('layouts.landing.custom.index');
        }elseif($landing_integration_type == 'url'){
            return redirect($redirect_url);
        }else{
            abort(404);
        }
    }

    public static function get_settings($name)
    {
        $data = DataSetting::where(['key' => $name])->first()?->value;
        return $data;
    }


    public function lang($local)
    {
        $direction = BusinessSetting::where('key', 'site_direction')->first();
        $direction = $direction->value ?? 'ltr';
        $language = BusinessSetting::where('key', 'system_language')->first();
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] == $local) {
                $direction = isset($data['direction']) ? $data['direction'] : 'ltr';
            }
        }
        session()->forget('landing_language_settings');
        Helpers::landing_language_load();
        session()->put('landing_site_direction', $direction);
        session()->put('landing_local', $local);
        return redirect()->back();
    }
    public static function get_settings_localization($name,$lang)
    {
        $config = null;
        $data = DataSetting::withoutGlobalScope('translate')->with(['translations' => function ($query) use ($lang) {
            return $query->where('locale', $lang);
        }])->where(['key' => $name])->first();
        if($data && count($data->translations)>0){
            $data = $data->translations[0]['value'];
        }else{
            $data = $data ? $data->value: '';
        }
        return $data;
    }

    public function maintenanceMode(){

        $maintenance = Cache::get('maintenance');


        if(!Cache::has('maintenance') ||  $maintenance['restaurant_panel'] == false  ){
            return to_route('home');
        }

        elseif (isset($maintenance['start_date']) && isset($maintenance['end_date'])) {
            $start = Carbon::parse($maintenance['start_date']);
            $end = Carbon::parse($maintenance['end_date']);
            $today = Carbon::now();
            if($today->gt($end)){
                return to_route('home');
            }
        }

        $maintenance_mode_data=   \App\Models\DataSetting::where('type','maintenance_mode')->whereIn('key' ,['maintenance_message_setup'])->pluck('value','key')
        ->map(function ($value) {
            return json_decode($value, true);
        })
        ->toArray();

                $selectedMaintenanceMessage     = data_get($maintenance_mode_data,'maintenance_message_setup',[]);


        $email = Helpers::get_business_data('email_address');
        $phone = Helpers::get_business_data('phone');


        return view('maintenance-mode',compact('email','phone','selectedMaintenanceMessage'));
    }

    public function subscription_invoice($id){

        $id= base64_decode($id);
        $BusinessData= ['admin_commission' ,'business_name','address','phone','logo','email_address'];
        $transaction= SubscriptionTransaction::with(['restaurant.vendor','package:id,package_name,price'])->findOrFail($id);
        $BusinessData=BusinessSetting::whereIn('key', $BusinessData)->pluck('value' ,'key') ;
        $logo=BusinessSetting::where('key', "logo")->first() ;
        $mpdf_view = View::make('subscription-invoice', compact('transaction','BusinessData','logo'));
        Helpers::gen_mpdf(view: $mpdf_view,file_prefix: 'Subscription',file_postfix: $id);
        return back();
    }
}
