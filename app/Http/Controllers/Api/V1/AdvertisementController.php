<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Advertisement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdvertisementController extends Controller
{
    public function get_adds(Request $request)
    {
        $zone_ids= $request->header('zoneId');
        $zone_ids=  json_decode($zone_ids, true)?? [];

        $cacheKey = 'advertisements_' . md5(json_encode($zone_ids));

        $Advertisement = Cache::remember($cacheKey, now()->addMinutes(20), function () use ($zone_ids) {
            $Advertisement= Advertisement::valid()->with('restaurant')

                ->when(count($zone_ids) > 0, function($query) use($zone_ids) {
                    $query->wherehas('restaurant', function($query) use($zone_ids){
                        $query->whereIn('zone_id',$zone_ids)->active();
                    });
                })

                ->orderByRaw('ISNULL(priority), priority ASC')
                ->get();

            try {
                $Advertisement->each(function ($advertisement) {
                    $advertisement->reviews_comments_count = (int) $advertisement->restaurant->reviews_comments()->count();
                    $reviewsInfo = $advertisement->restaurant->reviews()
                        ->selectRaw('avg(reviews.rating) as average_rating, count(reviews.id) as total_reviews, food.restaurant_id')
                        ->groupBy('food.restaurant_id')
                        ->first();

                    $advertisement->average_rating = (float)  $reviewsInfo?->average_rating ?? 0;
                });
            } catch (\Exception $e) {
                info($e->getMessage());
            }
            return $Advertisement;
        });

        return response()->json($Advertisement, 200);
    }

}
