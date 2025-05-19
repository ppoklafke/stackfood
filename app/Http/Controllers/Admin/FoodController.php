<?php

namespace App\Http\Controllers\Admin;

use App\Models\Allergy;
use App\Models\Nutrition;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Food;
use App\Models\Review;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Support\Str;
use App\Models\ItemCampaign;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Exports\FoodListExport;
use App\Scopes\RestaurantScope;
use App\Exports\FoodReviewExport;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\ProductLogic;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\RestaurantFoodExport;
use App\Models\Variation;
use App\Models\VariationOption;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    public function index()
    {
        $categories = Category::where(['position' => 0])->get();
    return view('admin-views.product.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name.0' => 'required',
            'name.*' => 'max:191',
            'category_id' => 'required',
            'image' => 'nullable|max:2048',
            'price' => 'required|numeric|between:.01,999999999999.99',
            'discount' => 'required|numeric|min:0',
            'restaurant_id' => 'required',
            'description.*' => 'max:1000',
            'veg'=>'required'
        ], [
            'description.*.max' => translate('messages.description_length_warning'),
            'name.0.required' => translate('messages.item_name_required'),
            'category_id.required' => translate('messages.category_required'),
            'veg.required'=>translate('messages.item_type_is_required')
        ]);


        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('messages.discount_can_not_be_more_than_or_equal'));
        }

        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        $tag_ids = [];
        if ($request->tags != null) {
            $tags = explode(",", $request->tags);
        }
        if(isset($tags)){
            foreach ($tags as $key => $value) {
                $tag = Tag::firstOrNew(
                    ['tag' => $value]
                );
                $tag->save();
                array_push($tag_ids,$tag->id);
            }
        }

        $nutrition_ids = [];
        if ($request->nutritions != null) {
            $nutritions = $request->nutritions;
        }
        if (isset($nutritions)) {
            foreach ($nutritions as $key => $value) {
                $nutrition = Nutrition::firstOrNew(
                    ['nutrition' => $value]
                );
                $nutrition->save();
                array_push($nutrition_ids, $nutrition->id);
            }
        }
        $allergy_ids = [];
        if ($request->allergies != null) {
            $allergies = $request->allergies;
        }
        if (isset($allergies)) {
            foreach ($allergies as $key => $value) {
                $allergy = Allergy::firstOrNew(
                    ['allergy' => $value]
                );
                $allergy->save();
                array_push($allergy_ids, $allergy->id);
            }
        }

        $food = new Food;
        $food->name = $request->name[array_search('default', $request->lang)];

        $category = [];
        if ($request->category_id != null) {
            $category[] = [
                'id' => $request->category_id,
                'position' => 1,
            ];
        }
        if ($request->sub_category_id != null) {
            $category[] = [
                'id' => $request->sub_category_id,
                'position' => 2,
            ];
        }
        if ($request->sub_sub_category_id != null) {
            $category[] = [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ];
        }

        $food->category_ids = json_encode($category);
        $food->category_id = $request?->sub_category_id ?? $request?->category_id;
        $food->description =  $request->description[array_search('default', $request->lang)];
        $food->choice_options = json_encode([]);
        $food->variations = json_encode([]);
        $food->price = $request->price;
        $food->image = Helpers::upload(dir: 'product/', format:'png', image:$request->file('image'));
        $food->available_time_starts = $request->available_time_starts;
        $food->available_time_ends = $request->available_time_ends;
        $food->discount =  $request->discount ?? 0;
        $food->discount_type = $request->discount_type;

        $food->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        $food->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
        $food->restaurant_id = $request->restaurant_id;
        $food->veg = $request->veg;
        $food->item_stock = $request?->item_stock ?? 0;
        $food->stock_type = $request->stock_type;
        $food->maximum_cart_quantity = $request->maximum_cart_quantity;
        $food->is_halal =  $request->is_halal ?? 0;


        if(isset($request->options))
        {
            foreach(array_values($request->options) as $key=>$option)
            {
                if($option['min'] > 0 &&  $option['min'] > $option['max']  ){
                    $validator->getMessageBag()->add('name', translate('messages.minimum_value_can_not_be_greater_then_maximum_value'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if(!isset($option['values'])){
                    $validator->getMessageBag()->add('name', translate('messages.please_add_options_for').$option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if($option['max'] > count($option['values'])  ){
                    $validator->getMessageBag()->add('name', translate('messages.please_add_more_options_or_change_the_max_value_for').$option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
            }

            $food->save();

            foreach(array_values($request->options) as $key=>$option)
            {
                $variation=  New Variation ();
                $variation->food_id =$food->id;
                $variation->name = $option['name'];
                $variation->type = $option['type'];
                $variation->min = $option['min'] ?? 0;
                $variation->max = $option['max'] ?? 0;
                $variation->is_required =   data_get($option, 'required') == 'on' ? true : false;
                $variation->save();

                foreach(array_values($option['values']) as $value)
                {
                    $VariationOption=  New VariationOption ();
                    $VariationOption->food_id =$food->id;
                    $VariationOption->variation_id =$variation->id;
                    $VariationOption->option_name = $value['label'];
                    $VariationOption->option_price = $value['optionPrice'];
                    $VariationOption->stock_type = $request?->stock_type ?? 'unlimited' ;
                    $VariationOption->total_stock = data_get($value, 'total_stock') == null || $VariationOption->stock_type == 'unlimited' ? 0 : data_get($value, 'total_stock');
                    $VariationOption->save();
                }
            }
        }
        else{
            $food->save();
        }


        $food->tags()->sync($tag_ids);
        $food->nutritions()->sync($nutrition_ids);
        $food->allergies()->sync($allergy_ids);

        Helpers::add_or_update_translations(request: $request, key_data:'name' , name_field:'name' , model_name: 'Food' ,data_id: $food->id,data_value: $food->name);
        Helpers::add_or_update_translations(request: $request, key_data:'description' , name_field:'description' , model_name: 'Food' ,data_id: $food->id,data_value: $food->description);

        return response()->json([], 200);
    }

    public function view($id)
    {
        $product = Food::withoutGlobalScope(RestaurantScope::class)->findOrFail($id);
        $reviews=Review::where(['food_id'=>$id])->with('customer')->latest()->paginate(config('default_pagination'));
        return view('admin-views.product.view', compact('product','reviews'));
    }

    public function edit($id)
    {
        $product = Food::withoutGlobalScope(RestaurantScope::class)->withoutGlobalScope('translate')->with('translations')->findOrFail($id);

        if(!$product)
        {
            Toastr::error(translate('messages.food_not_found'));
            return back();
        }
        $product_category = json_decode($product->category_ids);
        $categories = Category::where(['parent_id' => 0])->get();
        return view('admin-views.product.edit', compact('product', 'product_category', 'categories'));
    }

    public function status(Request $request)
    {
        $product = Food::withoutGlobalScope(RestaurantScope::class)->findOrFail($request->id);
        $product->status = $request->status;
        $product->save();

        if($request->status != 1){
            $product?->carts()?->delete();
        }
        Toastr::success(translate('messages.food_status_updated'));
        return back();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'array',
            'name.0' => 'required',
            'name.*' => 'max:191',
            'category_id' => 'required',
            'price' => 'required|numeric|between:.01,999999999999.99',
            'restaurant_id' => 'required',
            'veg' => 'required',
            'description' => 'array',
            'description.*' => 'max:1000',
            'discount' => 'required|numeric|min:0',
            'image' => 'nullable|max:2048',
        ], [
            'description.*.max' => translate('messages.description_length_warning'),
            'name.0.required' => translate('messages.item_name_required'),
            'category_id.required' => translate('messages.category_required'),
            'veg.required'=>translate('messages.item_type_is_required'),
        ]);


        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('messages.discount_can_not_be_more_than_or_equal'));
        }

        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }


        $tag_ids = [];
        if ($request->tags != null) {
            $tags = explode(",", $request->tags);
        }
        if(isset($tags)){
            foreach ($tags as $key => $value) {
                $tag = Tag::firstOrNew(
                    ['tag' => $value]
                );
                $tag->save();
                array_push($tag_ids,$tag->id);
            }
        }

        $nutrition_ids = [];
        if ($request->nutritions != null) {
            $nutritions = $request->nutritions;
        }
        if (isset($nutritions)) {
            foreach ($nutritions as $key => $value) {
                $nutrition = Nutrition::firstOrNew(
                    ['nutrition' => $value]
                );
                $nutrition->save();
                array_push($nutrition_ids, $nutrition->id);
            }
        }
        $allergy_ids = [];
        if ($request->allergies != null) {
            $allergies = $request->allergies;
        }
        if (isset($allergies)) {
            foreach ($allergies as $key => $value) {
                $allergy = Allergy::firstOrNew(
                    ['allergy' => $value]
                );
                $allergy->save();
                array_push($allergy_ids, $allergy->id);
            }
        }

        $p = Food::withoutGlobalScope(RestaurantScope::class)->find($id);

        $p->name = $request->name[array_search('default', $request->lang)];
        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }

        $p->category_id = $request->sub_category_id ?? $request->category_id;
        $p->category_ids = json_encode($category);
        $p->description = $request->description[array_search('default', $request->lang)];

        $p->choice_options = json_encode([]);
        $p->variations = json_encode([]);

        if(isset($request->options))
        {
            foreach(array_values($request->options) as $key=>$option)
            {
                if($option['min'] > 0 &&  $option['min'] > $option['max']  ){
                    $validator->getMessageBag()->add('name', translate('messages.minimum_value_can_not_be_greater_then_maximum_value'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if(!isset($option['values'])){
                    $validator->getMessageBag()->add('name', translate('messages.please_add_options_for').$option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if($option['max'] > count($option['values'])  ){
                    $validator->getMessageBag()->add('name', translate('messages.please_add_more_options_or_change_the_max_value_for').$option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }

                $variation=Variation::updateOrCreate([
                    'id'=> $option['variation_id'] ?? null,
                    'food_id'=> $p->id,
                    ],[
                        "name" => $option['name'],
                        "type" => $option['type'],
                        "min" => $option['min'] ?? 0,
                        "max" => $option['max'] ?? 0,
                        "is_required" => data_get($option, 'required') == 'on' ? true : false,
                    ]);

                foreach(array_values($option['values']) as $value)
                {
                    VariationOption::updateOrCreate([
                        'id'=> $value['option_id'] ?? null,
                        'food_id'=> $p->id,
                        'variation_id'=> $variation->id,
                    ],[
                        "option_name" =>$value['label'],
                        "option_price" => $value['optionPrice'],
                        "total_stock" =>data_get($value, 'total_stock') == null ||  $request?->stock_type == 'unlimited' ? 0 : data_get($value, 'total_stock'),
                        "stock_type" => $request?->stock_type ?? 'unlimited' ,
                        "sell_count" =>0 ,
                    ]);
                }
            }

        }
        if($request?->removedVariationOptionIDs && is_string($request?->removedVariationOptionIDs)){
            VariationOption::whereIn('id',explode(',',$request->removedVariationOptionIDs))->delete();
        }
        if($request?->removedVariationIDs && is_string($request?->removedVariationIDs)){
            VariationOption::whereIn('variation_id',explode(',',$request->removedVariationIDs))->delete();
            Variation::whereIn('id',explode(',',$request->removedVariationIDs))->delete();
        }

        $slug = Str::slug($request->name[array_search('default', $request->lang)]);
        $p->slug = $p->slug? $p->slug :"{$slug}{$p->id}";
        //combinations end
        $p->price = $request->price;
        $p->image = $request->has('image') ? Helpers::update(dir:'product/', old_image: $p->image, format:'png', image: $request->file('image')) : $p->image;
        $p->available_time_starts = $request->available_time_starts;
        $p->available_time_ends = $request->available_time_ends;

        $p->discount = $request->discount ?? 0;
        $p->discount_type = $request->discount_type;
        $p->item_stock = $request?->item_stock ?? 0;
        $p->stock_type = $request->stock_type;
        $p->sell_count = 0;

        $p->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        $p->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
        $p->restaurant_id = $request->restaurant_id;
        $p->veg = $request->veg;
        $p->maximum_cart_quantity = $request->maximum_cart_quantity;
        $p->is_halal =  $request->is_halal ?? 0;

        $p->save();
        $p->tags()->sync($tag_ids);
        $p->nutritions()->sync($nutrition_ids);
        $p->allergies()->sync($allergy_ids);


        Helpers::add_or_update_translations(request: $request, key_data:'name' , name_field:'name' , model_name: 'Food' ,data_id: $p->id,data_value: $p->name);
        Helpers::add_or_update_translations(request: $request, key_data:'description' , name_field:'description' , model_name: 'Food' ,data_id: $p->id,data_value: $p->description);

        return response()->json([], 200);
    }

    public function delete(Request $request)
    {
        $product = Food::withoutGlobalScope(RestaurantScope::class)->withoutGlobalScope('translate')->find($request->id);

        if($product->image)
        {
            Helpers::check_and_delete('product/' , $product['image']);
        }
        $product?->translations()?->delete();
        $product?->carts()?->delete();
        $product?->newVariationOptions()?->delete();
        $product?->newVariations()?->delete();
        $product->delete();
        Toastr::success(translate('messages.product_deleted_successfully'));
        return back();
    }



    public function variant_price(Request $request)
    {
        if($request->item_type=='food')
        {
            $product = Food::withoutGlobalScope(RestaurantScope::class)->find($request->id);
        }
        else
        {
            $product = ItemCampaign::find($request->id);
        }
        // $product = Food::withoutGlobalScope(RestaurantScope::class)->find($request->id);
        $str = '';
        $quantity = 0;
        $price = 0;
        $addon_price = 0;

        foreach (json_decode($product->choice_options) as $key => $choice) {
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if($request['addon_id'])
        {
            foreach($request['addon_id'] as $id)
            {
                $addon_price+= $request['addon-price'.$id]*$request['addon-quantity'.$id];
            }
        }

        if ($str != null) {
            $count = count(json_decode($product->variations));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variations)[$i]->type == $str) {
                    $price = json_decode($product->variations)[$i]->price - Helpers::product_discount_calculate($product, json_decode($product->variations)[$i]->price,$product->restaurant);
                }
            }
        } else {
            $price = $product->price - Helpers::product_discount_calculate($product, $product->price,$product->restaurant);
        }

        return array('price' => Helpers::format_currency(($price * $request->quantity)+$addon_price));
    }
    public function get_categories(Request $request)
    {
        $cat = Category::where(['parent_id' => $request->parent_id])->get();
        $res = '<option value="' . 0 . '" disabled selected>---'.translate('messages.Select').'---</option>';
        foreach ($cat as $row) {
            if ($row->id == $request->sub_category) {
                $res .= '<option value="' . $row->id . '" selected >' . $row->name . '</option>';
            } else {
                $res .= '<option value="' . $row->id . '">' . $row->name . '</option>';
            }
        }
        return response()->json([
            'options' => $res,
        ]);
    }

    public function get_foods(Request $request)
    {
        $foods = Food::withoutGlobalScope(RestaurantScope::class)->with('restaurant')->whereHas('restaurant', function($query)use($request){
            $query->where('zone_id', $request->zone_id);
        })->get();
        $res = '';
        if(count($foods)>0 && !$request->data)
        {
            $res = '<option value="' . 0 . '" disabled selected>---'.translate('messages.Select').'---</option>';
        }

        foreach ($foods as $row) {
            $res .= '<option value="'.$row->id.'" ';
            if($request->data)
            {
                $res .= in_array($row->id, $request->data)?'selected ':'';
            }
            $res .= '>'.$row->name.' ('.$row->restaurant->name.')'. '</option>';
        }
        return response()->json([
            'options' => $res,
        ]);
    }

    public function list(Request $request)
    {
        $key = explode(' ', $request['search']);
        $restaurant_id = $request->query('restaurant_id', 'all');
        $category_id = $request->query('category_id', 'all');
        $type = $request->query('type', 'all');
        $foods = Food::withoutGlobalScope(RestaurantScope::class)
        ->with(['restaurant','category.parent'])
        ->when(is_numeric($restaurant_id), function($query)use($restaurant_id){
            return $query->where('restaurant_id', $restaurant_id);
        })
        ->when(is_numeric($category_id), function($query)use($category_id){
            return $query->whereHas('category',function($q)use($category_id){
                return $q->whereId($category_id)->orWhere('parent_id', $category_id);
            });
        })
        ->when(isset($key) , function($q) use($key) {
            $q->where(function($q) use($key){
                foreach ($key as $value) {
                    $q->where('name', 'like', "%{$value}%");
                }
            });
        })
        ->type($type)
        ->latest()
        ->paginate(config('default_pagination'));
        $restaurant =$restaurant_id !='all'? Restaurant::findOrFail($restaurant_id):null;
        $category =$category_id !='all'? Category::with('translations')->findOrFail($category_id):null;
        return view('admin-views.product.list', compact('foods','restaurant','category', 'type'));
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $restaurant_id=$request->restaurant_id ?? null;

            $foods = Food::withoutGlobalScope(RestaurantScope::class)
                            ->when(is_numeric($restaurant_id), function($query)use($restaurant_id){
                                return $query->where('restaurant_id', $restaurant_id);
                            })
                            ->where(function($q) use($key){
                                foreach ($key as $value) {
                                    $q->where('name', 'like', "%{$value}%");
                                }
                            })

                            ->limit(50)->get();
            return response()->json(['count'=>count($foods),
                'view'=>view('admin-views.product.partials._table',compact('foods'))->render()
            ]);
    }

    public function search_vendor(Request $request){
        $key = explode(' ', $request['search']);
        $restaurant_id=$request->restaurant_id ?? null;
            $foods = Food::withoutGlobalScope(RestaurantScope::class)
                            ->when(is_numeric($restaurant_id), function($query)use($restaurant_id){
                                return $query->where('restaurant_id', $restaurant_id);
                            })
                            ->where(function($q) use($key){
                                foreach ($key as $value) {
                                    $q->where('name', 'like', "%{$value}%");
                                }
                            })->limit(50)->get();
            return response()->json(['count'=>count($foods),
                'view'=>view('admin-views.vendor.view.partials._product',compact('foods'))->render()
            ]);
    }

    public function review_list(Request $request)
    {
        $key = explode(' ', $request['search']);
        $reviews = Review::with(['customer','food'=> function ($q) {
            $q->withoutGlobalScope(RestaurantScope::class);
        }])
        ->when(isset($key), function($query) use($key){
            $query->whereHas('food', function ($query) use ($key) {
                foreach ($key as $value) {
                    $query->where('name', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.product.reviews-list', compact('reviews'));
    }

    public function reviews_status(Request $request)
    {
        $review = Review::find($request->id);
        $review->status = $request->status;
        $review->save();
        Toastr::success(translate('messages.review_visibility_updated'));
        return back();
    }

    public function bulk_import_index()
    {
        return view('admin-views.product.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {

        $request->validate([
            'upload_excel'=>'required|max:2048'
        ],[
            'upload_excel.required' => translate('messages.File_is_required!'),
            'upload_excel.max' => translate('messages.Max_file_size_is_2mb'),
        ]);
        try {
            $collections = (new FastExcel)->import($request->file('upload_excel'));
        } catch (\Exception $exception) {
            info(["line___{$exception->getLine()}",$exception->getMessage()]);
            Toastr::error(translate('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }

        $data = [];
        if($request->button == 'import'){
            try {
                foreach ($collections as $collection) {
                    if ($collection['Id'] === "" || $collection['Name'] === "" || $collection['CategoryId'] === "" || $collection['SubCategoryId'] === "" || $collection['Price'] === "" || empty($collection['AvailableTimeStarts'])  || empty($collection['AvailableTimeEnds']) || $collection['RestaurantId'] === "" || $collection['Discount'] === "") {
                        Toastr::error(translate('messages.please_fill_all_required_fields'));
                        return back();
                    }
                    if(isset($collection['Price']) && ($collection['Price'] < 0  )  ) {
                        Toastr::error(translate('messages.Price_must_be_greater_then_0_on_id').' '.$collection['Id']);
                        return back();
                    }
                    if(isset($collection['Discount']) && ($collection['Discount'] < 0  )  ) {
                        Toastr::error(translate('messages.Discount_must_be_greater_then_0_on_id').' '.$collection['Id']);
                        return back();
                    }
                    if (data_get($collection,'Image') != "" &&  strlen(data_get($collection,'Image')) > 30 ) {
                        Toastr::error(translate('messages.Image_name_must_be_in_30_char_on_id') . ' ' . $collection['Id']);
                        return back();
                    }

                    try{
                            $t1= Carbon::parse($collection['AvailableTimeStarts']);
                            $t2= Carbon::parse($collection['AvailableTimeEnds']) ;
                            if($t1->gt($t2)   ) {
                                Toastr::error(translate('messages.AvailableTimeEnds_must_be_greater_then_AvailableTimeStarts_on_id').' '.$collection['Id']);
                                return back();
                            }
                        }catch(\Exception $e){
                            info(["line___{$e->getLine()}",$e->getMessage()]);
                            Toastr::error(translate('messages.Invalid_AvailableTimeEnds_or_AvailableTimeStarts_on_id').' '.$collection['Id']);
                            return back();
                        }


                    array_push($data, [
                        'name' => $collection['Name'],
                        'description' => $collection['Description'],
                        'image' => $collection['Image'],
                        'category_id' => $collection['SubCategoryId']?$collection['SubCategoryId']:$collection['CategoryId'],
                        'category_ids' => json_encode([['id' => $collection['CategoryId'], 'position' => 1], ['id' => $collection['SubCategoryId'], 'position' => 2]]),
                        'restaurant_id' => $collection['RestaurantId'],
                        'price' => $collection['Price'],
                        'discount' => $collection['Discount'] ?? 0,
                        'discount_type' => $collection['DiscountType'] ?? 'percent',
                        'available_time_starts' => $collection['AvailableTimeStarts'],
                        'available_time_ends' => $collection['AvailableTimeEnds'],
                        'variations' => $collection['Variations'] ?? json_encode([]),
                        'add_ons' => $collection['Addons'] ?($collection['Addons']==""?json_encode([]):$collection['Addons']): json_encode([]),
                        'veg' => $collection['Veg'] == 'yes' ? 1 : 0,
                        'recommended' => $collection['Recommended'] == 'yes' ? 1 : 0,
                        'status' => $collection['Status'] == 'active' ? 1 : 0,
                        'created_at'=>now(),
                        'updated_at'=>now()
                    ]);
                }
            }catch(\Exception $e){
                info(["line___{$e->getLine()}",$e->getMessage()]);
                Toastr::error($e->getMessage());
                return back();
            }
            try{
                DB::beginTransaction();
                $chunkSize = 100;
                $chunk_items= array_chunk($data,$chunkSize);
                foreach($chunk_items as $key=> $chunk_item){
//                    DB::table('food')->insert($chunk_item);
                    foreach ($chunk_item as $item) {
                        $insertedId = DB::table('food')->insertGetId($item);
                        Helpers::updateStorageTable(get_class(new Food), $insertedId, $item['image']);
                    }
                }
                DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                info(["line___{$e->getLine()}",$e->getMessage()]);
                Toastr::error($e->getMessage());
                return back();
            }
            Toastr::success(translate('messages.product_imported_successfully', ['count'=>count($data)]));
            return back();
        }

        try{
            foreach ($collections as $collection) {
                if ($collection['Id'] === "" || $collection['Name'] === "" || $collection['CategoryId'] === "" || $collection['SubCategoryId'] === "" || $collection['Price'] === "" || empty($collection['AvailableTimeStarts'])  || empty($collection['AvailableTimeEnds']) || $collection['RestaurantId'] === "" || $collection['Discount'] === "") {
                    Toastr::error(translate('messages.please_fill_all_required_fields'));
                    return back();
                }
                if(isset($collection['Price']) && ($collection['Price'] < 0  )  ) {
                    Toastr::error(translate('messages.Price_must_be_greater_then_0_on_id').' '.$collection['Id']);
                    return back();
                }
                if(isset($collection['Discount']) && ($collection['Discount'] < 0  )  ) {
                    Toastr::error(translate('messages.Discount_must_be_greater_then_0_on_id').' '.$collection['Id']);
                    return back();
                }
                if (data_get($collection,'Image') != "" &&  strlen(data_get($collection,'Image')) > 30 ) {
                    Toastr::error(translate('messages.Image_name_must_be_in_30_char_on_id') . ' ' . $collection['Id']);
                    return back();
                }

                try{
                        $t1= Carbon::parse($collection['AvailableTimeStarts']);
                        $t2= Carbon::parse($collection['AvailableTimeEnds']) ;
                        if($t1->gt($t2)   ) {
                            Toastr::error(translate('messages.AvailableTimeEnds_must_be_greater_then_AvailableTimeStarts_on_id').' '.$collection['Id']);
                            return back();
                        }
                    }catch(\Exception $e){
                        info(["line___{$e->getLine()}",$e->getMessage()]);
                        Toastr::error(translate('messages.Invalid_AvailableTimeEnds_or_AvailableTimeStarts_on_id').' '.$collection['Id']);
                        return back();
                    }
                array_push($data, [
                    'id' => $collection['Id'],
                    'name' => $collection['Name'],
                    'description' => $collection['Description'],
                    'image' => $collection['Image'],
                    'category_id' => $collection['SubCategoryId']?$collection['SubCategoryId']:$collection['CategoryId'],
                    'category_ids' => json_encode([['id' => $collection['CategoryId'], 'position' => 1], ['id' => $collection['SubCategoryId'], 'position' => 2]]),
                    'restaurant_id' => $collection['RestaurantId'],
                    'price' => $collection['Price'],
                    'discount' => $collection['Discount'] ?? 0,
                    'discount_type' => $collection['DiscountType'],
                    'available_time_starts' => $collection['AvailableTimeStarts'],
                    'available_time_ends' => $collection['AvailableTimeEnds'],
                    'variations' => $collection['Variations'] ?? json_encode([]),
                    'add_ons' => $collection['Addons'] ?($collection['Addons']==""?json_encode([]):$collection['Addons']): json_encode([]),
                    'veg' => $collection['Veg'] == 'yes' ? 1 : 0,
                    'recommended' => $collection['Recommended'] == 'yes' ? 1 : 0,
                    'status' => $collection['Status'] == 'active' ? 1 : 0,
                    'updated_at'=>now()
                ]);
            }
        }catch(\Exception $e)
        {
            info(["line___{$e->getLine()}",$e->getMessage()]);
            Toastr::error($e->getMessage());
            return back();
        }
        try{
            DB::beginTransaction();
            $chunkSize = 100;
            $chunk_items= array_chunk($data,$chunkSize);
            foreach($chunk_items as $key=> $chunk_item){
//                DB::table('food')->upsert($chunk_item,['id'],['name','description','image','category_id','category_ids','price','discount','discount_type','available_time_starts','available_time_ends','variations','add_ons','restaurant_id','status','veg','recommended']);
                foreach ($chunk_item as $item) {
                    if (isset($item['id']) && DB::table('food')->where('id', $item['id'])->exists()) {
                        DB::table('food')->where('id', $item['id'])->update($item);
                        Helpers::updateStorageTable(get_class(new Food), $item['id'], $item['image']);
                    } else {
                        $insertedId = DB::table('food')->insertGetId($item);
                        Helpers::updateStorageTable(get_class(new Food), $insertedId, $item['image']);
                    }
                }
            }
            DB::commit();
        }catch(\Exception $e)
        {
            DB::rollBack();
            info(["line___{$e->getLine()}",$e->getMessage()]);
            Toastr::error($e->getMessage());
            return back();
        }

        Toastr::success(translate('messages.Food_imported_successfully', ['count' => count($data)]));
        return back();


    }

    public function bulk_export_index()
    {
        return view('admin-views.product.bulk-export');
    }

    public function bulk_export_data(Request $request)
    {
        $request->validate([
            'type'=>'required',
            'start_id'=>'required_if:type,id_wise',
            'end_id'=>'required_if:type,id_wise',
            'from_date'=>'required_if:type,date_wise',
            'to_date'=>'required_if:type,date_wise'
        ]);
        $products = Food::when($request['type']=='date_wise', function($query)use($request){
            $query->whereBetween('created_at', [$request['from_date'].' 00:00:00', $request['to_date'].' 23:59:59']);
        })
        ->when($request['type']=='id_wise', function($query)use($request){
            $query->whereBetween('id', [$request['start_id'], $request['end_id']]);
        })
        ->withoutGlobalScope(RestaurantScope::class)->get();
        return (new FastExcel(ProductLogic::format_export_foods(Helpers::Export_generator($products))))->download('Foods.xlsx');
    }



    public function food_variation_generator(Request $request){
        $validator = Validator::make($request->all(), [
            'options' => 'required',
        ]);

        $food_variations = [];
        if (isset($request->options)) {
            foreach (array_values($request->options) as $key => $option) {

                $temp_variation['name'] = $option['name'];
                $temp_variation['type'] = $option['type'];
                $temp_variation['min'] = $option['min'] ?? 0;
                $temp_variation['max'] = $option['max'] ?? 0;
                $temp_variation['required'] = $option['required'] ?? 'off';
                if ($option['min'] > 0 &&  $option['min'] > $option['max']) {
                    $validator->getMessageBag()->add('name', translate('messages.minimum_value_can_not_be_greater_then_maximum_value'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if (!isset($option['values'])) {
                    $validator->getMessageBag()->add('name', translate('messages.please_add_options_for') . $option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if ($option['max'] > count($option['values'])) {
                    $validator->getMessageBag()->add('name', translate('messages.please_add_more_options_or_change_the_max_value_for') . $option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                $temp_value = [];

                foreach (array_values($option['values']) as $value) {
                    if (isset($value['label'])) {
                        $temp_option['label'] = $value['label'];
                    }
                    $temp_option['optionPrice'] = $value['optionPrice'];
                    array_push($temp_value, $temp_option);
                }
                $temp_variation['values'] = $temp_value;
                array_push($food_variations, $temp_variation);
            }
        }

        return response()->json([
            'variation' => json_encode($food_variations)
        ]);
    }


    public function export(Request $request){
        try{
            $key = explode(' ', $request['search']);
            $restaurant_id = $request->query('restaurant_id', 'all');
            $category_id = $request->query('category_id', 'all');
            $type = $request->query('type', 'all');
            $foods = Food::withoutGlobalScope(RestaurantScope::class)
            ->with(['tags','restaurant','category.parent'])
            ->when(is_numeric($restaurant_id), function($query)use($restaurant_id){
                return $query->where('restaurant_id', $restaurant_id);
            })
            ->when(is_numeric($category_id), function($query)use($category_id){
                return $query->whereHas('category',function($q)use($category_id){
                    return $q->whereId($category_id)->orWhere('parent_id', $category_id);
                });
            })
            ->when(isset($key) , function($q) use($key) {
                $q->where(function($q) use($key){
                    foreach ($key as $value) {
                        $q->where('name', 'like', "%{$value}%");
                    }
                });
            })
            ->type($type)
            ->latest()
            ->get();

            $data=[
                'data' =>$foods,
                'search' =>$request['search'] ?? null,
                'restaurant' => $restaurant_id !='all'? Restaurant::findOrFail($restaurant_id)?->name:null,
                'category' => $category_id !='all'? Category::findOrFail($category_id)?->name:null,
            ];

            if($request->type == 'csv'){
                return Excel::download(new FoodListExport($data), 'FoodList.csv');
            }
            return Excel::download(new FoodListExport($data), 'FoodList.xlsx');
        }  catch(\Exception $e)
            {
                dd($e);
                Toastr::error("line___{$e->getLine()}",$e->getMessage());
                info(["line___{$e->getLine()}",$e->getMessage()]);
                return back();
            }
    }


    public function reviews_export(Request $request){
        try{
                $key = explode(' ', $request['search']);
                $reviews = Review::with(['customer','food'=> function ($q) {
                    $q->withoutGlobalScope(RestaurantScope::class);
                }])
                ->when(isset($key), function($query) use($key){
                    $query->whereHas('food', function ($query) use ($key) {
                        foreach ($key as $value) {
                            $query->where('name', 'like', "%{$value}%");
                        }
                    });
                })
                ->latest()->get();

                $data=[
                    'data' =>$reviews,
                    'search' =>$request['search'] ?? null,
                ];

                if($request->type == 'csv'){
                    return Excel::download(new FoodReviewExport($data), 'FoodReview.csv');
                }
                return Excel::download(new FoodReviewExport($data), 'FoodReview.xlsx');
            }  catch(\Exception $e){
                Toastr::error("line___{$e->getLine()}",$e->getMessage());
                info(["line___{$e->getLine()}",$e->getMessage()]);
                return back();
            }
    }




    public function food_wise_reviews_export(Request $request){
        try{
                $product = Food::withoutGlobalScope(RestaurantScope::class)->findOrFail($request->id)?->category_ids;
                $reviews=Review::where(['food_id'=> $request->id])->with('customer')->latest()->get();

                $data=[
                    'type' =>'single',
                    'category' =>\App\CentralLogics\Helpers::get_category_name($product),
                    'data' =>$reviews,
                    'search' =>$request['search'] ?? null,
                    'restaurant' =>$request['restaurant'] ?? null,
                ];

                if($request->type == 'csv'){
                    return Excel::download(new FoodReviewExport($data), 'FoodWiseReview.csv');
                }
                return Excel::download(new FoodReviewExport($data), 'FoodWiseReview.xlsx');
            }  catch(\Exception $e){
                Toastr::error("line___{$e->getLine()}",$e->getMessage());
                info(["line___{$e->getLine()}",$e->getMessage()]);
                return back();
            }
    }


    public function restaurant_food_export($type, $restaurant_id){
        try{
            $key = explode(' ', request()?->search);
            $foods =Food::withoutGlobalScope(\App\Scopes\RestaurantScope::class)
            ->with('category.parent')
            ->where('restaurant_id', $restaurant_id)
            ->when(isset($key) , function($q) use($key) {
                $q->where(function($q) use($key){
                    foreach ($key as $value) {
                        $q->where('name', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->get();

            $restaurant= Restaurant::where('id',$restaurant_id)->select(['name','zone_id'])->first();
            $data=[
                'data'=> $foods,
                'search'=> request()?->search ?? null,
                'zone'=>Helpers::get_zones_name($restaurant->zone_id),
                'restaurant_name'=> $restaurant->name,
            ];
            if($type == 'csv'){
                return Excel::download(new RestaurantFoodExport($data), 'FoodList.csv');
            }
            return Excel::download(new RestaurantFoodExport($data), 'FoodList.xlsx');
        }  catch(\Exception $e){
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }
    }
    public function updateStock(Request $request){
        $product = Food::withoutGlobalScope(RestaurantScope::class)->findOrFail($request->food_id);
        $product->item_stock = $request->item_stock;
        $product->sell_count =0;
        $product->save() ;
        if($request->option){
                foreach($request->option  as $key => $value ){
                    VariationOption::where('food_id',$product->id)->where('id',$key)->update([
                        'sell_count' => 0,
                        'total_stock'=> $value
                    ]);
                }
        }
        Toastr::success(translate('Stock_updated_successfully'));
        return back();
    }

}
