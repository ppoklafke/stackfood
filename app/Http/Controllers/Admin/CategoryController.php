<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Translation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Exports\CategoryExport;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\CategoryLogic;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class CategoryController extends Controller
{
    function index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $categories=Category::where(['position'=>0])->latest()
        ->when(isset($key) , function ($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        })
        ->paginate(config('default_pagination'));
        return view('admin-views.category.index',compact('categories'));
    }

    function sub_index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $categories=Category::with(['parent'])->where(['position'=>1])
        ->when(isset($key) , function ($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.category.sub-index',compact('categories'));
    }

    function sub_sub_index()
    {
        return view('admin-views.category.sub-sub-index');
    }

    function sub_category_index()
    {
        return view('admin-views.category.index');
    }

    function sub_sub_category_index()
    {
        return view('admin-views.category.index');
    }

    function store(Request $request)
    {
            $request->validate([
            'name' => 'required|unique:categories|max:100',
            'image' => 'nullable|max:2048',
            'name.0' => 'required',

        ], [
            'name.required' => translate('messages.Name is required!'),
            'name.0.required'=>translate('default_name_is_required'),
        ]);

        $category = new Category();
        $category->name = $request->name[array_search('default', $request->lang)];
        $category->image = $request->has('image') ? Helpers::upload(dir:'category/',format: 'png',image: $request->file('image')) : 'def.png';
        $category->parent_id = $request?->parent_id ??  0 ;
        $category->position = $request->position;
        $category->save();
        $data = [];
        $default_lang = str_replace('_', '-', app()->getLocale());

        foreach($request->lang as $index=>$key)
        {
                if($default_lang == $key && !($request->name[$index])){
                    if ($key != 'default') {
                        array_push($data, array(
                            'translationable_type' => 'App\Models\Category',
                            'translationable_id' => $category->id,
                            'locale' => $key,
                            'key' => 'name',
                            'value' => $category->name,
                        ));
                    }
                }else{
                    if ($request->name[$index] && $key != 'default') {
                        array_push($data, array(
                            'translationable_type' => 'App\Models\Category',
                            'translationable_id' => $category->id,
                            'locale' => $key,
                            'key' => 'name',
                            'value' => $request->name[$index],
                        ));
                    }
                }

        }
        if(count($data))
        {
            Translation::insert($data);
        }

        if($category->parent_id == 0){
            Toastr::success(translate('messages.category_added_successfully'));
        }else{
            Toastr::success(translate('messages.sub_category_added_successfully'));
        }

        return back();
    }

    public function edit($id)
    {
        $category = Category::withoutGlobalScope('translate')->findOrFail($id);
        return view('admin-views.category.edit', compact('category'));
    }

    public function status(Request $request)
    {
        $category = Category::find($request->id);
        $category->status = $request->status;
        $category->save();
        if($category->parent_id == 0){
            Toastr::success(translate('messages.category_status_updated'));
        }else{
            Toastr::success(translate('messages.sub_category_status_updated'));
        }
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100|unique:categories,name,'.$id,
            'image' => 'nullable|max:2048',
            'name.0' => 'required',
        ],[
            'name.0.required'=>translate('default_name_is_required'),
        ]);

        $category = Category::find($id);
        $slug = Str::slug($request->name[array_search('default', $request->lang)]);
        $category->slug = $category->slug? $category->slug :"{$slug}{$category->id}";
        $category->name = $request->name[array_search('default', $request->lang)];
        $category->image = $request->has('image') ? Helpers::update(dir:'category/', old_image:$category->image,format: 'png', image:$request->file('image')) : $category->image;
        $category->save();
        $default_lang = str_replace('_', '-', app()->getLocale());

        foreach($request->lang as $index=>$key)
        {

            if($default_lang == $key && !($request->name[$index])){
                if (isset($category->name) && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Category',
                            'translationable_id' => $category->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $category->name]
                    );
                }

            }else{

                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Category',
                            'translationable_id' => $category->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $request->name[$index]]
                    );
                }
            }


        }
        if($category->parent_id == 0){
            Toastr::success(translate('messages.category_updated_successfully'));
        }else{
            Toastr::success(translate('messages.sub_category_updated_successfully'));
        }
        return back();
    }

    public function delete(Request $request)
    {
        $category = Category::findOrFail($request->id);
        if ($category?->childes?->count()==0){
            $category?->translations()?->delete();
            $category->delete();
            if($category->parent_id == 0){
                Toastr::success(translate('messages.Category removed!'));
            }else{
                Toastr::success(translate('messages.Sub Category removed!'));
            }
        }else{
            Toastr::warning(translate('messages.remove_sub_categories_first'));
        }
        return back();
    }

    public function get_all(Request $request){
        $data = Category::where('name', 'like', '%'.$request->q.'%')->limit(8)->get()

        ->map(function ($category) {
            $data =$category->position == 0 ? translate('messages.main'): translate('messages.sub');
            return [
                'id' => $category->id,
                'text' => $category->name . ' (' .  $data   . ')',
            ];
        });

        $data[]=(object)['id'=>'all', 'text'=>'All'];

        return response()->json($data);
    }

    public function update_priority(Category $category, Request $request)
    {
        $priority = $request->priority??0;
        $category->priority = $priority;
        $category->save();
        Toastr::success(translate('messages.category_priority_updated successfully'));
        return back();

    }

    public function bulk_import_index()
    {
        return view('admin-views.category.bulk-import');
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
            info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
            Toastr::error(translate('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }

        if($request->button == 'import'){
            try{
            $data = [];
            foreach ($collections as $collection) {
                if (!isset($collection['Name']) || $collection['Name'] === "" || !isset($collection['Image']) || !isset($collection['Position']) ) {

                    Toastr::error(translate('messages.please_fill_all_required_fields'));
                    return back();
                }
                $parent_id = is_numeric($collection['ParentId'])?$collection['ParentId']:0;
                array_push($data, [
                    'name' => $collection['Name'],
                    'image' => $collection['Image'],
                    'parent_id' => $parent_id,
                    'position' => $collection['Position'],
                    'priority'=>is_numeric($collection['Priority']) ? $collection['Priority']:0,
                    'status' => $collection['Status'] == 'active' ? 1 : 0,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ]);
            }

                DB::beginTransaction();

                $chunkSize = 100;
                $chunk_categories= array_chunk($data,$chunkSize);

                foreach($chunk_categories as $key=> $chunk_category){
//                    DB::table('categories')->insert($chunk_category);
                    foreach ($chunk_category as $category) {
                        $insertedId = DB::table('categories')->insertGetId($category);
                        Helpers::updateStorageTable(get_class(new Category), $insertedId, $category['image']);
                    }
                }
                DB::commit();
            }catch(\Exception $exception)
            {
                DB::rollBack();
                info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
                Toastr::error(translate('messages.failed_to_import_data'));
                return back();
            }
            Toastr::success(translate('messages.category_imported_successfully', ['count'=>count($data)]));
            return back();
        }
        try{
        $data = [];
        foreach ($collections as $collection) {
            if (!isset($collection['Name']) || $collection['Name'] === "" || !isset($collection['Id'] ) || !isset($collection['Image']) || !isset($collection['Position']) ) {

                Toastr::error(translate('messages.please_fill_all_required_fields'));
                return back();
            }
            $parent_id = is_numeric($collection['ParentId'])?$collection['ParentId']:0;
            array_push($data, [
                'id' => $collection['Id'],
                'name' => $collection['Name'],
                'image' => $collection['Image'],
                'parent_id' => $parent_id,
                'position' => $collection['Position'],
                'priority'=>is_numeric($collection['Priority']) ? $collection['Priority']:0,
                'status' => $collection['Status']  == 'active' ? 1 : 0,
                'updated_at'=>now()
            ]);
        }

            DB::beginTransaction();

            $chunkSize = 100;
            $chunk_categories= array_chunk($data,$chunkSize);

            foreach($chunk_categories as $key=> $chunk_category){
//                DB::table('categories')->upsert($chunk_category,['id'],['name','image','parent_id','position','priority','status']);
                foreach ($chunk_category as $category) {
                    if (isset($category['id']) && DB::table('categories')->where('id', $category['id'])->exists()) {
                        DB::table('categories')->where('id', $category['id'])->update($category);
                        Helpers::updateStorageTable(get_class(new Category), $category['id'], $category['image']);
                    } else {
                        $insertedId = DB::table('categories')->insertGetId($category);
                        Helpers::updateStorageTable(get_class(new Category), $insertedId, $category['image']);
                    }
                }
            }
            DB::commit();
        }catch(\Exception $e)
        {
            DB::rollBack();
            info(["line___{$e->getLine()}",$e->getMessage()]);
            Toastr::error(translate('messages.failed_to_import_data'));
            return back();
        }
        Toastr::success(translate('messages.category_updated_successfully', ['count'=>count($data)]));
        return back();
    }

    public function bulk_export_index()
    {
        return view('admin-views.category.bulk-export');
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
        $categories = Category::when($request['type']=='date_wise', function($query)use($request){
            $query->whereBetween('created_at', [$request['from_date'].' 00:00:00', $request['to_date'].' 23:59:59']);
        })
        ->when($request['type']=='id_wise', function($query)use($request){
            $query->whereBetween('id', [$request['start_id'], $request['end_id']]);
        })
        ->get();
        return (new FastExcel(CategoryLogic::export_categories(Helpers::Export_generator($categories))))->download('Categories.xlsx');
    }

    // public function search(Request $request){
    //     $key = explode(' ', $request['search']);
    //     $categories=Category::
    //     when($request->sub_category, function($query){
    //         return $query->where('position','1');
    //     })
    //     ->where(function ($q) use ($key) {
    //         foreach ($key as $value) {
    //             $q->orWhere('name', 'like', "%{$value}%");
    //         }
    //     })->limit(50)->get();

    //     if($request->sub_category)
    //     {
    //         return response()->json([
    //             'view'=>view('admin-views.category.partials._sub_category_table',compact('categories'))->render(),
    //             'count'=>$categories->count()
    //         ]);
    //     }
    //     return response()->json([
    //         'view'=>view('admin-views.category.partials._table',compact('categories'))->render(),
    //         'count'=>$categories->count()
    //     ]);
    // }

    public function export_categories(Request $request){
        try{
                $key = explode(' ', $request['search']);
                $categories = Category::where('position','0')
                ->when(isset($key) , function ($q) use($key){
                    $q->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('name', 'like', "%{$value}%");
                        }
                    });
                })
                ->orderBy('id','desc')->get();
                $data=[
                    'data' =>$categories,
                    'search' =>$request['search'] ?? null,
                ];
                if($request->type == 'csv'){
                    return Excel::download(new CategoryExport($data), 'Categories.csv');
                }
                return Excel::download(new CategoryExport($data), 'Categories.xlsx');
            } catch(\Exception $e) {
                Toastr::error("line___{$e->getLine()}",$e->getMessage());
                info(["line___{$e->getLine()}",$e->getMessage()]);
                return back();
            }
    }
}
