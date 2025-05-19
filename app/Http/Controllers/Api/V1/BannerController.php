<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Campaign;
use App\CentralLogics\BannerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
{
    public function get_banners(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json(['errors' => $errors], 403);
        }

        $longitude = $request->header('longitude')??0;
        $latitude = $request->header('latitude')??0;
        $zone_id = json_decode($request->header('zoneId'), true);

        $bannersCacheKey = 'banners_' . md5(json_encode($zone_id));
        $campaignsCacheKey = 'campaigns_' . md5(json_encode([$zone_id, $longitude, $latitude]));

        $banners = Cache::remember($bannersCacheKey, now()->addMinutes(20), function () use ($zone_id) {
            return BannerLogic::get_banners($zone_id);
        });

        $campaigns = Cache::remember($campaignsCacheKey, now()->addMinutes(20), function () use ($zone_id, $longitude, $latitude) {
            return Campaign::whereHas('restaurants', function ($query) use ($zone_id) {
                $query->whereIn('zone_id', $zone_id)->Active()->where('campaign_status', 'confirmed');
            })->with('restaurants', function ($query) use ($zone_id, $longitude, $latitude) {
                return $query->WithOpen($longitude, $latitude)
                    ->whereIn('zone_id', $zone_id)
                    ->where('campaign_status', 'confirmed')
                    ->where('status', 1);
            })
                ->running()
                ->active()
                ->get();
        });

        try {
            return response()->json([
                'campaigns' => Helpers::basic_campaign_data_formatting($campaigns, true),
                'banners' => $banners
            ], 200);
        } catch (\Exception $e) {
            info($e->getMessage());
            return response()->json([], 200);
        }
    }
}
