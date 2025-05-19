<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\ProductLogic;
use App\CentralLogics\RestaurantLogic;
use App\Http\Controllers\Controller;
use App\Models\Allergy;
use App\Models\Food;
use App\Models\Nutrition;
use App\Models\Restaurant;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function get_latest_products(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
            'category_id' => 'required',
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $type = $request->query('type', 'all');

        $products = ProductLogic::get_latest_products(limit:$request['limit'], offset:$request['offset'],restaurant_id: $request['restaurant_id'],category_id: $request['category_id'], type:$type);
        $products['products'] = Helpers::product_data_formatting(data:$products['products'],multi_data: true, trans:false,local: app()->getLocale());
        return response()->json($products, 200);
    }

    public function get_searched_products(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $search_bar_default_status = \App\Models\BusinessSetting::where('key', 'search_bar_default_status')->first();
        $search_bar_default_status = $search_bar_default_status ? $search_bar_default_status->value : 1;
        $search_bar_sort_by_unavailable = \App\Models\PriorityList::where('name', 'search_bar_sort_by_unavailable')->where('type','unavailable')->first();
        $search_bar_sort_by_unavailable = $search_bar_sort_by_unavailable ? $search_bar_sort_by_unavailable->value : '';
        $search_bar_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'search_bar_sort_by_temp_closed')->where('type','temp_closed')->first();
        $search_bar_sort_by_temp_closed = $search_bar_sort_by_temp_closed ? $search_bar_sort_by_temp_closed->value : '';

        $zone_id= json_decode($request->header('zoneId'), true);

        $key = isset($request['name']) && $request['name'] != 'null'  ?  explode(' ', $request['name']) : null;

        if(is_array($key)){
            $key = array_filter($key);
            $key = array_values($key);
        }

        $limit = $request['limit']??10;
        $offset = $request['offset']??1;
        $min = $request->query('min_price');
        $max = $request->query('max_price');
        $type = $request->query('type', 'all');
        $query = Food::Active()->type($type)
            ->whereHas('restaurant', function($q)use($zone_id){
                $q->whereIn('zone_id', $zone_id);
            })
            ->select(['food.*'])
            ->selectSub(function ($subQuery) {
                $subQuery->selectRaw('active as temp_available')
                    ->from('restaurants')
                    ->whereColumn('restaurants.id', 'food.restaurant_id');
            }, 'temp_available')
            ->selectSub(function ($subQuery) {
                $subQuery->selectRaw('IF(((select count(*) from `restaurant_schedule` where `restaurants`.`id` = `restaurant_schedule`.`restaurant_id` and `restaurant_schedule`.`day` = ? and `restaurant_schedule`.`opening_time` < ? and `restaurant_schedule`.`closing_time` > ?) > 0), true, false) as open', [now()->dayOfWeek, now()->format('H:i:s'), now()->format('H:i:s')])
                    ->from('restaurants')
                    ->whereColumn('restaurants.id', 'food.restaurant_id');
            }, 'open')
            ->when($request->category_id, function($query)use($request){
                $query->whereHas('category',function($q)use($request){
                    return $q->whereId($request->category_id)->orWhere('parent_id', $request->category_id);
                });
            })
            ->when($request->restaurant_id, function($query) use($request){
                return $query->where('restaurant_id', $request->restaurant_id);
            })

            ->when($min && $max, function($query)use($min,$max){
                $query->whereBetween('price',[$min,$max]);
            })
            ->when($request->new == 1, function($query){
                return $query->latest();
            })
            ->when( $request->popular == 1, function($query){
                return $query->popular();
            })
            ->when($request->rating == 1, function($query){
                return $query->has('reviews')->withCount('reviews')->orderBy('reviews_count','desc');
            })

            ->when($request->rating_1 == 1, function($query){
                $query->where('avg_rating', '>=' , 1);
            })
            ->when($request->rating_2 == 1, function($query){
                $query->where('avg_rating', '>=' , 2);
            })
            ->when($request->rating_3 == 1, function($query){
                $query->where('avg_rating', '>=' , 3);
            })
            ->when($request->rating_4 == 1, function($query){
                $query->where('avg_rating', '>=' , 4);
            })
            ->when($request->rating_3_plus == 1, function($query){
                $query->where('avg_rating', '>' , 3);
            })
            ->when(($request->rating_4_plus == 1 && !($request->rating_5  == 1 || $request->rating_3_plus == 1 ) || ($request->rating_4_plus == 1 && $request->rating_5  == 1 && $request->rating_3_plus != 1) ), function($query){
                $query->where('avg_rating', '>' , 4);
            })
            ->when($request->rating_5 == 1 && !($request->rating_4_plus  == 1 || $request->rating_3_plus == 1) , function($query){
                $query->where('avg_rating', '>=' , 5);
            })

            ->when($request->discounted == 1, function($query){
                return $query->where(function($q){
                    $q->whereHas('restaurant.discount' , function($query){
                        $query->validate();
                    })->orwhere('discount', '>' , 0);
                });
            })

            ->when(isset($key) , function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                    $q->orWhereHas('translations',function($query)use($key){
                        $query->where(function($q)use($key){
                            foreach ($key as $value) {
                                $q->where('value', 'like', "%{$value}%");
                            };
                        });
                    });
                    $q->orWhereHas('tags',function($query)use($key){
                        $query->where(function($q1)use($key){
                            foreach ($key as $value) {
                                $q1->where('tag', 'like', "%{$value}%");
                            };
                        });
                    });
                    $q->orWhereHas('restaurant.cuisine',function($query) use($key){
                        $query->where(function($q2)use($key){
                            foreach ($key as $value) {
                                $q2->where('name', 'like', "%{$value}%");
                            };
                        });
                    });

                    $q->orWhereHas('allergies',function($query)use($key){
                        $query->where(function($q)use($key){
                            foreach ($key as $value) {
                                $q->where('allergy', 'like', "%{$value}%");
                            };
                        });
                    });
                    $q->orWhereHas('nutritions',function($query)use($key){
                        $query->where(function($q)use($key){
                            foreach ($key as $value) {
                                $q->where('nutrition', 'like', "%{$value}%");
                            };
                        });
                    });


                });
            })

            ->when(isset($request->sort_by) && ($request->sort_by == 'asc' || $request->sort_by == 'desc'), function($query)use($request){
                            return $query->orderBy('name' , $request->sort_by);
                        })
            ->when(isset($request->sort_by) && ( $request->sort_by == 'low') , function($query)use($request){
                            return $query->orderBy('price' ,'asc', );
                        })
            ->when(isset($request->sort_by) && ( $request->sort_by == 'high') , function($query)use($request){
                            return $query->orderBy('price' ,'desc', );
                        })

            ->orderByRaw("FIELD(name, ?) DESC", [$request['name']]);

        if ($search_bar_default_status == '1'){
            $query = $query->latest();
        }elseif ($search_bar_default_status == '0'){
            $time = Carbon::now()->toTimeString();

            if($search_bar_sort_by_unavailable == 'remove'){
                $query = $query->available($time);
            }elseif($search_bar_sort_by_unavailable == 'last'){
                $query = $query->orderBy(DB::raw("CASE WHEN available_time_starts <= '$time' AND available_time_ends >= '$time' THEN 0 ELSE 1 END"));
            }

            if($search_bar_sort_by_temp_closed == 'remove'){
                $query = $query->having('temp_available', '>', 0);
            }elseif($search_bar_sort_by_temp_closed == 'last'){
                $query = $query->orderByDesc('temp_available');
            }
        }
        $products = $query->paginate($limit, ['*'], 'page', $offset);

        $prices = Food::Active()
            ->type($type)
            ->whereHas('restaurant', function($q) use ($zone_id) {
                $q->whereIn('zone_id', $zone_id);
            })
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        $data =  [
            'total_size' => $products->total(),
            'min_price' => (float)$prices?->min_price,
            'max_price' => (float)$prices?->max_price,
            'limit' => $limit,
            'offset' => $offset,
            'products' => $products->items()
        ];

        $data['products'] = Helpers::product_data_formatting(data:$data['products'],multi_data: true, trans:false,local: app()->getLocale());
        return response()->json($data, 200);
    }

    public function get_popular_products(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $type = $request->query('type', 'all');
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');
        $zone_id= json_decode($request->header('zoneId'), true);
        $products = ProductLogic::popular_products(zone_id:$zone_id, limit:$request['limit'],offset: $request['offset'],type: $type,longitude:$longitude,latitude:$latitude);
        $products['products'] = Helpers::product_data_formatting(data:$products['products'], multi_data:true, trans:false, local:app()->getLocale());
        return response()->json($products, 200);
    }

    public function get_most_reviewed_products(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $type = $request->query('type', 'all');
        $zone_id= json_decode($request->header('zoneId'), true);
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');
        $products = ProductLogic::most_reviewed_products(zone_id:$zone_id,limit: $request['limit'], offset:$request['offset'], type:$type,longitude:$longitude,latitude:$latitude);
        $products['products'] = Helpers::product_data_formatting(data:$products['products'],multi_data: true, trans:false, local:app()->getLocale());
        return response()->json($products, 200);
    }

    public function get_product($id)
    {
        try {
            $product = ProductLogic::get_product($id , request()?->campaign ? true : false);
            $product = Helpers::product_data_formatting(data:$product, multi_data:false, trans:false, local:app()->getLocale());
            return response()->json($product, 200);
        } catch (\Exception $e) {
            info($e->getMessage());
            return response()->json([
                'errors' => ['code' => 'product-001', 'message' => translate('messages.not_found')]
            ], 404);
        }
    }

    public function get_related_products($id)
    {
        if (Food::find($id)) {
            $products = ProductLogic::get_related_products(product_id:$id);
            $products = Helpers::product_data_formatting(data:$products,multi_data: true, trans:false, local:app()->getLocale());
            return response()->json($products, 200);
        }
        return response()->json([
            'errors' => ['code' => 'product-001', 'message' => translate('messages.not_found')]
        ], 404);
    }

    public function get_set_menus()
    {
        try {
            $products = Helpers::product_data_formatting(data:Food::active()->with(['rating'])->where(['set_menu' => 1, 'status' => 1])->get(),multi_data: true, trans:false, local:app()->getLocale());
            return response()->json($products, 200);
        } catch (\Exception $e) {
            info($e->getMessage());
            return response()->json([
                'errors' => ['code' => 'product-001', 'message' => 'Set menu not found!']
            ], 404);
        }
    }

    public function get_product_reviews($food_id)
    {
        $reviews = Review::with(['customer', 'food', 'restaurant'])->where(['food_id' => $food_id])->active()->get();

        $storage = [];
        foreach ($reviews as $item) {
            $item['attachment'] = json_decode($item['attachment']);
            $item['food_name'] = null;
            if($item?->food)
            {
                $item['food_name'] = $item?->food?->name;
                if(count($item?->food?->translations)>0)
                {
                    $translate = array_column($item->food->translations->toArray(), 'value', 'key');
                    $item['food_name'] = $translate['name'];
                }
            }
            unset($item['food']);
            array_push($storage, $item);
        }
        return response()->json($storage, 200);
    }

    public function get_product_rating($id)
    {
        try {
            $product = Food::find($id);
            $overallRating = ProductLogic::get_overall_rating(reviews:$product->reviews);
            return response()->json(floatval($overallRating[0]), 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 403);
        }
    }

    public function submit_product_review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_id' => 'required',
            'order_id' => 'required',
            'comment' => 'nullable',
            'rating' => 'required|numeric|max:5',
            'attachment.*' => 'nullable|max:2048',
        ]);

        $product = Food::find($request->food_id);
        if (isset($product) == false) {
            $validator->errors()->add('food_id', translate('messages.food_not_found'));
        }

        $multi_review = Review::where(['food_id' => $request->food_id, 'user_id' => $request?->user()?->id, 'order_id'=>$request->order_id])->first();
        if (isset($multi_review)) {
            return response()->json([
                'errors' => [
                    ['code'=>'review','message'=> translate('messages.already_submitted')]
                ]
            ], 403);
        } else {
            $review = new Review;
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $image_array = [];
        if (!empty($request->file('attachment'))) {
            foreach ($request->file('attachment') as $image) {
                if ($image != null) {
                    if (!Storage::disk('public')->exists('review')) {
                        Storage::disk('public')->makeDirectory('review');
                    }
                    array_push($image_array, Storage::disk('public')->put('review', $image));
                }
            }
        }

        $review->user_id = $request?->user()?->id;
        $review->food_id = $request->food_id;
        $review->order_id = $request->order_id;
        $review->comment = $request->comment;
        $review->rating = $request->rating;
        $review->attachment = json_encode($image_array);
        $review->save();

        if($product->restaurant)
        {
            $restaurant_rating = RestaurantLogic::update_restaurant_rating(ratings:$product?->restaurant?->rating, product_rating:$request->rating);
            $product->restaurant->rating = $restaurant_rating;
            $product?->restaurant?->save();
        }

        $product->rating = ProductLogic::update_rating(ratings:$product->rating, product_rating:$request->rating);
        $product->avg_rating = ProductLogic::get_avg_rating(rating:json_decode($product->rating, true));
        $product?->save();
        $product->increment('rating_count');

        return response()->json(['message' => translate('messages.review_submited_successfully')], 200);
    }


    public function get_recommended(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $type = $request->query('type', 'all');
        $key = explode(' ', $request['name']);
        $zone_id= json_decode($request->header('zoneId'), true);
        $products = ProductLogic::recommended_products(zone_id:$zone_id, restaurant_id:$request->restaurant_id,limit:$request['limit'],offset: $request['offset'],type: $type ,name:$key );
        $products['products'] = Helpers::product_data_formatting(data:$products['products'],multi_data: true, trans:false, local:app()->getLocale());
        return response()->json($products, 200);
    }




    public function food_or_restaurant_search(Request $request){

        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        if (!$request->hasHeader('longitude') || !$request->hasHeader('latitude')) {
            $errors = [];
            array_push($errors, ['code' => 'longitude-latitude', 'message' => translate('messages.longitude-latitude_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        $zone_id= json_decode($request->header('zoneId'), true);
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $key = explode(' ', $request->name);

        $foods = Food::active()->whereHas('restaurant', function($q)use($zone_id){
            $q->whereIn('zone_id', $zone_id)->Weekday();
        })
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
            $q->orWhereHas('translations',function($query)use($key){
                $query->where(function($q)use($key){
                    foreach ($key as $value) {
                        $q->where('value', 'like', "%{$value}%");
                    };
                });
            });
            $q->orWhereHas('tags',function($query)use($key){
                $query->where(function($q)use($key){
                    foreach ($key as $value) {
                        $q->where('tag', 'like', "%{$value}%");
                    };
                });
            });
            $q->orWhereHas('allergies',function($query)use($key){
                $query->where(function($q)use($key){
                    foreach ($key as $value) {
                        $q->where('allergy', 'like', "%{$value}%");
                    };
                });
            });
            $q->orWhereHas('nutritions',function($query)use($key){
                $query->where(function($q)use($key){
                    foreach ($key as $value) {
                        $q->where('nutrition', 'like', "%{$value}%");
                    };
                });
            });
        })
        ->orderByRaw("FIELD(name, ?) DESC", [$request->name])
        ->limit(50)
        ->get(['id','name','image']);

        $restaurants = Restaurant::withOpen($longitude,$latitude)->whereIn('zone_id', $zone_id)->weekday()
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
            $q->orwhereHas('cuisine', function ($query) use ($key){
                $query->where(function($q)use($key){
                    foreach ($key as $value) {
                        $q->where('name', 'like', "%{$value}%");
                    };
                });
            });
        })
        ->active()
        ->limit(50)
        ->orderByRaw("FIELD(name, ?) DESC", [$request->name])
        ->select(['id','name','logo'])
        ->get();

        return [
            'foods' => $foods,
            'restaurants' => $restaurants
        ];

    }

    public function get_restaurant_popular_products(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $type = $request->query('type', 'all');
        $key = explode(' ', $request['name']);

        $zone_id= json_decode($request->header('zoneId'), true);
        $products = ProductLogic::get_restaurant_popular_products(zone_id:$zone_id,restaurant_id: $request->restaurant_id, type: $type,name:$key);
        $products = Helpers::product_data_formatting(data:$products,multi_data: true, trans:false, local:app()->getLocale());
        return response()->json($products, 200);
    }


    public function recommended_most_reviewed(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $type = $request->query('type', 'all');
        $key = explode(' ', $request['name']);

        $zone_id= json_decode($request->header('zoneId'), true);
        $products = ProductLogic::recommended_most_reviewed(zone_id:$zone_id,restaurant_id: $request->restaurant_id, type: $type,name:$key);


        $products = Helpers::product_data_formatting(data:$products,multi_data: true, trans:false, local:app()->getLocale());
        return response()->json($products, 200);
    }

    public function getAllergyNameList(){
        $names= Allergy::select(['allergy'])->pluck('allergy');
        return response()->json($names, 200);
    }
    public function getNutritionNameList(){
        $names= Nutrition::select(['nutrition'])->pluck('nutrition');
        return response()->json($names, 200);
    }
}
