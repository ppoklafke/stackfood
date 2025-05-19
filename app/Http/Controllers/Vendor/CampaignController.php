<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Admin;
use App\Models\Campaign;
use App\Models\ItemCampaign;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;


class CampaignController extends Controller
{
    function list(Request $request)
    {
        $key = explode(' ', $request['search']) ?? null;
        $campaigns=Campaign::with('restaurants')->running()->active()->latest()
        ->when(isset($key) , function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
        })

        ->paginate(config('default_pagination'));
        return view('vendor-views.campaign.list',compact('campaigns'));
    }
    function itemlist(Request $request)
    {
        $key = explode(' ', $request['search']) ?? null;
        $campaigns=ItemCampaign::where('restaurant_id', Helpers::get_restaurant_id())

        ->when(isset($key) , function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('vendor-views.campaign.food_list',compact('campaigns'));
    }

    public function remove_restaurant(Campaign $campaign, $restaurant)
    {
        $campaign?->restaurants()?->detach($restaurant);
        $campaign->save();
        Toastr::success(translate('messages.restaurant_remove_from_campaign'));
        return back();
    }
    public function addrestaurant(Campaign $campaign, $restaurant)
    {
        $campaign?->restaurants()?->attach($restaurant, ['campaign_status' => 'pending']);
        $campaign->save();
        try
        {
            $admin= Admin::where('role_id', 1)->first();
            $restaurant= Restaurant::where('id', $restaurant )->with('vendor')->first();
            $notification_status= Helpers::getNotificationStatusData('admin','campaign_join_request');

            if($notification_status?->mail_status == 'active' &&  config('mail.status') && Helpers::get_mail_status('campaign_request_mail_status_admin') == '1') {
                Mail::to($admin->email)->send(new \App\Mail\CampaignRequestMail($restaurant?->name));
                }
                $notification_status= null;
                $restaurant_notification_status= null;
                $notification_status= Helpers::getNotificationStatusData('restaurant','restaurant_campaign_join_request');
                $restaurant_notification_status= Helpers::getRestaurantNotificationStatusData($restaurant?->id,'restaurant_campaign_join_request');
            if($notification_status?->mail_status == 'active' && $restaurant_notification_status?->mail_status == 'active' &&  config('mail.status') && Helpers::get_mail_status('campaign_request_mail_status_restaurant') == '1') {
                Mail::to($restaurant?->vendor?->email)->send(new \App\Mail\VendorCampaignRequestMail($restaurant?->name,'pending'));
            }
        }
        catch(\Exception $e)
        {
            info($e->getMessage());
        }
        Toastr::success(translate('messages.restaurant_added_to_campaign'));
        return back();
    }


}
