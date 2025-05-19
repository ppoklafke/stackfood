<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\Tag;
use App\Models\Zone;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\Restaurant;
use App\Models\Translation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use MatanYadaev\EloquentSpatial\Objects\Point;


class VendorLoginController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth('vendor')->attempt($data)) {
            $token = $this->genarate_token($request['email']);
            $vendor = Vendor::where(['email' => $request['email']])->first();

            $restaurantSubscriptionCheck =  $this->restaurantSubscriptionCheck($vendor?->restaurants[0], $vendor, $token);
            if (data_get($restaurantSubscriptionCheck, 'type') != null) {
                return response()->json(data_get($restaurantSubscriptionCheck, 'data'), data_get($restaurantSubscriptionCheck, 'code'));
            }
            $vendor->auth_token = $token;
            $vendor?->save();
            return response()->json(['token' => $token, 'zone_wise_topic' => $vendor?->restaurants[0]?->zone?->restaurant_wise_topic], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => translate('Credential_do_not_match,_please_try_again.')]);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }

    private function genarate_token($email)
    {
        $token = Str::random(120);
        $is_available = Vendor::where('auth_token', $token)->where('email', '!=', $email)->count();
        if ($is_available) {
            $this->genarate_token($email);
        }
        return $token;
    }


    public function register(Request $request)
    {
        $status = BusinessSetting::where('key', 'toggle_restaurant_registration')->first();
        if (!isset($status) || $status->value == '0') {
            return response()->json(['errors' => Helpers::error_formater('self-registration', translate('messages.restaurant_self_registration_disabled'))]);
        }

        $validator = Validator::make($request->all(), [
            'fName' => 'required',
            'lat' => 'required|numeric|min:-90|max:90',
            'lng' => 'required|numeric|min:-180|max:180',
            'email' => 'required|email|unique:vendors',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|unique:vendors',
            'min_delivery_time' => 'required',
            'max_delivery_time' => 'required',
            'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
            'zone_id' => 'required',
            'logo' => 'required|max:2048',
            'cover_photo' => 'nullable|max:2048',
            'vat' => 'required',
            'delivery_time_type' => 'required',

        ], [
            'password.min_length' => translate('The password must be at least :min characters long'),
            'password.mixed' => translate('The password must contain both uppercase and lowercase letters'),
            'password.letters' => translate('The password must contain letters'),
            'password.numbers' => translate('The password must contain numbers'),
            'password.symbols' => translate('The password must contain symbols'),
            'password.uncompromised' => translate('The password is compromised. Please choose a different one'),
            'password.custom' => translate('The password cannot contain white spaces.'),
        ]);

        if ($request->zone_id) {
            $zone = Zone::query()
                ->whereContains('coordinates', new Point($request->lat, $request->lng, POINT_SRID))
                ->where('id', $request->zone_id)
                ->first();
            if (!$zone) {
                $validator->getMessageBag()->add('latitude', translate('messages.coordinates_out_of_zone'));
            }
        }

        $data = json_decode($request->translations, true);

        if (count($data) < 1) {
            $validator->getMessageBag()->add('translations', translate('messages.Name and description in english is required'));
        }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $tag_ids = [];
        if ($request->tags != null) {
            $tags = explode(",", $request->tags);
        }
        if (isset($tags)) {
            foreach ($tags as $key => $value) {
                $tag = Tag::firstOrNew(
                    ['tag' => $value]
                );
                $tag->save();
                array_push($tag_ids, $tag->id);
            }
        }

        $vendor = new Vendor();
        $vendor->f_name = $request->fName;
        $vendor->l_name = $request->lName;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->password = bcrypt($request->password);
        $vendor->status = null;
        $vendor->save();

        $restaurant = new Restaurant;
        $restaurant->name = $data[0]['value'];
        $restaurant->phone = $request->phone;
        $restaurant->email = $request->email;
        $restaurant->logo = Helpers::upload(dir: 'restaurant/', format: 'png', image: $request->file('logo'));
        $restaurant->cover_photo = Helpers::upload(dir: 'restaurant/cover/', format: 'png', image: $request->file('cover_photo'));
        $restaurant->address = $data[1]['value'];

        $restaurant->latitude = $request->lat;
        $restaurant->longitude = $request->lng;
        $restaurant->vendor_id = $vendor->id;
        $restaurant->zone_id = $request->zone_id;
        $restaurant->tax = $request->vat;
        $restaurant->delivery_time = $request->min_delivery_time . '-' . $request->max_delivery_time . '-' . $request->delivery_time_type;
        $restaurant->status = 0;
        $restaurant->restaurant_model = 'none';

        if (isset($request->additional_data)  && count(json_decode($request->additional_data, true)) > 0) {
            $restaurant->additional_data = $request->additional_data;
        }

        $additional_documents = [];
        if ($request->additional_documents) {
            foreach ($request->additional_documents as $key => $imagedata) {
                $additional = [];
                foreach ($imagedata as $file) {
                    if (is_file($file)) {
                        $file_name = Helpers::upload('additional_documents/', $file->getClientOriginalExtension(), $file);
                        $additional[] = ['file' => $file_name, 'storage' => Helpers::getDisk()];
                    }
                    $additional_documents[$key] = $additional;
                }
            }
            $restaurant->additional_documents = json_encode($additional_documents);
        }

        $restaurant->save();
        $restaurant->tags()->sync($tag_ids);

        foreach ($data as $key => $i) {
            $data[$key]['translationable_type'] = 'App\Models\Restaurant';
            $data[$key]['translationable_id'] = $restaurant->id;
        }
        Translation::insert($data);

        $cuisine_ids = [];
        $cuisine_ids = json_decode($request->cuisine_ids, true);
        $restaurant?->cuisine()?->sync($cuisine_ids);
        try {
            $admin = Admin::where('role_id', 1)->first();
            $notification_status = Helpers::getNotificationStatusData('restaurant', 'restaurant_registration');
            if ($notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('registration_mail_status_restaurant') == '1') {
                Mail::to($request['email'])->send(new \App\Mail\VendorSelfRegistration('pending', $vendor->f_name . ' ' . $vendor->l_name));
            }

            $notification_status = null;
            $notification_status = Helpers::getNotificationStatusData('admin', 'restaurant_self_registration');
            if ($notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('restaurant_registration_mail_status_admin') == '1') {
                Mail::to($admin['email'])->send(new \App\Mail\RestaurantRegistration('pending', $vendor->f_name . ' ' . $vendor->l_name));
            }
        } catch (\Exception $ex) {
            info($ex->getMessage());
        }

        if (Helpers::subscription_check()) {
            if ($request->business_plan == 'subscription' && $request->package_id != null) {
                $restaurant->package_id = $request->package_id;
                $restaurant->save();

                return response()->json([
                    'restaurant_id' => $restaurant->id,
                    'package_id' => $restaurant->package_id,
                    'type' => 'subscription',
                    'message' => translate('messages.application_placed_successfully')
                ], 200);
            } elseif ($request->business_plan == 'commission') {
                $restaurant->restaurant_model = 'commission';
                $restaurant->save();
                return response()->json([
                    'restaurant_id' => $restaurant->id,
                    'type' => 'commission',
                    'message' => translate('messages.application_placed_successfully')
                ], 200);
            } else {
                return response()->json([
                    'restaurant_id' => $restaurant->id,
                    'type' => 'business_model_fail',
                    'message' => translate('messages.application_placed_successfully')
                ], 200);
            }
        } else {
            $restaurant->restaurant_model = 'commission';
            $restaurant->save();
            return response()->json([
                'restaurant_id' => $restaurant->id,
                'type' => 'commission',
                'message' => translate('messages.application_placed_successfully')
            ], 200);
        }

        return response()->json([
            'restaurant_id' => $restaurant->id,
            'message' => translate('messages.application_placed_successfully')
        ], 200);
    }



    private function restaurantSubscriptionCheck($restaurant, $vendor, $token)
    {


        if ($restaurant?->restaurant_model == 'none') {
            $vendor->auth_token = $token;
            $vendor?->save();
            return [
                'type' => 'subscribed',
                'code' => 200,
                'data' => [
                    'subscribed' => [
                        'restaurant_id' => $restaurant?->id,
                        'token' => $token,
                        'package_id' => $restaurant?->package_id,
                        'zone_wise_topic' => $restaurant?->zone?->restaurant_wise_topic,
                        'type' => 'new_join'
                    ]
                ]
            ];
        }


        if ($restaurant->status == 0 && $vendor->status == 0) {

            return [
                'type' => 'errors',
                'code' => 403,
                'data' => [
                    'errors' => [
                        ['code' => 'auth-002', 'message' => translate('messages.Your_registration_is_not_approved_yet._You_can_login_once_admin_approved_the_request')]
                    ]
                ]
            ];
        } elseif ($restaurant->status == 0 && $vendor->status == 1 && in_array($restaurant?->restaurant_model ,['subscription' ,'commission']) ) {

            return [
                'type' => 'errors',
                'code' => 403,
                'data' => [
                    'errors' => [
                        ['code' => 'auth-002', 'message' => translate('messages.Your_account_is_suspended')]
                    ]
                ]
            ];
        }


        if ($restaurant?->restaurant_model == 'subscription') {
            $restaurant_sub = $restaurant?->restaurant_sub;
            if (isset($restaurant_sub)) {
                if ($restaurant_sub?->mobile_app == 0) {
                    return [
                        'type' => 'errors',
                        'code' => 401,
                        'data' => [
                            'errors' => [
                                ['code' => 'no_mobile_app', 'message' => translate('messages.Your Subscription Plan is not Active for Mobile App')]
                            ]
                        ]
                    ];
                }
            }
        }


        if ($restaurant?->restaurant_model == 'unsubscribed' && isset($restaurant?->restaurant_sub_update_application)) {
            return null;
        }

        if ($restaurant?->restaurant_model == 'unsubscribed' && !isset($restaurant?->restaurant_sub_update_application)) {
            $vendor->auth_token = $token;
            $vendor?->save();
            return [
                'type' => 'subscribed',
                'code' => 200,
                'data' => [
                    'subscribed' => [
                        'restaurant_id' => $restaurant?->id,
                        'token' => $token,
                        'zone_wise_topic' => $restaurant?->zone?->restaurant_wise_topic,
                        'type' => 'new_join'
                    ]
                ]
            ];
        }
        return null;
    }
}
