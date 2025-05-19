<?php

namespace App\CentralLogics;

use App\Models\Banner;
use App\Models\Food;
use App\Models\Restaurant;
use App\CentralLogics\Helpers;

class BannerLogic
{
    public static function get_banners($zone_id)
    {
        $banners = Banner::active()->whereIn('zone_id', $zone_id)->get();
        $data = [];
        foreach($banners as $banner)
        {
            if($banner->type=='restaurant_wise')
            {
                $restaurant = Restaurant::Active()->where(['id'=> $banner->data])->first();
                if (!$restaurant || !in_array($restaurant->zone_id, (array) $zone_id)) {
                    continue;
                }
                if($restaurant){
                    $data[]=[
                        'id'=>$banner->id,
                        'title'=>$banner->title,
                        'type'=>$banner->type,
                        'image'=>$banner->image,
                        'restaurant'=> $restaurant?Helpers::restaurant_data_formatting(data:$restaurant, multi_data:false):null,
                        'food'=>null,
                        'image_full_url'=>$banner->image_full_url,
                    ];
                }
            }
            if($banner->type=='item_wise')
            {
                $food = Food::wherehas('restaurant', function($query){
                    $query->Active();
                })->where(['id'=> $banner->data,'status' => 1])->first();
                if($food){
                    $data[]=[
                        'id'=>$banner->id,
                        'title'=>$banner->title,
                        'type'=>$banner->type,
                        'image'=>$banner->image,
                        'restaurant'=> null,
                        'food'=> $food?Helpers::product_data_formatting(data:$food, multi_data:false, trans:false, local:app()->getLocale()):null,
                        'image_full_url'=>$banner->image_full_url,
                    ];
                }
            }
        }
        return $data;
    }
}
