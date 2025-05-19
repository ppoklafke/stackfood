@extends('layouts.admin.app')

@section('title',translate('messages.react_landing_page'))

@section('content')
<div class="content container-fluid">
    <div class="page-header pb-0">
        <div class="d-flex flex-wrap justify-content-between">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{dynamicAsset('public/assets/admin/img/landing-page.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{ translate('messages.react_landing_pages') }}
                </span>
            </h1>
            <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                <strong class="mr-2">{{translate('See_how_it_works!')}}</strong>
                <div>
                    <i class="tio-info-outined"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-4 mt-2">
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            @include('admin-views.landing_page.top_menu.react_landing_menu')
        </div>
    </div>


    <form id="zone-setup-form" action="{{ route('admin.react_landing_page.availableZoneUpdate') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="page_type" value="react_landing_page">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        {{ translate('To view a list of all active zones on your') }} {{ translate('React Landing Page,') }} <br class="d-none d-md-inline-block"> {{ translate('Enable the')}} <strong>{{ translate('`Available Zones`') }}</strong> {{translate('feature') }}
                    </div>
                    <div class="col-sm-6">
                        <label
                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1 text--primary">
                                                    {{translate('messages.available_zone') }}
                                                </span>
                                            </span>
                            <input type="checkbox"
                                   data-id="available_zone_status"
                                   data-type="toggle"
                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                   data-title-on="<strong>{{ translate('messages.Want_to_enable_available_zone?') }}</strong>"
                                   data-title-off="<strong>{{ translate('messages.Want_to_disable_available_zone?') }}</strong>"
                                   data-text-on="<p>{{ translate('messages.If_you_enable_this,_available_zone_section_will_be_visible.') }}</p>"
                                   data-text-off="<p>{{ translate('messages.If_you_disable_this,_available_zone_section_will_not_be_visible.') }}</p>"
                                   class="status toggle-switch-input dynamic-checkbox-toggle"
                                   value="1"
                                   name="available_zone_status" id="available_zone_status"
                                {{ $available_zone_status == 1 ? 'checked' : '' }}>
                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3">
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
                                @foreach ($language as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link"
                                           href="#"
                                           id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                    </li>
                                @endforeach
                            </ul>
                                </div>
                        @endif
                        @if ($language)
                            <div class="lang_form"
                                 id="default-form">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="default_title">{{ translate('messages.title') }}
                                        ({{ translate('messages.Default') }})<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_50_characters') }}">
                                                <img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="">
                                            </span>
                                    </label>
                                    <input type="text" name="available_zone_title[]" id="default_title" maxlength="50"
                                           class="form-control" placeholder="{{ translate('messages.title') }}" value="{{$available_zone_title?->getRawOriginal('value')}}"
                                    >
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{ translate('messages.short_description') }} ({{ translate('messages.default') }})<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_200_characters') }}">
                                                <img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="">
                                            </span></label>
                                    <textarea type="text" name="available_zone_short_description[]" maxlength="200" placeholder="{{translate('messages.short_description')}}" class="form-control min-h-90px ckeditor">{{$available_zone_short_description?->getRawOriginal('value')}}</textarea>
                                </div>
                            </div>
                            @foreach ($language as $lang)
                                    <?php
                                        if(isset($available_zone_title->translations)&&count($available_zone_title->translations)){
                                            $available_zone_title_translate = [];
                                            foreach($available_zone_title->translations as $t)
                                            {
                                                if($t->locale == $lang && $t->key=='available_zone_title'){
                                                    $available_zone_title_translate[$lang]['value'] = $t->value;
                                                }
                                            }

                                        }
                                        if(isset($available_zone_short_description->translations)&&count($available_zone_short_description->translations)){
                                            $available_zone_short_description_translate = [];
                                            foreach($available_zone_short_description->translations as $t)
                                            {
                                                if($t->locale == $lang && $t->key=='available_zone_short_description'){
                                                    $available_zone_short_description_translate[$lang]['value'] = $t->value;
                                                }
                                            }

                                        }
                                    ?>
                                <div class="d-none lang_form"
                                     id="{{ $lang }}-form">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="{{ $lang }}_title">{{ translate('messages.title') }}
                                            ({{ strtoupper($lang) }})<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_50_characters') }}">
                                                <img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="">
                                            </span>
                                        </label>
                                        <input type="text" name="available_zone_title[]" maxlength="50" id="{{ $lang }}_title"
                                               class="form-control" value="{{ $available_zone_title_translate[$lang]['value']??'' }}" placeholder="{{ translate('messages.title') }}">
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{ translate('messages.short_description') }} ({{ strtoupper($lang) }})<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_200_characters') }}">
                                                <img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="">
                                            </span></label>
                                        <textarea type="text" name="available_zone_short_description[]" maxlength="200" placeholder="{{translate('messages.short_description')}}" class="form-control min-h-90px ckeditor">{{ $available_zone_short_description_translate[$lang]['value']??'' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div id="default-form">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{ translate('messages.title') }} ({{ translate('messages.default') }})</label>
                                    <input type="text" name="available_zone_title[]" class="form-control"
                                           placeholder="{{ translate('messages.title') }}" >
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{ translate('messages.short_description') }}
                                    </label>
                                    <textarea type="text" name="available_zone_short_description[]" placeholder="{{translate('messages.short_description')}}" class="form-control min-h-90px ckeditor"></textarea>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div>
                            <div class="d-flex justify-content-center">
                                <label class="text-dark d-block mb-4">
                                    <strong>{{ translate('Related Image') }}</strong>
                                    <small class="text-danger">* {{ translate('( Ratio 1:1 )') }}</small>
                                </label>
                            </div>
                            <div class="d-flex justify-content-center">
                                <label class="text-center position-relative">
                                    <img class="img--110 min-height-170px min-width-170px onerror-image image--border" id="viewer"
                                         data-onerror-image="{{ dynamicAsset('public/assets/admin/img/upload.png') }}"
                                         src="{{\App\CentralLogics\Helpers::get_full_url('available_zone_image', $available_zone_image?->value?? '', $available_zone_image?->storage[0]?->value ?? 'public','upload_image')}}"
                                         alt="logo image" />
                                    <div class="icon-file-group">
                                        <div class="icon-file">
                                            <i class="tio-edit"></i>
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card shadow-none border-0 bg-soft-danger">
                    <div class="card-body d-flex">
                        <i class="mr-2 mt-3 text-danger tio-info-outined"></i>
                        <p class="fs-15 text-dark m-0">
                            <strong>{{ translate('Note:') }}</strong> {{ translate('Customize the section by adding a title, short description, and images in the') }} <a href="{{ route('admin.zone.home') }}" target="_blank" class="text--underline text-006AE5">{{ translate('Zone Setup') }}</a> {{ translate('section. All created zones will be automatically displayed on the') }} {{ translate('React Landing Page. The zones will be based on the Zone Display Name.') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="btn--container justify-content-end">
                    <button class="btn btn--reset " type="reset">{{translate('reset')}}</button>
                    <button class="btn btn--primary" type="submit">{{translate('Save Information')}}</button>
                </div>
            </div>
        </div>
    </form>
    </div>


    <!-- How it Works -->
@endsection

@push('script_2')
    <script>
        // Form on reset
        const prevImage = $('#viewer').attr('src');
        $('#zone-setup-form').on('reset', function(){
            $('#customFileEg1').val(null);
            $('#viewer').attr('src', prevImage);
        })

        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });
    </script>
@endpush
