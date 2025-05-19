<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\DataSetting;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Carbon;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules\Password;

class SystemController extends Controller
{

    public function restaurant_data()
    {
        $new_order = DB::table('orders')->where(['checked' => 0])->count();
        return response()->json([
            'success' => 1,
            'data' => ['new_order' => $new_order]
        ]);
    }

    public function settings()
    {
        return view('admin-views.settings');
    }

    public function settings_update(Request $request)
    {
        $admin = Admin::findOrFail(auth('admin')?->id());
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|unique:admins,email,'.$admin->id,
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|unique:admins,phone,'.$admin->id,
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
            'l_name.required' => translate('messages.Last name is required!'),
        ]);

        if ($request->has('image')) {
            $image_name =Helpers::update(dir:'admin/', old_image: $admin->image, format: 'png', image: $request->file('image'));
        } else {
            $image_name = $admin['image'];
        }

        $admin->f_name = $request->f_name;
        $admin->l_name = $request->l_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->image = $image_name;
        $admin->save();
        Toastr::success(translate('messages.admin_updated_successfully'));
        return back();
    }

    public function settings_password_update(Request $request)
    {
        $request->validate([
            'password' => ['required','same:confirm_password', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
            'confirm_password' => 'required',
        ],[
            'password.min_length' => translate('The password must be at least :min characters long'),
            'password.mixed' => translate('The password must contain both uppercase and lowercase letters'),
            'password.letters' => translate('The password must contain letters'),
            'password.numbers' => translate('The password must contain numbers'),
            'password.symbols' => translate('The password must contain symbols'),
            'password.uncompromised' => translate('The password is compromised. Please choose a different one'),
            'password.custom' => translate('The password cannot contain white spaces.'),
        ]);

        $admin = Admin::findOrFail(auth('admin')->id());
        $admin->password = bcrypt($request['password']);
        $admin->save();
        Toastr::success(translate('messages.admin_password_updated_successfully'));
        return back();
    }

    public function maintenance_mode(Request $request)
    {

        if(env('APP_MODE') == 'demo'){
            Toastr::warning('Sorry! You can not enable maintainance mode in demo!');
            return back();
        }

        if($request->maintenance_duration !== 'until_change' ){
            $start = Carbon::parse($request['start_date']);
            $end = Carbon::parse($request['end_date']);
                if ($start->gte($end)) {
                    Toastr::error('Sorry!_start_date_can_not_be_grater_then_end_date');
                    return back();
                }
        }

        $maintenance_mode_data=  DataSetting::where('type','maintenance_mode')->whereIn('key' ,['maintenance_system_setup'])->pluck('value','key')
        ->map(function ($value) {
            return json_decode($value, true);
        })
        ->toArray();
        $systemTopicMap = [
            'user_mobile_app' => 'maintenance_mode_user_app',
            'deliveryman_app' => 'maintenance_mode_deliveryman_app',
            'restaurant_app' => 'maintenance_mode_restaurant_app',
        ];

        $maintenance_mode = BusinessSetting::firstOrNew(['key' => 'maintenance_mode']);

        if($request?->maintenance_mode_off == 1){
            $maintenance_mode->value= 0;
            $maintenance_mode->save();
            Cache::forget('maintenance');

            $notification=[
                'title' => translate('We_are_back'),
                'description' => translate('Maintenance mode is removed'),
                'image' => '',
                'order_id' => '',
            ];

            foreach ($systemTopicMap as $system => $topic) {
                if (in_array($system, data_get($maintenance_mode_data,'maintenance_system_setup',[]))) {
                    Helpers::send_push_notif_for_maintenance_mode($notification, $topic, 'maintenance');
                }
            }


            Toastr::success(translate('messages.Maintenance_is_off'));
            return back();
        }


        $systems = ['restaurant_panel', 'user_mobile_app', 'user_web_app', 'react_website' ,'deliveryman_app' ,'restaurant_app'];
        $selectedSystems = array_filter($systems, fn($system) => $request?->$system );
        $selectedSystems = array_values($selectedSystems);



        if(count($selectedSystems) == 0){
            Toastr::error(translate('messages.You_must_select_a_system_for_maintenance'));
            return back();
        }
        $old_maintenance_mode = $maintenance_mode->value;

        $maintenance_mode->value= 1 ;
        $maintenance_mode->save();


        $selectedMaintenanceSystem = DataSetting::firstOrNew(
            ['key' =>  'maintenance_system_setup',
            'type' =>  'maintenance_mode'],
        );
        $selectedMaintenanceSystem->value = json_encode($selectedSystems);
        $selectedMaintenanceSystem->save();


        $selectedMaintenanceDuration = DataSetting::firstOrNew(
            ['key' =>  'maintenance_duration_setup',
            'type' =>  'maintenance_mode'],
        );
        $selectedMaintenanceDuration->value =  json_encode([
            'maintenance_duration' => $request['maintenance_duration'],
            'start_date' => $request['start_date'] ?? null,
            'end_date' => $request['end_date'] ?? null,
        ]);

        $selectedMaintenanceDuration->save();




        $selectedMaintenanceMessage = DataSetting::firstOrNew(
            ['key' =>  'maintenance_message_setup',
            'type' =>  'maintenance_mode'],
        );
        $selectedMaintenanceMessage->value = json_encode([
            'business_number' => $request->has('business_number') ? 1 : 0,
            'business_email' => $request->has('business_email') ? 1 : 0,
            'maintenance_message' => $request['maintenance_message'],
            'message_body' => $request['message_body']
        ]);

        $selectedMaintenanceMessage->save();


        $maintenance = [
            'status' => $maintenance_mode?->value,
            'start_date' => $request['start_date'] ?? null,
            'end_date' => $request['end_date'] ?? null,
            'restaurant_panel' => in_array('restaurant_panel',$selectedSystems),
            'maintenance_duration' => $request['maintenance_duration'] ,
        ];

        Cache::put('maintenance', $maintenance, now()->addYears(1));

        if( $old_maintenance_mode != 1 || count(array_diff($selectedSystems,data_get($maintenance_mode_data,'maintenance_system_setup',[])))  > 0){
            $notification=[
                'title' => translate('maintenance_mode'),
                'description' => translate('We are Working On Something Special!'),
                'image' => '',
                'order_id' => '',
            ];

            foreach ($systemTopicMap as $system => $topic) {
                if ((in_array($system, $selectedSystems) && $old_maintenance_mode != 1 )|| in_array($system, array_diff($selectedSystems,data_get($maintenance_mode_data,'maintenance_system_setup',[]))) ) {
                    Helpers::send_push_notif_for_maintenance_mode($notification, $topic, 'maintenance');
                }
            }
        }
        if( count(array_diff(data_get($maintenance_mode_data,'maintenance_system_setup',[]),$selectedSystems)) > 0 ){

            $notification=[
                'title' => translate('We_are_back'),
                'description' => translate('Maintenance mode is removed'),
                'image' => '',
                'order_id' => '',
            ];

            foreach ($systemTopicMap as $system => $topic) {
                if ( in_array($system, array_diff(data_get($maintenance_mode_data,'maintenance_system_setup',[]),$selectedSystems)) ) {
                    Helpers::send_push_notif_for_maintenance_mode($notification, $topic, 'maintenance');
                }
            }

        }

        Toastr::success(translate('messages.Maintenance mode settings updated'));
    return back();
    }

    public function update_fcm_token(Request $request){
        $admin = $request?->user();
        $admin->firebase_token = $request->token;
        $admin?->save();

        return response()->json([]);

    }
    public function landing_page()
    {
        $landing_page = BusinessSetting::where('key', 'landing_page')->first();
        Helpers::businessUpdateOrInsert(['key' => 'landing_page'], [
                'value' =>$landing_page?->value == 1 ? 0 : 1,
            ]);

        if (isset($landing_page) && $landing_page->value) {
            return response()->json(['message' => translate('landing_page_is_off.')]);
        }
        return response()->json(['message' => translate('landing_page_is_on.')]);
    }
    public function system_currency(Request $request)
    {
        $currency_check=Helpers::checkCurrency($request['currency']);
        if( $currency_check !== true ){
        return response()->json(['data'=> translate($currency_check) ],200);
        }
        return response()->json([],200);
    }
}
