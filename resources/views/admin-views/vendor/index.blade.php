@extends('layouts.admin.app')

@section('title', translate('messages.add_new_restaurant'))
@push('css_or_js')
    <link rel="stylesheet" href="{{dynamicAsset('/public/assets/admin/css/intlTelInput.css')}}" />
    <link href="{{ dynamicAsset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">

@endpush
@section('content')
    <div class="content container-fluid initial-57">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-shop-outlined"></i>
                        {{ translate('messages.add_new_restaurant') }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
        @php($language = $language->value ?? null)
        @php($default_lang = str_replace('_', '-', app()->getLocale()))

        <form action="{{ route('admin.restaurant.store') }}" method="post" enctype="multipart/form-data"
            class="js-validate" id="res_form">
            @csrf
            <div class="row g-2">
                <div class="col-lg-6">
                    <div class="card shadow--card-2">
                        <div class="card-body">
                            @if($language)
                                <div class="js-nav-scroller hs-nav-scroller-horizontal">
                            <ul class="nav nav-tabs mb-4">
                                <li class="nav-item">
                                    <a class="nav-link lang_link active"
                                    href="#"
                                    id="default-link">{{ translate('Default') }}</a>
                                </li>
                                @foreach (json_decode($language) as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link"
                                            href="#"
                                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                    </li>
                                @endforeach
                            </ul>
                                </div>
                            @endif
                            <div class="lang_form" id="default-form">
                                <div class="form-group ">
                                <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.restaurant_name') }} ({{translate('messages.default')}})</label>
                                    <input type="text" name="name[]" class="form-control"  placeholder="{{ translate('messages.Ex:_ABC_Company') }} " maxlength="191"   >
                                </div>
                                <input type="hidden" name="lang[]" value="default">

                                <div>
                                    <label class="input-label" for="address">{{ translate('messages.restaurant_address') }} ({{translate('messages.default')}})</label>
                                    <textarea id="address" name="address[]" class="form-control h-70px" placeholder="{{ translate('messages.Ex:_House#94,_Road#8,_Abc_City') }} "  ></textarea>
                                </div>
                            </div>


                                @if ($language)
                                @foreach(json_decode($language) as $lang)
                                <div class="d-none lang_form" id="{{$lang}}-form">

                                    <div class="form-group" >
                                        <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.restaurant_name') }} ({{strtoupper($lang)}})</label>
                                        <input type="text" name="name[]" class="form-control"  placeholder="{{ translate('messages.Ex:_ABC_Company') }} " maxlength="191"  >
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">

                                    <div>
                                        <label class="input-label" for="address">{{ translate('messages.restaurant_address') }} ({{strtoupper($lang)}})</label>
                                        <textarea id="address{{$lang}}" name="address[]" class="form-control h-70px" placeholder="{{ translate('messages.Ex:_House#94,_Road#8,_Abc_City') }} "  ></textarea>
                                    </div>

                                </div>
                                @endforeach

                            @endif

                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow--card-2">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-header-icon mr-1"><i class="tio-dashboard"></i></span>
                                <span>{{translate('Restaurant_Logo_&_Covers')}}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap flex-sm-nowrap justify-content-around __gap-12px">

                                <div class="d-flex flex-column align-items-center gap-3">
                                    <p class="mb-0">{{ translate('logo') }}</p>

                                    <div class="image-box">
                                        <label for="image-input" class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-2">
                                        <img width="30" class="upload-icon" src="{{dynamicAsset('public/assets/admin/img/upload-icon.png')}}" alt="Upload Icon">
                                        <span class="upload-text">{{ translate('Upload Image')}}</span>
                                        <img src="#" alt="Preview Image" class="preview-image">
                                        </label>
                                        <button type="button" class="delete_image">
                                        <i class="tio-delete"></i>
                                        </button>
                                        <input type="file" id="image-input" name="logo" accept="image/*" hidden>
                                    </div>

                                    <p class="opacity-75 max-w220 mx-auto text-center">
                                        {{ translate('Image format - jpg png jpeg gif Image Size -maximum size 2 MB Image Ratio - 1:1')}}
                                    </p>
                                </div>

                                <div>
                                    <div class="d-flex flex-column align-items-center gap-3 mw-100">
                                        <p class="mb-0">{{ translate('Restaurant_Cover') }}</p>

                                        <div class="image-box banner2">
                                            <label for="image-input2" class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-2">
                                            <img width="30" class="upload-icon" src="{{dynamicAsset('public/assets/admin/img/upload-icon.png')}}" alt="Upload Icon">
                                            <span class="upload-text">{{ translate('Upload Image')}}</span>
                                            <img src="#" alt="Preview Image" class="preview-image">
                                            </label>
                                            <button type="button" class="delete_image">
                                            <i class="tio-delete"></i>
                                            </button>
                                            <input type="file" id="image-input2" name="cover_photo" accept="image/*" hidden>
                                        </div>

                                        <p class="opacity-75 max-w220 mx-auto text-center">
                                            {{ translate('Image format - jpg png jpeg gif Image Size -maximum size 2 MB Image Ratio - 2:1')}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card shadow--card-2">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-header-icon mr-1"><i class="tio-dashboard"></i></span>
                                <span>{{translate('Restaurant_Info')}}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="input-label" for="tax">{{translate('messages.vat/tax (%)')}}</label>
                                    <input id="tax" type="number" name="tax" class="form-control h--45px"
                                        placeholder="{{ translate('messages.Ex:_100') }}" min="0" step=".01" required
                                        value="{{ old('tax') }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <label class="input-label" for="tax">{{translate('Estimated_Delivery_Time_(_Min_&_Maximum_Time_)')}}</label>
                                        <input type="text" required id="time_view" class="form-control" readonly>
                                        <a href="javascript:void(0)" class="floating-date-toggler">&nbsp;</a>
                                        <span class="offcanvas"></span>
                                        <div class="floating--date" id="floating--date">
                                            <div class="card shadow--card-2">
                                                <div class="card-body">
                                                    <div class="floating--date-inner">
                                                        <div class="item">
                                                            <label class="input-label"
                                                                for="minimum_delivery_time">{{ translate('Minimum_Time') }}</label>
                                                            <input id="minimum_delivery_time" type="number" name="minimum_delivery_time" class="form-control h--45px" placeholder="{{ translate('messages.Ex:_30') }}"
                                                                pattern="^[0-9]{2}$" required value="{{ old('minimum_delivery_time') }}">
                                                        </div>
                                                        <div class="item">
                                                            <label class="input-label"
                                                                for="maximum_delivery_time">{{ translate('Maximum_Time') }}</label>
                                                            <input id="maximum_delivery_time" type="number" name="maximum_delivery_time" class="form-control h--45px" placeholder="{{ translate('messages.Ex:_60') }}"
                                                                pattern="[0-9]{2}" required value="{{ old('maximum_delivery_time') }}">
                                                        </div>
                                                        <div class="item smaller">
                                                            <select name="delivery_time_type" id="delivery_time_type" class="custom-select">
                                                                <option value="min">{{translate('messages.minutes')}}</option>
                                                                <option value="hours">{{translate('messages.hours')}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="item smaller">
                                                            <button type="button" class="btn btn--primary deliveryTime">{{ translate('done') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card shadow--card-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="cuisine">{{ translate('messages.cuisine') }}</label>
                                        <select name="cuisine_ids[]" id="cuisine" class="form-control h--45px min--45 js-select2-custom"
                                        multiple="multiple"  data-placeholder="{{ translate('messages.select_Cuisine') }}" >
                                            <option value="" disabled>{{ translate('messages.select_Cuisine') }}</option>
                                            @foreach (\App\Models\Cuisine::where('status',1 )->get(['id','name']) as $cu)
                                                    <option value="{{ $cu->id }}">{{ $cu->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="choice_zones">{{ translate('messages.zone') }}
                                                <span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.select_zone_for_map') }}"
                                                class="input-label-secondary"><img
                                                    src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span>
                                                </label>
                                        <select name="zone_id" id="choice_zones" required class="form-control h--45px js-select2-custom"
                                            data-placeholder="{{ translate('messages.select_zone') }}">
                                            <option value="" selected disabled>{{ translate('messages.select_zone') }}</option>
                                            @foreach (\App\Models\Zone::where('status',1 )->get(['id','name']) as $zone)
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

                                    <div class="form-group">
                                        <label class="input-label" for="latitude">{{ translate('messages.latitude') }}<span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.This_point_marks_the_latitude_of_the_restaurant’s_location_on_the_map.') }}"
                                                class="input-label-secondary"><img
                                                    src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span></label>
                                        <input type="text" id="latitude" name="latitude" class="form-control h--45px disabled"
                                            placeholder="{{ translate('messages.Ex:_-94.22213') }} " value="{{ old('latitude') }}" required readonly>
                                    </div>
                                    <div class="form-group mb-md-0">
                                        <label class="input-label" for="longitude">{{ translate('messages.longitude') }}
                                                <span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.This_point_marks_the_longitude_of_the_restaurant’s_location_on_the_map') }}"
                                                class="input-label-secondary"><img
                                                    src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span>
                                                </label>
                                        <input type="text" name="longitude" class="form-control h--45px disabled" placeholder="{{ translate('messages.Ex:_103.344322') }} "
                                            id="longitude" value="{{ old('longitude') }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <input id="pac-input" class="controls rounded initial-8" title="{{translate('messages.search_your_location_here')}}" type="text" placeholder="{{translate('messages.search_here')}}"/>
                                    <div style="height: 370px !important" id="map"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card shadow--card-2">
                        <div class="card-header">
                            <h4 class="card-title m-0 d-flex align-items-center"> <span class="card-header-icon mr-2"><i class="tio-user"></i></span> <span>{{ translate('messages.owner_info') }}</span></h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="f_name">{{ translate('messages.first_name') }}</label>
                                        <input id="f_name" type="text" name="f_name" class="form-control h--45px"
                                            placeholder="{{ translate('messages.Ex:_Jhone') }}"
                                            value="{{ old('f_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="l_name">{{ translate('messages.last_name') }}</label>
                                        <input id="l_name" type="text" name="l_name" class="form-control h--45px"
                                            placeholder="{{ translate('messages.Ex:_Doe') }}"
                                            value="{{ old('l_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="phone">{{ translate('messages.phone') }}</label>
                                        <input id="phone" type="tel" name="phone" class="form-control h--45px" placeholder="{{ translate('messages.Ex:_+9XXX-XXX-XXXX') }} "
                                            value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                @if (isset($page_data) && count($page_data) > 0 )

                <div class="col-lg-12">
                    <div class="card shadow--card-2">
                        <div class="card-header">
                            <h4 class="card-title m-0 d-flex align-items-center"> <span class="card-header-icon mr-2"><i class="tio-user"></i></span> <span>{{ translate('messages.Additional_Data') }}</span></h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                @foreach ( data_get($page_data,'data',[])  as $key=>$item)
                                    @if (!in_array($item['field_type'], ['file' , 'check_box']) )
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="{{ $item['input_data'] }}">{{translate($item['input_data'])  }}</label>
                                                <input id="{{ $item['input_data'] }}" {{ $item['is_required']  == 1? 'required' : '' }} type="{{ $item['field_type'] }}" name="additional_data[{{ $item['input_data'] }}]" class="form-control h--45px"
                                                    placeholder="{{ translate($item['placeholder_data']) }}"
                                                >
                                            </div>
                                        </div>
                                        @elseif ($item['field_type'] == 'check_box' )
                                            @if ($item['check_data'] != null)
                                            <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for=""> {{translate($item['input_data'])  }} </label>
                                                @foreach ($item['check_data'] as $k=> $i)
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="additional_data[{{ $item['input_data'] }}][]"  class="form-check-input" value="{{ $i }}"> {{ translate($i) }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                            </div>
                                            @endif
                                        @elseif ($item['field_type'] == 'file' )
                                            @if ($item['media_data'] != null)
                                            <?php
                                            $image= '';
                                            $pdf= '';
                                            $docs= '';
                                                if(data_get($item['media_data'],'image',null)){
                                                    $image ='.jpg, .jpeg, .png,';
                                                }
                                                if(data_get($item['media_data'],'pdf',null)){
                                                    $pdf =' .pdf,';
                                                }
                                                if(data_get($item['media_data'],'docs',null)){
                                                    $docs =' .doc, .docs, .docx' ;
                                                }
                                                $accept = $image.$pdf. $docs ;
                                            ?>
                                                <div class="col-md-4 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="{{ $item['input_data'] }}">{{translate($item['input_data'])  }}</label>
                                                        <input id="{{ $item['input_data'] }}" {{ $item['is_required']  == 1? 'required' : '' }} type="{{ $item['field_type'] }}" name="additional_documents[{{ $item['input_data'] }}][]" class="form-control h--45px"
                                                            placeholder="{{ translate($item['placeholder_data']) }}"
                                                                {{ data_get($item['media_data'],'upload_multiple_files',null) ==  1  ? 'multiple' : '' }} accept="{{ $accept ??  '.jpg, .jpeg, .png'  }}"
                                                            >
                                                    </div>
                                                </div>
                                            @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
                @endif

                    <div class="col-lg-12">
                        <div class="card shadow--card-2 border-0">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <span class="card-header-icon mr-2"><i class="tio-label"></i></span>
                                    <span>{{ translate('tags') }}</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <input type="text" class="form-control" name="tags" placeholder="Enter tags" data-role="tagsinput">
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-lg-12" >
                        <label class="input-label" for="sub-categories">
                            {{translate('tags')}}
                        </label>
                        <select name="tags" class="form-control multiple-select2"  data-placeholder="{{ translate('messages.Type your content and  press enter') }}" multiple>
                        </select>
                    </div> --}}




                <div class="col-lg-12">
                    <div class="card shadow--card-2">
                        <div class="card-header">
                            <h4 class="card-title m-0 d-flex align-items-center"><span class="card-header-icon mr-2"><i class="tio-user"></i></span> <span>{{ translate('messages.account_info') }}</span></h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="email">{{ translate('messages.email') }}</label>
                                        <input id="email" type="email" name="email" class="form-control h--45px" placeholder="{{ translate('messages.Ex:_Jhone@company.com') }} "
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="js-form-message form-group">
                                        <label class="input-label"
                                            for="signupSrPassword">{{ translate('messages.password') }}
                                            <span class="input-label-secondary ps-1" data-toggle="tooltip" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>

                                        </label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control h--45px" name="password"
                                                id="signupSrPassword"
                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                                placeholder="{{ translate('messages.Ex:_8+_Character') }}"
                                                aria-label="{{translate('messages.password_length_8+')}}"
                                                required data-msg="Your password is invalid. Please try again."
                                                data-hs-toggle-password-options='{
                                                                                    "target": [".js-toggle-password-target-1"],
                                                                                    "defaultClass": "tio-hidden-outlined",
                                                                                    "showClass": "tio-visible-outlined",
                                                                                    "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                                                                    }'>
                                            <div class="js-toggle-password-target-1 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                    <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="js-form-message form-group">
                                        <label class="input-label"
                                            for="signupSrConfirmPassword">{{ translate('messages.confirm_password') }}</label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control h--45px" name="confirmPassword"
                                                id="signupSrConfirmPassword"
                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                                placeholder="{{ translate('messages.Ex:_8+_Character') }}"
                                                aria-label="{{translate('messages.password_length_8+')}}"
                                                required data-msg="Password does not match the confirm password."
                                                data-hs-toggle-password-options='{
                                                                                        "target": [".js-toggle-password-target-2"],
                                                                                        "defaultClass": "tio-hidden-outlined",
                                                                                        "showClass": "tio-visible-outlined",
                                                                                        "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                                                        }'>
                                            <div class="js-toggle-password-target-2 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                    <i class="js-toggle-passowrd-show-icon-2 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn--container justify-content-end mt-3">
                <button id="reset_btn" type="button" class="btn btn--reset">{{translate('messages.reset')}}</button>
                <button type="submit" class="btn btn--primary h--45px"><i class="tio-save"></i> {{ translate('messages.save_information') }}</button>
            </div>
        </form>

    </div>

@endsection

@push('script_2')
<script src="{{ dynamicAsset('public/assets/admin') }}/js/tags-input.min.js"></script>
    <script src="{{ dynamicAsset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script
            src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&loading=async&libraries=drawing,places&v=3.58&language={{ str_replace('_', '-', app()->getLocale()) }}&callback=initMap">
    </script>
    <script>
        "use strict";
        $(document).on('ready', function() {
            $('.offcanvas').on('click', function(){
                $('.offcanvas, .floating--date').removeClass('active')
            })
            $('.floating-date-toggler').on('click', function(){
                $('.offcanvas, .floating--date').toggleClass('active')
            })
        });

        $(document).on('ready', function() {
            @if (isset(auth('admin')->user()->zone_id))
            $('#choice_zones').trigger('change');
            @endif
        });


        $("#customFileEg1").change(function() {
            readURL(this, 'viewer');
        });

        $("#coverImageUpload").change(function() {
            readURL(this, 'coverImageViewer');
        });

        $('#res_form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ dynamicAsset('public/assets/admin/img/400x400/img2.jpg') }}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error('{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ translate('messages.file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
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

                zonePolygon = null;

                infoWindow = new google.maps.InfoWindow({
                    content: "{{  translate('Click_the_map_inside_the_red_marked_area_to_get_Lat/Lng!!!') }}",
                    position: myLatlng,
                });

                bounds = new google.maps.LatLngBounds();

                    infoWindow = new google.maps.InfoWindow();
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                myLatlng = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude,
                                };
                                infoWindow.setPosition(myLatlng);
                                infoWindow.setContent("{{ translate('Select_Zone_From_The_Dropdown') }}");
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
                        markers.forEach((marker) => {
                        marker.setMap(null);
                        });
                        markers = [];
                        // For each place, get the icon, name and location.
                        const bounds = new google.maps.LatLngBounds();
                        places.forEach((place) => {
                        if (!place.geometry || !place.geometry.location) {
                            console.log("Returned place contains no geometry");
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
                        "{{ translate('Select_Zone_From_The_Dropdown.') }}" :
                        "{{ translate('Error:_Your_browser_doesnot_support_geolocation.') }}"
                    );
                    infoWindow.open(map);
                }
                $('#choice_zones').on('change', function() {
                    infoWindow.close();
                    var id = $(this).val();
                    $.get({
                        url: '{{ url('/') }}/admin/zone/get-coordinates/' + id,
                        dataType: 'json',
                        success: function(data) {
                            if (zonePolygon) {
                                zonePolygon.setMap(null);
                            }
                            zonePolygon = new google.maps.Polygon({
                                paths: data.coordinates,
                                strokeColor: "#FF0000",
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: 'white',
                                fillOpacity: 0,
                            });
                            zonePolygon.setMap(map);
                            // zonePolygon.getPaths().forEach(function(path) {
                            //     path.forEach(function(latlng) {
                            //         bounds.extend(latlng);
                            //         map.fitBounds(bounds);
                            //     });
                            // });


                            bounds = new google.maps.LatLngBounds();
                            zonePolygon.getPaths().forEach(function(path) {
                                path.forEach(function(latlng) {
                                    bounds.extend(latlng);
                                });
                            });
                            map.fitBounds(bounds);

                            infoWindow = new google.maps.InfoWindow({
                                content: "{{  translate('Click_the_map_inside_the_red_marked_area_to_get_Lat/Lng!') }}",
                                position: bounds.getCenter(),
                            });
                        infoWindow.open(map);
                            map.setCenter(data.center);
                            google.maps.event.addListener(zonePolygon, 'click', function(mapsMouseEvent) {
                                infoWindow.close();
                                // Create a new InfoWindow.
                                infoWindow = new google.maps.InfoWindow({
                            position: mapsMouseEvent.latLng,
                            content: JSON.stringify(mapsMouseEvent.latLng.toJSON(),
                                null, 2),
                        });
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null,
                            2);
                            infoWindow.close();
                        var coordinates = JSON.parse(coordinates);
                                    document.getElementById('latitude').value = coordinates['lat'];
                                    document.getElementById('longitude').value = coordinates['lng'];
                                    infoWindow.open(map);
                                });
                            },
                        });
                    });
                    document.addEventListener('keypress', function (e) {
                        if (e.keyCode === 13 || e.which === 13) {
                            e.preventDefault();
                            return false;
                        }
                    });

        $('#reset_btn').click(function(){
            $('#name').val(null);
            $('#tax').val(null);
            $('#address').val(null);
            $('#minimum_delivery_time').val(null);
            $('#maximum_delivery_time').val(null);
            $('#viewer').attr('src', "{{ dynamicAsset('public/assets/admin/img/upload.png') }}");
            $('#customFileEg1').val(null);
            $('#coverImageViewer').attr('src', "{{ dynamicAsset('public/assets/admin/img/upload-img.png') }}");
            $('#coverImageUpload').val(null);
            $('#choice_zones').val(null).trigger('change');
            $('#f_name').val(null);
            $('#l_name').val(null);
            $('#phone').val(null);
            $('#email').val(null);
            $('#signupSrPassword').val(null);
            $('#signupSrConfirmPassword').val(null);
            zonePolygon.setMap(null);
            $('#coordinates').val(null);
            $('#latitude').val(null);
            $('#longitude').val(null);
        })


    $('.deliveryTime').click(function(){
        var min = $("#minimum_delivery_time").val();
        var max = $("#maximum_delivery_time").val();
        var type = $("#delivery_time_type").val();
        $("#floating--date").removeClass('active');
        $("#time_view").val(min+' to '+max+' '+type);

    })
</script>
@endpush
