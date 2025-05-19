<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Models\Admin;
use App\Models\Translation;
use Illuminate\Http\Request;

use App\Models\Advertisement;
use App\Rules\WordValidation;
use App\CentralLogics\Helpers;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{

    public function index(Request $request)
    {
        $restaurant_id = $request?->vendor?->restaurants[0]?->id;
        $limit = $request['limit']??25;
        $offset = $request['offset']??1;

        $key = explode(' ', $request['search']);
        $adds=Advertisement::where('restaurant_id',$restaurant_id)

        ->when($request?->ads_type === 'pending',function($query){
            $query->where('status','pending');
        })
        ->when($request?->ads_type === 'denied',function($query){
            $query->where('status','denied');
        })
        ->when($request?->ads_type === 'paused',function($query){
            $query->where('status','paused');
        })
        ->when($request?->ads_type === 'running',function($query){
            $query->valid();
        })
        ->when($request?->ads_type === 'approved',function($query){
            $query->approved();
        })
        ->when($request?->ads_type === 'expired',function($query){
            $query->expired();
        })
        ->when($request?->search ,function($query)use($key) {
            foreach ($key as $value) {
            $query->where(function($query) use ($value){
                    $query->where('id', 'like', "%{$value}%");
                });
            };
        })
        ->paginate($limit, ['*'], 'page', $offset);


        $all=Advertisement::where('restaurant_id',$restaurant_id)
        ->count();
        $running=Advertisement::where('restaurant_id',$restaurant_id)
        ->valid()->count();
        $pending=Advertisement::where('restaurant_id',$restaurant_id)
        ->where('status','pending')->count();
        $denied=Advertisement::where('restaurant_id',$restaurant_id)
        ->where('status','denied')->count();
        $paused=Advertisement::where('restaurant_id',$restaurant_id)
        ->where('status','paused')->count();
        $approved=Advertisement::where('restaurant_id',$restaurant_id)
        ->approved()->count();
        $expired=Advertisement::where('restaurant_id',$restaurant_id)
        ->expired()->count();

        $data = [
            'total_size' => $adds->total(),
            'limit' => $limit,
            'offset' => $offset,
            'all' => $all,
            'running' => $running,
            'pending' => $pending,
            'denied' => $denied,
            'paused' => $paused,
            'approved' => $approved,
            'expired' => $expired,
            'adds' => $adds->items()
        ];

        return response()->json($data,200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|max:255',
            'description' => 'nullable|max:65000',
            'dates' => 'required',
            'advertisement_type' => 'required|in:video_promotion,restaurant_promotion',

            'cover_image' => 'required_if:advertisement_type,restaurant_promotion|image|mimes:jpg,png,jpeg,webp|max:2048',
            'profile_image' => 'required_if:advertisement_type,restaurant_promotion|image|mimes:jpg,png,jpeg,webp|max:2048',
            'video_attachment' => 'required_if:advertisement_type,video_promotion|file|mimes:mp4,mkv,webm|max:5120',

        ], [
            'video_attachment.required_if' => translate('Your_video_attachment_is_missing'),
            'cover_image.required_if' => translate('Your_cover_image_is_missing'),
            'profile_image.required_if' => translate('Your_profile_image_is_missing'),

        ]);
        $restaurant_id = $request?->vendor?->restaurants[0]?->id;

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $dateRange = $request->dates;
        list($startDate, $endDate) = explode(' - ', $dateRange);
        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($startDate));
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($endDate));
        $startDate = $startDate->startOfDay();
        $endDate = $endDate->endOfDay();

        if ($startDate < \Carbon\Carbon::today()) {
            $validator->getMessageBag()->add('date', translate('messages.Start date must be greater than or equal to today'));
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($endDate < $startDate) {
            $validator->getMessageBag()->add('date', translate('messages.End date must be greater than start date'));
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $data = json_decode($request?->translations, true);

        if (count($data) < 1) {
            $validator->getMessageBag()->add('translations', translate('messages.Title and description in english is required'));
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);

        }

        $advertisement = New Advertisement();
        $advertisement->restaurant_id = $restaurant_id;
        $advertisement->add_type = $request->advertisement_type;
        $advertisement->title = $data[0]['value'];
        $advertisement->description = $data[1]['value'];
        $advertisement->start_date = $startDate;
        $advertisement->end_date = $endDate;
        $advertisement->priority = null;
        $advertisement->is_rating_active = $request->advertisement_type == 'restaurant_promotion' ?  $request?->is_rating_active ?? 0 : 0;
        $advertisement->is_review_active = $request->advertisement_type == 'restaurant_promotion' ?  $request?->is_review_active ?? 0 : 0;
        $advertisement->is_paid =  0;
        $advertisement->created_by_id = $request?->vendor?->id;
        $advertisement->created_by_type =  'App\Models\Vendor';
        $advertisement->status = 'pending';

        $advertisement->cover_image = $request->has('cover_image') &&  $request->advertisement_type == 'restaurant_promotion' ?  Helpers::upload(dir: 'advertisement/', format:$request->file('cover_image')->getClientOriginalExtension(), image:$request->file('cover_image')) : null;
        $advertisement->profile_image = $request->has('profile_image') &&  $request->advertisement_type == 'restaurant_promotion' ?  Helpers::upload(dir: 'advertisement/', format:$request->file('profile_image')->getClientOriginalExtension(), image:$request->file('profile_image')) : null;
        $advertisement->video_attachment = $request->has('video_attachment') &&  $request->advertisement_type == 'video_promotion' ?  Helpers::upload(dir: 'advertisement/', format:$request->file('video_attachment')->getClientOriginalExtension(), image:$request->file('video_attachment')) : null;
        $advertisement->save();
        foreach ($data as $key=>$item) {
            $data[$key]['translationable_type'] = 'App\Models\Advertisement';
            $data[$key]['translationable_id'] = $advertisement->id;
        }
        Translation::insert($data);


        try {
            $notification_status= Helpers::getNotificationStatusData('admin','advertisement_add');

            if($notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('new_advertisement_mail_status_admin') == '1'){
                Mail::to(Admin::where('role_id', 1)->first()?->email)->send(new \App\Mail\AdminAdversitementMail($advertisement?->restaurant?->name,'new_advertisement' ,$advertisement->id));
        }
        } catch (\Throwable $th) {
            //throw $th;
        }


        return response()->json(['message' => translate('messages.advertisement_added_successfully_&_submited_for_admin\'s_approval')], 200);

    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'nullable|max:255',
            'description' => 'nullable|max:65000',
            'dates' => 'required',
            'advertisement_type' => 'required|in:video_promotion,restaurant_promotion',

            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'video_attachment' => 'nullable|file|mimes:mp4,mkv,webm|max:5120',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $dateRange = $request->dates;
        list($startDate, $endDate) = explode(' - ', $dateRange);
        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($startDate));
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($endDate));
        $startDate = $startDate->startOfDay();
        $endDate = $endDate->endOfDay();


        if ($endDate < $startDate) {
            $validator->getMessageBag()->add('date', translate('messages.End date must be greater than start date'));
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }


        $data = json_decode($request->translations, true);

        if (count($data) < 1) {
            $validator->getMessageBag()->add('translations', translate('messages.Title and description in english is required'));
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);

        }
        $advertisement =Advertisement::where('id',$id)->first();

        $advertisement->title = $data[0]['value'];
        $advertisement->description = $data[1]['value'];
        $advertisement->start_date = $startDate;
        $advertisement->end_date = $endDate;

        $advertisement->is_rating_active = $request->advertisement_type == 'restaurant_promotion' ?  $request?->is_rating_active ?? 0 : 0;
        $advertisement->is_review_active = $request->advertisement_type == 'restaurant_promotion' ?  $request?->is_review_active ?? 0 : 0;
        $advertisement->is_updated = $advertisement->status == 'pending' ? 0 : 1;

        $advertisement->status ='pending';


        if( $advertisement->add_type != $request->advertisement_type){

            if($request->advertisement_type == 'video_promotion' &&  !$request->has('video_attachment')){
                $validator->getMessageBag()->add('file_required', translate('messages.You_must_need_to_add_a_promotional_video_file'));
                return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            }

            if($request->advertisement_type == 'restaurant_promotion' &&  (!$request->has('cover_image') || !$request->has('profile_image'))  ){
                $validator->getMessageBag()->add('file_required', translate('messages.You_must_need_to_add_cover_&_profile_image'));
                return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            }


            if($advertisement->cover_image && $request->advertisement_type == 'video_promotion')
            {
                Helpers::check_and_delete('advertisement/' , $advertisement->cover_image);
            }
            if($advertisement->profile_image && $request->advertisement_type == 'video_promotion')
            {
                Helpers::check_and_delete('advertisement/' , $advertisement->profile_image);
            }
            if($advertisement->video_attachment && $request->advertisement_type == 'restaurant_promotion')
            {
                Helpers::check_and_delete('advertisement/' , $advertisement->video_attachment);
            }
        }

        $advertisement->add_type = $request->advertisement_type;
        $advertisement->cover_image = $request->has('cover_image') &&  $request->advertisement_type == 'restaurant_promotion' ? Helpers::update(dir:'advertisement/', old_image: $advertisement->cover_image, format:$request->file('cover_image')->getClientOriginalExtension(), image: $request->file('cover_image')) : $advertisement->cover_image;
        $advertisement->profile_image = $request->has('profile_image') &&  $request->advertisement_type == 'restaurant_promotion' ? Helpers::update(dir:'advertisement/', old_image: $advertisement->profile_image, format:$request->file('profile_image')->getClientOriginalExtension(), image: $request->file('profile_image')) : $advertisement->profile_image;
        $advertisement->video_attachment = $request->has('video_attachment') &&  $request->advertisement_type == 'video_promotion' ? Helpers::update(dir:'advertisement/', old_image: $advertisement->video_attachment, format:$request->file('video_attachment')->getClientOriginalExtension(), image: $request->file('video_attachment')) : $advertisement->video_attachment;
        $advertisement->save();

        foreach ($data as $key=>$item) {
            Translation::updateOrInsert(
                ['translationable_type' => 'App\Models\Advertisement',
                    'translationable_id' => $advertisement->id,
                    'locale' => $item['locale'],
                    'key' => $item['key']],
                ['value' => $item['value']]
            );
        }

        try {
            $notification_status= Helpers::getNotificationStatusData('admin','advertisement_update');

            if($notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('update_advertisement_mail_status_admin') == '1'){
                    Mail::to(Admin::where('role_id', 1)->first()?->email)->send(new \App\Mail\AdminAdversitementMail($advertisement?->restaurant?->name,'update_advertisement' ,$advertisement->id));
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response()->json(['message' => translate('messages.advertisement_updated_successfully_&_submited_for_admin\'s_approval')], 200);
    }


    public function show(Request $request,$id)
    {
        $restaurant_id = $request?->vendor?->restaurants[0]?->id;
        $advertisement =Advertisement::where('id',$id)->where('restaurant_id', $restaurant_id)->first();
        return response()->json($advertisement,200);
    }

    public function destroy(Request $request,$id)
    {
        $restaurant_id = $request?->vendor?->restaurants[0]?->id;
        $advertisement =Advertisement::where('id',$id)->where('restaurant_id', $restaurant_id)->first();

        if($advertisement?->cover_image)
        {
            Helpers::check_and_delete('advertisement/' , $advertisement->cover_image);
        }
        if($advertisement?->profile_image)
        {
            Helpers::check_and_delete('advertisement/' , $advertisement->profile_image);
        }
        if($advertisement?->video_attachment)
        {
            Helpers::check_and_delete('advertisement/' , $advertisement->video_attachment);
        }
        $advertisement?->translations()?->delete();
        $advertisement?->delete();

        $adds=Advertisement::whereNotNull('priority')->orderByRaw('ISNULL(priority), priority ASC')->get();

        $newPriority = 1;
        foreach ($adds as $advertisement) {
            $advertisement->priority = $newPriority++;
            $advertisement->save();
        }


        return response()->json(['message' => translate('messages.advertisement_deleted')], 200);
    }

    public function status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required|in:paused,approved',
            'pause_note' => ['required_if:status,paused', new WordValidation],
            'cancellation_note' => ['required_if:status,denied', new WordValidation],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $restaurant_id = $request?->vendor?->restaurants[0]?->id;
        $advertisement =Advertisement::where('id',$request->id)->where('restaurant_id', $restaurant_id)->first();
        $advertisement->status = in_array($request->status,['paused','approved']) ? $request->status : $advertisement->status;
        $advertisement->pause_note = $request?->pause_note ?? null;
        $advertisement?->save();
        if( $request->status == 'paused'){
            return response()->json(['message' => translate('messages.Advertisement_Paused_Successfully')], 200);
        }
        elseif($request->status == 'approved'){
            return response()->json(['message' => translate('messages.Advertisement_Resumed_Successfully')], 200);
        }


        return response()->json(['message' => translate('messages.Advertisement_status_changed')], 200);
    }



    public function copyAddPost(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'nullable|max:255',
            'description' => 'nullable|max:65000',
            'dates' => 'required',
            'advertisement_type' => 'required|in:video_promotion,restaurant_promotion',

            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'video_attachment' => 'nullable|file|mimes:mp4,mkv,webm|max:5120',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $advertisement= Advertisement::where('id', $request->id)->first();
        $restaurant_id = $request?->vendor?->restaurants[0]?->id;


        $dateRange = $request->dates;
        list($startDate, $endDate) = explode(' - ', $dateRange);
        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($startDate));
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($endDate));
        $startDate = $startDate->startOfDay();
        $endDate = $endDate->endOfDay();


        if ($endDate < $startDate) {
            $validator->getMessageBag()->add('date', translate('messages.End date must be greater than start date'));
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }


        $data = json_decode($request->translations, true);

        if (count($data) < 1) {
            $validator->getMessageBag()->add('translations', translate('messages.Title and description in english is required'));
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);

        }


        $newAdvertisement = New Advertisement();

        $newAdvertisement->restaurant_id = $restaurant_id;
        $newAdvertisement->add_type = $request->advertisement_type;
        $newAdvertisement->title = $data[0]['value'];
        $newAdvertisement->description = $data[1]['value'];
        $newAdvertisement->start_date = $startDate;
        $newAdvertisement->end_date = $endDate;
        $newAdvertisement->priority = null;
        $newAdvertisement->is_rating_active = $request->advertisement_type == 'restaurant_promotion' ?  $request?->is_rating_active ?? 0 : 0;
        $newAdvertisement->is_review_active = $request->advertisement_type == 'restaurant_promotion' ?  $request?->is_review_active ?? 0 : 0;
        $newAdvertisement->is_paid =  0;
        $newAdvertisement->created_by_id = $request?->vendor?->id;
        $newAdvertisement->created_by_type =  'App\Models\Vendor';
        $newAdvertisement->status = 'pending';

    if($request->advertisement_type == 'restaurant_promotion' ){
        if($request->has('cover_image')){
            $newAdvertisement->cover_image =  Helpers::upload(dir: 'advertisement/', format:$request->file('cover_image')->getClientOriginalExtension(), image:$request->file('cover_image'));
        } else{
            $newAdvertisement->cover_image =$this->copyAttachment($advertisement ,'cover_image');
        }
        if($request->has('profile_image')){
            $newAdvertisement->profile_image =  Helpers::upload(dir: 'advertisement/', format:$request->file('profile_image')->getClientOriginalExtension(), image:$request->file('profile_image'));
        } else{
            $newAdvertisement->profile_image =$this->copyAttachment($advertisement ,'profile_image');
        }

    }

    if($request->advertisement_type == 'video_promotion' ){
        if($request->has('video_attachment')){
            $newAdvertisement->video_attachment =  Helpers::upload(dir: 'advertisement/', format:$request->file('video_attachment')->getClientOriginalExtension(), image:$request->file('video_attachment'));
        } else{
            $newAdvertisement->video_attachment =$this->copyAttachment($advertisement ,'video_attachment');
        }
    }

        $newAdvertisement->save();

        foreach ($data as $key=>$item) {
            $data[$key]['translationable_type'] = 'App\Models\Advertisement';
            $data[$key]['translationable_id'] = $newAdvertisement->id;
        }
        Translation::insert($data);

            try {
            $notification_status= Helpers::getNotificationStatusData('admin','advertisement_add');

            if($notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('new_advertisement_mail_status_admin') == '1'){
                Mail::to(Admin::where('role_id', 1)->first()?->email)->send(new \App\Mail\AdminAdversitementMail($advertisement?->restaurant?->name,'new_advertisement' ,$advertisement->id));
        }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response()->json(['message' => translate('messages.Advertisement_Added_Successfully')], 200);






    }


    private function copyAttachment($attachment , $fileKeyName)
    {

        $oldDisk = 'public';
            if ($attachment->storage && count($attachment->storage) > 0) {
                foreach ($attachment->storage as $value) {
                    if ($value['key'] == $fileKeyName) {
                        $oldDisk = $value['value'];
                        }
                }
            }
                    $oldPath = "advertisement/{$attachment->{$fileKeyName}}";
                    $newFileName =Carbon::now()->toDateString() . "-" . uniqid() . '.'.explode('.',$attachment->{$fileKeyName})[1];
                    $newPath = "advertisement/{$newFileName}";
                    $dir = 'advertisement/';
                    $newDisk = Helpers::getDisk();

            try{
                if (Storage::disk($oldDisk)->exists($oldPath)) {
                    if (!Storage::disk($newDisk)->exists($dir)) {
                        Storage::disk($newDisk)->makeDirectory($dir);
                    }
                    $fileContents = Storage::disk($oldDisk)->get($oldPath);
                    Storage::disk($newDisk)->put($newPath, $fileContents);
                }
            } catch (\Exception $e) {
            }

            return $newFileName ?? null;

    }
}
