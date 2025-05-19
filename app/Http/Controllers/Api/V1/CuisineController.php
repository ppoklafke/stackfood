<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cuisine;
use App\Models\Restaurant;
use App\Models\PriorityList;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CuisineController extends Controller
{
    public function get_all_cuisines()
    {

        $cuisine_list_default_status = BusinessSetting::where('key', 'cuisine_list_default_status')->first()?->value ?? 1;
        $cuisine_list_sort_by_general = PriorityList::where('name', 'cuisine_list_sort_by_general')->where('type','general')->first()?->value ?? '';

        $Cuisines = Cuisine::where('status',1)

        ->when($cuisine_list_default_status  == 1  || ($cuisine_list_default_status  != 1 &&  $cuisine_list_sort_by_general == 'latest') , function ($query) {
            $query->latest();
        })



        ->when($cuisine_list_default_status  != 1 &&  $cuisine_list_sort_by_general == 'oldest', function ($query) {
            $query->oldest();
        })
        ->when($cuisine_list_default_status  != 1 &&  $cuisine_list_sort_by_general == 'a_to_z', function ($query) {
            $query->orderby('name');
        })
        ->when($cuisine_list_default_status  != 1 &&  $cuisine_list_sort_by_general == 'z_to_a', function ($query) {
            $query->orderby('name','desc');
        })
        ->when($cuisine_list_default_status  != 1 &&  $cuisine_list_sort_by_general == 'restaurant_count', function ($query) {
            $query->withCount('restaurants')
            ->orderByDesc('restaurants_count');
        })

        ->get();




        return response()->json( ['Cuisines' => $Cuisines], 200);
    }
    public function get_restaurants(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cuisine_id' => 'required',
            'limit' => 'required',
            'offset' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 203);
        }
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');

        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $all_restaurant_default_status = \App\Models\BusinessSetting::where('key', 'all_restaurant_default_status')->first();
        $all_restaurant_default_status = $all_restaurant_default_status ? $all_restaurant_default_status->value : 1;
        $all_restaurant_sort_by_general = \App\Models\PriorityList::where('name', 'all_restaurant_sort_by_general')->where('type','general')->first();
        $all_restaurant_sort_by_general = $all_restaurant_sort_by_general ? $all_restaurant_sort_by_general->value : '';
        $all_restaurant_sort_by_unavailable = \App\Models\PriorityList::where('name', 'all_restaurant_sort_by_unavailable')->where('type','unavailable')->first();
        $all_restaurant_sort_by_unavailable = $all_restaurant_sort_by_unavailable ? $all_restaurant_sort_by_unavailable->value : '';
        $all_restaurant_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'all_restaurant_sort_by_temp_closed')->where('type','temp_closed')->first();
        $all_restaurant_sort_by_temp_closed = $all_restaurant_sort_by_temp_closed ? $all_restaurant_sort_by_temp_closed->value : '';


        $zone_id= json_decode($request->header('zoneId'), true);
        $limit = $request->query('limit', 1);
        $offset = $request->query('offset', 1);

        $query=Restaurant::whereIn('zone_id',$zone_id)
        ->with(['discount'=>function($q){
            return $q->validate();
        }])
        ->cuisine($request->cuisine_id)->active()->WithOpen($longitude,$latitude)->withCount('foods');

        if($all_restaurant_default_status == '0') {
            if($all_restaurant_sort_by_temp_closed == 'remove'){
                $query = $query->where('active', '>', 0);
            }elseif($all_restaurant_sort_by_temp_closed == 'last'){
                $query = $query->orderByDesc('active');
            }

            if($all_restaurant_sort_by_unavailable == 'remove'){
                $query = $query->having('open', '>', 0);
            }elseif($all_restaurant_sort_by_unavailable == 'last'){
                $query = $query->orderBy('open', 'desc');
            }

            if($all_restaurant_sort_by_general == 'latest_created') {
                $query = $query->latest();
            }elseif($all_restaurant_sort_by_general == 'nearest_first') {
                $query = $query->orderBy('distance');
            }elseif($all_restaurant_sort_by_general == 'rating') {
                $query = $query->selectSub(function ($query) {
                    $query->selectRaw('AVG(reviews.rating)')
                        ->from('reviews')
                        ->join('food', 'food.id', '=', 'reviews.food_id')
                        ->whereColumn('food.restaurant_id', 'restaurants.id')
                        ->groupBy('food.restaurant_id');
                }, 'avg_r')->orderBy('avg_r', 'desc');
            }elseif($all_restaurant_sort_by_general == 'review_count') {
                $query = $query->withCount('reviews')->orderBy('reviews_count', 'desc');
            }elseif($all_restaurant_sort_by_general == 'order_count') {
                $query = $query->withCount('orders')->orderBy('orders_count', 'desc');
            } elseif ($all_restaurant_sort_by_general == 'a_to_z') {
                $query = $query->orderBy('name');
            } elseif ($all_restaurant_sort_by_general == 'z_to_a') {
                $query = $query->orderByDesc('name');
            }

        }

        $restaurants = $query->paginate($limit, ['*'], 'page', $offset);

        $restaurants_data = Helpers::restaurant_data_formatting($restaurants->items(), true);

        $data = [
            'total_size' => $restaurants->total(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $restaurants_data,
        ];
        return response()->json($data, 200);

    }
}
