@php use App\CentralLogics\Helpers; @endphp
@extends('layouts.admin.app')

@section('title', translate('messages.landing_page_settings'))

@section('content')

<div class="content container-fluid">
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-start">
            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{ dynamicAsset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                </div>
                <span>
                    {{ translate('React_Landing_Page') }}
                </span>
            </h1>
            <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                <div>
                    <i class="tio-info-outined"></i>
                </div>
            </div>
        </div>
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            @include('admin-views.landing_page.top_menu.react_landing_menu')
        </div>
    </div>



        <div class="d-flex justify-content-between __gap-12px mt-3 mb-2">
            <h5 class="title mr-2 d-flex align-items-center">
                <span class="card-header-icon mr-2">
                    <i class="tio-settings-outlined"></i>
                </span>
                {{translate('Fixed_Banner_Section')}}
            </h5>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.react_landing_page.settings', 'react-download-apps-banner-image') }}" enctype="multipart/form-data"     method="post">
                    @csrf
                    <label class="form-label d-block mb-2">
                        {{ translate('Banner_Image') }} <span class="text--primary">({{ translate('1500x300') }})</span>
                    </label>
                    <div class="position-relative d-inline-block">
                        <label class="upload-img-3 upload-image-5 border--dashed border-1px m-0 rounded border-9EADC1">
                            <div class="img">
                                <img  src="{{ Helpers::get_full_url('react_download_apps_image', $react_download_apps_banner_image?->value,$react_download_apps_banner_image?->storage[0]?->value ?? 'public','upload_image')}}"
                                data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload.png')}}"
                                class="onerror-image" alt="">
                            </div>
                            <input type="file" required name="react_download_apps_banner_image" hidden>
                        </label>
                            @if ($react_download_apps_banner_image?->value)
                            <span id="remove_image" class="remove_image_button remove-image"
                            data-id="remove_image"
                            data-title="{{translate('Warning!')}}"
                            data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"
                                > <i class="tio-clear"></i></span>
                            @endif
                    </div>

                <div class="btn--container justify-content-end mt-3">
                    <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                    <button type="submit"   class="btn btn--primary">{{translate('Save')}}</button>
                </div>
                </form>
            </div>
        </div>

        <div class="d-flex justify-content-between __gap-12px  mt-5 mb-2">
            <h5 class="card-title d-flex align-items-center">
                <span class="card-header-icon mr-2">
                    <i class="tio-settings-outlined"></i>
                </span>
                {{translate('Customer_App_Download_Section')}}
            </h5>
        </div>
        @php($default_lang = str_replace('_', '-', app()->getLocale()))
        @if($language)
            <ul class="nav nav-tabs mb-4 border-0">
                <li class="nav-item">
                    <a class="nav-link lang_link active"
                    href="#"
                    id="default-link">{{translate('messages.default')}}</a>
                </li>
                @foreach (json_decode($language) as $lang)
                    <li class="nav-item">
                        <a class="nav-link lang_link"
                            href="#"
                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.react_landing_page.settings', 'react-download-apps') }}" enctype="multipart/form-data"     method="post">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="lang_form default-form">
                                <div class="row g-3">
                                        <input type="hidden" name="lang[]" value="default">
                                        <div class="col-12">
                                            <label for="react_download_apps_title"  class="form-label">{{translate('Title')}}  ({{ translate('default') }})
                                                     <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                            </label>
                                            <input id="react_download_apps_title" type="text" maxlength="30" class="form-control" placeholder="{{translate('messages.Enter_Title...')}}" name="react_download_apps_title[]"   value="{{ $react_download_apps_title?->getRawOriginal('value') ?? '' }}" >
                                        </div>
                                        <div class="col-12">
                                            <label for="react_download_apps_sub_title" class="form-label">{{translate('Subtitle')}}  ({{ translate('default') }})
                                                  <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_subtitle_within_100_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                            </label>
                                            <input id="react_download_apps_sub_title" type="text" maxlength="100" class="form-control" placeholder="{{translate('Enter_Sub_Title')}}" name="react_download_apps_sub_title[]"  value="{{ $react_download_apps_sub_title?->getRawOriginal('value') ?? '' }}">
                                        </div>
                                        <div class="col-12">
                                            <label for="react_download_apps_tag" class="form-label">{{translate('Tag_line')}}  ({{ translate('default') }})
                                                <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('Write_the_Tagline_within_100_characters') }}">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                            <input id="react_download_apps_tag" type="text" maxlength="100" class="form-control" placeholder="{{translate('Enter_Tag_line')}}" name="react_download_apps_tag[]"  value="{{ $react_download_apps_tag?->getRawOriginal('value') ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                            @if ($language)
                                @forelse(json_decode($language) as $lang)
                                    <?php
                                        if($react_download_apps_title?->translations){
                                                $react_download_apps_title_translate = [];
                                                foreach($react_download_apps_title->translations as $t)
                                                {
                                                    if($t->locale == $lang && $t->key=='react_download_apps_title'){
                                                        $react_download_apps_title_translate[$lang]['value'] = $t->value;
                                                    }
                                                }
                                            }
                                        if($react_download_apps_sub_title?->translations){
                                                $react_download_apps_sub_title_translate = [];
                                                foreach($react_download_apps_sub_title->translations as $t)
                                                {
                                                    if($t->locale == $lang && $t->key=='react_download_apps_sub_title'){
                                                        $react_download_apps_sub_title_translate[$lang]['value'] = $t->value;
                                                    }
                                                }
                                            }
                                        if($react_download_apps_tag?->translations){
                                                $react_download_apps_tag_translate = [];
                                                foreach($react_download_apps_tag->translations as $t)
                                                {
                                                    if($t->locale == $lang && $t->key=='react_download_apps_tag'){
                                                        $react_download_apps_tag_translate[$lang]['value'] = $t->value;
                                                    }
                                                }
                                            }
                                    ?>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                    <div class="d-none lang_form" id="{{$lang}}-form1">
                                        <div class="row g-3">
                                                <div class="col-12">
                                                    <label  for="react_download_apps_title{{$lang}}"  class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})
                                                         <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                                    </label>
                                                    <input  id="react_download_apps_title{{$lang}}"  type="text" maxlength="30" class="form-control" name="react_download_apps_title[]" placeholder="{{translate('messages.Enter_Title...')}}" value="{{ $react_download_apps_title_translate[$lang]['value'] ?? ''}}">
                                                </div>
                                                <div class="col-12">
                                                    <label  for="react_download_apps_sub_title{{$lang}}" class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                                                      <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_subtitle_within_100_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                                    </label>
                                                    <input  id="react_download_apps_sub_title{{$lang}}" type="text" maxlength="100" class="form-control" placeholder="{{translate('Enter_Sub_Title')}}" name="react_download_apps_sub_title[]" value="{{ $react_download_apps_sub_title_translate[$lang]['value'] ?? ''}}">
                                                </div>
                                                <div class="col-12">
                                                    <label for="react_download_apps_tag{{$lang}}"  class="form-label">{{translate('Tag_line')}} ({{strtoupper($lang)}})
                                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('Write_the_Tagline_within_100_characters') }}">
                                                            <i class="tio-info-outined"></i>
                                                        </span>
                                                    </label>
                                                    <input id="react_download_apps_tag{{$lang}}"  type="text" maxlength="100" class="form-control" placeholder="{{translate('Enter_Tag_line')}}" name="react_download_apps_tag[]" value="{{ $react_download_apps_tag_translate[$lang]['value'] ?? ''}}">
                                                </div>
                                        </div>
                                    </div>
                                    @empty
                                @endforelse
                            @endif
                        </div>

                            <div class="col-md-6">
                                <label class="form-label d-block mb-2">
                                {{ translate('Banner') }}  <span class="text--primary">({{ translate('1:1') }} )</span>
                                </label>
                                <div class="position-relative d-inline-block">
                                    <label class="upload-img-3 m-0">
                                        <div class="img">
                                            <img  src="{{ Helpers::get_full_url('react_download_apps_image', $react_download_apps_image?->value,$react_download_apps_image?->storage[0]?->value ?? 'public','upload_1_1')}}"
                                            data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-3.png')}}"
                                            class="vertical-img max-w-187px onerror-image" alt="">
                                        </div>
                                        <input type="file" name="react_download_apps_image" hidden>
                                    </label>

                                    @if ($react_download_apps_image?->value)
                                    <span id="remove_image_1" class="remove_image_button remove-image"
                                            data-id="remove_image_1"
                                            data-title="{{translate('Warning!')}}"
                                            data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"
                                        > <i class="tio-clear"></i></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <h5 class="card-title mb-3">
                                    <img src="http://localhost/stackfood/public/assets/admin/img/andriod.png" class="mr-2" alt="">
                                    {{ translate('messages.Playstore_Button') }}
                                </h5>
                                <div class="__bg-F8F9FC-card">
                                    <div class="form-group mb-md-0">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label text-capitalize m-0">
                                                    {{translate('messages.Download_Link')}}
                                                    <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Add_the_Customer_app_download_address_(Play_Store)_where_the_button_will_redirect') }}">
                                                        <i class="tio-info-outined"></i>
                                                    </span>
                                                </label>
                                            <label class="toggle-switch toggle-switch-sm m-0">
                                                <input type="checkbox"  id="react_download_apps_button_status"
                                                data-id="react_download_apps_button_status"
                                                data-type="toggle"
                                                data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/play-store-on.png') }}"
                                                data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/play-store-off.png') }}"
                                                data-title-on="<strong>{{translate('Want_to_enable_the_Customer_App_Download_button_here')}}</strong>"
                                                data-title-off="<strong>{{translate('Want_to_disable_the_Customer_App_Download_button')}}</strong>"
                                                data-text-on="<p>{{translate('If_enabled,_everyone_can_see_the_button_on_the_landing_page')}}</p>"
                                                data-text-off="<p>{{translate('If_disabled,_it_will_be_hidden_from_the_landing_page')}}</p>"
                                                class="status toggle-switch-input dynamic-checkbox-toggle"
                                                name="react_download_apps_button_status" value="1"  {{ $react_download_apps_button_status?->value ?? null  == 1 ? 'checked': ''  }}>
                                                <span class="toggle-switch-label text mb-0">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <input  type="url" placeholder="{{translate('Ex:_https://www.apple.com/app-store/')}}" class="form-control h--45px" name="react_download_apps_button_name" value="{{ $react_download_apps_button_name?->getRawOriginal('value') ?? null }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title mb-3">
                                    <img src="http://localhost/stackfood/public/assets/admin/img/apple.png" class="mr-2" alt="">
                                    {{ translate('messages.App_Store_Button') }}
                                </h5>
                                <div class="__bg-F8F9FC-card">
                                    <div class="form-group mb-md-0">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-capitalize m-0">
                                                {{translate('messages.Download_Link')}}
                                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Add_the_Customer_app_download_address_(App_Store)_where_the_button_will_redirect') }}">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox"
                                            id="react_download_apps_link_status"
                                                    data-id="react_download_apps_link_status"
                                                    data-type="toggle"
                                                    data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/apple-on.png') }}"
                                                    data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/apple-off.png') }}"
                                                    data-title-on="<strong>{{translate('Want_to_enable_the_Customer_App_Download_button_here')}}</strong>"
                                                    data-title-off="<strong>{{translate('Want_to_disable_the_Customer_App_Download_button')}}</strong>"
                                                    data-text-on="<p>{{translate('If_enabled,_everyone_can_see_the_button_on_the_landing_page')}}</p>"
                                                    data-text-off="<p>{{translate('If_disabled,_it_will_be_hidden_from_the_landing_page')}}</p>"
                                                    class="status toggle-switch-input dynamic-checkbox-toggle"
                                                    name="react_download_apps_link_status" value="1"  {{ $react_download_apps_link_data['react_download_apps_link_status'] ?? null  == 1 ? 'checked': ''  }}>
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                        <input type="url" placeholder="{{translate('Ex:_https://www.apple.com/app-store/')}}" class="form-control h--45px" name="react_download_apps_link" value="{{ $react_download_apps_link_data['react_download_apps_link']  ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit"   class="btn btn--primary">{{translate('Save')}}</button>
                    </div>
            </form>
            </div>
        </div>
        </div>




<form  id="remove_image_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $react_download_apps_banner_image?->id}}" >
    <input type="hidden" name="model_name" value="DataSetting" >
    <input type="hidden" name="image_path" value="react_download_apps_image" >
    <input type="hidden" name="field_name" value="value" >
</form>
<form  id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $react_download_apps_image?->id}}" >
    <input type="hidden" name="model_name" value="DataSetting" >
    <input type="hidden" name="image_path" value="react_download_apps_image" >
    <input type="hidden" name="field_name" value="value" >
</form>


@endsection
