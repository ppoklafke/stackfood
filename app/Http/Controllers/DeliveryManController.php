<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\DataSetting;
use App\Models\DeliveryMan;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class DeliveryManController extends Controller
{
    public function create()
    {
        $status = BusinessSetting::where('key', 'toggle_dm_registration')->first()?->value;
        if($status == 1)
        {
            $page_data=   DataSetting::Where('type' , 'deliveryman')->where('key' , 'deliveryman_page_data')->first()?->value;
            $page_data =  $page_data ? json_decode($page_data ,true)  :[];
            return view('dm-registration',compact('page_data')) ;
        }
        Toastr::error(translate('messages.not_found'));
        return back();
    }

    public function store(Request $request)
    {
        $status = BusinessSetting::where('key', 'toggle_dm_registration')->first()?->value;
        if ($status == 0) {
            return response()->json([
                'errors' => [
                    ['code' => 'toggle_dm_registration', 'message' => translate('messages.not_found')]
                ]
            ]);
        }

        $validator = Validator::make($request->all(), [
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'identity_number' => 'required|max:30',
            'email' => 'required|email|unique:delivery_men',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|unique:delivery_men',
            'zone_id' => 'required',
            'vehicle_id' => 'required',
            'earning' => 'required',
            'image' => 'nullable|max:2048',
            'identity_image.*' => 'nullable|max:2048',
            // 'additional_documents' => 'nullable|array|max:5',
            // 'additional_documents.*' => 'nullable|max:2048',

            'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
            ], [
                'f_name.required' => translate('messages.first_name_is_required'),
                'zone_id.required' => translate('messages.select_a_zone'),
                'vehicle_id.required' => translate('messages.select_a_vehicle'),
                'earning.required' => translate('messages.select_dm_type'),
                'password.min_length' => translate('The password must be at least :min characters long'),
                'password.mixed' => translate('The password must contain both uppercase and lowercase letters'),
                'password.letters' => translate('The password must contain letters'),
                'password.numbers' => translate('The password must contain numbers'),
                'password.symbols' => translate('The password must contain symbols'),
                'password.uncompromised' => translate('The password is compromised. Please choose a different one'),
                'password.custom' => translate('The password cannot contain white spaces.'),

                // 'additional_documents.max' => translate('You_can_chose_max_5_files_only'),
            ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if ($request->has('image')) {
            $image_name = Helpers::upload(dir:'delivery-man/',format: 'png', image:$request->file('image'));
        } else {
            $image_name = 'def.png';
        }

        $id_img_names = [];
        if (!empty($request->file('identity_image'))) {
            foreach ($request->identity_image as $img) {
                $identity_image = Helpers::upload(dir:'delivery-man/',format: 'png', image:$img);
                array_push($id_img_names, ['img'=>$identity_image, 'storage'=> Helpers::getDisk()]);
            }
            $identity_image = json_encode($id_img_names);
        } else {
            $identity_image = json_encode([]);
        }

        $dm = New DeliveryMan();
        $dm->f_name = $request->f_name;
        $dm->l_name = $request->l_name;
        $dm->email = $request->email;
        $dm->phone = $request->phone;
        $dm->identity_number = $request->identity_number;
        $dm->identity_type = $request->identity_type;
        $dm->zone_id = $request->zone_id;
        $dm->vehicle_id = $request->vehicle_id;
        $dm->identity_image = $identity_image;
        $dm->image = $image_name;
        $dm->active = 0;
        $dm->earning = $request->earning;
        $dm->password = bcrypt($request->password);
        $dm->application_status= 'pending';


        if(isset($request->additional_data)  && count($request->additional_data) > 0){
            $dm->additional_data = json_encode($request->additional_data) ;
        }

        $additional_documents = [];
        if ($request->additional_documents) {
            foreach ($request->additional_documents as $key => $data) {
                $additional = [];
                foreach($data as $file){
                    if(is_file($file)){
                        $file_name = Helpers::upload('additional_documents/dm/', $file->getClientOriginalExtension(), $file);
                        $additional[] = ['file'=>$file_name, 'storage'=> Helpers::getDisk()];
                    }
                    $additional_documents[$key] = $additional;
                }
            }
            $dm->additional_documents = json_encode($additional_documents);
        }


        $dm->save();
        try{
            $admin= Admin::where('role_id', 1)->first();

            $notification_status= Helpers::getNotificationStatusData('deliveryman','deliveryman_registration');

            if( $notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('registration_mail_status_dm') == '1'){
                Mail::to($request->email)->send(new \App\Mail\DmSelfRegistration('pending', $dm->f_name.' '.$dm->l_name));
                }
                $notification_status=null;
            $notification_status= Helpers::getNotificationStatusData('admin','deliveryman_self_registration');
            if( $notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('dm_registration_mail_status_admin') == '1'){
                Mail::to($admin['email'])->send(new \App\Mail\DmRegistration('pending', $dm->f_name.' '.$dm->l_name));
            }
        }catch(\Exception $exception){
            info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
        }

        return response()->json([], 200);
    }
}
