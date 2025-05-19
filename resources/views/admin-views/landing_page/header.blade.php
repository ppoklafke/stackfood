@extends('layouts.admin.app')
@section('title', translate('messages.Admin_Landing_Page'))
@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                    </div>
                    <span>
                        {{ translate('Admin_Landing_Page') }}
                    </span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works!')}}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.landing_page.top_menu.admin_landing_menu')
            </div>
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

        <div class="d-flex justify-content-between __gap-12px mb-3">
            <h5 class="card-title d-flex align-items-center">
                <span class="card-header-icon mr-2">
                    <img src="{{dynamicAsset('public/assets/admin/img/react_header.png')}}" alt="" class="mw-100">
                </span>
                {{translate('Header_Content_Section')}}
            </h5>
        </div>
        <div class="card">
            <form action="{{ route('admin.landing_page.settings', 'header-data') }}" method="post">
                @csrf
                <div class="card-body">
                    <div class="lang_form default-form">
                        <div class="row g-4" >
                            <input type="hidden" name="lang[]" value="default">
                            <div class="col-sm-6">
                                <label for="header_title" class="form-label">{{translate('Title')}}
                                    <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_50_characters') }}">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </label>
                                <input type="text"  maxlength="50"  id="header_title" name="header_title[]" value="{{ $header_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('messages.Enter_Title...')}}">
                            </div>
                            <div class="col-sm-6">
                                <label for="header_sub_title" class="form-label">{{translate('Subtitle')}}
                                    <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_100_characters') }}">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </label>
                                <input type="text" maxlength="100"   id="header_sub_title" name="header_sub_title[]" value="{{ $header_sub_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('messages.Enter_Subtitle...')}}">
                            </div>
                            <div class="col-sm-12">
                                <label for="header_tag_line" class="form-label">{{translate('Tagline')}}
                                    <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_tagline_within_40_characters') }}">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </label>
                                <input type="text" maxlength="40"  id="header_tag_line" name="header_tag_line[]" value="{{ $header_tag_line?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('messages.Enter Tag Line')}}">
                            </div>
                        </div>
                    </div>

                    @if($language)
                    @foreach(json_decode($language) as $lang)
                    <?php
                        if($header_title?->translations){
                                $header_title_translate = [];
                                foreach($header_title?->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='header_title'){
                                        $header_title_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($header_sub_title?->translations){
                                $header_sub_title_translate = [];
                                foreach($header_sub_title?->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='header_sub_title'){
                                        $header_sub_title_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }

                        if($header_tag_line?->translations){
                                $header_tag_line_translate = [];
                                foreach($header_tag_line?->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='header_tag_line'){
                                        $header_tag_line_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }

                        ?>
                    <div class="d-none lang_form" id="{{$lang}}-form">

                            <input type="hidden" name="lang[]" value="{{$lang}}">

                            <div class="row g-4" >
                                <div class="col-sm-6">
                                    <label for="header_title{{$lang}}" class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_50_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span></label>
                                    <input id="header_title{{$lang}}" type="text" maxlength="50"  name="header_title[]" value="{{$header_title_translate[$lang]['value']??''}}"class="form-control" placeholder="{{translate('messages.Enter_Title...')}}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="header_sub_title{{$lang}}" class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_100_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <input id="header_sub_title{{$lang}}" type="text" maxlength="100"  name="header_sub_title[]" value="{{$header_sub_title_translate[$lang]['value']??''}}" class="form-control" placeholder="{{translate('messages.Enter_Subtitle...')}}">
                                </div>
                                <div class="col-sm-12">
                                    <label for="header_tag_line{{$lang}}" class="form-label">{{translate('Tagline')}} ({{strtoupper($lang)}})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_tagline_within_40_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <input id="header_tag_line{{$lang}}" type="text" maxlength="40" name="header_tag_line[]" value="{{$header_tag_line_translate[$lang]['value']??''}}" class="form-control" placeholder="{{translate('messages.Enter Tag Line')}}">
                                </div>
                            </div>
                    </div>

                    @endforeach
                    @endif
                        <br>
                        <label class="form-label">
                        {{ translate('messages.Button_Content') }}
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="__bg-F8F9FC-card">
                                    <div class="lang_form default-form">
                                        <div class="form-group mb-md-0">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label for="header_app_button_name" class="form-label text-capitalize m-0">
                                                        {{translate('Button_Name')}}
                                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_button_name_within_10_characters') }}">
                                                            <i class="tio-info-outined"></i>
                                                        </span>
                                                    </label>
                                            </div>
                                            <input id="header_app_button_name" type="text" maxlength="10" value="{{ $header_app_button_name?->getRawOriginal('value') ?? null }}" placeholder="{{translate('Ex:_Order_now')}}" class="form-control h--45px" name="header_app_button_name[]" >
                                        </div>
                                    </div>

                                    @if($language)
                                        @foreach(json_decode($language) as $lang)
                                            <?php

                                                if($header_app_button_name?->translations){
                                                        $header_app_button_name_translate = [];
                                                        foreach($header_app_button_name?->translations as $t)
                                                        {
                                                            if($t->locale == $lang && $t->key=='header_app_button_name'){
                                                                $header_app_button_name_translate[$lang]['value'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                ?>

                                            <div class="d-none lang_form" id="{{$lang}}-form1">
                                                <div class="form-group mb-md-0">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <label for="header_app_button_name{{$lang}}" class="form-label text-capitalize m-0">
                                                                {{translate('Button_Name')}} ({{strtoupper($lang)}})
                                                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_button_name_within_10_characters') }}">
                                                                    <i class="tio-info-outined"></i>
                                                                </span>
                                                            </label>
                                                    </div>
                                                    <input id="header_app_button_name{{$lang}}" type="text" maxlength="10"  value="{{$header_app_button_name_translate[$lang]['value']??''}}" placeholder="{{translate('Ex:_Order_now')}}" class="form-control h--45px" name="header_app_button_name[]" >
                                                </div>
                                            </div>

                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="__bg-F8F9FC-card">
                                    <div class="form-group mb-md-0">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-capitalize m-0">
                                                {{translate('Redirect_Link')}}
                                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Add_the_link/address_where_the_button_will_redirect.') }}">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                                <label class="toggle-switch toggle-switch-sm m-0">
                                                    <input type="checkbox" {{$header_app_button_status  == 1 ? 'checked': '' }} id="button_status" value="1"
                                                        data-id="button_status"
                                                        data-type="status"
                                                        data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/mail-success.png') }}"
                                                        data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/mail-warning.png') }}"
                                                        data-title-on="<strong>{{translate('Want_to_enable_the_Header_button_here')}}</strong>"
                                                        data-title-off="<strong>{{translate('Want_to_disable_the_Header_button_here')}}</strong>"
                                                        data-text-on="<p>{{translate('If_enabled,_everyone_can_see_the_Header_button_on_the_landing_page')}}</p>"
                                                        data-text-off="<p>{{translate('If_disabled,_Header_button_will_be_hidden_from_the_landing_page')}}</p>"
                                                        class="status toggle-switch-input dynamic-checkbox"
                                                        name="header_app_button_status" >
                                                    <span class="toggle-switch-label text mb-0">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                    </div>
                                        <input type="url"  placeholder="{{translate('Ex:_https://www.apple.com/app-store/')}}" class="form-control h--45px" name="redirect_link" value="{{  $header_button_content ?? null }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit"   class="btn btn--primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
        <div class="d-flex justify-content-between __gap-12px mb-3">
            <h5 class="card-title d-flex align-items-center">
                <span class="card-header-icon mr-2">
                    <img src="{{dynamicAsset('public/assets/admin/img/react_header.png')}}" alt="" class="mw-100">
                </span>
                {{translate('Image_Content')}}
            </h5>
        </div>
        <div class="card">
            <form action="{{ route('admin.landing_page.settings', 'header-data-images') }}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="card-body">

                <div class="d-flex gap-40px">
                    <div>
                        <label class="form-label d-block mb-2">
                            {{translate('Content_Image')}}  <span class="text--primary">{{translate('(800x800px)')}}</span>
                        </label>
                            <div class="position-relative">
                                <label class="upload-img-3 m-0 d-block">
                                        <div class="img">
                                            <img  src="{{ $header_content_image_full_url }}"
                                                    data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-6.png')}}"
                                                    class="vertical-img max-w-187px onerror-image" alt="">
                                        </div>
                                    <input type="file"   name="header_content_image" hidden="">
                                </label>

                                    @if (isset($image_content['header_content_image'] ))
                                    <span id="header_content_image"
                                            class="remove_image_button remove-image"
                                            data-id="header_content_image"
                                            data-title="{{translate('Warning!')}}"
                                            data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"
                                        > <i class="tio-clear"></i></span>
                                    @endif
                            </div>
                    </div>
                    <div class="d-flex flex-column">
                        <div>
                            <label class="form-label d-block mb-2">
                                {{translate('Section_Background_Image')}}  <span class="text--primary">{{translate('(1600x1700px)')}}</span>
                            </label>
                            <div class="position-relative d-inline-block">
                                <label class="upload-img-3 m-0 d-block ">
                                    <div class="img">
                                        <img  src="{{ $header_bg_image_full_url }}"
                                                data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-6.png')}}"
                                                class="vertical-img max-w-187px onerror-image" alt="">

                                    </div>
                                    <input type="file"   name="header_bg_image" hidden="">
                                </label>
                                    @if (isset($image_content['header_bg_image'] ))
                                    <span id="remove_image"
                                            class="remove_image_button remove-image"
                                            data-id="remove_image"
                                            data-title="{{translate('Warning!')}}"
                                            data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"
                                        > <i class="tio-clear"></i></span>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn--container justify-content-end mt-3">
                    <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                    <button type="submit"   class="btn btn--primary">{{translate('Save')}}</button>
                </div>
            </div>
            </form>


            <form  id="remove_image_form" action="{{ route('admin.remove_image') }}" method="post">
            @csrf
                <input type="hidden" name="id" value="{{  $header_image_content?->id}}" >
                <input type="hidden" name="json" value="1" >
                <input type="hidden" name="model_name" value="DataSetting" >
                <input type="hidden" name="image_path" value="header_image" >
                <input type="hidden" name="field_name" value="header_bg_image" >
            </form>
            <form  id="header_content_image_form" action="{{ route('admin.remove_image') }}" method="post">
            @csrf
                <input type="hidden" name="id" value="{{  $header_image_content?->id}}" >
                <input type="hidden" name="json" value="1" >
                <input type="hidden" name="model_name" value="DataSetting" >
                <input type="hidden" name="image_path" value="header_image" >
                <input type="hidden" name="field_name" value="header_content_image" >
            </form>



        </div>
        <br>
        <div class="d-flex justify-content-between __gap-12px mb-3">
            <h5 class="card-title d-flex align-items-center">
                <span class="card-header-icon mr-2">
                    <img src="{{dynamicAsset('public/assets/admin/img/react_header.png')}}" alt="" class="mw-100">
                </span>
                {{translate('Floating_Icon_Content')}}
            </h5>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.landing_page.settings', 'header-data-floating-icon') }}" method="post">
                    @csrf
                <div class="row g-4">
                    <div class="col-sm-6 col-md-3">

                        <label  for="header_floating_total_order" class="form-label">{{translate('Total_Order')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_number_of_orders_you_have_completed') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="number" min="0"  id="header_floating_total_order" name="header_floating_total_order" value="{{ $header_floating_content['header_floating_total_order'] ?? null }}" class="form-control" placeholder="{{translate('messages.Ex:_34')}}">
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <label for="header_floating_total_user"  class="form-label">{{translate('Total_User')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_number_of_total_users_on_your_system') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="number" min="0"   id="header_floating_total_user" name="header_floating_total_user"  value="{{ $header_floating_content['header_floating_total_user'] ?? null }}" class="form-control" placeholder="{{translate('messages.Ex:_34')}}">
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <label for="header_floating_total_reviews"  class="form-label">{{translate('Total_Reviews')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_number_of_reviews_you_have_received_from_customers') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="number" min="0"  id="header_floating_total_reviews" name="header_floating_total_reviews"  value="{{ $header_floating_content['header_floating_total_reviews'] ?? null }}"  class="form-control" placeholder="{{translate('messages.Ex:_34')}}">
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


@endsection
