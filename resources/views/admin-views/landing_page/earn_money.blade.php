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
            <a class="nav-link lang_link active" href="#" id="default-link">{{translate('messages.default')}}</a>
        </li>
        @foreach (json_decode($language) as $lang)
        <li class="nav-item">
            <a class="nav-link lang_link" href="#" id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
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
    <form action="{{ route('admin.landing_page.settings', 'earn-money-data') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="row g-3 lang_form default-form" id="default-form">
                <input type="hidden" name="lang[]" value="default">
                <div class="col-sm-6">
                    <label for="earn_money_title"  class="form-label">{{translate('Title')}}
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input id="earn_money_title"  maxlength="40" type="text" name="earn_money_title[]" value="{{ $earn_money_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('Enter_Title')}}">
                </div>
                <div class="col-sm-6">
                    <label  for="earn_money_sub_title" class="form-label">{{translate('Subtitle')}}
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input  id="earn_money_sub_title" maxlength="70" type="text" name="earn_money_sub_title[]" value="{{ $earn_money_sub_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('Enter_Title')}}">
                </div>
            </div>
            @if($language)
            @forelse(json_decode($language) as $lang)
            <?php
                    if($earn_money_title?->translations){
                            $earn_money_title_translate = [];
                            foreach($earn_money_title->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='earn_money_title'){
                                    $earn_money_title_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                    if($earn_money_sub_title?->translations){
                            $earn_money_sub_title_translate = [];
                            foreach($earn_money_sub_title->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='earn_money_sub_title'){
                                    $earn_money_sub_title_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                        ?>

            <div class="row g-3 d-none lang_form" id="{{$lang}}-form">
                <input type="hidden" name="lang[]" value="{{$lang}}">
                <div class="col-sm-6">
                    <label for="earn_money_title{{$lang}}" class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input id="earn_money_title{{$lang}}" type="text" maxlength="40" name="earn_money_title[]" value="{{ $earn_money_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter_Title')}}">
                </div>
                <div class="col-sm-6">
                    <label for="earn_money_sub_title{{$lang}}" class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input id="earn_money_sub_title{{$lang}}" type="text" maxlength="70" name="earn_money_sub_title[]" value="{{ $earn_money_sub_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter_Title')}}">
                </div>
            </div>
            @empty
            @endforelse
            @endif
            <div class="btn--container justify-content-end mt-3">
                <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                <button type="submit"   class="btn btn--primary">{{translate('Save')}}</button>
            </div>
</div>
    </form>
</div>
<br>
<br>
<div class="d-flex justify-content-between __gap-12px mb-3">
    <h5 class="card-title d-flex align-items-center">
        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
        {{translate('Registration_Section')}}
    </h5>

</div>

<div class="card">
    <form action="{{ route('admin.landing_page.settings', 'earn-money-data-reg-section') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row g-3 ">
                <div class="col-sm-6">
                    <div class="form-group lang_form default-form">
                        <input type="hidden" name="lang[]" value="default">

                        <label for="earn_money_reg_title" class="form-label">{{translate('Title')}}

                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input id="earn_money_reg_title" type="text" maxlength="40" name="earn_money_reg_title[]" value="{{ $earn_money_reg_title?->getRawOriginal('value') ?? null }}" class="form-control" placeholder="{{translate('Enter_Title')}}">
                    </div>

                    @if($language)
                    @forelse(json_decode($language) as $lang)
                    <input type="hidden" name="lang[]" value="{{$lang}}">
                    <?php
                                            if($earn_money_reg_title?->translations){
                                                    $earn_money_reg_title_translate = [];
                                                    foreach($earn_money_reg_title->translations as $t)
                                                    {
                                                        if($t->locale == $lang && $t->key=='earn_money_reg_title'){
                                                            $earn_money_reg_title_translate[$lang]['value'] = $t->value;
                                                        }
                                                    }
                                                }

                                            ?>

                    <div class="form-group d-none lang_form" id="{{$lang}}-form1">
                        <label for="earn_money_reg_title{{$lang}}" class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})

                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input id="earn_money_reg_title{{$lang}}" type="text" maxlength="40" name="earn_money_reg_title[]" class="form-control" placeholder="{{translate('Enter_Title')}}" value="{{  $earn_money_reg_title_translate[$lang]['value'] ?? '' }}">
                    </div>

                    @empty
                    @endforelse
                    @endif

                    <div class="d-flex gap-40px">
                        <div class="d-flex flex-column">
                            <label class="form-label d-block mb-2">
                                {{translate('Feature Icon *')}} <span class="text--primary">{{translate('(2:1)')}}</span>
                            </label>
                            <div class="position-relative">

                                <label class="upload-img-3 m-0 d-block my-auto">
                                    <div class="img">
                                        <img  src="{{ $earn_money_reg_image_full_url }}"
                                              data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-3.png')}}"
                                              class="img__aspect-unset mw-100 min-w-187px onerror-image" alt="">
                                    </div>
                                    <input type="file" name="earn_money_reg_image" hidden="">
                                </label>
                                @if ($earn_money_reg_image?->value)
                                <span id="remove_image_1"  class="remove_image_button remove-image"
                                      data-id="remove_image_1"
                                      data-title="{{translate('Warning!')}}"
                                      data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"> <i class="tio-clear"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">{{translate('Restaurant_Registration_Button')}}</label>
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group lang_form default-form">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="earn_money_restaurant_req_button_name" class="form-label text-capitalize m-0">
                                            {{translate('Button_Name')}}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>

                                    </div>
                                    <input id="earn_money_restaurant_req_button_name" type="text" maxlength="15" placeholder="{{translate('Ex:_Order_now')}}" class="form-control h--45px" name="earn_money_restaurant_req_button_name[]" value="{{ $earn_money_restaurant_req_button_name?->getRawOriginal('value') ?? '' }}">
                                </div>

                                @if($language)
                                @forelse(json_decode($language) as $lang)
                                <?php
                                                        if($earn_money_restaurant_req_button_name?->translations){
                                                            $earn_money_restaurant_req_button_name_translate = [];
                                                            foreach($earn_money_restaurant_req_button_name->translations as $t)
                                                            {
                                                                if($t->locale == $lang && $t->key=='earn_money_restaurant_req_button_name'){
                                                                    $earn_money_restaurant_req_button_name_translate[$lang]['value'] = $t->value;
                                                                }
                                                            }
                                                        }

                                                    ?>

                                <div class="form-group d-none lang_form" id="{{$lang}}-form2">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="earn_money_restaurant_req_button_name{{$lang}}" class="form-label text-capitalize m-0">
                                            {{translate('Button_Name')}} ({{strtoupper($lang)}})
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                    </div>
                                    <input id="earn_money_restaurant_req_button_name{{$lang}}" type="text" maxlength="15" placeholder="{{translate('Ex:_Order_now')}}" class="form-control h--45px" name="earn_money_restaurant_req_button_name[]" value="{{ $earn_money_restaurant_req_button_name_translate[$lang]['value'] ?? '' }}">
                                </div>

                                @empty
                                @endforelse
                                @endif

                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label text-capitalize m-0">
                                            {{translate('Redirect_Link')}}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Add_the_link/address_where_the_Restaurant_Registration_button_will_redirect.')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox" id="earn_money_restaurant_req_button_status"
                                                   data-id="earn_money_restaurant_req_button_status"
                                                   data-type="toggle"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/mail-success.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/mail-warning.png') }}"
                                                   data-title-on="<strong>{{translate('Want_to_enable_the_Restaurant_Registration_button_here')}}</strong>"
                                                   data-title-off="<strong>{{translate('Want_to_disable_the_Restaurant_Registration_button_here')}}</strong>"
                                                   data-text-on="<p>{{translate('If_enabled,_everyone_can_see_the_Restaurant_Registration_button_on_the_landing_page')}}</p>"
                                                   data-text-off="<p>{{translate('If_disabled,_Restaurant_Registration_button_will_be_hidden_from_the_landing_page')}}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox-toggle"

                                                   name="earn_money_restaurant_req_button_status" value="1" {{ $earn_money_restaurant_req_button_status?->value == 1 ? 'checked': ''  }}>
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                    </div>
                                    <input type="url" placeholder="{{translate('Ex:_https://www.apple.com/app-store/')}}" class="form-control h--45px" name="earn_money_restaurant_req_button_link" value="{{ $earn_money_restaurant_req_button_link  ?? '' }} ">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">{{translate('DeliveryMan_Registration_Button')}}</label>
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group lang_form default-form">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="earn_money_delivety_man_req_button_name" class="form-label text-capitalize m-0">
                                            {{translate('Button_Name')}}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>

                                    </div>
                                    <input id="earn_money_delivety_man_req_button_name" type="text" maxlength="15" placeholder="{{translate('Ex:_Order_now')}}" class="form-control h--45px" name="earn_money_delivety_man_req_button_name[]" value="{{  $earn_money_delivety_man_req_button_name->value ?? '' }}">
                                </div>

                                @if($language)
                                @forelse(json_decode($language) as $lang)
                                <?php
                                                        if($earn_money_delivety_man_req_button_name?->translations){
                                                                $earn_money_delivety_man_req_button_name_translate = [];
                                                                foreach($earn_money_delivety_man_req_button_name->translations as $t)
                                                                {
                                                                    if($t->locale == $lang && $t->key=='earn_money_delivety_man_req_button_name'){
                                                                        $earn_money_delivety_man_req_button_name_translate[$lang]['value'] = $t->value;
                                                                    }
                                                                }
                                                            }

                                                    ?>

                                <div class="form-group d-none lang_form" id="{{$lang}}-form3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="earn_money_delivety_man_req_button_name{{$lang}}" class="form-label text-capitalize m-0">
                                            {{translate('Button_Name')}} ({{strtoupper($lang)}})
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                    </div>
                                    <input id="earn_money_delivety_man_req_button_name{{$lang}}" type="text" maxlength="15" placeholder="{{translate('Ex:_Order_now')}}" class="form-control h--45px" name="earn_money_delivety_man_req_button_name[]" value="{{  $earn_money_delivety_man_req_button_name_translate[$lang]['value']?? '' }}">
                                </div>

                                @empty
                                @endforelse
                                @endif

                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label text-capitalize m-0">
                                            {{translate('Redirect_Link')}}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Add_the_link/address_where_the_Deliveryman_Registration_button_will_redirect.')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox"  id="earn_money_delivery_man_req_button_status"
                                                   data-id="earn_money_delivery_man_req_button_status"
                                                   data-type="toggle"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/mail-success.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/mail-warning.png') }}"
                                                   data-title-on="<strong>{{translate('Want_to_enable_the_Deliveryman_Registration_button_here')}}</strong>"
                                                   data-title-off="<strong>{{translate('Want_to_disable_the_Deliveryman_Registration_button_here')}}</strong>"
                                                   data-text-on="<p>{{translate('If_enabled,_everyone_can_see_the_Deliveryman_Registration_button_on_the_landing_page')}}</p>"
                                                   data-text-off="<p>{{translate('If_disabled,_Deliveryman_Registration_button_will_be_hidden_from_the_landing_page')}}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox-toggle"
                                                   name="earn_money_delivery_man_req_button_status" value="1" {{ $earn_money_delivery_man_req_button_status?->value   == 1 ? 'checked': ''  }}>
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                    </div>
                                    <input type="url" placeholder="{{translate('Ex:_https://www.apple.com/app-store/')}}" class="form-control h--45px" name="earn_money_delivery_req_button_link" value="{{ $earn_money_delivery_man_req_button_link ?? '' }}">
                                </div>
                            </div>
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
</div>


<form id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $earn_money_reg_image?->id}}">
    <input type="hidden" name="model_name" value="DataSetting">
    <input type="hidden" name="image_path" value="earn_money">
    <input type="hidden" name="field_name" value="value">
</form>
@endsection
