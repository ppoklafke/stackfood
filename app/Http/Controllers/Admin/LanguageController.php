<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LanguageController extends Controller
{
    public function index()
    {       $language = BusinessSetting::where('key', 'system_language')->exists();
        if(!$language){
            Helpers::insert_business_settings_key('system_language','[{"id":1,"direction":"ltr","code":"en","status":1,"default":true}]');
        }
        return view('admin-views.business-settings.language.index');
    }

    public function store(Request $request)
    {
        $language = BusinessSetting::where('key', 'system_language')->first();
        $lang_array = [];
        $codes = [];
        foreach (json_decode($language?->value, true) as $key => $data) {
            if ($data['code'] != $request['code']) {
                if (!array_key_exists('default', $data)) {
                    $default = array('default' => ($data['code'] == 'en') ? true : false);
                    $data = array_merge($data, $default);
                }
                array_push($lang_array, $data);
                array_push($codes, $data['code']);
            }
        }
        array_push($codes, $request['code']);

        if (!file_exists(base_path('resources/lang/' . $request['code']))) {
            mkdir(base_path('resources/lang/' . $request['code']), 0777, true);
        }

        $lang_file = fopen(base_path('resources/lang/' . $request['code'] . '/' . 'messages.php'), "w") or die("Unable to open file!");
        $read = file_get_contents(base_path('resources/lang/en/messages.php'));
        fwrite($lang_file, $read);

        $lang_array[] = [
            'id' => count(json_decode($language?->value, true)) + 1,
            'code' => $request['code'],
            'direction' => $request['direction'],
            'status' => 0,
            'default' => false,
        ];
        Helpers::businessUpdateOrInsert(['key' => 'system_language'], [
            'value' => $lang_array
        ]);

        Helpers::businessUpdateOrInsert(['key' => 'language'], [
            'value' => json_encode($codes),
        ]);

        Toastr::success('Language Added!');
        return back();
    }

    public function update_status(Request $request)
    {
        $language = BusinessSetting::where('key', 'system_language')->first();
        $lang_array = [];
        foreach (json_decode($language?->value, true) as $key => $data) {

            if ($data['code'] == $request['code']) {
                if( array_key_exists('default', $data) && $data['default'] == true ){
                    return response()->json(['error' => 403]);
                }
                $lang = [
                    'id' => $data['id'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'] == 1 ? 0 : 1,
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                $lang_array[] = $lang;
            } else {
                $lang = [
                    'id' => $data['id'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                $lang_array[] = $lang;
            }
        }
        $businessSetting = BusinessSetting::where('key', 'system_language')->update([
            'value' => $lang_array
        ]);
        return $businessSetting;
    }

    public function update_default_status(Request $request)
    {
        $language = BusinessSetting::where('key', 'system_language')->first();
        // dd($language);


        $lang_array = [];
        foreach (json_decode($language?->value, true) as $key => $data) {
            if ($data['code'] == $request['code']) {

               if($data['default'] == true){
                Toastr::warning(translate('messages.You_can_not_change_the_default_status_of_this_language'));
                return back();
               }

                $lang = [
                    'id' => $data['id'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => 1,
                    'default' => true,
                ];
                $lang_array[] = $lang;
            } else {
                $lang = [
                    'id' => $data['id'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => false,
                ];
                $lang_array[] = $lang;
            }
        }
        BusinessSetting::where('key', 'system_language')->update([
            'value' => $lang_array
        ]);

        $direction = BusinessSetting::where('key', 'site_direction')->first();
        $direction = $direction->value ?? 'ltr';
        $language = BusinessSetting::where('key', 'system_language')->first();
        foreach (json_decode($language?->value, true) as $key => $data) {
            if ($data['code'] == $request['code']) {
                $direction = isset($data['direction']) ? $data['direction'] : 'ltr';
            }
        }
        session()->forget('language_settings');
        Helpers::language_load();
        session()->put('local', $request['code']);
        session()->put('site_direction', $direction);
        Toastr::success('Default Language Changed!');
        return back();
    }

    public function update(Request $request)
    {
        $language = BusinessSetting::where('key', 'system_language')->first();
        $lang_array = [];
        foreach (json_decode($language?->value, true) as $key => $data) {
            if ($data['code'] == $request['code']) {
                $lang = [
                    'id' => $data['id'],
                    'direction' => $request['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                $lang_array[] = $lang;
            } else {
                $lang = [
                    'id' => $data['id'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                $lang_array[] = $lang;
            }
        }
        BusinessSetting::where('key', 'system_language')->update([
            'value' => $lang_array
        ]);
        Toastr::success('Language updated!');
        return back();
    }

    public function convertArrayToCollection($lang, $items, $perPage = null, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $options = [
        "path" => route('admin.language.translate',[$lang]),
        "pageName" => "page"
        ];
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function translate(Request $request,$lang)
    {
        $searchTerm =$request['search'];
        $full_data = include(base_path('resources/lang/' . $lang . '/messages.php'));
        $full_data = array_filter($full_data, fn($value) => !is_null($value) && $value !== '');

        if (!empty($searchTerm)) {
            $full_data = array_filter($full_data, function ($value, $key) use ($searchTerm) {
                return (stripos($value, $searchTerm) !== false) || (stripos(ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key))), $searchTerm) !== false);
            }, ARRAY_FILTER_USE_BOTH);
        }


        ksort($full_data);
        $full_data = $this->convertArrayToCollection($lang,$full_data,config('default_pagination'));

        return view('admin-views.business-settings.language.translate', compact('lang', 'full_data'));
    }

    public function translate_key_remove(Request $request, $lang)
    {
        $full_data = include(base_path('resources/lang/' . $lang . '/messages.php'));
        unset($full_data[$request['key']]);
        $str = "<?php return " . var_export($full_data, true) . ";";
        file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $str);
    }

    public function translate_submit(Request $request, $lang)
    {
        $full_data = include(base_path('resources/lang/' . $lang . '/messages.php'));
        $data_filtered = [];
        foreach ($full_data as $key => $data) {
            $data_filtered[$key] = $data;
        }
        $data_filtered[$request['key']] = $request['value'];
        $str = "<?php return " . var_export($data_filtered, true) . ";";
        file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $str);
    }

    public function auto_translate(Request $request, $lang): \Illuminate\Http\JsonResponse
    {
        $lang_code = Helpers::getLanguageCode($lang);

        $full_data = include(base_path('resources/lang/' . $lang . '/messages.php'));
        $data_filtered = [];
        foreach ($full_data as $key => $data) {
            $data_filtered[$key] = $data;
        }
        $translated=  str_replace('_', ' ', Helpers::remove_invalid_charcaters($request['key']));
        $translated = Helpers::auto_translator($translated, 'en', $lang);
        $data_filtered[$request['key']] = $translated;
        $str = "<?php return " . var_export($data_filtered, true) . ";";
        file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $str);

        return response()->json([
            'translated_data' => $translated
        ]);
    }
    public function auto_translate_all(Request $request, $lang): \Illuminate\Http\JsonResponse
    {
        try {
            $translating_count= $request?->translating_count <= 0 ? 1: $request->translating_count ;
            $lang_code = Helpers::getLanguageCode($lang);

            if($lang === 'en'){
                return response()->json([
                    'message' => translate('All_datas_are_translated') , 'data' => 'success'
                ]);
            }

            $data_filtered = [];
            $data_filtered_2 = [];
            $new_messages_path = base_path('resources/lang/' . $lang . '/new-messages.php');
            $count=0;
            $start_time = now();
            $items_processed = 20;
            if(!file_exists($new_messages_path)){
                $str = "<?php return " . var_export($data_filtered, true) . ";";
                file_put_contents(base_path('resources/lang/' . $lang . '/new-messages.php'), $str);
            }

            $translated_data = include(base_path('resources/lang/' . $lang . '/new-messages.php'));
            $full_data = include(base_path('resources/lang/' . $lang . '/messages.php'));
            $translated_data_count= count($translated_data);

            if($translated_data_count > 0){
                foreach ($translated_data as $key_1 => $data_1) {
                    if($count > $items_processed){
                        break;
                    }
                    $translated=  str_replace('_', ' ', Helpers::remove_invalid_charcaters($key_1));
                    $translated = Helpers::auto_translator($translated, 'en', $lang);
                    $data_filtered_2[$key_1] = $translated;
                    unset($translated_data[$key_1]);
                    $count++;
                }


                $str = "<?php return " . var_export($translated_data, true) . ";";
                file_put_contents(base_path('resources/lang/' . $lang . '/new-messages.php'), $str);
                $merged_data = array_replace($full_data, $data_filtered_2);

                $str = "<?php\n\nreturn " . var_export($merged_data, true) . ";\n";
                file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $str);
                $renmaining_translated_data_count= count($translated_data);
                $percentage =  $renmaining_translated_data_count > 0 && $translating_count > 0 ?  100 - ( ($renmaining_translated_data_count/$translating_count)* 100) : 0;


                $percentage= $percentage > 0 ? $percentage : 1;

                $end_time =now();
                $time_taken = $start_time->diffInSeconds($end_time);
                $rate_per_second = $time_taken > 0 ? $items_processed / $time_taken : 0.01;
                $total_time_needed = $renmaining_translated_data_count > 0 ? $renmaining_translated_data_count / $rate_per_second : 1;

                $hours = floor($total_time_needed / 3600);
                $minutes = floor( 2 + (($total_time_needed % 3600) / 60));
                $seconds = $total_time_needed % 60;


                return response()->json([
                    'message' =>  translate('translating') , 'data' => 'translating', 'total' => $translated_data_count, 'percentage'=> round($percentage,1), 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds,
                    'status' =>  $renmaining_translated_data_count > 0 ? 'pending' : 'done'
                ]);

            } else{

                    foreach ($full_data as $key => $data) {
                        if (preg_match('/^[\x20-\x7E\x{2019}]+$/u', $data)) {
                            $data_filtered[$key] = $data;
                            $str = "<?php return " . var_export($data_filtered, true) . ";";
                            file_put_contents(base_path('resources/lang/' . $lang . '/new-messages.php'), $str);
                            }
                    }
                    return response()->json([
                        'message' =>  translate('data_prepared') , 'data' => 'data_prepared' , 'total' => count($data_filtered)
                    ]);

            }
            return response()->json([
                'message' => translate('All_datas_are_translated') , 'data' => 'success'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage() , 'data' => 'error'
            ]);
        }
    }

    public function delete($lang)
    {
        $language = BusinessSetting::where('key', 'system_language')->first();

        $del_default = false;
        foreach (json_decode($language?->value, true) as $key => $data) {
            if ($data['code'] == $lang && array_key_exists('default', $data) && $data['default'] == true) {
                $del_default = true;
            }
        }

        $lang_array = [];
        foreach (json_decode($language?->value, true) as $key => $data) {
            if ($data['code'] != $lang) {
                $lang_data = [
                    'id' => $data['id'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => ($del_default == true && $data['code'] == 'en') ? 1 : $data['status'],
                    'default' => ($del_default == true && $data['code'] == 'en') ? true : (array_key_exists('default', $data) ? $data['default'] : (($data['code'] == 'en') ? true : false)),
                ];
                array_push($lang_array, $lang_data);
            }
        }

        BusinessSetting::where('key', 'system_language')->update([
            'value' => $lang_array
        ]);

        $dir = base_path('resources/lang/' . $lang);
        if (File::isDirectory($dir)) {
            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
        }


        $languages = array();
        $language = BusinessSetting::where('key', 'language')->first();
        foreach (json_decode($language?->value, true) as $key => $data) {
            if ($data != $lang) {
                array_push($languages, $data);
            }
        }
        if (in_array('en', $languages)) {
            unset($languages[array_search('en', $languages)]);
        }
        array_unshift($languages, 'en');

        Helpers::businessUpdateOrInsert(['key' => 'language'], [
            'value' => json_encode($languages),
        ]);

        Toastr::success('Removed Successfully!');
        return back();
    }

    public function lang($local)
    {
        $direction = BusinessSetting::where('key', 'site_direction')->first();
        $direction = $direction->value ?? 'ltr';
        $language = BusinessSetting::where('key', 'system_language')->first();
        foreach (json_decode($language?->value, true) as $key => $data) {
            if ($data['code'] == $local) {
                $direction = isset($data['direction']) ? $data['direction'] : 'ltr';
            }
        }
        session()->forget('language_settings');
        Helpers::language_load();
        session()->put('local', $local);
        session()->put('site_direction', $direction);
        return redirect()->back();
    }
}
