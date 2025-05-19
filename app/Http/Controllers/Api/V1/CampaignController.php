<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\ItemCampaign;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CampaignController extends Controller
{
    public function get_basic_campaigns(Request $request){
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 200);
        }
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');
        $zone_id= json_decode($request->header('zoneId'), true);
        try {
            $campaigns = Campaign::whereHas('restaurants', function($query)use($zone_id){
                $query->whereIn('zone_id', $zone_id);
            })
            ->with('restaurants',function($query)use($zone_id,$longitude,$latitude){
                return $query->WithOpen($longitude,$latitude)->whereIn('zone_id', $zone_id)->wherePivot('campaign_status', 'confirmed')->active();
            })
            ->running()->active()->get();
            $campaigns=Helpers::basic_campaign_data_formatting($campaigns, true);
            return response()->json($campaigns, 200);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()], 200);
        }
    }
    public function basic_campaign_details(Request $request){
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 200);
        }
        $zone_id= json_decode($request->header('zoneId'), true);

        $validator = Validator::make($request->all(), [
            'basic_campaign_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        try {
            $longitude= $request->header('longitude');
            $latitude= $request->header('latitude');
            $campaign = Campaign::with(['restaurants'=>function($q)use($zone_id){
                $q->whereIn('zone_id', $zone_id);
            }])
            ->with('restaurants',function($query)use($zone_id,$longitude,$latitude){
                return $query->WithOpen($longitude,$latitude)->withcount('foods')->with(['discount'=>function($q){
                    return $q->validate();
                }])->whereIn('zone_id', $zone_id)->wherePivot('campaign_status', 'confirmed')->active();
            })
            ->running()->active()->whereId($request->basic_campaign_id)->first();

            $campaign=Helpers::basic_campaign_data_formatting($campaign, false);

            $campaign['restaurants'] = Helpers::restaurant_data_formatting($campaign['restaurants'], true);

            return response()->json($campaign, 200);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()], 500);
        }
    }
    public function get_item_campaigns(Request $request){
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 200);
        }
        $campaign_food_default_status = \App\Models\BusinessSetting::where('key', 'campaign_food_default_status')->first();
        $campaign_food_default_status = $campaign_food_default_status ? $campaign_food_default_status->value : 1;
        $campaign_food_sort_by_general = \App\Models\PriorityList::where('name', 'campaign_food_sort_by_general')->where('type','general')->first();
        $campaign_food_sort_by_general = $campaign_food_sort_by_general ? $campaign_food_sort_by_general->value : '';
        $campaign_food_sort_by_unavailable = \App\Models\PriorityList::where('name', 'campaign_food_sort_by_unavailable')->where('type','unavailable')->first();
        $campaign_food_sort_by_unavailable = $campaign_food_sort_by_unavailable ? $campaign_food_sort_by_unavailable->value : '';
        $campaign_food_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'campaign_food_sort_by_temp_closed')->where('type','temp_closed')->first();
        $campaign_food_sort_by_temp_closed = $campaign_food_sort_by_temp_closed ? $campaign_food_sort_by_temp_closed->value : '';
        $zone_id= json_decode($request->header('zoneId'), true);
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');
        try {
            $query = ItemCampaign::with('restaurant')
            ->Active()->whereHas('restaurant', function($q) use ($zone_id) {
                $q->whereIn('zone_id', $zone_id)->Weekday()->active();
            })
                ->select(['item_campaigns.*'])
                // ->leftJoin('restaurants', 'item_campaigns.restaurant_id', '=', 'restaurants.id')
                ->selectSub(function ($subQuery) {
                    $subQuery->selectRaw('active as temp_available')
                        ->from('restaurants')
                        ->whereColumn('restaurants.id', 'item_campaigns.restaurant_id');
                }, 'temp_available')
                ->selectSub(function ($subQuery) {
                    $subQuery->selectRaw('IF(((select count(*) from `restaurant_schedule` where `restaurants`.`id` = `restaurant_schedule`.`restaurant_id` and `restaurant_schedule`.`day` = ? and `restaurant_schedule`.`opening_time` < ? and `restaurant_schedule`.`closing_time` > ?) > 0), true, false) as open', [now()->dayOfWeek, now()->format('H:i:s'), now()->format('H:i:s')])
                        ->from('restaurants')
                        ->whereColumn('restaurants.id', 'item_campaigns.restaurant_id');
                }, 'open');
            if($campaign_food_default_status == '1'){
                $query = $query->running();
            }else{
                if($campaign_food_sort_by_unavailable == 'remove'){
                    $query = $query->running()->having('open', '>', 0);
                }elseif($campaign_food_sort_by_unavailable == 'last'){
                    $query = $query->orderByRaw("CASE WHEN start_date <= CURDATE() AND end_date >= CURDATE() AND start_time <= CURTIME() AND end_time >= CURTIME() THEN 0 ELSE 1 END")
                    ->orderByDesc('open');
                }

                if($campaign_food_sort_by_temp_closed == 'remove'){
                    $query = $query->having('temp_available', '>', 0);
                }elseif($campaign_food_sort_by_temp_closed == 'last'){
                    $query = $query->orderByDesc('temp_available');
                }

                if ($campaign_food_sort_by_general == 'nearest_first') {
                    $query = $query->selectSub(function ($subQuery) use ($longitude, $latitude) {
                        $subQuery->selectRaw('ST_Distance_Sphere(point(longitude, latitude), point(?, ?)) as distance', [$longitude, $latitude])
                            ->from('restaurants')
                            ->whereColumn('restaurants.id', 'item_campaigns.restaurant_id');
                    }, 'distance')
                        ->orderBy('distance');
                } elseif ($campaign_food_sort_by_general == 'order_count') {
                    $query = $query->withCount('orderdetails')->orderByDesc('orderdetails_count');
                } elseif ($campaign_food_sort_by_general == 'a_to_z') {
                    $query = $query->orderBy('title');
                } elseif ($campaign_food_sort_by_general == 'z_to_a') {
                    $query = $query->orderByDesc('title');
                } elseif ($campaign_food_sort_by_general == 'nearest_end_first') {
                    $query = $query->orderBy('end_date');
                } elseif ($campaign_food_sort_by_general == 'latest_created') {
                    $query = $query->latest();
                }
            }

            $campaigns =  $query->get();
            $campaigns= Helpers::product_data_formatting($campaigns, true, false, app()->getLocale());
            return response()->json($campaigns, 200);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()], 200);
        }
    }
}
