<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSetting;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class PageSetupController extends Controller
{
    public function restaurant_page_setup(){
        $page_data=   DataSetting::Where('type' , 'restaurant')->where('key' , 'restaurant_page_data')->first()?->value;
        return view('admin-views.business-settings.join_us_page_setup.rest_join', compact('page_data'));
    }

    public function restaurant_page_setup_update(Request $request){
        $request->validate([
//            'input_data' => 'required|array',
//            'placeholder_data' => 'required|array',
//            'field_type' => 'required|array',
        ]);

        // dd($request->all());
        $method_fields = [];

        foreach (($request->input_data ?? []) as $key => $field_name) {

            $media_data = [];
            // $check_box_i = [];

            if($request->field_type[$key] == 'check_box') {
                $check_box_i[$key]=  $request->check_box_input[$key];
            }

            if($request->field_type[$key] == 'file') {
                $media_data[$key] =
                ['upload_multiple_files' =>  isset($request->upload_multiple_files)   ? (in_array($key, $request->upload_multiple_files) ? 1 : 0 ): 0,
                    'image' =>  isset($request->image) ? (in_array($key, $request->image) ? 1 : 0 ): 0,
                    'pdf' =>  isset($request->pdf)  ? (in_array($key, $request->pdf) ? 1 : 0 ): 0,
                    'docs' =>  isset($request->docs)  ? (in_array($key, $request->docs) ? 1 : 0 ): 0,
                ];
            }

            $method_fields['data'][$key] = [
                'field_type' => $request->field_type[$key],
                'input_data' =>strtolower(str_replace(' ', "_", $request->input_data[$key])),
                'check_data' =>  $check_box_i[$key] ?? null,
                'media_data' =>  $media_data[$key] ?? null,
                'placeholder_data' => $request->placeholder_data[$key] ?? '',
                'is_required' =>  isset($request->is_required) ? (in_array($key, $request->is_required) ? 1 : 0 ): 0,
            ];
        }
// dd($method_fields);
        $data = DataSetting::firstOrNew(
            ['key' =>  'restaurant_page_data',
            'type' =>  'restaurant'],
        );
        $data->value = json_encode($method_fields);
        $data->save();

        Toastr::success(translate('Data_added_successfully'));
        return back();

    }
    public function deliveryman_page_setup(){
        $page_data=   DataSetting::Where('type' , 'deliveryman')->where('key' , 'deliveryman_page_data')->first()?->value;
        return view('admin-views.business-settings.join_us_page_setup.dm_join', compact('page_data'));

    }

    public function deliveryman_page_setup_update(Request $request){
        $request->validate([
//            'input_data' => 'required|array',
//            'placeholder_data' => 'required|array',
//            'field_type' => 'required|array',
        ]);

        $method_fields = [];

        foreach (($request->input_data ?? []) as $key => $field_name) {

            $media_data = [];
            // $check_box_i = [];

            if($request->field_type[$key] == 'check_box') {
                $check_box_i[$key]=  $request->check_box_input[$key];
            }

            if($request->field_type[$key] == 'file') {
                $media_data[$key] =
                ['upload_multiple_files' =>  isset($request->upload_multiple_files)   ? (in_array($key, $request->upload_multiple_files) ? 1 : 0 ): 0,
                    'image' =>  isset($request->image) ? (in_array($key, $request->image) ? 1 : 0 ): 0,
                    'pdf' =>  isset($request->pdf)  ? (in_array($key, $request->pdf) ? 1 : 0 ): 0,
                    'docs' =>  isset($request->docs)  ? (in_array($key, $request->docs) ? 1 : 0 ): 0,
                ];
            }

            $method_fields['data'][$key] = [
                'field_type' => $request->field_type[$key],
                'input_data' =>strtolower(str_replace(' ', "_", $request->input_data[$key])),
                'check_data' =>  $check_box_i[$key] ?? null,
                'media_data' =>  $media_data[$key] ?? null,
                'placeholder_data' => $request->placeholder_data[$key] ?? '',
                'is_required' =>  isset($request->is_required) ? (in_array($key, $request->is_required) ? 1 : 0 ): 0,
            ];
        }
        $data = DataSetting::firstOrNew(
            ['key' =>  'deliveryman_page_data',
            'type' =>  'deliveryman'],
        );

        $data->value = json_encode($method_fields);
        $data->save();

        Toastr::success(translate('Data_added_successfully'));
        return back();

    }
}
