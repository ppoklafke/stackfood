<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Food;
use App\Models\User;
use App\Models\Zone;
use App\Models\Order;
use App\Models\UserInfo;
use Carbon\CarbonInterval;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Mail\EmailVerification;
use App\Models\BusinessSetting;
use App\Models\CustomerAddress;
use App\CentralLogics\SMS_module;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\EmailVerifications;
use App\Models\PhoneVerification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Modules\Gateways\Traits\SmsGateway;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use MatanYadaev\EloquentSpatial\Objects\Point;

class CustomerController extends Controller
{
    public function address_list(Request $request)
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;

        $addresses = CustomerAddress::where('user_id', $request?->user()?->id)->latest()->paginate($limit, ['*'], 'page', $offset);

        $data =  [
            'total_size' => $addresses->total(),
            'limit' => $limit,
            'offset' => $offset,
            'addresses' => Helpers::address_data_formatting($addresses->items())
        ];
        return response()->json($data, 200);
    }

    public function add_new_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $zone = Zone::whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))->get(['id']);
        if (count($zone) == 0) {
            $errors = [];
            array_push($errors, ['code' => 'coordinates', 'message' => translate('messages.service_not_available_in_this_area')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $address = [
            'user_id' => $request?->user()?->id,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'floor' => $request->floor,
            'road' => $request->road,
            'house' => $request->house,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'zone_id' => $zone[0]->id,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('customer_addresses')->insert($address);
        return response()->json(['message' => translate('messages.New address added'), 'zone_ids' => array_column($zone->toArray(), 'id')], 200);
    }

    public function update_address(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $zone = Zone::whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))->first();
        if (!$zone) {
            $errors = [];
            array_push($errors, ['code' => 'coordinates', 'message' => translate('messages.service_not_available_in_this_area')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $address = [
            'user_id' => $request?->user()?->id,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'floor' => $request->floor,
            'road' => $request->road,
            'house' => $request->house,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'zone_id' => $zone->id,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('customer_addresses')->where('id', $id)->update($address);
        return response()->json(['message' => translate('messages.address_updated'), 'zone_id' => $zone->id], 200);
    }

    public function delete_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (DB::table('customer_addresses')->where(['id' => $request['address_id'], 'user_id' => $request?->user()?->id])->first()) {
            DB::table('customer_addresses')->where(['id' => $request['address_id'], 'user_id' => $request?->user()?->id])->delete();
            return response()->json(['message' => translate('messages.Address_removed')], 200);
        }
        return response()->json(['message' => translate('messages.not_found')], 404);
    }

    public function get_order_list(Request $request)
    {
        $orders = Order::with('restaurant')->where('is_guest', 0)->where(['user_id' => $request?->user()?->id])->get();
        return response()->json($orders, 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $details = OrderDetail::where(['order_id' => $request['order_id']])->get();
        foreach ($details as $det) {
            $det['product_details'] = json_decode($det['product_details'], true);
        }

        return response()->json($details, 200);
    }

    public function info(Request $request)
    {

        if (!$request->hasHeader('X-localization')) {

            $errors = [];
            array_push($errors, ['code' => 'current_language_key', 'message' => translate('messages.current_language_key_required')]);
            return response()->json([
                'errors' => $errors
            ], 200);
        }


        $current_language = $request->header('X-localization');
        $user = User::findOrFail($request->user()->id);
        $user->current_language_key = $current_language;
        $user->save();


        $data = $request?->user();
        $data['userinfo'] = $data?->userinfo;
        $data['order_count'] = (int)$request?->user()?->orders()->where('is_guest', 0)->count();
        $data['member_since_days'] = (int)$request?->user()?->created_at?->diffInDays();
        $discount_data = Helpers::getCusromerFirstOrderDiscount(order_count: $data['order_count'], user_creation_date: $request->user()->created_at, refby: $request->user()->ref_by);
        $data['is_valid_for_discount'] = data_get($discount_data, 'is_valid');
        $data['discount_amount'] = (float) data_get($discount_data, 'discount_amount');
        $data['discount_amount_type'] = data_get($discount_data, 'discount_amount_type');
        $data['validity'] = (string) data_get($discount_data, 'validity');

        unset($data['orders']);
        return response()->json($data, 200);
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $request?->user()?->id,
            'image' => 'nullable|max:2048',
            'password' => ['nullable', Password::min(8)],

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }



        $message=translate('messages.profile_successfully_updated');

        if($request->button_type == 'change_password'){

            $message=translate('messages.Password_successfully_updated');
        }

        $user = User::where(['id' => $request?->user()?->id])->with('userinfo')->first();

        $login_settings = array_column(BusinessSetting::whereIn('key', ['email_verification_status', 'phone_verification_status', 'firebase_otp_verification'])->get(['key', 'value'])->toArray(), 'value', 'key');

        if($request->button_type != 'change_password' && !$request->otp ){
            if (  data_get($login_settings, 'phone_verification_status') == 1  && ($user->phone != $request->phone  || $request->button_type == 'phone' || (!$user->is_phone_verified  && !$request->button_type) )) {
                if (data_get($login_settings, 'firebase_otp_verification') == 1) {
                    return response()->json(['verification_on' => 'phone', 'verification_medium' => 'firebase', 'otp_send' => true, 'message' => translate('Otp_successfully_sent')], 200);
                } else {
                    $verification_data =  $this->verification_check($request->phone);
                    return response()->json(['verification_on' => 'phone', 'verification_medium' => 'SMS', 'otp_send' => $verification_data['is_success'], 'message' => $verification_data['message']], $verification_data['code']);
                }

            } elseif ( data_get($login_settings, 'email_verification_status') == 1 && ($user->email != $request->email || $request->button_type == 'email' || !$user->is_email_verified && !$request->button_type )) {
                $verification_data =  $this->verification_check_email(['email' => $request->email, 'name' => $user?->f_name . ' ' . $user?->l_name]);
                return response()->json(['verification_on' => 'email', 'verification_medium' => 'email', 'otp_send' => $verification_data['is_success'], 'message' => $verification_data['message']], $verification_data['code']);
            }
        }


        if($user->is_email_verified  == 1 && $user->email != $request->email ){
            $user->is_email_verified = 0;
            $user->save();
        }


        if ($request->verification_on == 'phone' && $request->otp) {
            if ($request->verification_medium == 'firebase') {
                $validator = Validator::make($request->all(), [
                    'session_info' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => Helpers::error_processor($validator)], 403);
                }
                $verification_data =  $this->check_firebase_otp($request);
            } else{
                $verification_data =  $this->check_SMS_otp($request);
            }

            if ($verification_data['is_success'] == false) {
                return response()->json(['verification_on' => 'phone', 'verification_medium' => $verification_data['verification_medium'], 'message' => $verification_data['message']], $verification_data['code']);
            }
            $user->is_phone_verified = 1;
            $user->save();

            $message=translate('messages.Phone_successfully_verified');
        }

        if ($request->verification_on == 'email' && $request->otp) {
            $verification_data =  $this->check_email_otp($request);
            if ($verification_data['is_success'] == false) {
                return response()->json(['verification_on' => 'email', 'verification_medium' => $verification_data['verification_medium'], 'message' => $verification_data['message']], $verification_data['code']);
            }
            $user->is_email_verified = 1;
            $user->save();

            $message=translate('messages.Email_successfully_verified');

        }

        $this->update_user_data($user, $request);


        return response()->json(['message' => $message], 200);
    }
    public function update_interest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'interest' => 'required|array',
        ], [
            'interest.required ' => translate('Please_select_your_interested_preferences')
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $userDetails = [
            'interest' => json_encode($request->interest),
        ];

        User::where(['id' => $request?->user()?->id])->update($userDetails);

        return response()->json(['message' => translate('messages.interest_updated_successfully')], 200);
    }

    public function update_cm_firebase_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        DB::table('users')->where('id', $request?->user()?->id)->update([
            'cm_firebase_token' => $request['cm_firebase_token']
        ]);

        return response()->json(['message' => translate('messages.updated_successfully')], 200);
    }

    public function get_suggested_food(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => 'Zone id is required!']);
            return response()->json([
                'errors' => $errors
            ], 403);
        }


        $zone_id = json_decode($request->header('zoneId'), true);

        $interest = $request?->user()?->interest;
        $interest = isset($interest) ? json_decode($interest) : null;
        // return response()->json($interest, 200);

        $products =  Food::active()->whereHas('restaurant', function ($q) use ($zone_id) {
            $q->whereIn('zone_id', $zone_id);
        })
            ->when(isset($interest), function ($q) use ($interest) {
                return $q->whereIn('category_id', $interest);
            })
            ->when($interest == null, function ($q) {
                return $q->popular();
            })->limit(5)->get();
        $products = Helpers::product_data_formatting($products, true, false, app()->getLocale());
        return response()->json($products, 200);
    }

    public function update_zone(Request $request)
    {
        if (!$request->hasHeader('zoneId') && is_numeric($request->header('zoneId'))) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $customer = $request->user();
        $customer->zone_id = (int)json_decode($request->header('zoneId'), true)[0];
        $customer->save();
        return response()->json([], 200);
    }

    public function remove_account(Request $request)
    {
        $user = $request->user();

        if (Order::where('user_id', $user->id)->where('is_guest', 0)->whereIn('order_status', ['pending', 'accepted', 'confirmed', 'processing', 'handover', 'picked_up'])->count()) {
            return response()->json(['errors' => [['code' => 'on-going', 'message' => translate('messages.user_account_delete_warning')]]], 403);
        }
        $request?->user()?->token()->revoke();
        if ($user?->userinfo) {
            $user?->userinfo?->delete();
        }
        $user->delete();
        return response()->json([]);
    }


    private function verification_check($phone)
    {
        $otp_interval_time = 60; //seconds
        $verification_data = DB::table('phone_verifications')->where('phone', $phone)->first();
        if (isset($verification_data) &&  Carbon::parse($verification_data->updated_at)->DiffInSeconds() < $otp_interval_time) {
            $time = $otp_interval_time - Carbon::parse($verification_data->updated_at)->DiffInSeconds();
            return ['is_success' => false,  'message' => translate('messages.please_try_again_after_') . $time . ' ' . translate('messages.seconds'), 'code' => 403];
        }

        $otp = rand(100000, 999999);
        if(env('APP_MODE') == 'test'){
            $otp = '123456';
        }
        DB::table('phone_verifications')->updateOrInsert(
            ['phone' => $phone],
            [
                'token' => $otp,
                'otp_hit_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $published_status = 0;
        $payment_published_status = config('get_payment_publish_status');
        if (isset($payment_published_status[0]['is_published'])) {
            $published_status = $payment_published_status[0]['is_published'];
        }

        if ($published_status == 1) {
            $response = SmsGateway::send($phone, $otp);
        } else {
            $response = SMS_module::send($phone, $otp);
        }
        if (env('APP_MODE') != 'test' && $response !== 'success') {
            return ['is_success' => false,  'message' => translate('failed_to_send_otp'), 'code' => 403];
        }
        return  ['is_success' => true,  'message' => translate('OTP_successfully_send'), 'code' => 200];
    }
    private function verification_check_email($data)
    {
        $otp = rand(100000, 999999);
        if(env('APP_MODE') == 'test'){
            $otp = '123456';
        }
        DB::table('email_verifications')->updateOrInsert(
            ['email' => $data['email']],
            [
                'token' => $otp,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        try {
            $mailResponse = null;
            $mail_status = Helpers::get_mail_status('profile_verification_mail_status_user');

            if (config('mail.status') && $mail_status == '1') {
                Mail::to($data['email'])->send(new EmailVerification($otp, $data['name'],'profile_update' ));
                $mailResponse = 'success';
            }
        } catch (\Exception $ex) {
            info($ex->getMessage());
            $mailResponse = null;
        }
        if (env('APP_MODE') != 'test' && $mailResponse !== 'success') {
            return  ['is_success' => false,  'message' => translate('failed_to_send_mail'), 'code' => 403];
        }
        return  ['is_success' => true,  'message' => translate('OTP_successfully_send_to_mail'), 'code' => 200];
    }
    private function update_user_data($user, $request)
    {
        if ($request->has('image')) {
            $imageName = Helpers::update(dir: 'profile/', old_image: $user?->image, format: 'png', image: $request->file('image'));
        } else {
            $imageName = $user?->image;
        }

        if ($request['password'] != null && strlen($request['password']) > 5) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $user?->password;
        }

        $name = $request->name;
        $nameParts = explode(' ', $name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';



        $user->f_name = $firstName;
        $user->l_name = $lastName;
        $user->image = $imageName;
        $user->password = $pass;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->save();

        if ($user->userinfo) {
            UserInfo::where(['user_id' => $user?->id])->update([
                'f_name' => $firstName,
                'l_name' => $lastName,
                'email' => $request->email,
                'image' => $imageName
            ]);
        }
        return true;
    }
    private function check_firebase_otp($request)
    {
        $webApiKey = BusinessSetting::where('key', 'firebase_web_api_key')->first()?->value ?? '';

        $response = Http::post('https://identitytoolkit.googleapis.com/v1/accounts:signInWithPhoneNumber?key=' . $webApiKey, [
            'sessionInfo' => $request->session_info,
            'phoneNumber' => $request->phone,
            'code' => $request->otp,
        ]);

        $responseData = $response->json();

        if (isset($responseData['error'])) {
            return  ['is_success' => false, 'verification_medium' => 'firebase', 'message' => $responseData['error']['message'], 'code' => 403];
        }
        return ['is_success' => true, 'verification_medium' => 'firebase',  'message' => translate('Otp_verification_successful'), 'code' => 200];
    }
    private function check_email_otp($request)
    {
        $email_verification =  EmailVerifications::where(['email' => $request['email'], 'token' => $request['otp']])->first();
        if ($email_verification) {
            $email_verification->delete();
            return ['is_success' => true, 'verification_medium' => 'email',  'message' => translate('Otp_verification_successful'), 'code' => 200];
        }

        // $max_otp_hit = 5;
        // $max_otp_hit_time = 60; // seconds
        // $temp_block_time = 600; // seconds

        // $verification_data =EmailVerifications::where('email', $request['email'])->first();

        // if (isset($verification_data)) {
        //     if (isset($verification_data->temp_block_time) && Carbon::parse($verification_data->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
        //         $time = $temp_block_time - Carbon::parse($verification_data->temp_block_time)->DiffInSeconds();
        //         return  ['is_success'=> false, 'verification_medium'=>'email' , 'message'=> translate('messages.please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans() ,'code' => 403];
        //     }

        //     if ($verification_data->is_temp_blocked == 1 && Carbon::parse($verification_data->updated_at)->DiffInSeconds() >= $max_otp_hit_time) {
        //             $verification_data->otp_hit_count = 0;
        //             $verification_data->is_temp_blocked = 0;
        //             $verification_data->temp_block_time = null;
        //             $verification_data->created_at = now();
        //             $verification_data->updated_at = now();
        //             $verification_data->save();
        //     }

        //     if ($verification_data->otp_hit_count >= $max_otp_hit && Carbon::parse($verification_data->updated_at)->DiffInSeconds() < $max_otp_hit_time && $verification_data->is_temp_blocked == 0) {

        //             $verification_data->is_temp_blocked = 1;
        //             $verification_data->temp_block_time = now();
        //             $verification_data->created_at = now();
        //             $verification_data->updated_at = now();
        //             $verification_data->save();
        //         return  ['is_success'=> false, 'verification_medium'=>'email' , 'message'=> translate('messages.Too_many_attemps') ,'code' => 403];
        //     }
        //     $verification_data->otp_hit_count = $verification_data->otp_hit_count+1;
        //     $verification_data->updated_at = now();
        //     $verification_data->temp_block_time = null;
        //     $verification_data->save();

        // }else{
        //     return  ['is_success'=> false, 'verification_medium'=>'email' , 'message'=> translate('email_not_found!!!') ,'code' => 403];
        // }
        return  ['is_success' => false, 'verification_medium' => 'email', 'message' => translate('OTP_does_not_match!!!'), 'code' => 403];
    }
    private function check_SMS_otp($request)
    {
        $phone_verification =  PhoneVerification::where(['phone' => $request['phone'], 'token' => $request['otp']])->first();
        if ($phone_verification) {
            $phone_verification->delete();
            return ['is_success' => true, 'verification_medium' => 'SMS',  'message' => translate('Otp_verification_successful'), 'code' => 200];
        }

        $max_otp_hit = 5;
        $max_otp_hit_time = 60; // seconds
        $temp_block_time = 600; // seconds

        $verification_data = PhoneVerification::where('phone', $request['phone'])->first();

        if (isset($verification_data)) {
            if (isset($verification_data->temp_block_time) && Carbon::parse($verification_data->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                $time = $temp_block_time - Carbon::parse($verification_data->temp_block_time)->DiffInSeconds();
                return  ['is_success' => false, 'verification_medium' => 'SMS', 'message' => translate('messages.please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(), 'code' => 403];
            }

            if ($verification_data->is_temp_blocked == 1 && Carbon::parse($verification_data->updated_at)->DiffInSeconds() >= $max_otp_hit_time) {
                $verification_data->otp_hit_count = 0;
                $verification_data->is_temp_blocked = 0;
                $verification_data->temp_block_time = null;
                $verification_data->created_at = now();
                $verification_data->updated_at = now();
                $verification_data->save();
            }

            if ($verification_data->otp_hit_count >= $max_otp_hit && Carbon::parse($verification_data->updated_at)->DiffInSeconds() < $max_otp_hit_time && $verification_data->is_temp_blocked == 0) {

                $verification_data->is_temp_blocked = 1;
                $verification_data->temp_block_time = now();
                $verification_data->created_at = now();
                $verification_data->updated_at = now();
                $verification_data->save();
                return  ['is_success' => false, 'verification_medium' => 'SMS', 'message' => translate('messages.Too_many_attemps'), 'code' => 403];
            }
            $verification_data->otp_hit_count = $verification_data->otp_hit_count + 1;
            $verification_data->updated_at = now();
            $verification_data->temp_block_time = null;
            $verification_data->save();
        } else {
            return  ['is_success' => false, 'verification_medium' => 'SMS', 'message' => translate('Phone_not_found!!!'), 'code' => 403];
        }
        return  ['is_success' => false, 'verification_medium' => 'SMS', 'message' => translate('OTP_does_not_match!!!'), 'code' => 403];
    }
}
