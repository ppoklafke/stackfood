@php use App\CentralLogics\Helpers; @endphp
@extends('layouts.admin.app')
@section('title', translate('messages.Admin_Landing_Page'))
@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px"
                             alt="public">
                    </div>
                    <span>
                    {{ translate('Admin_Landing_Page') }}
                </span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal"
                     data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
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
                    <a class="nav-link lang_link active" href="#"
                       id="default-link">{{translate('messages.default')}}</a>
                </li>
                @foreach (json_decode($language) as $lang)
                    <li class="nav-item">
                        <a class="nav-link lang_link" href="#"
                           id="{{ $lang }}-link">{{ Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
        <div class="d-flex justify-content-between __gap-12px mb-3">
            <h5 class="card-title d-flex align-items-center">
            <span class="card-header-icon mr-2">
                <img src="{{dynamicAsset('public/assets/admin/img/react_header.png')}}" alt="" class="mw-100">
            </span>
                {{translate('Title_&_Subtitle_Section')}}
            </h5>
        </div>
        <div class="card">
            <form action="{{ route('admin.landing_page.settings', 'why-choose-us-data') }}" method="post">
                @csrf
                <div class="card-body">
                    <div class="row g-3 lang_form" id="default-form">
                        <input type="hidden" name="lang[]" value="default">
                        <div class="col-sm-6">
                            <label for="why_choose_us_title" class="form-label">{{translate('Title')}}
                                <span class="input-label-secondary text--title" data-toggle="tooltip"
                                      data-placement="right"
                                      data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                            </label>
                            <input id="why_choose_us_title" type="text" maxlength="20" name="why_choose_us_title[]"
                                   value="{{ $why_choose_us_title?->getRawOriginal('value') ?? null}}"
                                   class="form-control" placeholder="{{translate('Enter_Title')}}">
                        </div>
                        <div class="col-sm-6">
                            <label for="why_choose_us_sub_title" class="form-label">{{translate('Subtitle')}}
                                <span class="input-label-secondary text--title" data-toggle="tooltip"
                                      data-placement="right"
                                      data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                            </label>
                            <input id="why_choose_us_sub_title" type="text" maxlength="70"
                                   name="why_choose_us_sub_title[]"
                                   value="{{ $why_choose_us_sub_title?->getRawOriginal('value') ?? null}}"
                                   class="form-control" placeholder="{{translate('Enter_Title')}}">
                        </div>
                    </div>
                    @if($language)
                        @foreach(json_decode($language) as $lang)
                                <?php
                                if ($why_choose_us_title?->translations && count($why_choose_us_title?->translations)) {
                                    $why_choose_us_title_translate = [];
                                    foreach ($why_choose_us_title->translations as $t) {
                                        if ($t->locale == $lang && $t->key == 'why_choose_us_title') {
                                            $why_choose_us_title_translate[$lang]['value'] = $t->value;
                                        }
                                    }
                                }
                                if ($why_choose_us_sub_title?->translations && count($why_choose_us_sub_title?->translations)) {
                                    $why_choose_us_sub_title_translate = [];
                                    foreach ($why_choose_us_sub_title->translations as $t) {
                                        if ($t->locale == $lang && $t->key == 'why_choose_us_sub_title') {
                                            $why_choose_us_sub_title_translate[$lang]['value'] = $t->value;
                                        }
                                    }
                                }
                                ?>

                            <div class="row g-3 d-none lang_form" id="{{$lang}}-form">
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                                <div class="col-sm-6">
                                    <label for="why_choose_us_title{{$lang}}" class="form-label">{{translate('Title')}}
                                        ({{strtoupper($lang)}})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                              data-placement="right"
                                              data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                                    </label>
                                    <input id="why_choose_us_title{{$lang}}" type="text" maxlength="20"
                                           name="why_choose_us_title[]"
                                           value="{{ $why_choose_us_title_translate[$lang]['value'] ?? '' }}"
                                           class="form-control" placeholder="{{translate('Enter_Title')}}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="why_choose_us_sub_title{{$lang}}"
                                           class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                              data-placement="right"
                                              data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                                    </label>
                                    <input id="why_choose_us_sub_title{{$lang}}" type="text" maxlength="70"
                                           name="why_choose_us_sub_title[]"
                                           value="{{ $why_choose_us_sub_title_translate[$lang]['value'] ?? '' }}"
                                           class="form-control" placeholder="{{translate('Enter_Title')}}">
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
        <div class="d-flex justify-content-between __gap-12px mb-3">
            <h5 class="card-title d-flex align-items-center">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>

                {{translate('messages.First_image')}}
            </h5>
        </div>
        <div class="card">
            <form action="{{ route('admin.landing_page.settings', 'why-choose-us-data-1') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="max-w-555px">
                                <div class="form-group lang_form default-form">
                                    <label for="title1" class="form-label">{{translate('Title')}} <span
                                            class="input-label-secondary text--title" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span> </label>
                                    <input id="title1" type="text" name="title[]" maxlength="30" class="form-control"
                                           placeholder="{{translate('Enter_Title')}}"
                                           value="{{ $data_1?->getRawOriginal('value') ?? ''}}">
                                    <input type="hidden" name="lang[]" value="default">
                                </div>
                                <input type="hidden" value="why_choose_us_title_1" name="key">

                                <input type="hidden" value="why_choose_us_image_1" name="key_image">

                                @if($language)
                                    @forelse(json_decode($language) as $lang)
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                            <?php
                                            if ($data_1?->translations && count($data_1?->translations)) {
                                                $why_choose_us_title_1_translate = [];
                                                foreach ($data_1->translations as $t) {
                                                    if ($t->locale == $lang && $t->key == 'why_choose_us_title_1') {
                                                        $why_choose_us_title_1_translate[$lang]['value'] = $t->value;
                                                    }
                                                }
                                            }
                                            ?>

                                        <div class="form-group d-none lang_form" id="{{$lang}}-form1">
                                            <label for="title1{{$lang}}" class="form-label">{{translate('Title')}}
                                                ({{strtoupper($lang)}}) <span class="input-label-secondary text--title"
                                                                              data-toggle="tooltip"
                                                                              data-placement="right"
                                                                              data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span></label>
                                            <input id="title1{{$lang}}" type="text" name="title[]" maxlength="30"
                                                   class="form-control" placeholder="{{translate('Enter_Title')}}"
                                                   value="{{ $why_choose_us_title_1_translate[$lang]['value'] ?? '' }}">
                                        </div>
                                    @empty
                                    @endforelse
                                @endif
                                <div class="d-flex flex-column">
                                    <label class="form-label d-block mb-2">
                                        {{translate('messages.Section_Background_Image')}} <span
                                            class="text--primary">{{translate('(730x215px)')}}</span>
                                    </label>
                                    <div class="position-relative">

                                        <label class="upload-img-3 m-0 d-block my-auto">
                                            <div class="img">
                                                <img src="{{ Helpers::get_full_url('why_choose_us_image', $data_1_image?->value,$data_1_image?->storage[0]?->value ?? 'public')}}"
                                                     data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-5.png')}}"
                                                     class="vertical-img max-w-555px onerror-image" alt="">
                                            </div>
                                            <input type="file" name="image" hidden="">
                                        </label>
                                        @if ($data_1_image?->value)
                                            <span id="remove_image_1"
                                                  class="remove_image_button remove-image"
                                                  data-id="remove_image_1"
                                                  data-title="{{translate('Warning!')}}"
                                                  data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"> <i
                                                    class="tio-clear"></i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
        <div class="d-flex justify-content-between __gap-12px mb-3">
            <h5 class="card-title d-flex align-items-center">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                {{translate('messages.Second_image')}}
            </h5>

        </div>
        <div class="card">
            <form action="{{ route('admin.landing_page.settings', 'why-choose-us-data-1') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="max-w-555px">
                                <div class="form-group lang_form default-form">
                                    <label for="title2" class="form-label">{{translate('Title')}} <span
                                            class="input-label-secondary text--title" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span> </label>
                                    <input id="title2" type="text" name="title[]" maxlength="30" class="form-control"
                                           placeholder="{{translate('Enter_Title')}}"
                                           value="{{ $data_2?->getRawOriginal('value') ?? ''}}">
                                    <input type="hidden" name="lang[]" value="default">
                                </div>
                                <input type="hidden" value="why_choose_us_title_2" name="key">

                                <input type="hidden" value="why_choose_us_image_2" name="key_image">

                                @if($language)
                                    @forelse(json_decode($language) as $lang)
                                        <input type="hidden" name="lang[]" value="{{$lang}}">

                                            <?php
                                            if ($data_2?->translations && count($data_2?->translations)) {
                                                $why_choose_us_title_2_translate = [];
                                                foreach ($data_2->translations as $t) {
                                                    if ($t->locale == $lang && $t->key == 'why_choose_us_title_2') {
                                                        $why_choose_us_title_2_translate[$lang]['value'] = $t->value;
                                                    }
                                                }
                                            }
                                            ?>

                                        <div class="form-group d-none lang_form" id="{{$lang}}-form2">
                                            <label for="title2{{$lang}}" class="form-label">{{translate('Title')}}
                                                ({{strtoupper($lang)}}) <span class="input-label-secondary text--title"
                                                                              data-toggle="tooltip"
                                                                              data-placement="right"
                                                                              data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span></label>
                                            <input id="title2{{$lang}}" type="text" name="title[]" maxlength="30"
                                                   class="form-control" placeholder="{{translate('Enter_Title')}}"
                                                   value="{{ $why_choose_us_title_2_translate[$lang]['value'] ?? '' }}">
                                        </div>
                                    @empty
                                    @endforelse
                                @endif
                                <div class="d-flex flex-column">
                                    <label class="form-label d-block mb-2">
                                        {{translate('messages.Section_Background_Image')}} <span
                                            class="text--primary">{{translate('(450x215px)')}}</span>
                                    </label>
                                    <div class="position-relative">

                                        <label class="upload-img-3 m-0 d-block my-auto">
                                            <div class="img">
                                                <img src="{{ Helpers::get_full_url('why_choose_us_image', $data_2_image?->value,$data_2_image?->storage[0]?->value ?? 'public')}}"
                                                     data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-5.png')}}"
                                                     class="vertical-img max-w-555px onerror-image" alt="">
                                            </div>
                                            <input type="file" name="image" hidden="">
                                        </label>
                                        @if ($data_2_image?->value)
                                            <span id="remove_image_2" class="remove_image_button remove-image"
                                                  data-id="remove_image_2"
                                                  data-title="{{translate('Warning!')}}"
                                                  data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"> <i
                                                    class="tio-clear"></i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
        <div class="d-flex justify-content-between __gap-12px mb-3">
            <h5 class="card-title d-flex align-items-center">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>

                {{translate('messages.Third_image')}}
            </h5>
        </div>
        <div class="card">
            <form action="{{ route('admin.landing_page.settings', 'why-choose-us-data-1') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="max-w-555px">
                                <div class="form-group lang_form default-form">
                                    <label for="title3" class="form-label">{{translate('Title')}} <span
                                            class="input-label-secondary text--title" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span> </label>
                                    <input id="title3" type="text" name="title[]" maxlength="30" class="form-control"
                                           placeholder="{{translate('Enter_Title')}}"
                                           value="{{ $data_3?->getRawOriginal('value')?? ''}}">
                                    <input type="hidden" name="lang[]" value="default">
                                </div>
                                <input type="hidden" value="why_choose_us_title_3" name="key">

                                <input type="hidden" value="why_choose_us_image_3" name="key_image">

                                @if($language)
                                    @forelse(json_decode($language) as $lang)
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                            <?php
                                            if ($data_3?->translations && count($data_3?->translations)) {
                                                $why_choose_us_title_3_translate = [];
                                                foreach ($data_3->translations as $t) {
                                                    if ($t->locale == $lang && $t->key == 'why_choose_us_title_3') {
                                                        $why_choose_us_title_3_translate[$lang]['value'] = $t->value;
                                                    }
                                                }
                                            }
                                            ?>

                                        <div class="form-group d-none lang_form" id="{{$lang}}-form3">
                                            <label for="title3{{$lang}}" class="form-label">{{translate('Title')}}
                                                ({{strtoupper($lang)}}) <span class="input-label-secondary text--title"
                                                                              data-toggle="tooltip"
                                                                              data-placement="right"
                                                                              data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span></label>
                                            <input id="title3{{$lang}}" type="text" name="title[]" maxlength="30"
                                                   class="form-control" placeholder="{{translate('Enter_Title')}}"
                                                   value="{{ $why_choose_us_title_3_translate[$lang]['value'] ?? '' }}">
                                        </div>
                                    @empty
                                    @endforelse
                                @endif

                                <div class="d-flex flex-column">
                                    <label class="form-label d-block mb-3">
                                        {{translate('messages.Section_Background_Image')}} <span
                                            class="text--primary">{{translate('(730x215px)')}}</span>
                                    </label>
                                    <div class="position-relative">

                                        <label class="upload-img-3 m-0 d-block my-auto">
                                            <div class="img">
                                                <img src="{{ Helpers::get_full_url('why_choose_us_image', $data_3_image?->value,$data_3_image?->storage[0]?->value ?? 'public')}}"
                                                     data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-5.png')}}"
                                                     class="vertical-img max-w-555px onerror-image" alt="">
                                            </div>
                                            <input type="file" name="image" hidden="">
                                        </label>
                                        @if ($data_3_image?->value)
                                            <span id="remove_image_3" class="remove_image_button remove-image"
                                                  data-id="remove_image_3"
                                                  data-title="{{translate('Warning!')}}"
                                                  data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"> <i
                                                    class="tio-clear"></i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
        <div class="d-flex justify-content-between __gap-12px mb-3">
            <h5 class="card-title d-flex align-items-center">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>

                {{translate('messages.Forth_image')}}
            </h5>
        </div>
        <div class="card">
            <form action="{{ route('admin.landing_page.settings', 'why-choose-us-data-1') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="max-w-555px">
                                <div class="form-group lang_form default-form">
                                    <label for="title4" class="form-label">{{translate('Title')}} <span
                                            class="input-label-secondary text--title" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span> </label>
                                    <input id="title4" type="text" name="title[]" maxlength="30" class="form-control"
                                           placeholder="{{translate('Enter_Title')}}"
                                           value="{{ $data_4?->getRawOriginal('value') ?? ''}}">
                                    <input type="hidden" name="lang[]" value="default">
                                </div>
                                <input type="hidden" value="why_choose_us_title_4" name="key">

                                <input type="hidden" value="why_choose_us_image_4" name="key_image">

                                @if($language)
                                    @forelse(json_decode($language) as $lang)
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                            <?php
                                            if ($data_4?->translations && count($data_4?->translations)) {
                                                $why_choose_us_title_4_translate = [];
                                                foreach ($data_4->translations as $t) {
                                                    if ($t->locale == $lang && $t->key == 'why_choose_us_title_4') {
                                                        $why_choose_us_title_4_translate[$lang]['value'] = $t->value;
                                                    }
                                                }
                                            }
                                            ?>

                                        <div class="form-group d-none lang_form" id="{{$lang}}-form4">
                                            <label for="title4{{$lang}}" class="form-label">{{translate('Title')}}
                                                ({{strtoupper($lang)}}) <span class="input-label-secondary text--title"
                                                                              data-toggle="tooltip"
                                                                              data-placement="right"
                                                                              data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span></label>
                                            <input id="title4{{$lang}}" type="text" name="title[]" maxlength="30"
                                                   class="form-control" placeholder="{{translate('Enter_Title')}}"
                                                   value="{{ $why_choose_us_title_4_translate[$lang]['value'] ?? '' }}">
                                        </div>
                                    @empty
                                    @endforelse
                                @endif
                                <div class="d-flex flex-column">
                                    <label class="form-label d-block mb-3">
                                        {{translate('messages.Section_Background_Image')}} <span
                                            class="text--primary">{{translate('(450x215px)')}}</span>
                                    </label>
                                    <div class="position-relative">

                                        <label class="upload-img-3 m-0 d-block my-auto">
                                            <div class="img">
                                                <img src="{{ Helpers::get_full_url('why_choose_us_image', $data_4_image?->value,$data_4_image?->storage[0]?->value ?? 'public')}}"
                                                     data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-5.png')}}"
                                                     class="vertical-img max-w-555px onerror-image" alt="">
                                            </div>
                                            <input type="file" name="image" hidden="">
                                        </label>
                                        @if ($data_4_image?->value)
                                            <span id="remove_image_4"
                                                  class="remove_image_button remove-image"
                                                  data-id="remove_image_4"
                                                  data-title="{{translate('Warning!')}}"
                                                  data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"> <i
                                                    class="tio-clear"></i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <form id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{  $data_1_image?->id}}">
        <input type="hidden" name="model_name" value="DataSetting">
        <input type="hidden" name="image_path" value="why_choose_us_image">
        <input type="hidden" name="field_name" value="value">
    </form>
    <form id="remove_image_2_form" action="{{ route('admin.remove_image') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{  $data_2_image?->id}}">
        <input type="hidden" name="model_name" value="DataSetting">
        <input type="hidden" name="image_path" value="why_choose_us_image">
        <input type="hidden" name="field_name" value="value">
    </form>
    <form id="remove_image_3_form" action="{{ route('admin.remove_image') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{  $data_3_image?->id}}">
        <input type="hidden" name="model_name" value="DataSetting">
        <input type="hidden" name="image_path" value="why_choose_us_image">
        <input type="hidden" name="field_name" value="value">
    </form>
    <form id="remove_image_4_form" action="{{ route('admin.remove_image') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{  $data_4_image?->id}}">
        <input type="hidden" name="model_name" value="DataSetting">
        <input type="hidden" name="image_path" value="why_choose_us_image">
        <input type="hidden" name="field_name" value="value">
    </form>
@endsection
