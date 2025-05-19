<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Zone;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\Restaurant;
use App\Models\DataSetting;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPackage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use MatanYadaev\EloquentSpatial\Objects\Point;

class VendorController extends Controller
{
    public function create()
    {
        $status = BusinessSetting::where('key', 'toggle_restaurant_registration')->first();
        if(!isset($status) || $status->value == '0')
        {
            Toastr::error(translate('messages.not_found'));
            return back();
        }
        $page_data=   DataSetting::Where('type' , 'restaurant')->where('key' , 'restaurant_page_data')->first()?->value;
        $page_data =  $page_data ? json_decode($page_data ,true)  :[];

        $admin_commission= BusinessSetting::where('key','admin_commission')->first()?->value;
        $business_name= BusinessSetting::where('key','business_name')->first()?->value;
        $packages= SubscriptionPackage::where('status',1)->latest()->get();


        return view('vendor-views.auth.register-step-1',compact('page_data','admin_commission','business_name','packages')) ;
    }

    public function store(Request $request)
    {
        $status = BusinessSetting::where('key', 'toggle_restaurant_registration')->first();
        if(!isset($status) || $status->value == '0')
        {
            Toastr::error(translate('messages.not_found'));
            return back();
        }
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'name.*' => 'max:191',
            'name.0' => 'required',
            'address.0' => 'required',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'email' => 'required|email|unique:vendors',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|unique:vendors',
            'minimum_delivery_time' => 'required',
            'maximum_delivery_time' => 'required',
            'zone_id' => 'required',
            'logo' => 'required|max:2048',
            'cover_photo' => 'required|max:2048',
            'tax' => 'required',
            'delivery_time_type'=>'required',
            'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
            'package_id' => 'required_if:business_plan,subscription-base|nullable',

        ],[
            'password.min_length' => translate('The password must be at least :min characters long'),
            'password.mixed' => translate('The password must contain both uppercase and lowercase letters'),
            'password.letters' => translate('The password must contain letters'),
            'password.numbers' => translate('The password must contain numbers'),
            'password.symbols' => translate('The password must contain symbols'),
            'password.uncompromised' => translate('The password is compromised. Please choose a different one'),
            'password.custom' => translate('The password cannot contain white spaces.'),
            'name.0.required'=>translate('default_restaurant_name_is_required'),
            'address.0.required'=>translate('default_restaurant_address_is_required'),
            'package_id.required_if' => translate('messages.You_must_select_a_package'),
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        if($request?->latitude == null  &&  $request?->longitude == null){
            return back()->withErrors($validator)->withInput();
        }

        if($request->zone_id)
        {
            $zone = Zone::query()
            ->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))
            ->where('id',$request->zone_id)
            ->first();
            if(!$zone){
                $validator->getMessageBag()->add('latitude', translate('messages.coordinates_out_of_zone'));
                return back()->withErrors($validator)->withInput();
            }
        }


        if ($request->delivery_time_type == 'min') {
            $minimum_delivery_time = (int) $request->input('minimum_delivery_time');
            if ($minimum_delivery_time < 10) {
                $validator->getMessageBag()->add('minimum_delivery_time', translate('messages.minimum_delivery_time_should_be_more_than_10_min'));
                return back()->withErrors($validator)->withInput();
            }
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
        try{
            $cuisine_ids = [];
            $cuisine_ids=$request->cuisine_ids;

            DB::beginTransaction();
            $vendor = new Vendor();
            $vendor->f_name = $request->f_name;
            $vendor->l_name = $request->l_name;
            $vendor->email = $request->email;
            $vendor->phone = $request->phone;
            $vendor->password = bcrypt($request->password);
            $vendor->status = null;
            $vendor->save();

            $restaurant = new Restaurant;
            $restaurant->name =  $request->name[array_search('default', $request->lang)];
            $restaurant->phone = $request->phone;
            $restaurant->email = $request->email;
            $restaurant->logo = Helpers::upload( dir:'restaurant/',  format:'png', image:  $request->file('logo'));
            $restaurant->cover_photo = Helpers::upload( dir:'restaurant/cover/', format: 'png',  image: $request->file('cover_photo'));
            $restaurant->address = $request->address[array_search('default', $request->lang)];
            $restaurant->latitude = $request->latitude;
            $restaurant->longitude = $request->longitude;
            $restaurant->vendor_id = $vendor->id;
            $restaurant->zone_id = $request->zone_id;
            $restaurant->tax = $request->tax;
            $restaurant->delivery_time =$request->minimum_delivery_time .'-'. $request->maximum_delivery_time.'-'.$request->delivery_time_type;
            $restaurant->status = 0;
            $restaurant->restaurant_model = 'none';

            if(isset($request->additional_data)  && count($request->additional_data) > 0){
                $restaurant->additional_data = json_encode($request->additional_data) ;
            }

            $additional_documents = [];
            if ($request->additional_documents) {
                foreach ($request->additional_documents as $key => $data) {
                    $additional = [];
                    foreach($data as $file){
                        if(is_file($file)){
                            $file_name = Helpers::upload('additional_documents/', $file->getClientOriginalExtension(), $file);
                            $additional[] = ['file'=>$file_name, 'storage'=> Helpers::getDisk()];
                        }
                        $additional_documents[$key] = $additional;
                    }
                }
                $restaurant->additional_documents = json_encode($additional_documents);
            }

            $restaurant->save();
            $restaurant?->cuisine()?->sync($cuisine_ids);
            $restaurant->tags()->sync($tag_ids);

            Helpers::add_or_update_translations(request: $request, key_data: 'name', name_field: 'name', model_name: 'Restaurant', data_id: $restaurant->id, data_value: $restaurant->name);
            Helpers::add_or_update_translations(request: $request, key_data: 'address', name_field: 'address', model_name: 'Restaurant', data_id: $restaurant->id, data_value: $restaurant->address);

            DB::commit();
            try{
                $admin= Admin::where('role_id', 1)->first();
                $notification_status= Helpers::getNotificationStatusData('restaurant','restaurant_registration');

                if( $notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('registration_mail_status_restaurant') == '1'){
                    Mail::to($request['email'])->send(new \App\Mail\VendorSelfRegistration('pending', $vendor->f_name.' '.$vendor->l_name));
                }
                $notification_status= null ;
                $notification_status= Helpers::getNotificationStatusData('admin','restaurant_self_registration');

                if( $notification_status?->mail_status == 'active' && config('mail.status') &&  Helpers::get_mail_status('restaurant_registration_mail_status_admin')== '1'){
                    Mail::to($admin['email'])->send(new \App\Mail\RestaurantRegistration('pending', $vendor->f_name.' '.$vendor->l_name));
                }
            }catch(\Exception $exception){
                info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
            }

            if (Helpers::subscription_check()) {
                if ($request->business_plan == 'subscription-base' && $request->package_id != null) {
                    $key = ['subscription_free_trial_days', 'subscription_free_trial_type', 'subscription_free_trial_status'];
                    $free_trial_settings = BusinessSetting::whereIn('key', $key)->pluck('value', 'key');
                    $restaurant->package_id = $request->package_id;
                    $restaurant->save();

                    return view('vendor-views.auth.register-subscription-payment', [
                        'package_id' => $request->package_id,
                        'restaurant_id' => $restaurant->id,
                        'free_trial_settings' => $free_trial_settings,
                        'payment_methods' => Helpers::getActivePaymentGateways(),

                    ]);
                } elseif ($request->business_plan == 'commission-base') {
                    $restaurant->restaurant_model = 'commission';
                    $restaurant->save();
                    return view('vendor-views.auth.register-complete', [
                        'type' => 'commission'
                    ]);
                } else {
                    $admin_commission = BusinessSetting::where('key', 'admin_commission')->first();
                    $business_name = BusinessSetting::where('key', 'business_name')->first();
                    $packages = SubscriptionPackage::where('status', 1)->latest()->get();
                    Toastr::error(translate('messages.please_follow_the_steps_properly.'));
                    return view('vendor-views.auth.register-step-2', [
                        'admin_commission' => $admin_commission?->value,
                        'business_name' => $business_name?->value,
                        'packages' => $packages,
                        'restaurant_id' => $restaurant->id,
                        'type' => $request->type
                    ]);
                }
            } else {
                $restaurant->restaurant_model = 'commission';
                $restaurant->save();
                Toastr::success(translate('messages.your_restaurant_registration_is_successful'));
                return view('vendor-views.auth.register-complete', [
                    'type' => 'commission'
                ]);
            }





        }catch(\Exception $ex){
            DB::rollback();
            info($ex->getMessage());
            Toastr::success(translate('messages.something_went_wrong_Please_try_again.'));
            return back();
        }

    }

    public function business_plan(Request $request)
    {
        $restaurant = Restaurant::find($request->restaurant_id);

        if ($request->business_plan == 'subscription-base' && $request->package_id != null) {
            $key = ['subscription_free_trial_days', 'subscription_free_trial_type', 'subscription_free_trial_status'];
            $free_trial_settings = BusinessSetting::whereIn('key', $key)->pluck('value', 'key');

            return view('vendor-views.auth.register-subscription-payment', [
                'package_id' => $request->package_id,
                'restaurant_id' => $request->restaurant_id,
                'free_trial_settings' => $free_trial_settings,
                'payment_methods' => Helpers::getActivePaymentGateways(),

            ]);
        } elseif ($request->business_plan == 'commission-base') {
            $restaurant->restaurant_model = 'commission';
            $restaurant->save();
            return view('vendor-views.auth.register-complete', [
                'type' => 'commission'
            ]);
        } else {
            $admin_commission = BusinessSetting::where('key', 'admin_commission')->first();
            $business_name = BusinessSetting::where('key', 'business_name')->first();
            $packages = SubscriptionPackage::where('status', 1)->latest()->get();
            Toastr::error(translate('messages.please_follow_the_steps_properly.'));
            return view('vendor-views.auth.register-step-2', [
                'admin_commission' => $admin_commission?->value,
                'business_name' => $business_name?->value,
                'packages' => $packages,
                'restaurant_id' => $request->restaurant_id,
                'type' => $request->type
            ]);
        }
    }


    public function payment(Request $request)
    {
        $request->validate([
            'package_id' => 'required',
            'restaurant_id' => 'required',
            'payment' => 'required'
        ]);
        $restaurant = Restaurant::Where('id', $request->restaurant_id)->first(['id', 'vendor_id']);
        $package = SubscriptionPackage::withoutGlobalScope('translate')->find($request->package_id);

        if (!in_array($request->payment, ['free_trial'])) {
            $url = route('restaurant.final_step', ['restaurant_id' => $restaurant->id ?? null]);
            return redirect()->away(Helpers::subscriptionPayment(restaurant_id: $restaurant->id, package_id: $package->id, payment_gateway: $request->payment, payment_platform: 'web', url: $url, type: 'new_join'));
        }
        if ($request->payment == 'free_trial') {
            $plan_data =   Helpers::subscription_plan_chosen(restaurant_id: $restaurant->id, package_id: $package->id, payment_method: 'free_trial', discount: 0, reference: 'free_trial', type: 'new_join');
        }
        $plan_data != false ?  Toastr::success(translate('Successfully_Subscribed.')) : Toastr::error(translate('Something_went_wrong!.'));
        return to_route('restaurant.final_step');
    }

    public function back(Request $request){
        // $restaurant_id = $request->restaurant_id;
        $restaurant_id = base64_decode($request->restaurant_id);
        Restaurant::findOrFail($restaurant_id);
        $admin_commission= BusinessSetting::where('key','admin_commission')->first();
        $business_name= BusinessSetting::where('key','business_name')->first();
        $packages= SubscriptionPackage::where('status',1)->latest()->get();
        return view('vendor-views.auth.register-step-2',[
            'admin_commission'=> $admin_commission?->value,
            'business_name'=> $business_name?->value,
            'packages'=> $packages,
            'restaurant_id' => $restaurant_id
            ]);
    }

    public function final_step(Request $request)
    {


        $restaurant_id = null;
        $payment_status = null;
        if ($request?->restaurant_id && is_string($request?->restaurant_id)) {
            $data = explode('?', $request?->restaurant_id);
            $restaurant_id = $data[0];
            $payment_status = $data[1]  != 'flag=success' ? 'fail' : 'success';
        }

        return view('vendor-views.auth.register-complete', ['restaurant_id' => $restaurant_id, 'payment_status' => $payment_status]);
    }
}
