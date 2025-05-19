@extends('layouts.admin.app')

@section('title', translate('email_template'))
@push('css_or_js')
<link rel="stylesheet" href="{{asset('public/assets/admin/css/view-pages/email-templates.css')}}">
@endpush


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center __gap-15px">
                <h1 class="page-header-title mr-3 mb-0">
                    <span class="page-header-icon">
                        <img src="{{ asset('public/assets/admin/img/email-setting.png') }}" class="w--26" alt="">
                    </span>
                    <span>
                        {{ translate('messages.Email Templates') }}
                    </span>
                </h1>
                @include('admin-views.business-settings.email-format-setting.partials.email-template-options')
            </div>
            @include('admin-views.business-settings.email-format-setting.partials.restaurant-email-template-setting-links')
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active">
                <div class="card mb-3">
                    @php($mail_status=\App\Models\BusinessSetting::where('key','subscription_successful_mail_status_restaurant')->first()?->value ?? '0')

                    <div class="card-body">
                        <div class="maintenance-mode-toggle-bar d-flex flex-wrap justify-content-between border rounded align-items-center p-2">
                            <h5 class="text-capitalize m-0 text--primary pl-2">
                                {{translate('Send Mail on Subscription_Successful?')}}
                        <span class="form-label-secondary text--primary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('If_a_restaurant_successfully_subscribs_to_a_plan_they_will_get_a_confirmation_email.') }}">
                                    <img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="">
                                </span>
                            </h5>
                            <label class="toggle-switch toggle-switch-sm">

                                <input type="checkbox" class="status toggle-switch-input dynamic-checkbox"
                                       data-id="mail-status"
                                       data-type="status"
                                       data-image-on='{{asset('/public/assets/admin/img/modal')}}/place-order-on.png'
                                       data-image-off="{{asset('/public/assets/admin/img/modal')}}/place-order-off.png"
                                       data-title-on="{{translate('Want_to_enable_Subscription_Successful_mail?')}}"
                                       data-title-off="{{translate('Want_to_disable_Subscription_Successful_mail?')}}"
                                       data-text-on="<p>{{translate('If_enabled,_restaurants_will_get_a_Subscription_Successful_email_when_they_successfully_subscribs.')}}</p>"
                                       data-text-off="<p>{{translate('If_disabled,_restaurants_will_not_get_a_Subscription_Successful_email_when_a_restaurant_successfully_subscribs.')}}</p>"
                                       id="mail-status" {{$mail_status == '1'?'checked':''}}>

                                <span class="toggle-switch-label text mb-0">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                        <form action="{{route('admin.business-settings.email-status',['restaurant','subscription-successful',$mail_status == '1'?0:1])}}" method="get" id="mail-status_form">
                        </form>
                    </div>
                </div>
                @php($data=\App\Models\EmailTemplate::where('type','restaurant')->where('email_type', 'subscription-successful')->first())
                @php($template= $template ?? $data?->email_template ?? 5)
                <form action="{{ route('admin.business-settings.email-setup', ['restaurant','subscription-successful']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="email-format-wrapper">
                                <div class="left-content">
                                    <div class="d-inline-block">
                                        @include('admin-views.business-settings.email-format-setting.partials.email-template-section')
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            @include('admin-views.business-settings.email-format-setting.templates.email-format-'.$template)
                                        </div>
                                    </div>
                                </div>
                                <div class="right-content">
                                    <div class="d-flex flex-wrap justify-content-between __gap-15px mt-2 mb-5">
                                        @php($data=\App\Models\EmailTemplate::withoutGlobalScope('translate')->where('type','restaurant')->where('email_type', 'subscription-successful')->first())
                                        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                                        @php($language = $language->value ?? null)
                                        @php($defaultLang = str_replace('_', '-', app()->getLocale()))
                                        @if($language)
                                            <ul class="nav nav-tabs m-0 border-0">
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
                                        <div class="d-flex justify-content-end">
                                            <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center py-1" type="button" data-toggle="modal" data-target="#instructions">
                                                <strong class="mr-2">{{translate('Read Instructions')}}</strong>
                                                <div class="blinkings">
                                                    <i class="tio-info-outined"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-3">
                                            {{translate('Icon')}}
                                        </h5>
                                        <label class="custom-file">
                                            <input type="file" name="icon" id="mail-icon" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <span class="custom-file-label">{{ translate('messages.Choose File') }}</span>
                                        </label>
                                    </div>
                                    <br>
                                    <div>
                                        <h5 class="card-title mb-3">
                                            <img src="{{asset('public/assets/admin/img/pointer.png')}}" class="mr-2" alt="">
                                            {{translate('Header Content')}}
                                        </h5>
                                        @if ($language)
                                            <div class="__bg-F8F9FC-card default-form lang_form" id="default-form">
                                                <div class="form-group">
                                                    <label class="form-label">{{translate('Main Title')}}({{ translate('messages.default') }})</label>
                                                    <input type="text" name="title[]" value="{{ $data?->getRawOriginal('title') }}" data-id="mail-title" placeholder="Order has been placed successfully !" class="form-control">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="form-label">
                                                        {{ translate('Mail Body Message') }}({{ translate('messages.default') }})
                                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="Lorem ipsum">
                                                            <i class="tio-info-outined"></i>
                                                        </span>
                                                    </label>
                                                    <textarea class="form-control" id="ckeditor" data-id="mail-body" name="body[]">
                                                        {!! $data?->getRawOriginal('body') !!}
                                                    </textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" name="lang[]" value="default">
                                            @foreach(json_decode($language) as $lang)
                                            <?php
                                            if($data && count($data['translations'])){
                                                $translate = [];
                                                foreach($data['translations'] as $t)
                                                {
                                                    if($t->locale == $lang && $t->key=="title"){
                                                        $translate[$lang]['title'] = $t->value;
                                                    }
                                                    if($t->locale == $lang && $t->key=="body"){
                                                        $translate[$lang]['body'] = $t->value;
                                                    }
                                                }
                                            }
                                                ?>
                                                <div class="__bg-F8F9FC-card d-none lang_form" id="{{$lang}}-form">
                                                    <div class="form-group">
                                                        <label class="form-label">{{translate('Main Title')}}({{strtoupper($lang)}})</label>
                                                        <input type="text" name="title[]" placeholder="Order has been placed successfully !" class="form-control" value="{{$translate[$lang]['title']??''}}">
                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <label class="form-label">
                                                            {{ translate('Mail Body Message') }}({{strtoupper($lang)}})

                                                        </label>
                                                        <textarea class="ckeditor form-control" name="body[]">
                                                           {!! $translate[$lang]['body']??'' !!}
                                                        </textarea>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="lang[]" value="{{$lang}}">
                                            @endforeach
                                        @else
                                            <div class="__bg-F8F9FC-card default-form">
                                                <div class="form-group">
                                                    <label class="form-label">{{translate('Main Title')}}</label>
                                                    <input type="text" name="title[]" placeholder="Order has been placed successfully !" class="form-control">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="form-label">
                                                        {{ translate('Mail Body Message') }}
                                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="Lorem ipsum">
                                                            <i class="tio-info-outined"></i>
                                                        </span>
                                                    </label>
                                                    <textarea class="ckeditor form-control" name="body[]">
                                                      {{ translate('Hi_Sabrina') }},
                                                    </textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" name="lang[]" value="default">
                                        @endif

                                    </div>
                                    <br>
                                    <div>
                                        <h5 class="card-title mb-3">
                                            <img src="{{asset('public/assets/admin/img/pointer.png')}}" class="mr-2" alt="">
                                            {{translate('Footer Content')}}
                                        </h5>
                                        <div class="__bg-F8F9FC-card">
                                                @if ($language)
                                                        <div class="form-group lang_form default-form">
                                                            <label class="form-label">
                                                                {{translate('Section Text')}}({{ translate('messages.default') }})
                                                            </label>
                                                            <input type="text" data-id="mail-footer" name="footer_text[]"  placeholder="{{ translate('Please_contact_us_for_any_queries;_we’re_always_happy_to_help.') }}"class="form-control" value="{{ $data?->getRawOriginal('footer_text') }}">
                                                        </div>
                                                    @foreach(json_decode($language) as $lang)
                                                    <?php
                                                    if($data && count($data['translations'])){
                                                        $translate = [];
                                                        foreach($data['translations'] as $t)
                                                        {
                                                            if($t->locale == $lang && $t->key=="footer_text"){
                                                                $translate[$lang]['footer_text'] = $t->value;
                                                            }
                                                        }
                                                        }
                                                        ?>
                                                        <div class="form-group d-none lang_form" id="{{$lang}}-form2">
                                                            <label class="form-label">
                                                                {{translate('Section Text')}}({{strtoupper($lang)}})
                                                            </label>
                                                            <input type="text" name="footer_text[]"  placeholder="{{ translate('Please_contact_us_for_any_queries;_we’re_always_happy_to_help.') }}"class="form-control" value="{{ $translate[$lang]['footer_text']??'' }}">
                                                        </div>
                                                    @endforeach
                                                @else
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        {{translate('Section Text')}}
                                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="Lorem ipsum">
                                                            <i class="tio-info-outined"></i>
                                                        </span>
                                                    </label>
                                                    <input type="text" placeholder="{{ translate('Please_contact_us_for_any_queries;_we’re_always_happy_to_help.') }}"class="form-control" name="footer_text[]" value="">
                                                </div>
                                                @endif
                                                    @include('admin-views.business-settings.email-format-setting.partials.social-media-and-footer-section')
                                            <div class="form-group mb-0">
                                                @if ($language)
                                                        <div class="form-group lang_form default-form">
                                                            <label class="form-label">
                                                                {{translate('Copyright Content')}}({{ translate('messages.default') }})
                                                            </label>
                                                            <input type="text" data-id="mail-copyright" name="copyright_text[]"  placeholder="{{ translate('Ex:_Copyright_2023_6amMart._All_right_reserved') }}" class="form-control" value="{{ $data?->getRawOriginal('copyright_text') }}">
                                                        </div>
                                                    @foreach(json_decode($language) as $lang)
                                                    <?php
                                           $translate = [];
                                           if($data && count($data['translations'])){
                                                        foreach($data['translations'] as $t)
                                                        {
                                                            if($t->locale == $lang && $t->key=="copyright_text"){
                                                                $translate[$lang]['copyright_text'] = $t->value;
                                                            }
                                                        }
                                                        }
                                                        ?>
                                                        <div class="form-group d-none lang_form" id="{{$lang}}-form3">
                                                            <label class="form-label">
                                                                {{translate('Copyright Content')}}({{strtoupper($lang)}})
                                                            </label>
                                                            <input type="text" name="copyright_text[]"  placeholder="{{ translate('Ex:_Copyright_2023_6amMart._All_right_reserved') }}" class="form-control" value="{{ $translate[$lang]['copyright_text']??'' }}">
                                                        </div>
                                                    @endforeach
                                                @else
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        {{translate('Copyright Content')}}
                                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="Lorem ipsum">
                                                            <i class="tio-info-outined"></i>
                                                        </span>
                                                    </label>
                                                    <input type="text" placeholder="{{ translate('Ex:_Copyright_2023_6amMart._All_right_reserved') }}" class="form-control" name="copyright_text[]" value="">
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn--container justify-content-end mt-3">
                                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('Reset')}}</button>
                                        <button type="submit" class="btn btn--primary">{{translate('Save')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Instructions Modal -->
@include('admin-views.business-settings.email-format-setting.partials.email-template-instructions')

    </div>
@endsection
@push('script_2')
        <!-- Email Template-->
        <script src="{{asset('public/assets/admin/ckeditor/ckeditor.js')}}"></script>
        <script src="{{asset('public/assets/admin/js/view-pages/email-templates.js')}}"></script>
        <!-- Email Template End-->
@endpush
