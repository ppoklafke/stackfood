@extends('layouts.admin.app')

@section('title',translate('messages.promotional_banner'))


@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title"><i class="tio-edit"></i>{{translate('messages.promotional_banner')}}</h1>
            </div>
        </div>
    </div>

        <div class="col-lg-12 mb-3 mb-lg-12">
            <div class="card h-100">
                <div class="card-body">
                    <form action="{{route('admin.banner.promotional_banner_update')}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                                    @php($language = $language->value ?? null)
                                    @php($default_lang = str_replace('_', '-', app()->getLocale()))
                                    @if($language)
                                <div class="js-nav-scroller hs-nav-scroller-horizontal">
                                    <ul class="nav nav-tabs mb-4">
                                        <li class="nav-item">
                                            <a class="nav-link lang_link active" href="#"
                                                id="default-link">{{translate('messages.default')}}</a>
                                        </li>
                                        @foreach (json_decode($language) as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link lang_link" href="#" id="{{ $lang }}-link">{{
                                                \App\CentralLogics\Helpers::get_language_name($lang) . '(' .
                                                strtoupper($lang) . ')' }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                    <div class="lang_form" id="default-form">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="default_title">{{translate('messages.title')}}
                                                ({{translate('messages.default')}})</label>
                                            <input type="text" name="promotional_banner_title[]" id="default_title" class="form-control"
                                                placeholder="{{translate('messages.new_banner')}}" value="{{ $banner_title?->getRawOriginal('value') ?? null }}"
                                                 >
                                        </div>
                                        <input type="hidden" name="lang[]" value="default">
                                    </div>
                                    @foreach(json_decode($language) as $lang)

                                    <?php
                                                    if($banner_title?->translations){
                                                        $translate = [];
                                                        foreach($banner_title['translations'] as $t)
                                                        {
                                                            if($t->locale == $lang && $t->key=="promotional_banner_title"){
                                                                $translate[$lang]['promotional_banner_title'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                ?>
                                    <div class="d-none lang_form" id="{{$lang}}-form">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="{{$lang}}_title">{{translate('messages.title')}}
                                                ({{strtoupper($lang)}})</label>
                                            <input type="text" name="promotional_banner_title[]" id="{{$lang}}_title" class="form-control"
                                                placeholder="{{translate('messages.new_banner')}}"
                                                value="{{$translate[$lang]['promotional_banner_title']??''}}"
                                                 >
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                    </div>
                                    @endforeach
                                    @else
                                    <div id="default-form">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{translate('messages.title')}} ({{
                                                translate('messages.default') }})</label>
                                            <input type="text" name="promotional_banner_title[]" class="form-control"
                                                placeholder="{{translate('messages.new_banner')}}"
                                                  value="{{$banner_title?->value}}"
                                                  maxlength="100">
                                        </div>
                                        <input type="hidden" name="lang[]" value="default">
                                    </div>
                                    @endif
                                </div>

                            </div>

                        </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mb-3 mb-lg-12">
            <div class="card h-100">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 d-flex justify-content-between">
                                <span class="d-flex g-1">
                                    <img src="{{dynamicAsset('public/assets/admin/img/other-banner.png')}}" class="h-85"
                                        alt="">
                                </span>
                                <div>
                                    <div class="blinkings">
                                        <div>
                                            <i class="tio-info-outined"></i>
                                        </div>
                                        <div class="business-notes">
                                            <h6><img src="{{dynamicAsset('/public/assets/admin/img/notes.png')}}" alt="">
                                                {{translate('Note')}}</h6>
                                            <div>
                                                {{translate('messages.this_banner_is_only_for_web.')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <h3 class="form-label d-block mb-5 text-center">
                                    {{translate('Upload_Banner')}}
                                </h3>
                                <label class="__upload-img aspect-5-1 m-auto d-block">
                                    <div class="img">
                                        <img class="onerror-image" style="object-fit: cover"
                                             src="{{ \App\CentralLogics\Helpers::get_full_url('banner',$banner_image?->value,$banner_image?->storage[0]?->value ?? 'public','upload_image') }}"
                                             data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload-placeholder.png')}}" alt="">
                                    </div>
                                    <input type="file" name="promotional_banner_image" hidden>
                                </label>

                                <div class="text-center mt-5">
                                    <h3 class="form-label d-block mt-2">
                                        {{translate('Min_Size_for_Better_Resolution_5:1')}}
                                    </h3>
                                    <p>{{translate('image_format_:_jpg_,_png_,_jpeg_|_maximum_size:_2_MB')}}</p>

                                </div>
                            </div>
                        </div>

                    </div>
            </div>

        </div>
        <div class="btn--container justify-content-end mt-3">
            <button type="submit" class="btn btn--primary">{{translate('messages.Save')}}</button>
        </div>
        </form>

    </div>

</div>


@endsection

@push('script_2')
<script>
    $(document).ready(function() {
        "use strict"
        $(".__upload-img, .upload-img-4, .upload-img-2, .upload-img-5, .upload-img-1, .upload-img").each(function(){
            var targetedImage = $(this).find('.img');
            var targetedImageSrc = $(this).find('.img img');
            function proPicURL(input) {
                if (input.files && input.files[0]) {
                    var uploadedFile = new FileReader();
                    uploadedFile.onload = function (e) {
                        targetedImageSrc.attr('src', e.target.result);
                        targetedImage.addClass('image-loaded');
                        targetedImage.hide();
                        targetedImage.fadeIn(650);
                    }
                    uploadedFile.readAsDataURL(input.files[0]);
                }
            }
            $(this).find('input').on('change', function () {
                proPicURL(this);
            })
        })
    });
</script>
@endpush
