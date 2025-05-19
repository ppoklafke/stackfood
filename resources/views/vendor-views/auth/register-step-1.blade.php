@extends('layouts.landing.app')
@section('title', translate('messages.restaurant_registration'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset('public/assets/landing/css/style.css') }}" />
    <link href="{{ dynamicAsset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ dynamicAsset('public/assets/admin/css/select2.min.css') }}"/> --}}
    <style>
        .password-feedback {
            /* display: none; */
            width: 100%;
            margin-top: .25rem;
            font-size: .875em;
            color: #35dc80;
        }
        .valid {
            color: green;
        }

        .invalid {
            color: red;
        }
    </style>
@endpush
@section('content')
    <!-- Page Header Gap -->
    <div class="h-148px"></div>
    <!-- Page Header Gap -->

    <section class="m-0 landing-inline-1 section-gap">
        <div class="container">
            <!-- Page Header -->
            <div class="step__header">
                <h4 class="title"> {{ translate('messages.Restaurant_registration_application') }}</h4>
                <div class="step__wrapper">
                    <div id="show-step1" class="step__item current">
                        <span class="shapes"></span>
                        {{ translate('General Information') }}
                    </div>
                    <div id="show-step2" class="step__item">
                        <span class="shapes"></span>
                        {{ translate('Business Plan') }}
                    </div>
                    <div class="step__item">
                        <span class="shapes"></span>
                        {{ translate('Complete') }}
                    </div>
                </div>

            </div>
            <!-- End Page Header -->
            @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
            @php($language = $language->value ?? null)
            @php($default_lang = str_replace('_', '-', app()->getLocale()))

            <form class=" reg-form js-validate" action="{{ route('restaurant.store') }}" method="post"
                enctype="multipart/form-data" id="form-id">
                @csrf
                <div id="reg-form-div">
                    <div class="card __card">
                        <div class="card-header py-3 bg-transparent">
                            <h5 class="card-title my-1 text--primary">
                                <span class="card-header-icon">
                                    <i class="fa-solid fa-store"></i>
                                </span>
                                {{ translate('messages.restaurant_info') }}
                            </h5>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-4">
                                @if ($language)
                                <div class="js-nav-scroller hs-nav-scroller-horizontal">
                                    <ul class="nav nav-tabs mb-4">
                                        <li class="nav-item">
                                            <a class="nav-link lang_link active" href="#"
                                                id="default-link">{{ translate('Default') }}</a>
                                        </li>
                                        @foreach (json_decode($language) as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link lang_link" href="#"
                                                    id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    @if ($language)
                                        <div class="form-group mb-0 lang_form" id="default-form">
                                            <label class="form-label"
                                                for="exampleFormControlInput1">{{ translate('messages.restaurant_name') }}
                                                ({{ translate('messages.default') }})  <small class="text-danger">
                                                    *</small></label>
                                            <input type="text" id="default_name" name="name[]"
                                                value="{{ old('name.0') }}" required
                                                data-field-name="{{ translate('Default_Restaurant_Name') }}"
                                                class="form-control"
                                                placeholder="{{ translate('messages.Ex :_ABC Company') }}" maxlength="191">
                                        </div>
                                        <input type="hidden" name="lang[]" value="default">
                                        @foreach (json_decode($language) as $key => $lang)
                                            <div class="form-group  mb-0 d-none lang_form" id="{{ $lang }}-form">
                                                <label class="form-label"
                                                    for="exampleFormControlInput1">{{ translate('messages.restaurant_name') }}
                                                    ({{ strtoupper($lang) }})
                                                </label>
                                                <input type="text" name="name[]" value="{{ old('name.' . $key + 1) }}"
                                                    class="form-control"
                                                    placeholder="{{ translate('messages.Ex :_ABC Company') }}"
                                                    maxlength="191">
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{ $lang }}">
                                        @endforeach
                                    @else
                                        <div class="form-group mb-0">
                                            <label class="form-label"
                                                for="exampleFormControlInput1">{{ translate('messages.restaurant_name') }}</label>
                                            <input type="text" name="name[]" class="form-control"
                                                placeholder="{{ translate('messages.Ex :_ABC Company') }}" maxlength="191">
                                        </div>
                                        <input type="hidden" name="lang[]" value="default">
                                    @endif
                                </div>

                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="tax">{{ translate('messages.vat/tax') }}
                                            (%) <small class="text-danger">
                                                *</small></label>
                                        <input type="number" name="tax" id="tax" class="form-control"
                                            data-field-name="{{ translate('vat/tax') }}"
                                            placeholder="{{ translate('messages.vat/tax') }}" min="0" step=".01"
                                            required value="{{ old('tax') }}">
                                    </div>
                                </div>



                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="lang_form default-form">
                                        <div class="form-group mb-0">
                                            <label class="form-label"
                                                for="address">{{ translate('messages.restaurant_address') }}
                                                ({{ translate('messages.default') }}) <small class="text-danger">
                                                    *</small></label>
                                            <textarea type="text" id="address" name="address[]" required
                                                data-field-name="{{ translate('Default_Restaurant_Address') }}" class="form-control h--77px"
                                                placeholder="{{ translate('messages.restaurant_address') }}">{{ old('address.0') }}</textarea>
                                        </div>
                                    </div>
                                    {{-- </div> --}}


                                    @if ($language)

                                        @foreach (json_decode($language) as $key => $lang)
                                            <div class="d-none lang_form" id="{{ $lang }}-form1">
                                                <div class="form-group mb-0">
                                                    <label class="form-label"
                                                        for="address">{{ translate('messages.restaurant_address') }}
                                                        ({{ strtoupper($lang) }})
                                                    </label>
                                                    <textarea type="text" name="address[]" class="form-control h--77px"
                                                        placeholder="{{ translate('messages.restaurant_address') }}">{{ old('address.' . $key + 1) }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-sm-3 col-md-2 col-lg-2">
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                            for="minimum_delivery_time">{{ translate('messages.min_delivery_time') }} <small class="text-danger">
                                                *</small> </label>
                                        <input type="number" id="minimum_delivery_time" name="minimum_delivery_time"
                                            class="form-control" placeholder="30" pattern="^[0-9]{2}$" required
                                            data-field-name="{{ translate('minimum_delivery_time') }}"
                                            value="{{ old('minimum_delivery_time') }}">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-2 col-lg-2">
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                            for="maximum_delivery_time">{{ translate('messages.max_delivery_time') }} <small class="text-danger">
                                                *</small></label>
                                        <input type="number" id="max_delivery_time" name="maximum_delivery_time"
                                            class="form-control" placeholder="40" pattern="[0-9]{2}" required
                                            data-field-name="{{ translate('maximum_delivery_time') }}"
                                            value="{{ old('maximum_delivery_time') }}">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-2 col-lg-2">
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="maximum_delivery_time"></label>
                                        <select name="delivery_time_type" required id="delivery_time_type"
                                            class="form-control js-select2-custom select2-container--default">
                                            <option selected value="min">{{ translate('messages.Minutes') }}</option>
                                            <option value="hours">{{ translate('messages.Hours') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-29px">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="text-center">
                                                <img class="landing-initial-1" id="coverImageViewer"
                                                    src="{{ dynamicAsset('/public/assets/landing/img/restaurant-cover.png') }}"
                                                    alt="Product thumbnail" />
                                            </div>
                                            <div class="landing-input-file-grp">
                                                <label for="name"
                                                    class="form-label pt-3">{{ translate('messages.restaurant_cover_photo') }}
                                                    <span class="text-danger">* ({{ translate('messages.ratio') }}
                                                        2:1)</span></label>
                                                <label class="custom-file">
                                                    <input type="file" required name="cover_photo"
                                                        id="coverImageUpload"
                                                        data-field-name="{{ translate('cover_photo') }}"
                                                        class="form-control"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="text-center">
                                                <img class="landing-initial-1" id="logoImageViewer"
                                                    src="{{ dynamicAsset('/public/assets/landing/img/restaurant-logo.png') }}"
                                                    alt="Product thumbnail" />
                                            </div>
                                            <div class="landing-input-file-grp">
                                                <label
                                                    class="form-label pt-3">{{ translate('messages.restaurant_logo') }}<small
                                                        class="text-danger"> * (
                                                        {{ translate('messages.ratio') }}
                                                        1:1
                                                        )</small></label>
                                                <label class="custom-file">
                                                    <input type="file" name="logo" id="customFileEg1"
                                                        data-field-name="{{ translate('logo') }}" class="form-control"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                        required>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label mb-2 pb-1"
                                            for="cuisine">{{ translate('messages.cuisine') }}
                                        </label>
                                        <select name="cuisine_ids[]" id="cuisine"
                                            class="form-control js-select2-custom select2-container--default"
                                            multiple="multiple"
                                            data-placeholder="{{ translate('messages.select_Cuisine') }}">
                                            <option value="" disabled>{{ translate('messages.select_Cuisine') }}
                                            </option>
                                            @foreach (\App\Models\Cuisine::where('status', 1)->get(['id', 'name']) as $cu)
                                                <option value="{{ $cu->id }}">{{ $cu->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label mb-2 pb-1"
                                            for="choice_zones">{{ translate('messages.zone') }}
                                            <small class="text-danger">
                                                *</small>
                                            <span class="input-label-secondary ps-1"
                                                title="{{ translate('messages.select_zone_for_map') }}"><img
                                                    src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.select_zone_for_map') }}"></span>
                                        </label>
                                        <select name="zone_id" id="choice_zones" required
                                            data-field-name="{{ translate('Zone') }}"
                                            class="form-control js-select2-custom select2-container--default"
                                            data-placeholder="{{ translate('messages.select_zone') }}">
                                            <option value="" selected disabled>
                                                {{ translate('messages.select_zone') }}
                                            </option>
                                            @foreach (\App\Models\Zone::active()->get(['id', 'name']) as $zone)
                                                @if (isset(auth('admin')->user()->zone_id))
                                                    @if (auth('admin')->user()->zone_id == $zone->id)
                                                        <option value="{{ $zone->id }}" selected>{{ $zone->name }}
                                                        </option>
                                                    @endif
                                                @else
                                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label mb-2 pb-1"
                                            for="latitude">{{ translate('messages.latitude') }}
                                            <small class="text-danger">
                                                *</small><span
                                                class="input-label-secondary ps-1"
                                                title="{{ translate('messages.restaurant_lat_lng_warning') }}"><img
                                                    src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span></label>
                                        <input type="text" id="latitude" name="latitude" class="form-control"
                                            data-field-name="{{ translate('Must_click_on_the_map_for_lat/long') }}"
                                            placeholder="{{ translate('messages.Ex :') }} -94.22213"
                                            value="{{ old('latitude') }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label mb-2 pb-1"
                                            for="longitude">{{ translate('messages.longitude') }}<small class="text-danger">
                                                *</small><span
                                                class="input-label-secondary ps-1"
                                                title="{{ translate('messages.restaurant_lat_lng_warning') }}"><img
                                                    src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span></label>
                                        <input type="text" name="longitude" class="form-control"
                                            placeholder="{{ translate('messages.Ex :') }} 103.344322" id="longitude"
                                            data-field-name="{{ translate('Must_click_on_the_map_for_lat/long') }}"
                                            value="{{ old('longitude') }}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-sm-12 mt-4">
                                <input id="pac-input" class="controls rounded landing-initial-2"
                                    title="{{ translate('messages.search_your_location_here') }}" type="text"
                                    placeholder="{{ translate('messages.search_here') }}" />
                                <div id="map"></div>
                            </div>
                            <h5 class="card-title mb-3 text--primary text-capitalize mt-4 pt-1">
                                {{ translate('messages.owner_info') }}
                            </h5>
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="f_name">{{ translate('messages.first_name') }}<small class="text-danger">
                                                *</small></label>
                                        <input type="text" id="f_name" name="f_name" class="form-control"
                                            placeholder="{{ translate('messages.first_name') }}"
                                            value="{{ old('f_name') }}" data-field-name="{{ translate('first_name') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="l_name">{{ translate('messages.last_name') }}<small class="text-danger">
                                                *</small></label>
                                        <input type="text" id="l_name" name="l_name" class="form-control"
                                            data-field-name="{{ translate('last_name') }}"
                                            placeholder="{{ translate('messages.last_name') }}"
                                            value="{{ old('l_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="phone">{{ translate('messages.phone') }}<small class="text-danger">
                                                *</small></label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            data-field-name="{{ translate('phone') }}"
                                            placeholder="{{ translate('messages.Ex :') }} 017********"
                                            value="{{ old('phone') }}" required>
                                    </div>


                                </div>
                            </div>


                            <h5 class="card-title my-1 text--primary text-capitalize mt-4 pt-1">
                                {{ translate('messages.tags') }}
                            </h5>

                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <input type="text" class="form-control" name="tags" placeholder="Enter tags"
                                        data-role="tagsinput">
                                </div>
                            </div>


                            @if (isset($page_data) && count($page_data) > 0)
                                <div class="col-lg-12">
                                    <h5 class="card-title my-1 text--primary text-capitalize mt-4 pt-1">
                                        {{ translate('messages.Additional_Data') }}
                                    </h5>
                                    <div class="row">
                                        @foreach (data_get($page_data, 'data', []) as $key => $item)
                                            @if (!in_array($item['field_type'], ['file', 'check_box']))
                                                <div class="col-md-4 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="{{ $item['input_data'] }}">{{ translate($item['input_data']) }}

                                                                @if ($item['is_required'] == 1)
                                                                <small class="text-danger">
                                                                    *</small>

                                                                @endif

                                                        </label>
                                                        <input id="{{ $item['input_data'] }}"
                                                            {{ $item['is_required'] == 1 ? 'required' : '' }}
                                                            data-field-name="{{ translate($item['input_data']) }}"
                                                            type="{{ $item['field_type'] }}"
                                                            name="additional_data[{{ $item['input_data'] }}]"
                                                            class="form-control h--45px"
                                                            placeholder="{{ translate($item['placeholder_data']) }}"
                                                            value="">
                                                    </div>
                                                </div>
                                            @elseif ($item['field_type'] == 'check_box')
                                                @if ($item['check_data'] != null)
                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label  class="form-label" for=""> {{ translate($item['input_data']) }}
                                                                @if ($item['is_required'] == 1)
                                                                <small class="text-danger">
                                                                    *</small>
                                                                @endif
                                                            </label>
                                                            @foreach ($item['check_data'] as $k => $i)
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox"
                                                                            name="additional_data[{{ $item['input_data'] }}][]"
                                                                            class="form-check-input"
                                                                            value="{{ $i }}">
                                                                        {{ translate($i) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @elseif ($item['field_type'] == 'file')
                                                @if ($item['media_data'] != null)
                                                    <?php
                                                    $image = '';
                                                    $pdf = '';
                                                    $docs = '';
                                                    if (data_get($item['media_data'], 'image', null)) {
                                                        $image = '.jpg, .jpeg, .png,';
                                                    }
                                                    if (data_get($item['media_data'], 'pdf', null)) {
                                                        $pdf = ' .pdf,';
                                                    }
                                                    if (data_get($item['media_data'], 'docs', null)) {
                                                        $docs = ' .doc, .docs, .docx';
                                                    }
                                                    $accept = $image . $pdf . $docs;
                                                    ?>
                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label class="form-label"
                                                                for="{{ $item['input_data'] }}">{{ translate($item['input_data']) }}
                                                                @if ($item['is_required'] == 1)
                                                                <small class="text-danger">
                                                                    *</small>

                                                                @endif

                                                            </label>
                                                            <input id="{{ $item['input_data'] }}"
                                                                {{ $item['is_required'] == 1 ? 'required' : '' }}
                                                                data-field-name="{{ translate($item['input_data']) }}"
                                                                type="{{ $item['field_type'] }}"
                                                                name="additional_documents[{{ $item['input_data'] }}][]"
                                                                class="form-control h--45px"
                                                                placeholder="{{ translate($item['placeholder_data']) }}"
                                                                {{ data_get($item['media_data'], 'upload_multiple_files', null) == 1 ? 'multiple' : '' }}
                                                                accept="{{ $accept ?? '.jpg, .jpeg, .png' }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <h5 class="card-title my-1 text--primary text-capitalize mt-4 pt-1">
                                {{ translate('messages.login_info') }}
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-12 col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="email">{{ translate('messages.email') }} <small class="text-danger">
                                                *</small></label>
                                        <input type="email" id="email" name="email"
                                            data-field-name="{{ translate('messages.Email') }}"
                                            class="form-control __form-control"
                                            placeholder="{{ translate('messages.Ex:') }} ex@example.com"
                                            value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                            title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                            for="exampleInputPassword">{{ translate('messages.password') }}  <small class="text-danger">
                                                *</small>
                                            <span class="form-label-secondary" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img
                                                    src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>

                                        </label>
                                        <label class="position-relative m-0 d-block">
                                            <input type="password" name="password"
                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                class="form-control __form-control form-control __form-control-user"
                                                minlength="6" id="exampleInputPassword" required
                                                 data-field-name="{{ translate('messages.password') }}"
                                                value="{{ old('password') }}">
                                            <span class="show-password">
                                                <span class="icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </span>
                                                <span class="icon-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </label>
                                        <div id="password-feedback" class="pass d-none password-feedback">
                                            {{ translate('messages.password_not_matched') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleRepeatPassword">{{ translate('messages.confirm_password') }}  <small class="text-danger">
                                                *</small></label>
                                        <label class="position-relative m-0 d-block">
                                            <input type="password" name="confirm-password"
                                                class="form-control __form-control form-control __form-control-user"
                                                minlength="6" id="exampleRepeatPassword"
                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                 data-field-name="{{ translate('messages.confirm_password') }}"
                                                required value="{{ old('confirm-password') }}">
                                            <span class="show-password">
                                                <span class="icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </span>
                                                <span class="icon-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </label>
                                        <div class="pass invalid-feedback">
                                            {{ translate('messages.password_not_matched') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end pt-4 d-flex flex-wrap justify-content-end gap-3">
                                <button type="reset" id='reset-btn'
                                    class="btn btn--reset ">{{ translate('Reset') }}</button>

                                @if (\App\CentralLogics\Helpers::subscription_check() != 1)
                                <button  type="submit" class="btn btn--primary submitBtn">{{ translate('Next') }}</button>
                                @else
                                <button  type="button" id="show-business-plan-div" class="btn btn--primary submitBtn">{{ translate('Next') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if (\App\CentralLogics\Helpers::subscription_check())
                    <div class="d-none" id="business-plan-div">
                        <h4 class="register--title text-center mb-40px"> {{ translate('messages.business_plans') }}</h4>

                        <div class="card __card mb-3">
                            <h4 class="card-title text-center pt-4">
                                @if (count($packages) > 0 && \App\CentralLogics\Helpers::commission_check())
                                {{ translate('Choose Your Business Plan') }}
                                @elseif (!count($packages) && !\App\CentralLogics\Helpers::commission_check())
                                {{ translate('No business plan is available') }}
                                @else
                                {{ translate('Your Business Plan') }}
                                @endif
                            </h4>
                            <div class="card-body mb-2 p-4">
                                <div class="row">
                                    @if (\App\CentralLogics\Helpers::commission_check())
                                        <div class="col-sm-6">
                                            <label class="plan-check-item">
                                                <input type="radio" name="business_plan" value="commission-base"
                                                    class="d-none" checked>
                                                <div class="plan-check-item-inner">
                                                    <h5>{{ translate('Commision_Base') }}</h5>
                                                    <p>
                                                        {{ translate('restaurant will pay') }}
                                                        {{ $admin_commission }}% {{ translate('commission to') }}
                                                        {{ $business_name }}
                                                        {{ translate('from each order. You will get access of all the features and options  in restaurant panel , app and interaction with user.') }}
                                                    </p>
                                                </div>
                                            </label>
                                        </div>
                                    @endif
                                    @if (count($packages) > 0)
                                    <div class="col-sm-6">
                                        <label class="plan-check-item">
                                            <input type="radio" name="business_plan" value="subscription-base" {{ !\App\CentralLogics\Helpers::commission_check() ? 'checked' : ''  }}
                                                class="d-none">
                                            <div class="plan-check-item-inner">
                                                <h5>{{ translate('Subscription Base') }}</h5>
                                                <p>
                                                    {{ translate('Run restaurant by puchasing subsciption packages. You will have access the features of in restaurant panel , app and interaction with user according to the subscription packages.') }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                    @endif



                                    @if ( !\App\CentralLogics\Helpers::commission_check() && !count($packages) )
                                    <div class="col-12">
                                        <div class="empty--data text-center">
                                            <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                                            <h5>
                                            </h5>
                                        </div>
                                    </div>
                                    @endif




                                </div>
                                <div id="subscription-plan">
                                    <br>
                                    <h4 class="card-title text-center">
                                        {{ translate('Choose Subscription Package') }}
                                    </h4>
                                        <div class="card-body">
                                            <div class="plan-slider owl-theme owl-carousel owl-refresh">

                                                @forelse ($packages as $key=> $package)
                                                    <label
                                                        class="__plan-item {{ count($packages) > 4 &&  $key == 2 ||( count($packages) < 5 &&  $key == 1) || count($packages) == 1 ? 'active' : '' }} ">
                                                            <input type="radio" name="package_id" {{ count($packages) > 4 &&  $key == 2 ||( count($packages) < 5 &&  $key == 1) || count($packages) == 1 ? 'checked' : '' }}  value="{{ $package->id }}"  class="d-none">
                                                        <div class="inner-div">
                                                            <div class="text-center">

                                                                <h3 class="title">{{ $package->package_name }}</h3>
                                                                <h2 class="price">
                                                                    {{ \App\CentralLogics\Helpers::format_currency($package->price) }}
                                                                </h2>
                                                                <div class="day-count">{{ $package->validity }}
                                                                    {{ translate('messages.days') }}</div>
                                                            </div>
                                                            <ul class="info">

                                                                @if ($package->pos)
                                                                    <li>
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-1.svg') }}"
                                                                            class="check" alt="">
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-2.svg') }}"
                                                                            class="check-white" alt=""> <span>
                                                                            {{ translate('messages.POS') }} </span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->mobile_app)
                                                                    <li>
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-1.svg') }}"
                                                                            class="check" alt="">
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-2.svg') }}"
                                                                            class="check-white" alt=""> <span>
                                                                            {{ translate('messages.mobile_app') }} </span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->chat)
                                                                    <li>
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-1.svg') }}"
                                                                            class="check" alt="">
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-2.svg') }}"
                                                                            class="check-white" alt=""> <span>
                                                                            {{ translate('messages.chatting_options') }}
                                                                        </span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->review)
                                                                    <li>
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-1.svg') }}"
                                                                            class="check" alt="">
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-2.svg') }}"
                                                                            class="check-white" alt=""> <span>
                                                                            {{ translate('messages.review_section') }} </span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->self_delivery)
                                                                    <li>
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-1.svg') }}"
                                                                            class="check" alt="">
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-2.svg') }}"
                                                                            class="check-white" alt=""> <span>
                                                                            {{ translate('messages.self_delivery') }} </span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->max_order == 'unlimited')
                                                                    <li>
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-1.svg') }}"
                                                                            class="check" alt="">
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-2.svg') }}"
                                                                            class="check-white" alt=""> <span>
                                                                            {{ translate('messages.Unlimited_Orders') }}
                                                                        </span>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-1.svg') }}"
                                                                            class="check" alt="">
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-2.svg') }}"
                                                                            class="check-white" alt=""> <span>
                                                                            {{ $package->max_order }}
                                                                            {{ translate('messages.Orders') }} </span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->max_product == 'unlimited')
                                                                    <li>
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-1.svg') }}"
                                                                            class="check" alt="">
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-2.svg') }}"
                                                                            class="check-white" alt=""> <span>
                                                                            {{ translate('messages.Unlimited_uploads') }}
                                                                        </span>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-1.svg') }}"
                                                                            class="check" alt="">
                                                                        <img src="{{ dynamicAsset('/public/assets/landing/img/check-2.svg') }}"
                                                                            class="check-white" alt=""> <span>
                                                                            {{ $package->max_product }}
                                                                            {{ translate('messages.uploads') }} </span>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </label>

                                                @empty
                                                @endforelse

                                            </div>
                                        </div>
                                </div>
                                <div class="text-end mt-4 d-flex flex-wrap justify-content-end gap-3">
                                    <button type="button" id="back-to-form"
                                        class="btn btn--reset">{{ translate('Back') }}</button>
                                    <button type="submit" {{ !\App\CentralLogics\Helpers::commission_check() && !count($packages) ? 'disabled'  : ''}}
                                        class="btn btn--primary submitBtn">{{ translate('Next') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @endif

            </form>
        </div>
    </section>
    <!-- Page Header Gap -->
    <div class="h-148px"></div>
    <!-- Page Header Gap -->

@endsection
@push('script_2')
    <script src="{{ dynamicAsset('public/assets/admin') }}/js/tags-input.min.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()?->value }}&loading=async&libraries=drawing,places&v=3.58&language={{ str_replace('_', '-', app()->getLocale()) }}&callback=initMap">
    </script>
    <script>
        "use strict";
        $(".lang_link").click(function(e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');
            let form_id = this.id;
            let lang = form_id.substring(0, form_id.length - 5);
            $("#" + lang + "-form").removeClass('d-none');
            $("#" + lang + "-form1").removeClass('d-none');
            if (lang === "default") {
                $(".default-form").removeClass("d-none");
            }
        });



        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + viewer).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        $("#customFileEg1").change(function() {
            readURL(this, 'logoImageViewer');
        });

        $("#coverImageUpload").change(function() {
            readURL(this, 'coverImageViewer');
        });



        @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
        @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
        let zonePolygon = null;
        let map = null;
        let bounds = null;
        let infoWindow = null;

        function initMap() {


            let myLatlng = {
                lat: {{ $default_location ? $default_location['lat'] : '23.757989' }},
                lng: {{ $default_location ? $default_location['lng'] : '90.360587' }}
            };
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: myLatlng,
            });

            infoWindow = new google.maps.InfoWindow({
                content: "{{ translate('Click_the_map_to_get_Lat/Lng!') }}",
                position: myLatlng,
            });
            bounds = new google.maps.LatLngBounds();
            // Create the initial InfoWindow.
            infoWindow.open(map);
            //get current location block
            infoWindow = new google.maps.InfoWindow();
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        myLatlng = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        infoWindow.setPosition(myLatlng);
                        infoWindow.setContent("{{ translate('Location_found') }}");
                        infoWindow.open(map);
                        map.setCenter(myLatlng);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
            //-----end block------
            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            let markers = [];
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        return;
                    }
                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };
                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(
                browserHasGeolocation ?
                "Error: {{ translate('The Geolocation service failed.') }}" :
                "Error: {{ translate('Your browser does not support geolocation.') }}"
            );
            infoWindow.open(map);
        }
        $('#choice_zones').on('change', function() {
            let id = $(this).val();
            $.get({
                url: '{{ url('/') }}/admin/zone/get-coordinates/' + id,
                dataType: 'json',
                success: function(data) {
                    if (zonePolygon) {
                        zonePolygon.setMap(null);
                    }

                    let bounds = new google.maps.LatLngBounds();

                    zonePolygon = new google.maps.Polygon({
                        paths: data.coordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'white',
                        fillOpacity: 0,
                    });
                    zonePolygon.setMap(map);
                    zonePolygon.getPath().forEach(function(latlng) {
                        bounds.extend(latlng);
                    });

                    map.fitBounds(bounds);
                    map.setCenter(bounds.getCenter());

                    google.maps.event.addListener(zonePolygon, 'click', function(mapsMouseEvent) {
                        infoWindow.close();
                        infoWindow = new google.maps.InfoWindow({
                            position: mapsMouseEvent.latLng,
                            content: JSON.stringify(mapsMouseEvent.latLng.toJSON(),
                                null, 2),
                        });
                        let coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null,
                            2);
                        coordinates = JSON.parse(coordinates);

                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);
                    });
                },
            });
        });

        $('select').select2({
            width: '100%',
            placeholder: "Select an Option",
            allowClear: true
        });
    </script>
    <script src="{{ dynamicAsset('public/assets/admin/js/select2.min.js') }}"></script>

    <script>
        $(document).on('keyup', 'input[name="password"]', function() {
            const password = $(this).val();
            const feedback = $('#password-feedback');
            const minLength = password.length >= 8;
            const hasLowerCase = /[a-z]/.test(password);
            const hasUpperCase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            if (minLength && hasLowerCase && hasUpperCase && hasNumber && hasSymbol) {
                feedback.text("{{ translate('Password is valid') }}");
                feedback.removeClass('invalid').removeClass('d-none').addClass('valid');
            } else {
                feedback.text("{{ translate('Password format is invalid') }}");
                feedback.removeClass('valid').removeClass('d-none').addClass('invalid');
            }
        });

        $('#exampleInputPassword ,#exampleRepeatPassword').on('keyup', function() {
                let pass = $("#exampleInputPassword").val();
                let passRepeat = $("#exampleRepeatPassword").val();
                if (pass == passRepeat) {
                    $('.pass').hide();
                } else {
                    $('.pass').show();
                }
            });
        $('#show-business-plan-div').on('click', function(e) {
            e.preventDefault();
            const fileInput = document.querySelector('#customFileEg1');
            const coverPhotoInput = document.querySelector('#coverImageUpload');
            const maxFileSize = 2097152; // 2MB in bytes
            const requiredFields = $('input[required]');
            let isValid = true;
            requiredFields.each(function() {
                if ($(this).val().trim() === '') {
                    var fieldName = $(this).attr('data-field-name');

                    if (fieldName) {
                        toastr.error(fieldName + " {{ translate('field is required') }}");
                        isValid = false;
                        return false;
                    }
                }
            });
            if ($('#tax').val() > 100) {
                toastr.error("{{ translate('tax/vat_max_value_100') }}");
                isValid = false;
                return false;
            } else if (fileInput.files[0] && fileInput.files[0].size > maxFileSize) {
                toastr.error("{{ translate('restaurant_logo_must_be_less_than_2MB') }}");
                isValid = false;
                return false;
            } else if (coverPhotoInput.files[0] && coverPhotoInput.files[0].size > maxFileSize) {
                toastr.error("{{ translate('cover_photo_must_be_less_than_2MB') }}");
                isValid = false;
                return false;
            }

            if (isValid) {
                $('#business-plan-div').removeClass('d-none');
                $('#reg-form-div').addClass('d-none');
                $('#show-step2').addClass('current');
                $('#show-step1').removeClass('current').addClass('active');
                $(window).scrollTop(0);
            }
        });

        $('#back-to-form').on('click', function() {
            $('#business-plan-div').addClass('d-none');
            $('#reg-form-div').removeClass('d-none');
            $('#show-step1').addClass('current').removeClass('active') ;
            $('#show-step2').removeClass('current');
            $(window).scrollTop(0);
        })
        $("#form-id").on('submit', function(e) {
            const radios = document.querySelectorAll('input[name="business_plan"]');
            let selectedValue = null;

            for (const radio of radios) {
                if (radio.checked) {
                    selectedValue = radio.value;
                    break;
                }
            }

            if (selectedValue === 'subscription-base') {
                const package_radios = document.querySelectorAll('input[name="package_id"]');
                let selectedpValue = null;
                for (const pradio of package_radios) {
                    if (pradio.checked) {
                        selectedpValue = pradio.value;
                        break;
                    }
                }

                if (!selectedpValue) {
                    toastr.error("{{ translate('You_must_select_a_package') }}");
                    e.preventDefault();
                }
            }

        });

        $('.plan-slider').owlCarousel({
            loop: false,
            margin: 0,
            responsiveClass: true,
            nav:false,
            dots:false,
            items: 3,
            startPosition: 0,

            responsive: {
                0: {
                    items: 1.1,
                },
                375: {
                    items: 1.3,
                },
                576: {
                    items: 1.7,
                },
                768: {
                    items: 2.2,
                },
                992: {
                    items: 3,
                },
                1200: {
                    items: 4,
                }
            }
        })

        $(window).on('load', function() {
            $('input[name="business_plan"]').each(function() {
                if ($(this).is(':checked')) {
                    if ($(this).val() == 'subscription-base') {
                        $('#subscription-plan').show()
                    } else {
                        $('#subscription-plan').hide()
                    }
                }
            })
            $('input[name="package_id"]').each(function() {
                if ($(this).is(':checked')) {
                    $(this).closest('.__plan-item').addClass('active')
                }
            })
        })
        $('input[name="business_plan"]').on('change', function() {
            if ($(this).val() == 'subscription-base') {
                $('#subscription-plan').slideDown()
            } else {
                $('#subscription-plan').slideUp()
            }
        })
        $('input[name="package_id"]').on('change', function() {
            $('input[name="package_id"]').each(function() {
                $(this).closest('.__plan-item').removeClass('active')
            })
            $(this).closest('.__plan-item').addClass('active')
        })
        $('#reset-btn').on('click', function() {
            location.reload()
        })
    </script>
@endpush
