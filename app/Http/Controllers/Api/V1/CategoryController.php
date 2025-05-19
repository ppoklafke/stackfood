<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Food;
use App\Models\Category;
use App\Models\PriorityList;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\CentralLogics\CategoryLogic;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function get_categories(Request $request)
    {
        try {
            $category_list_default_status = BusinessSetting::where('key', 'category_list_default_status')->first()?->value ?? 1;
            $category_list_sort_by_general = PriorityList::where('name', 'category_list_sort_by_general')->where('type','general')->first()?->value ?? '';

            $zone_id=  $request->header('zoneId') ? json_decode($request->header('zoneId'), true) : [];
            $name= $request->query('name');
            $categories = Category::withCount(['products','childes'])->with(['childes' => function($query)  {
                $query->withCount(['products','childes']);
            }])
            ->where(['position'=>0,'status'=>1])

            ->when($name, function($q)use($name){
                $key = explode(' ', $name);
                $q->where(function($q)use($key){
                    foreach ($key as $value){
                        $q->orWhere('name', 'like', '%'.$value.'%')->orWhere('slug', 'like', '%'.$value.'%');
                    }
                    return $q;
                });
            })

            ->when($category_list_default_status  == 1 , function ($query) {
                $query->orderBy('priority','desc');
            })


            ->when($category_list_default_status  != 1 &&  $category_list_sort_by_general == 'latest', function ($query) {
                $query->latest();
            })
            ->when($category_list_default_status  != 1 &&  $category_list_sort_by_general == 'oldest', function ($query) {
                $query->oldest();
            })
            ->when($category_list_default_status  != 1 &&  $category_list_sort_by_general == 'a_to_z', function ($query) {
                $query->orderby('name');
            })
            ->when($category_list_default_status  != 1 &&  $category_list_sort_by_general == 'z_to_a', function ($query) {
                $query->orderby('name','desc');
            })


            ->get();



            if(count($zone_id) > 0){
                foreach ($categories as $category) {
                        $productCountQuery = Food::active()
                        ->whereHas('restaurant', function ($query) use ($zone_id) {
                            $query->whereIn('zone_id', $zone_id);
                        })
                        ->whereHas('category',function($q)use($category){
                            return $q->whereId($category->id)->orWhere('parent_id', $category->id);
                        })
                        ->withCount('orders');

                        $productCount = $productCountQuery->count();
                        $orderCount = $productCountQuery->sum('order_count');

                        $category['products_count'] = $productCount;
                        $category['order_count'] = $orderCount;
                    // unset($category['childes']);
                }
                if($category_list_default_status  != 1 &&  $category_list_sort_by_general == 'order_count'){

                    $categories = $categories->sortByDesc('order_count')->values()->all();
                }


                return response()->json($categories, 200);
            }

            return response()->json(Helpers::category_data_formatting($categories, true), 200);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    public function get_childes($id)
    {
        try {
            $categories = Category::when(is_numeric($id),function ($qurey) use($id){
                $qurey->where(['parent_id' => $id,'status'=>1]);
                })
                ->when(!is_numeric($id),function ($qurey) use($id){
                    $qurey->where(['slug' => $id,'status'=>1]);
                })
            ->orderBy('priority','desc')->get();
            return response()->json(Helpers::category_data_formatting($categories, true), 200);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()], 200);
        }
    }

    public function get_products($id, Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $additional_data=[
            'category_id' =>  $id,
            'zone_id' => json_decode($request->header('zoneId'), true),
            'limit' =>  $request['limit'] ?? 25,
            'offset' =>  $request['offset'] ?? 1,
            'type' =>  $request->query('type', 'all') ?? 'all',
            'veg' =>  $request->veg ?? 0,
            'non_veg' =>  $request->non_veg ?? 0,
            'new' =>  $request->new ?? 0,
            'avg_rating' => $request->avg_rating ?? 0,
            'top_rated' =>  $request->top_rated ?? 0,
            'start_price' => json_decode($request->price)[0] ?? 0,
            'end_price' => json_decode($request->price)[1] ?? 0,
            'longitude' =>$request->header('longitude') ?? 0,
            'latitude' => $request->header('latitude') ?? 0,
        ];


        $data = CategoryLogic::products($additional_data);
        $data['products'] = Helpers::product_data_formatting($data['products'] , true, false, app()->getLocale());

        if(auth('api')->user() !== null){
            $customer_id =auth('api')->user()->id;
            Helpers::visitor_log('category',$customer_id,$id,false);
        }

        return response()->json($data, 200);
    }


    public function get_restaurants($id, Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $additional_data=[
            'category_id' =>  $id,
            'zone_id' => json_decode($request->header('zoneId'), true),
            'limit' =>  $request['limit'] ?? 25,
            'offset' =>  $request['offset'] ?? 1,
            'type' =>  $request->query('type', 'all') ?? 'all',
            'longitude' =>$request->header('longitude') ?? 0,
            'latitude' => $request->header('latitude') ?? 0,
            'veg' =>  $request->veg ?? 0,
            'non_veg' =>  $request->non_veg ?? 0,
            'new' =>  $request->new ?? 0,
            'avg_rating' => $request->avg_rating ?? 0,
            'top_rated' =>  $request->top_rated ?? 0,
        ];


        $data = CategoryLogic::restaurants($additional_data);
        $data['restaurants'] = Helpers::restaurant_data_formatting($data['restaurants'] , true);

        return response()->json($data, 200);
    }



    public function get_all_products($id,Request $request)
    {
        try {
            return response()->json(Helpers::product_data_formatting(CategoryLogic::all_products($id), true, false, app()->getLocale()), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
