@php use App\CentralLogics\Helpers; @endphp
@extends('layouts.admin.app')

@section('title', translate('messages.landing_page_settings'))
@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title text-capitalize">
                    <!-- <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                    </div> -->
                    <span>
                        {{ translate('React_Registration_Page') }}
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
                @include('admin-views.registration-page.top_menu.react_registration_menu')
            </div>
        </div>
        <div class="">
            <div class="page--header-title mb-3">
                <h1 class="page-header-title">{{ translate('Hero_Section_Image') }}</h1>
            </div>
            <form action="{{ route('admin.business-settings.react-registration-page.hero-update') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card p-sm-4 p-3">
                    <h4 class="text-center mb-5 mt-md-4 mt-2">{{ translate('Upload_Image') }}</h4>
                    <div class="registration-upload mb-5">
                        <div class="image-box">
                            <label for="image-input" class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-1">
                                <img width="30" class="upload-icon mb-2 {{ isset($image_content['hero_image_content'])?'d-none':'' }}" src="{{dynamicAsset('/public/assets/admin/img/image-upload.png')}}" alt="Upload Icon">
                                <span class="drag-text upload-icon fs-12 {{ isset($image_content['hero_image_content'])?'d-none':'' }}">
                                    <span class="clickupload-text mb-0 upload-icon ">{{ translate('Click_to_upload') }}</span> <br>
                                    {{ translate('or_drag_and_drop') }}
                                </span>
                                <img src="{{ $hero_image_content_full_url??'' }}" data-original-src="{{ isset($image_content['hero_image_content'])? $hero_image_content_full_url :'' }}"
                                data-onerror-image="{{dynamicAsset('/public/assets/admin/img/image-upload.png')}}" alt="Preview Image" class="preview-image onerror-image {{ isset($image_content['hero_image_content'])?'d-block':'' }}">
                            </label>
                            @if (isset($image_content['hero_image_content'] ))
                                        <span id="hero_image_content"
                                                class="remove_image_button remove-image"
                                                data-id="hero_image_content"
                                                data-title="{{translate('Warning!')}}"
                                                data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"
                                            > <i class="tio-clear"></i></span>
                                        @endif
                            <input type="file" id="image-input" name="hero_image_content" accept="image/*" hidden="">
                        </div>
                    </div>
                    <p class="text-center image-formate fs-12">{{ translate('JPG,_JPEG,_PNG_Less_Than_5MB') }} <span class="">({{ translate('1200 x 750 px') }})</span></p>
                    <div class="btn--container justify-content-sm-end mt-lg-8 mt-4">
                        <button type="reset" class="btn btn--reset">{{ translate('reset') }}</button>
                        <button type="submit" class="btn btn--primary">{{ translate('add') }}</button>
                    </div>
                </div>
            </form>

        </div>

        <form  id="hero_image_content_form" action="{{ route('admin.remove_image') }}" method="post">
            @csrf
                <input type="hidden" name="id" value="{{  $hero_image_content?->id}}" >
                <input type="hidden" name="json" value="1" >
                <input type="hidden" name="model_name" value="DataSetting" >
                <input type="hidden" name="image_path" value="hero_image" >
                <input type="hidden" name="field_name" value="hero_image_content" >
            </form>


@endsection

@push('script')
<script>
    $(document).ready(function () {
        $('form').on('reset', function () {
            var $previewImage = $('.preview-image');
            var originalSrc = $previewImage.data('original-src');
            if (originalSrc) {
                $previewImage.attr('src', originalSrc).addClass('d-block');
            }
            $('#image-input').val('');

            $('.upload-icon').removeClass('d-block');
        });
    });
</script>
@endpush

