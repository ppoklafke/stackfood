<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReactFaq;
use App\Models\DataSetting;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\ReactOpportunity;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class RegistrationPageController extends Controller
{
    public function react_hero_index()
    {
        $hero_image_content=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_registration_page')->where('key','hero_image_content')->first()  ?? null;
        $image_content=json_decode($hero_image_content?->value?? null ,true );
        $hero_image_content_full_url = Helpers::get_full_url('hero_image',$image_content['hero_image_content'] ?? 'double_screen_image.png',$image_content['hero_image_content_storage']??'public') ;
        return view('admin-views.registration-page.react.hero',compact('hero_image_content','image_content','hero_image_content_full_url'));
    }

    public function react_hero_save(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $request->validate([
            'hero_image_content' => 'required|image|max:5120',
        ], [
            'hero_image_content.required' => translate('Hero image is required.'),
            'hero_image_content.image' => translate('The uploaded file must be an image (jpeg, png, etc).'),
            'hero_image_content.max' => translate('The hero image size must not exceed 5MB.'),
        ]);

        $settingKey = 'hero_image_content';
        $settingType = 'react_registration_page';

        $dataSetting = DataSetting::firstOrNew([
            'type' => $settingType,
            'key' => $settingKey,
        ]);

        $existingData = json_decode($dataSetting->value, true) ?? [];

        $imageName = $existingData['hero_image_content'] ?? null;
        $storage = $existingData['hero_image_content_storage'] ?? 'public';

        if ($request->hasFile('hero_image_content')) {
            $file = $request->file('hero_image_content');

            if ($imageName) {
                $imageName = Helpers::update(
                    dir: 'hero_image/',
                    old_image: $imageName,
                    format: 'png',
                    image: $file
                );
            } else {
                $imageName = Helpers::upload(
                    dir: 'hero_image/',
                    format: 'png',
                    image: $file
                );
            }

            $storage = Helpers::getDisk();
        }

        $dataSetting->value = json_encode([
            'hero_image_content' => $imageName,
            'hero_image_content_storage' => $storage,
        ]);

        $dataSetting->save();

        Toastr::success(translate('messages.registration_page_image_content_updated'));
        return back();
    }

    public function react_stepper_index()
    {
        $step1_title = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_registration_page')->where('key','step1_title')->first();
        $step1_sub_title = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_registration_page')->where('key','step1_sub_title')->first();
        $step1_image = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_registration_page')->where('key','step1_image')->first();

        $step2_title = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_registration_page')->where('key','step2_title')->first();
        $step2_sub_title = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_registration_page')->where('key','step2_sub_title')->first();
        $step2_image = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_registration_page')->where('key','step2_image')->first();

        $step3_title = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_registration_page')->where('key','step3_title')->first();
        $step3_sub_title = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_registration_page')->where('key','step3_sub_title')->first();
        $step3_image = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_registration_page')->where('key','step3_image')->first();

        $language=BusinessSetting::where('key','language')->first()?->value ?? null;
        return view('admin-views.registration-page.react.stepper',compact('step1_title','step1_sub_title','step1_image','step2_title','step2_sub_title','step2_image','step3_title','step3_sub_title','step3_image','language'));
    }

    public function update_react_stepper(Request $request, $tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        $steps = [
            'step1' => ['title' => 'step1_title', 'sub_title' => 'step1_sub_title', 'image' => 'step1_image'],
            'step2' => ['title' => 'step2_title', 'sub_title' => 'step2_sub_title', 'image' => 'step2_image'],
            'step3' => ['title' => 'step3_title', 'sub_title' => 'step3_sub_title', 'image' => 'step3_image'],
        ];

        if (!array_key_exists($tab, $steps)) {
            Toastr::error(translate('messages.invalid_step'));
            return back();
        }

        $step = $steps[$tab];

        $request->validate([
            $step['title'] => 'required|max:254',
            $step['sub_title'] => 'required|max:254',
        ]);

        $defaultIndex = array_search('default', $request->lang);
        if (
            empty($request[$step['title']][$defaultIndex]) ||
            empty($request[$step['sub_title']][$defaultIndex])
        ) {
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
        }

        $titleModel = $this->updateOrCreateSetting($step['title'], $request[$step['title']][$defaultIndex]);
        $subTitleModel = $this->updateOrCreateSetting($step['sub_title'], $request[$step['sub_title']][$defaultIndex]);

        $this->handleTranslations($request->lang, $request[$step['title']], $titleModel, $step['title'],'value');
        $this->handleTranslations($request->lang, $request[$step['sub_title']], $subTitleModel, $step['sub_title'],'value');

        if ($request->hasFile($step['image'])) {
            $imageModel = DataSetting::firstOrNew([
                'type' => 'react_registration_page',
                'key' => $step['image'],
            ]);

            $oldImage = $imageModel->value;
            $newImage = empty($oldImage)
                ? Helpers::upload('step_image/', 'png', $request->file($step['image']))
                : Helpers::update('step_image/', $oldImage, 'png', $request->file($step['image']));

            $imageModel->value = $newImage;
            $imageModel->type = 'react_registration_page';
            $imageModel->key = $step['image'];
            $imageModel->save();
        }

        Toastr::success(translate("messages.landing_page_{$tab}_section_updated"));
        return back();
    }

    public function opportunities(Request $request){
        $key = explode(' ', $request['search']);

        $opportunities=  ReactOpportunity::latest()
        ->when(isset($key) , function ($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%")->orWhere('sub_title', 'like', "%{$value}%");
                }
            });
        })
        ->paginate(config('default_pagination'));
        $language=BusinessSetting::where('key','language')->first()?->value ?? null ;
        return view('admin-views.registration-page.react.opportunity', compact('opportunities','language'));
    }

    public function opportunity_store(Request $request){
        $request->validate([
            'title' => 'required|max:100',
            'sub_title' => 'required|max:1000',
            'image' => 'required|max:2048',
        ]);

        if($request->title[array_search('default', $request->lang)] == '' || $request->sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
            }

        $opportunity = new ReactOpportunity();
        $opportunity->title =  $request->title[array_search('default', $request->lang)];
        $opportunity->sub_title =  $request->sub_title[array_search('default', $request->lang)];
        $opportunity->image = Helpers::upload(dir:'opportunity_image/', format: 'png',image: $request->file('image'));
        $opportunity->save();

        $this->handleTranslations($request->lang, $request->title, $opportunity, 'opportunity_title','title');
        $this->handleTranslations($request->lang, $request->sub_title, $opportunity, 'opportunity_sub_title','sub_title');


        Toastr::success(translate('messages.opportunity_added_successfully'));
        return back();
    }

    public function opportunity_status(Request $request)
    {

        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $opportunities = ReactOpportunity::findOrFail($request->id);
        $opportunities->status = $request->status;
        $opportunities->save();
        Toastr::success(translate('messages.opportunity_status_updated'));
        return back();
    }


    public function opportunity_edit($id)
    {
        $opportunity = ReactOpportunity::withoutGlobalScope('translate')->with('translations')->findOrFail($id);
        return view('admin-views.landing_page.opportunity_edit', compact('opportunity'));
    }

    public function opportunity_update(Request $request, $id)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $request->validate([
            'title' => 'required|max:100',
            'sub_title' => 'required|max:1000',
            'image' => 'nullable|max:2048',
        ]);

        if($request->title[array_search('default', $request->lang)] == '' || $request->sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_sub_title_is_required'));
            return back();
            }
        $opportunity = ReactOpportunity::findOrFail($id);

        $opportunity->title = $request->title[array_search('default', $request->lang)];
        $opportunity->sub_title = $request->sub_title[array_search('default', $request->lang)];
        $opportunity->image = $request->has('image') ? Helpers::update( dir: 'opportunity_image/', old_image:  $opportunity->image, format:  'png', image:  $request->file('image')) : $opportunity->image;
        $opportunity->save();

        $this->handleTranslations($request->lang, $request->title, $opportunity, 'opportunity_title','title');
        $this->handleTranslations($request->lang, $request->sub_title, $opportunity, 'opportunity_sub_title','sub_title');

        Toastr::success(translate('messages.opportunity_updated_successfully'));
        return back();
    }


    public function opportunity_destroy(ReactOpportunity $opportunity)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.delete_option_is_disable_for_demo'));
            return back();
        }

        Helpers::check_and_delete('opportunity_image/' , $opportunity->image);

        $opportunity?->translations()?->delete();
        $opportunity?->delete();
        Toastr::success(translate('messages.opportunity_deleted_successfully'));
        return back();
    }

    public function faqs(Request $request){
        $key = explode(' ', $request['search']);
        $faqs=  ReactFaq::latest()
        ->when(isset($key) , function ($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('question', 'like', "%{$value}%")->orWhere('answer', 'like', "%{$value}%");
                }
            });
        })
        ->paginate(config('default_pagination'));
        $language=BusinessSetting::where('key','language')->first()?->value ?? null ;
        $faqCount = ReactFaq::max('priority')??0;
        $priorities = ReactFaq::pluck('priority');
        return view('admin-views.registration-page.react.faq', compact('faqs','language','faqCount','priorities'));
    }

    public function faq_store(Request $request){
        $request->validate([
            'question' => 'required|max:100',
            'answer' => 'required|max:1000',
            'priority' => 'required'
        ]);

        if($request->question[array_search('default', $request->lang)] == '' || $request->answer[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_question_and_answer_is_required'));
            return back();
            }

            DB::transaction(function () use ($request) {
                $existing = ReactFaq::where('priority', $request->priority)->first();

                if ($existing) {
                    $newPriority = ReactFaq::max('priority') + 1;
                    $existing->update(['priority' => $newPriority]);
                }
                $faq = new ReactFaq();
                $faq->question = $request->question[array_search('default', $request->lang)];
                $faq->answer = $request->answer[array_search('default', $request->lang)];
                $faq->priority = $request->priority;
                $faq->save();

                $this->handleTranslations($request->lang, $request->question, $faq, 'faq_question','question');
                $this->handleTranslations($request->lang, $request->answer, $faq, 'faq_answer','answer');
            });


        Toastr::success(translate('messages.faq_added_successfully'));
        return back();
    }

    public function faq_status(Request $request)
    {

        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $faqs = ReactFaq::findOrFail($request->id);
        $faqs->status = $request->status;
        $faqs->save();
        Toastr::success(translate('messages.faq_status_updated'));
        return back();
    }


    public function faq_edit($id)
    {
        $faq = ReactFaq::withoutGlobalScope('translate')->with('translations')->findOrFail($id);
        return view('admin-views.landing_page.faq_edit', compact('faq'));
    }

    public function faq_update(Request $request, $id)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $request->validate([
            'question' => 'required|max:100',
            'answer' => 'required|max:1000',
            'priority' => 'required'
        ]);

        if (
            $request->question[array_search('default', $request->lang)] == '' ||
            $request->answer[array_search('default', $request->lang)] == ''
        ) {
            Toastr::error(translate('default_question_and_answer_is_required'));
            return back();
        }

        DB::transaction(function () use ($request, $id) {
            $faq = ReactFaq::findOrFail($id);

            $existing = ReactFaq::where('priority', $request->priority)
                ->where('id', '!=', $id)
                ->first();

            if ($existing) {
                // Swap priorities
                $tempPriority = $faq->priority;
                $faq->priority = $existing->priority;
                $existing->priority = $tempPriority;

                $existing->save();
            } else {
                $faq->priority = $request->priority;
            }

            $faq->question = $request->question[array_search('default', $request->lang)];
            $faq->answer = $request->answer[array_search('default', $request->lang)];
            $faq->save();

            $this->handleTranslations($request->lang, $request->question, $faq, 'faq_question', 'question');
            $this->handleTranslations($request->lang, $request->answer, $faq, 'faq_answer', 'answer');
        });

        Toastr::success(translate('messages.faq_updated_successfully'));
        return back();
    }



    public function faq_destroy(ReactFaq $faq)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.delete_option_is_disable_for_demo'));
            return back();
        }

        Helpers::check_and_delete('faq_image/' , $faq->image);

        $faq?->translations()?->delete();
        $faq?->delete();
        Toastr::success(translate('messages.faq_deleted_successfully'));
        return back();
    }


    private function updateOrCreateSetting($key, $value)
    {
        $setting = DataSetting::firstOrNew([
            'type' => 'react_registration_page',
            'key' => $key,
        ]);
        $setting->value = $value;
        $setting->type = 'react_registration_page';
        $setting->save();

        return $setting;
    }

    private function handleTranslations($langs, $values, $model, $key, $attribute)
    {
        $defaultLang = str_replace('_', '-', app()->getLocale());

        foreach ($langs as $index => $locale) {
            $value = $values[$index] ?? null;

            if ($locale == $defaultLang && empty($value)) {
                $value = $model->$attribute;
            }

            if (!empty($value) && $locale !== 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type' => get_class($model),
                        'translationable_id' => $model->id,
                        'locale' => $locale,
                        'key' => $key,
                    ],
                    ['value' => $value]
                );
            }
        }
    }

}

