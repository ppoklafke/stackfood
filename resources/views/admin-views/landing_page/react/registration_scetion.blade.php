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
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal"
                    data-target="#how-it-works">
                    <strong class="mr-2">{{ translate('See_how_it_works') }}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.landing_page.top_menu.react_landing_menu')
            </div>
        </div>


        @php($default_lang = str_replace('_', '-', app()->getLocale()))
        @if ($language)
            <ul class="nav nav-tabs mb-4 border-0">
                <li class="nav-item">
                    <a class="nav-link lang_link active" href="#"
                        id="default-link">{{ translate('messages.default') }}</a>
                </li>
                @foreach (json_decode($language) as $lang)
                    <li class="nav-item">
                        <a class="nav-link lang_link" href="#"
                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between __gap-12px mb-3">
                    <h5 class="card-title d-flex align-items-center">
                        <span class="card-header-icon mr-2">
                            <img src="{{ dynamicAsset('public/assets/admin/img/seller.png') }}" alt="" class="mw-100">
                        </span>
                        {{ translate('Restaurant_Registration_Section') }}
                    </h5>
                </div>

                <form action="{{ route('admin.react_landing_page.settings', 'react-regisrtation-section-content') }}"
                    enctype="multipart/form-data" method="post">
                    @csrf


                    <input type="hidden" name="lang[]" value="default">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="lang_form default-form">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="react_restaurant_section_title" class="form-label">{{ translate('Title') }}
                                            ({{ translate('default') }})
                                            <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <input type="text" maxlength="30" class="form-control"
                                            placeholder="{{ translate('messages.Enter_Title...') }}"
                                            id="react_restaurant_section_title" name="react_restaurant_section_title[]"
                                            value="{{ $react_restaurant_section_title?->getRawOriginal('value') ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="react_restaurant_section_sub_title" class="form-label">{{ translate('Subtitle') }}
                                            ({{ translate('default') }})
                                            <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('Write_the_subtitle_within_60_characters') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <input type="text" maxlength="60" class="form-control"
                                            placeholder="{{ translate('Enter_Subtitle') }}"
                                            id="react_restaurant_section_sub_title" name="react_restaurant_section_sub_title[]"
                                            value="{{ $react_restaurant_section_sub_title?->getRawOriginal('value') ?? '' }}">
                                    </div>
                                </div>
                            </div>


                            @if ($language)
                                @forelse(json_decode($language) as $lang)
                                    <?php
                                    if ($react_restaurant_section_title?->translations) {
                                        $react_restaurant_section_title_translate = [];
                                        foreach ($react_restaurant_section_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'react_restaurant_section_title') {
                                                $react_restaurant_section_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }
                                    if ($react_restaurant_section_sub_title?->translations) {
                                        $react_restaurant_section_sub_title_translate = [];
                                        foreach ($react_restaurant_section_sub_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'react_restaurant_section_sub_title') {
                                                $react_restaurant_section_sub_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }

                                    ?>

                                    <div class="d-none lang_form" id="{{ $lang }}-form1">
                                        <input type="hidden" name="lang[]" value="{{ $lang }}">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="react_restaurant_section_title{{$lang}}" class="form-label">{{ translate('Title') }}
                                                    ({{ strtoupper($lang) }})
                                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                                        <i class="tio-info-outined"></i>
                                                    </span>
                                                </label>
                                                <input id="react_restaurant_section_title{{$lang}}" type="text" maxlength="30" class="form-control"
                                                    name="react_restaurant_section_title[]"
                                                    placeholder="{{ translate('messages.Enter_Title...') }}"
                                                    value="{{ $react_restaurant_section_title_translate[$lang]['value'] ?? '' }}">
                                            </div>
                                            <div class="col-12">
                                                <label for="react_restaurant_section_sub_title{{$lang}}" class="form-label">{{ translate('Subtitle') }}
                                                    ({{ strtoupper($lang) }})
                                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('Write_the_subtitle_within_60_characters') }}">
                                                        <i class="tio-info-outined"></i>
                                                    </span>
                                                </label>
                                                <input id="react_restaurant_section_sub_title{{$lang}}" type="text" maxlength="60" class="form-control"
                                                    placeholder="{{ translate('Enter_Subtitle') }}"
                                                    name="react_restaurant_section_sub_title[]"
                                                    value="{{ $react_restaurant_section_sub_title_translate[$lang]['value'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                @endforelse
                            @endif

                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block mb-2">
                                {{ translate('messages.Icon') }} / {{ translate('messages.Image') }} <span
                                    class="text--primary">{{ translate('messages.(1:1)') }}</span>
                            </label>
                            <div class="position-relative d-inline-block">
                                <label class="upload-img-3 m-0">
                                    <div class="img">
                                        <img src="{{ \App\CentralLogics\Helpers::get_full_url('react_restaurant_section_image', $react_restaurant_section_image?->value,$react_restaurant_section_image?->storage[0]?->value ?? 'public') }}"
                                              data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-3.png')}}"
                                              class="vertical-img max-w-187px onerror-image" alt="">

                                    </div>
                                    <input type="file" name="react_restaurant_section_image" hidden>
                                </label>
                                @if ($react_restaurant_section_image?->value)
                                    <span id="remove_image_2"
                                          class="remove_image_button remove-image"
                                          data-id="remove_image_2"
                                          data-title="{{translate('Warning!')}}"
                                          data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>" >
                                        <i class="tio-clear"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="__bg-F8F9FC-card">
                                <div class="lang_form default-form">
                                    <div class="form-group mb-md-0">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="react_restaurant_section_button_name" class="form-label text-capitalize m-0">
                                                {{ translate('Button_Name') }} ({{ translate('default') }})
                                                <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                    data-placement="right"
                                                    data-original-title="{{ translate('Write_the_button_name_within_15_characters') }}">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>

                                        </div>
                                        <input id="react_restaurant_section_button_name" type="text" maxlength="15"
                                            placeholder="{{ translate('Ex:_Order_now') }}" class="form-control h--45px"
                                            name="react_restaurant_section_button_name[]"
                                            value="{{ $react_restaurant_section_button_name?->getRawOriginal('value') ?? null }}">
                                    </div>
                                </div>


                                @if ($language)
                                    @forelse(json_decode($language) as $lang)
                                        <?php
                                        if ($react_restaurant_section_button_name?->translations) {
                                            $react_restaurant_section_button_name_translate = [];
                                            foreach ($react_restaurant_section_button_name->translations as $t) {
                                                if ($t->locale == $lang && $t->key == 'react_restaurant_section_button_name') {
                                                    $react_restaurant_section_button_name_translate[$lang]['value'] = $t->value;
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="d-none lang_form" id="{{ $lang }}-form2">
                                            <div class="form-group mb-md-0">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label for="react_restaurant_section_button_name{{$lang}}" class="form-label text-capitalize m-0">
                                                        {{ translate('Button_Name') }} ({{ strtoupper($lang) }})
                                                        <span class="input-label-secondary text--title"
                                                            data-toggle="tooltip" data-placement="right"
                                                            data-original-title="{{ translate('Write_the_button_name_within_15_characters') }}">
                                                            <i class="tio-info-outined"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                                <input id="react_restaurant_section_button_name{{$lang}}" type="text" maxlength="15"
                                                    placeholder="{{ translate('Ex:_Order_now') }}"
                                                    class="form-control h--45px"
                                                    name="react_restaurant_section_button_name[]"
                                                    value="{{ $react_restaurant_section_button_name_translate[$lang]['value'] ?? '' }}">
                                            </div>
                                        </div>

                                    @empty
                                    @endforelse
                                @endif

                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label text-capitalize m-0">
                                            {{ translate('Redirect_Link') }}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('Add_the_restaurant_registration_address_(Play_Store/_App_Store/Web)_where_the_button_will_redirect.') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox"
                                                id="react_restaurant_section_button_status"
                                                   data-id="react_restaurant_section_button_status"
                                                   data-type="status"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/store-self-reg-on.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/store-self-reg-off.png') }}"
                                                   data-title-on="<strong>{{ translate('Want_to_enable_the_Restaurant_Registration_button') }}</strong>"
                                                   data-title-off="<strong>{{ translate('Want_to_disable_the_Restaurant_Registration_button') }}</strong>"
                                                   data-text-on="<p>{{translate('If_enabled,_everyone_can_see_it_on_the_landing_page')}}</p>"
                                                   data-text-off="<p>{{translate('If_disabled,_it_will_be_hidden_from_the_landing_page')}}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox"
                                                name="react_restaurant_section_button_status" value="1"
                                                {{ $react_restaurant_section_button_status?->value ?? null == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                    <input type="url"
                                        placeholder="{{ translate('Ex:_https://www.apple.com/app-store/') }}"
                                        class="form-control h--45px" name="react_restaurant_section_link"
                                        value="{{ $react_restaurant_section_link_data ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>

                    <div class="d-flex justify-content-between __gap-12px mb-3">
                        <h5 class="card-title d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <img src="{{ dynamicAsset('public/assets/admin/img/reg_dm.png') }}" alt=""
                                    class="mw-100">
                            </span>
                            {{ translate('Deliveryman_Registration_Section') }}
                        </h5>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">

                            <div class="lang_form default-form">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="react_delivery_section_title" class="form-label">{{ translate('Title') }}
                                            ({{ translate('default') }})
                                            <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <input type="text" maxlength="30" class="form-control"
                                            placeholder="{{ translate('messages.Enter_Title...') }}"
                                            id="react_delivery_section_title" name="react_delivery_section_title[]"
                                            value="{{ $react_delivery_section_title?->getRawOriginal('value') ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="react_delivery_section_sub_title" class="form-label">{{ translate('Subtitle') }}
                                            ({{ translate('default') }})
                                            <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('Write_the_subtitle_within_60_characters') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <input type="text" maxlength="60" class="form-control"
                                            placeholder="{{ translate('Enter_Subtitle') }}"
                                            id="react_delivery_section_sub_title" name="react_delivery_section_sub_title[]"
                                            value="{{ $react_delivery_section_sub_title?->getRawOriginal('value') ?? '' }}">
                                    </div>
                                </div>
                            </div>


                            @if ($language)
                                @forelse(json_decode($language) as $lang)
                                    <?php
                                    if ($react_delivery_section_title?->translations) {
                                        $react_delivery_section_title_translate = [];
                                        foreach ($react_delivery_section_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'react_delivery_section_title') {
                                                $react_delivery_section_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }
                                    if ($react_delivery_section_sub_title?->translations) {
                                        $react_delivery_section_sub_title_translate = [];
                                        foreach ($react_delivery_section_sub_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'react_delivery_section_sub_title') {
                                                $react_delivery_section_sub_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }

                                    ?>

                                    <div class="d-none lang_form" id="{{ $lang }}-form3">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="react_delivery_section_title{{$lang}}" class="form-label">{{ translate('Title') }}
                                                    ({{ strtoupper($lang) }})
                                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                                        <i class="tio-info-outined"></i>
                                                    </span>
                                                </label>
                                                <input id="react_delivery_section_title{{$lang}}"  type="text" maxlength="30" class="form-control"
                                                    name="react_delivery_section_title[]"
                                                    placeholder="{{ translate('messages.Enter_Title...') }}"
                                                    value="{{ $react_delivery_section_title_translate[$lang]['value'] ?? '' }}">
                                            </div>
                                            <div class="col-12">
                                                <label for="react_delivery_section_sub_title{{$lang}}" class="form-label">{{ translate('Subtitle') }}
                                                    ({{ strtoupper($lang) }})
                                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('Write_the_subtitle_within_60_characters') }}">
                                                        <i class="tio-info-outined"></i>
                                                    </span>
                                                </label>
                                                <input  id="react_delivery_section_sub_title{{$lang}}" type="text" maxlength="60" class="form-control"
                                                    placeholder="{{ translate('Enter_Subtitle') }}"
                                                    name="react_delivery_section_sub_title[]"
                                                    value="{{ $react_delivery_section_sub_title_translate[$lang]['value'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                @endforelse
                            @endif


                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block mb-2">
                                {{ translate('messages.Icon') }} / {{ translate('messages.Image') }} <span
                                    class="text--primary">{{ translate('messages.(1:1)') }}</span>
                            </label>
                            <div class="position-relative d-inline-block">

                                <label class="upload-img-3 m-0">
                                    <div class="img">
                                        <img  src="{{ \App\CentralLogics\Helpers::get_full_url('react_delivery_section_image', $react_delivery_section_image?->value,$react_delivery_section_image?->storage[0]?->value ?? 'public') }}"
                                              data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-3.png')}}"
                                              class="vertical-img max-w-187px onerror-image" alt="">

                                    </div>
                                    <input type="file" name="react_delivery_section_image" hidden>
                                </label>
                                @if ($react_delivery_section_image?->value)
                                    <span id="remove_image_1" class="remove_image_button remove-image"
                                          data-id="remove_image_1"
                                          data-title="{{translate('Warning!')}}"
                                          data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>" >
                                        <i class="tio-clear"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="__bg-F8F9FC-card">
                                <div class="lang_form default-form">
                                    <div class="form-group mb-md-0">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="react_delivery_section_button_name" class="form-label text-capitalize m-0">
                                                {{ translate('Button_Name') }} ({{ translate('default') }})
                                                <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                    data-placement="right"
                                                    data-original-title="{{ translate('Write_the_button_name_within_15_characters') }}">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>

                                        </div>
                                        <input id="react_delivery_section_button_name" type="text" maxlength="15"
                                            placeholder="{{ translate('Ex:_Order_now') }}" class="form-control h--45px"
                                            name="react_delivery_section_button_name[]"
                                            value="{{ $react_delivery_section_button_name?->getRawOriginal('value') ?? null }}">
                                    </div>
                                </div>

                                @if ($language)
                                    @forelse(json_decode($language) as $lang)
                                        <?php
                                        if ($react_delivery_section_button_name?->translations) {
                                            $react_delivery_section_button_name_translate = [];
                                            foreach ($react_delivery_section_button_name->translations as $t) {
                                                if ($t->locale == $lang && $t->key == 'react_delivery_section_button_name') {
                                                    $react_delivery_section_button_name_translate[$lang]['value'] = $t->value;
                                                }
                                            }
                                        }
                                        ?>

                                        <div class="d-none lang_form" id="{{ $lang }}-form4">
                                            <div class="form-group mb-md-0">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label for="react_delivery_section_button_name{{$lang}}" class="form-label text-capitalize m-0">
                                                        {{ translate('Button_Name') }} ({{ strtoupper($lang) }})
                                                        <span class="input-label-secondary text--title"
                                                            data-toggle="tooltip" data-placement="right"
                                                            data-original-title="{{ translate('Write_the_button_name_within_15_characters') }}">
                                                            <i class="tio-info-outined"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                                <input id="react_delivery_section_button_name{{$lang}}" type="text" maxlength="15"
                                                    placeholder="{{ translate('Ex:_Order_now') }}"
                                                    class="form-control h--45px"
                                                    name="react_delivery_section_button_name[]"
                                                    value="{{ $react_delivery_section_button_name_translate[$lang]['value'] ?? '' }}">
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                @endif

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label text-capitalize m-0">
                                            {{ translate('Redirect_Link') }}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('Add_the_deliveryman_registration_address_(Play_Store/_App_Store/Web)_where_the_button_will_redirect.') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox"
                                                id="react_delivery_section_button_status"
                                                   data-id="react_delivery_section_button_status"
                                                   data-type="status"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/home-delivery-on.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/home-delivery-off.png') }}"
                                                   data-title-on="<strong>{{ translate('Want_to_enable_the_Deliveryman_Registration_button') }}</strong>"
                                                   data-title-off="<strong>{{ translate('Want_to_disable_the_Deliveryman_Registration_button') }}</strong>"
                                                   data-text-on="<p>{{translate('If_enabled,_everyone_can_see_it_on_the_landing_page')}}</p>"
                                                   data-text-off="<p>{{translate('If_disabled,_it_will_be_hidden_from_the_landing_page')}}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox"
                                                name="react_delivery_section_button_status" value="1"
                                                {{ $react_delivery_section_button_status?->value ?? null == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                    <input type="url"
                                        placeholder="{{ translate('Ex:_https://www.apple.com/app-store/') }}"
                                        class="form-control h--45px" name="react_delivery_section_link"
                                        value="{{ $react_delivery_section_link_data ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                        <button type="submit"
                            class="btn btn--primary">{{ translate('Save') }}</button>
                    </div>
            </form>
            </div>
        </div>
    </div>

    <form id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $react_delivery_section_image?->id }}">
        <input type="hidden" name="model_name" value="DataSetting">
        <input type="hidden" name="image_path" value="react_delivery_section_image">
        <input type="hidden" name="field_name" value="value">
    </form>
    <form id="remove_image_2_form" action="{{ route('admin.remove_image') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $react_restaurant_section_image?->id }}">
        <input type="hidden" name="model_name" value="DataSetting">
        <input type="hidden" name="image_path" value="react_restaurant_section_image">
        <input type="hidden" name="field_name" value="value">
    </form>



@endsection
