<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\Models\Conversation;
use App\Models\UserInfo;
use App\Models\Message;
use App\Models\User;
use App\Models\Admin;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    public function list (Request $request)
    {
        if($request->query('tab')){
            $tab = $request->query('tab');
        }else{
            $tab = 'customer';
        }
        $conversations = Conversation::with(['sender', 'receiver', 'last_message'])->WhereUserType('admin')->WhereUserType($tab);

        if($request->query('key')) {
            $key = explode(' ', $request->get('key'));
            $conversations = $conversations->where(function($qu)use($key){
                    $qu->whereHas('sender',function($query)use($key){
                    foreach ($key as $value) {
                        $query->where('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%")->orWhere('phone', 'like', "%{$value}%");
                    }
                })
                ->orWhereHas('receiver',function($query1)use($key){
                    foreach ($key as $value) {
                        $query1->where('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%")->orWhere('phone', 'like', "%{$value}%");
                    }
                });
            });
        }
        $conversations = $conversations->orderBy('last_message_time', 'DESC')
        ->paginate(8);

        if ($request->ajax()) {
            $view = view('admin-views.messages.data',compact('conversations','tab'))->render();
            return response()->json(['html'=>$view]);
        }

        return view('admin-views.messages.index', compact('conversations','tab'));
    }

    public function view($conversation_id,$user_id)
    {
        $conversation = Conversation::find($conversation_id);
        $lastmessage = $conversation?->last_message;
        if($lastmessage && $lastmessage->sender_id == $user_id ) {
            $conversation->unread_message_count = 0;
            $conversation->save();
        }
        Message::where(['conversation_id' => $conversation->id])->where('sender_id',$user_id)->update(['is_seen' => 1]);
        $convs = Message::where(['conversation_id' => $conversation_id])->get();
        $receiver = UserInfo::find($user_id);
        $user = $receiver;
        return response()->json([
            'view' => view('admin-views.messages.partials._conversations', compact('convs', 'user', 'receiver'))->render()
        ]);
    }

    public function store(Request $request, $user_id)
    {
        if ($request->has('images')) {
            $validator = Validator::make($request->all(), [
                'images.*' => 'max:5120',
            ],[
                'images.*.max' => translate('Max File Upload limit is 5mb')
            ]);

            if ($validator->fails()) {
                $validator->getMessageBag()->add('images', translate('Max File Upload limit is 5mb'));
                return response()->json(['errors' => Helpers::error_processor($validator)]);
            }
            $image_name=[];
            foreach($request->images as $key=>$img)
            {
                $name = Helpers::upload(dir:'conversation/', format:$img->getClientOriginalExtension(), image:$img);
                array_push($image_name,['img'=>$name, 'storage'=> Helpers::getDisk()]);
            }
        } else {
            $image_name = null;
            $validator = Validator::make($request->all(), [
                'reply' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => Helpers::error_processor($validator)]);
            }
        }

        $fcm_token_web= null;
        $fcm_token= null;
        $error_message = null;
        $file_count=0;

        $admin = Admin::find(auth('admin')->id());
        $sender = UserInfo::where('admin_id',$admin->id)->first();
        if(!$sender){
            $sender = new UserInfo();
            $sender->admin_id = $admin->id;
            $sender->f_name = $admin->f_name;
            $sender->l_name = $admin->l_name;
            $sender->phone = $admin->phone;
            $sender->email = $admin->email;
            $sender->image = $admin->image;
            $sender->save();
        }

//        $user = User::find($user_id);
//        $fcm_token=$user->cm_firebase_token;
//        $receiver = UserInfo::where('user_id', $user->id)->first();
//        $user = $receiver;
//        if(!$receiver){
//            $receiver = new UserInfo();
//            $receiver->user_id = $user->id;
//            $receiver->f_name = $user->f_name;
//            $receiver->l_name = $user->l_name;
//            $receiver->phone = $user->phone;
//            $receiver->email = $user->email;
//            $receiver->image = $user->image;
//            $receiver->save();
//        }
        if($request->receiver_type == 'customer'){
            $receiver = UserInfo::where('user_id',$request->receiver_id)->first();
            $user = User::find($request->receiver_id);

            if(!$receiver){
                $receiver = new UserInfo();
                $receiver->user_id = $user->id;
                $receiver->f_name = $user->f_name;
                $receiver->l_name = $user->l_name;
                $receiver->phone = $user->phone;
                $receiver->email = $user->email;
                $receiver->image = $user->image;
                $receiver->save();
            }
            $receiver_id = $receiver->id;
            $fcm_token=$user->cm_firebase_token;

        }else if($request->receiver_type == 'vendor'){
            $receiver = UserInfo::where('vendor_id',$request->receiver_id)->first();
            $user = Vendor::find($request->receiver_id);
            if(!$receiver){
                $receiver = new UserInfo();
                $receiver->vendor_id = $user->id;
                $receiver->f_name = $user?->restaurants[0]?->getRawOriginal('name');
                $receiver->l_name = '';
                $receiver->phone = $user->phone;
                $receiver->email = $user->email;
                $receiver->image = $user?->restaurants[0]?->logo;
                $receiver->save();
            }

            $receiver_id = $receiver->id;
            $fcm_token=$user->firebase_token;
            $fcm_token_web=$user->fcm_token_web;

        }

        $conversation = Conversation::WhereConversation(0,$receiver_id)->first();


        if(!$conversation){
            $conversation = new Conversation;
            $conversation->sender_id = 0;
            $conversation->sender_type = 'admin';
            $conversation->receiver_id = $receiver_id;
            $conversation->receiver_type = 'user';
            $conversation->last_message_time = Carbon::now()->toDateTimeString();
            $conversation->save();

            $conversation= Conversation::find($conversation->id);
        }

        $message = new Message();
        $message->conversation_id = $conversation->id;
        $message->sender_id = $sender->id;
        $message->message = $request->reply;
        if($image_name && count($image_name)>0){
            $message->file = json_encode($image_name, JSON_UNESCAPED_SLASHES);
            $file_count= count($image_name);
        }
        try {
            if($message->save()) {
            $conversation->unread_message_count = $conversation->unread_message_count? $conversation->unread_message_count+1:1;
            $conversation->last_message_id=$message->id;
            $conversation->last_message_time = Carbon::now()->toDateTimeString();
            $conversation->save();
                if($request->receiver_type == 'vendor' || $request->receiver_type == 'delivery_man' || $request->receiver_type == 'customer' ) {
                    $data = [
                        'title' => translate('messages.message'),
                        'description' => $message?->message ??  $file_count.' '.translate('messages.Attachments'),
                        'order_id' => '',
                        'image' => '',
                        'message' => json_encode($message),
                        'type' => 'message',
                        'conversation_id' => $conversation->id,
                        'sender_type' => 'admin'
                    ];
                        Helpers::send_push_notif_to_device($fcm_token, $data);
                        if ($fcm_token_web) {
                            Helpers::send_push_notif_to_device($fcm_token_web, $data);
                        }
                    }
//                $data = [
//                    'title' =>translate('messages.message'),
//                    'description' =>translate('messages.message_description'),
//                    'order_id' => '',
//                    'image' => '',
//                    'message' => json_encode($message),
//                    'type'=> 'message',
//                    'conversation_id'=> $conversation->id,
//                    'sender_type'=> 'admin'
//                ];
//                Helpers::send_push_notif_to_device($fcm_token, $data);
            }

        } catch (\Exception $e) {
            info($e->getMessage());
            $error_message = $e->getMessage();
        }


        if($request->reply || $file_count > 0){
            $response_message=  translate('Message sent') ;
        }
        if( $request->has('images') && $file_count <= 0 ){
            $error_message =   $error_message  == null ? translate('messages.Unable_to_sent_Attachments') : $error_message ;
        }

        $convs = Message::where(['conversation_id' => $conversation->id])->get();
        $user = $receiver;
        return response()->json([
            'view' => view('admin-views.messages.partials._conversations', compact('convs', 'user', 'receiver'))->render(),
            'error_message' => $error_message ?? null,
            'response_message' => $response_message ?? null
        ]);
    }
}
