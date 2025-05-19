@extends('layouts.vendor.app')

@section('title','Advertisement Create')


@section('advertisement_create')
active
@endsection


@push('css_or_js')
    <link rel="stylesheet" type="text/css" href="{{dynamicAsset('public/assets/admin/css/daterangepicker.css')}}"/>
@endpush

@section('content')
<div class="content container-fluid">


    <!-- Advertisement -->
    <h1 class="page-header-title mb-3">
        {{ translate('Create_Advertisement') }}
    </h1>
    <div class="card mb-20">
        <div class="card-body p-30">
            <form id="create-add-form"  method="POST" enctype="multipart/form-data" >
                @csrf
                @method("POST")

                <input type="hidden" value="{{ \App\CentralLogics\Helpers::get_restaurant_id() }}" name="restaurant_id" >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="js-nav-scroller hs-nav-scroller-horizontal">
                        <ul class="nav nav-tabs mb-3 border-0">
                        <li class="nav-item">
                            <a class="nav-link lang_link active"
                            href="#"
                            id="default-link">{{translate('messages.default')}}</a>
                        </li>

                        @if ($language)
                        @foreach ($language as $lang)
                            <li class="nav-item">
                                <a class="nav-link lang_link"
                                    href="#"
                                    id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                            <div class="lang_form" id="default-form">


                            <div class="mb-20">
                                <label class="form-label">{{ translate('Advertisement_Title') }} ({{ translate('Default') }})</label>
                                <input type="text" class="form-control" id="title" name="title[]"
                                    value="{{ old('title.0') }}" placeholder="{{ translate('Exclusive_Offer') }}" maxlength="255"
                                    data-preview-text="preview-title">
                            </div>
                            <div class="form-floating mb-20">
                                <label class="form-label">{{ translate('Short_Description') }} ({{ translate('Default') }})</label>
                                <textarea class="form-control resize-none" id="description"
                                    placeholder="{{ translate('Get_Discount') }}" name="description[]"
                                    data-preview-text="preview-description">{{ old('description.0') }}</textarea>
                                </div>

                            <input type="hidden" name="lang[]" value="default">
                            </div>

                            @foreach ($language as $key => $lang)
                            <div class="d-none lang_form"
                                id="{{ $lang }}-form">




                                <div class="mb-20">
                                    <label class="form-label">{{ translate('Advertisement_Title') }}   ({{ strtoupper($lang) }})</label>
                                    <input type="text" class="form-control" id="title" name="title[]"
                                        value="{{ old('title.0') }}" placeholder="{{ translate('Exclusive_Offer') }}" maxlength="255"
                                        data-preview-text="preview-title">
                                </div>
                                <div class="form-floating mb-20">
                                    <label class="form-label">{{ translate('Short_Description') }}   ({{ strtoupper($lang) }})</label>
                                    <textarea class="form-control resize-none" id="description"
                                        placeholder="{{ translate('Get_Discount') }}" name="description[]"
                                        data-preview-text="preview-description">{{ old('description.0') }}</textarea>
                                    </div>

                                <input type="hidden" name="lang[]" value="{{ $lang }}">
                            </div>
                        @endforeach

                            @else

                            <div class="mb-20">
                                <label class="form-label">{{ translate('Advertisement_Title') }}</label>
                                <input type="text" class="form-control" id="title" name="title[]"
                                    value="{{ old('title.0') }}" placeholder="{{ translate('Exclusive_Offer') }}" maxlength="255"
                                    data-preview-text="preview-title">
                            </div>
                            <div class="form-floating mb-20">
                                <label class="form-label">{{ translate('Short_Description') }}</label>
                                <textarea class="form-control resize-none" id="description"
                                    placeholder="{{ translate('Get_Discount') }}" name="description[]"
                                    data-preview-text="preview-description">{{ old('description.0') }}</textarea>
                            </div>
                            <input type="hidden" name="lang[]" value="default">

                            @endif


                        <div class="mb-20">
                            <label class="form-label">{{ translate('Advertisement_Type') }}</label>
                            <select class="js-select form-control w-100 promotion_type" name="advertisement_type">
                                <option value="video_promotion">{{ translate('Video_Promotion') }}</option>
                                <option value="restaurant_promotion" selected="">{{ translate('restaurant_promotion') }}</option>
                            </select>
                        </div>
                        <div class="mb-20">
                            <label class="form-label">{{ translate('Validity') }}</label>
                            <div class="position-relative">
                                <i class="tio-calendar-month icon-absolute-on-right"></i>
                                <input type="text" class="form-control h-45 position-relative bg-transparent" name="dates" placeholder="dd/mm/yyyy - dd/mm/yyyy">
                            </div>
                        </div>

                        <div class="promotion-typewise-upload-box" id="video-upload-box">
                            <label class="form-label">{{ translate('Upload Related Files') }}</label>
                            <div class="border rounded p-3">
                                <div class="d-flex flex-column align-items-center gap-3">
                                    <p class="title-color mb-0 ">{{ translate('Upload Your Video') }}

                                        ({{ translate('16:9') }})</p>

                                    <div class="upload-file">
                                        <input type="file" class="video_attachment" name="video_attachment"
                                            accept="video/mp4, video/webm, video/mkv">
                                        <div class="upload-file__img upload-file__img_banner upload-file__video-not-playable h-140">
                                        </div>
                                        <button class="remove-file-button" type="button">
                                            <i class="tio-clear"></i>
                                        </button>
                                    </div>

                                    <p class="opacity-75 max-w220 mx-auto text-center fs-12">
                                        {{ translate('Maximum 5 MB') }}
                                        <br>
                                        {{ translate('Supports: MP4, WEBM, MKV') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="promotion-typewise-upload-box" id="profile-upload-box">
                            <h5 class="mb-3">{{ translate('Show Review') }} &amp; {{ translate('Ratings') }}</h5>
                            <div class="card bg--secondary shadow-none">
                                <div class="card-body p-3">
                                    <div class="w-100 d-flex flex-wrap gap-3">
                                        <label class="form-check form--check-2 me-3">
                                            <input type="checkbox" class="form-check-input" value="1" name="review" checked="">
                                            <span class="form-check-label">{{ translate('Review') }}</span>
                                        </label>
                                        <label class="form-check form--check-2">
                                            <input type="checkbox" class="form-check-input" value="1" name="rating" checked="">
                                            <span class="form-check-label">{{ translate('Rating') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <label class="form-label">{{ translate('Upload Related Files') }}</label>
                            <div class="d-flex flex-wrap justify-content-center gap-3 border rounded p-3">
                                <div class="d-flex flex-column align-items-center gap-3">
                                    <p class="title-color mb-0">{{ translate('Profile Image') }} <span class="text-danger">({{ translate('Ratio - 1:1') }})</span></p>

                                    <div class="upload-file">
                                        <input type="file" class="cover_attachment js-upload-input"
                                            data-target="profile-prev-image" name="profile_image"
                                            accept=".png,.jpg,.jpeg,.gif, |image/*">
                                        <div class="upload-file__img">
                                            <img src="{{dynamicAsset('public/assets/admin/img/media/upload-file.png')}}" alt="" >
                                        </div>
                                        <button class="remove-file-button" type="button">
                                            <i class="tio-clear"></i>
                                        </button>
                                    </div>

                                    <p class="opacity-75 max-w220 mx-auto text-center fs-12">
                                        {{ translate('Supports: PNG, JPG, JPEG, WEBP') }}
                                        <br>
                                        {{ translate('Maximum 2 MB') }}
                                    </p>
                                </div>
                                <div class="d-flex flex-column align-items-center gap-3">
                                    <p class="title-color mb-0">{{ translate('Upload Cover') }} <span class="text-danger">({{ translate('Ratio - 2:1') }})</span></p>
                                    <div class="upload-file">
                                        <input type="file" class="cover_attachment js-upload-input"
                                            data-target="main-image" name="cover_image"
                                            accept=".png,.jpg,.jpeg,.gif, |image/*">
                                        <div class="upload-file__img upload-file__img_banner">
                                            <img src="{{dynamicAsset('public/assets/admin/img/media/banner-upload-file.png')}}" alt="" >
                                        </div>
                                        <button class="remove-file-button" type="button">
                                            <i class="tio-clear"></i>
                                        </button>
                                    </div>

                                    <p class="opacity-75 max-w220 mx-auto text-center fs-12">
                                        {{ translate('Supports: PNG, JPG, JPEG, WEBP') }}
                                        <br>
                                        {{ translate('Maximum 2 MB') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-sticky top-80px text-8797AB">
                            <div class="bg-light p-3 p-sm-4 rounded">
                                <label class="form-label">{{ translate('Advertisement Preview') }}</label>
                                <div id="video-preview-box" class="video-preview-box">
                                    <div class="bg--secondary rounded">
                                        <div class="video h-200">
                                            <video controls>
                                                {{ translate('Your browser does not support the video tag.') }}
                                            </video>
                                        </div>
                                        <div
                                            class="prev-video-box rounded bg-white px-3 py-4 position-relative gap-4 mt-n2">
                                            <div class="profile-img">
                                            </div>
                                            <div
                                                class="d-flex align-items-center justify-content-between gap-2">
                                                <div class="d-flex flex-column gap-2 flex-grow-1">
                                                    <div class="preview-title w-100">
                                                        <h5 class="main-text pe-4">{{ translate('Title') }}</h5>
                                                        <div class="placeholder-text bg--secondary p-2 w-50"></div>
                                                    </div>
                                                    <div class="preview-description w-100">
                                                        <div class="main-text line-limit-2">{{ translate('messages.Description') }}
                                                        </div>
                                                        <div class="placeholder-text bg--secondary p-2 w-75"></div>
                                                    </div>
                                                    <div class="preview-description w-100">
                                                        <div class="placeholder-text bg--secondary p-2 w-65"></div>
                                                    </div>
                                                </div>
                                                <a class="btn btn--primary py-2 px-3 cursor-auto">
                                                    <span class="tio-arrow-forward"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="profile-preview-box" class="profile-preview-box">
                                    <div class="bg--secondary rounded">
                                        <!-- Existing Profile Banner Image -->
                                        <div class="main-image rounded min-h-200" style="background: url('') center center / cover no-repeat">
                                        </div>
                                        <div class="rounded bg-white px-3 py-4 position-relative mt-n2">
                                            <div class="preview-title preview-description">
                                                <div class="wishlist-btn bg--secondary placeholder-text"></div>
                                                <div class="static-text wishlist-btn-2">
                                                    <div
                                                        class="h-100 w-100 d-flex align-items-center justify-content-center">
                                                        <i class="tio-heart-outlined"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex align-items-center justify-content-between gap-2">
                                                <!-- Existing Profile Image -->
                                                <div class="profile-prev-image bg--secondary me-xl-3" style="background: url('') center center / cover no-repeat">
                                                </div>
                                                <div class="review-rating-demo">
                                                    <div class="rating-text static-text">
                                                        <div class="rating-number d-flex align-items-center">
                                                            <i class="tio-star"></i><span>{{ $rating }}</span>
                                                        </div>
                                                    </div>
                                                    <span class="review--text static-text">({{ $review }}+)</span>
                                                </div>
                                                <div class="w-0 d-flex flex-column gap-2 flex-grow-1">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="preview-title w-100">
                                                            <h5 class="main-text pe-4">{{ translate('Title') }}</h5>
                                                            <div class="placeholder-text bg--secondary p-2 w-50"></div>
                                                        </div>
                                                    </div>
                                                    <div class="preview-description w-100">
                                                        <div class="main-text line-limit-2">{{ translate('messages.Description') }}
                                                        </div>
                                                        <div class="placeholder-text bg--secondary p-2 w-75"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            </div>
                            </div>
                            </div>
                                <div class="btn--container justify-content-end">
                                    <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('Reset') }}</button>
                                    <button type="submit" class="btn btn--primary">{{ translate('Submit') }}</button>
                                </div>
            </form>
        </div>
    </div>
    <!-- Advertisement -->

</div>
@endsection

@push('script_2')

    <script type="text/javascript" src="{{dynamicAsset('public/assets/admin/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{dynamicAsset('public/assets/admin/js/daterangepicker.min.js')}}"></script>

    <script>
        $(function() {
            $('input[name="dates"]').daterangepicker({
                // timePicker: true,
                minDate: new Date(),
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(10, 'day'),
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
            $('input[name="dates"]').attr('placeholder', "{{ translate('Select date') }}");

            $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
            $('.js-select').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>


    <!-- Video Upload Handlr -->
    <script>
        $(".video_attachment").on("change", function (event) {
            const videoEl = $(".video > video")
            const prevVideoBox = $('.prev-video-box')
            let file = event.target.files[0];
            let blobURL = URL.createObjectURL(file);
            const prevImage = $(this).closest('.upload-file').find('.upload-file__img').find('img').attr('src');
            videoEl.css('display', 'block');
            videoEl.attr('src', blobURL);
            videoEl.siblings('.play-icon').hide();
            $(this).closest('.upload-file').find('.upload-file__img').html('<video src="' + blobURL + '" controls></video>');
            $(this).closest('.upload-file').find('.remove-file-button').show()
            $(this).closest('.upload-file').find('.remove-file-button').on('click', function () {
                $(this).hide()
                videoEl.siblings('.play-icon').show();
                $(this).closest('.upload-file').find('.upload-file__img').find('img').attr('src', prevImage);
                $(this).closest('.upload-file').find('.video_attachment').val('');
                $(this).closest('.upload-file').find('.video > video').css('display', 'none');
                videoEl.css('display', 'none');
                videoEl.attr('src', '');
            })
        })

        $(window).on('load', function () {
            handleUploadBox();

            const videoEl = $(".video > video")
            let blobURL = "";
            // prev video attachment file
            {{-- blobURL = "{{dynamicAsset('storage/app/public/advertisement').'/' . $advertisement?->attachment?->file_name}}"; --}}

            videoEl.css('display', 'block');
            videoEl.attr('src', blobURL);
            $(".video_attachment").closest('.upload-file').find('.upload-file__img').html('<video src="' + blobURL + '" controls></video>');
            $(".video_attachment").closest('.upload-file').find('.remove-file-button').show()
            $(".video_attachment").closest('.upload-file').find('.remove-file-button').on('click', function () {
                $(this).hide()
                $(this).closest('.upload-file').find('.upload-file__img').html('<img src="{{dynamicAsset('public/assets/admin/img/media/video-banner.png')}}" alt="">');
                $(this).closest('.upload-file').find('.video_attachment').val('');
                $(this).closest('.upload-file').find('.video > video').css('display', 'none');
                videoEl.css('display', 'none');
                videoEl.attr('src', '');
            })
        })
    </script>

    <!-- Select Toggler Scripts -->
    <script>
        const handleUploadBox = () => {
            const value = $('.promotion_type').val();
            if (value == 'video_promotion') {
                $('#video-upload-box, #video-preview-box').show();
                $('#profile-upload-box, #profile-preview-box').hide();
            } else {
                $('#video-upload-box, #video-preview-box').hide();
                $('#profile-upload-box, #profile-preview-box').show();
            }
        }
        $(window).on('load', function () {
            handleUploadBox()
        })

        $('.promotion_type').on('change', function () {
            handleUploadBox();
            $('.remove-file-button').click()
        })
    </script>

    <!-- Profile Promotion Image Upload Handlr -->
    <script>
        $(".js-upload-input").on("change", function (event) {
            let file = event.target.files[0];
            const target = $(this).data('target');
            let blobURL = URL.createObjectURL(file);
            const prevImage = $(this).closest('.upload-file').find('.upload-file__img').find('img').attr('src');
            $(this).closest('.upload-file').find('.upload-file__img').html('<img src="' + blobURL + '" alt="">');
            $(this).closest('.upload-file').find('.remove-file-button').show()
            $('#profile-preview-box').find('.' + target).css('background', 'url(' + blobURL + ') no-repeat center center / cover');
            $(this).closest('.upload-file').find('.remove-file-button').on('click', function () {
                $('#profile-preview-box').find('.' + target).css('background', 'rgba(117, 133, 144, 0.1)');
                $(this).hide();
                $(this).closest('.upload-file').find('.upload-file__img').find('img').attr('src', prevImage);
                file ? $(this).closest('.upload-file').find('.js-upload-input').val(file) : ''
            })
        })
    </script>

    <!-- Title and Description Change Handlr -->
    <script>
        $('[data-preview-text]').on('input', function (event) {
            const target = $(this).data('preview-text');
            if (event.target.value) {
                $('.' + target).each(function () {
                    $(this).find('.main-text').text(event.target.value)
                    $(this).find('.placeholder-text').hide()
                    $(this).find('.static-text').show()
                })
            } else {
                $('.' + target).each(function () {
                    $(this).find('.main-text').text('')
                    $(this).find('.placeholder-text').show()
                    $(this).find('.static-text').hide()
                })
            }
        })
        const resetTextHandlr = () => {
            $('[data-preview-text]').each(function () {
                const target = $(this).data('preview-text');
                const value = $(this).val()
                if (value) {
                    $('.' + target).each(function () {
                        $(this).find('.main-text').text(value)
                        $(this).find('.placeholder-text').hide()
                        $(this).find('.static-text').show()
                    })
                }
            })
        }
        $(window).on('load', function () {
            resetTextHandlr()
        })

        $('#create-add-form').on('reset', function () {
            window.location.reload()
        })
    </script>

    <!-- Review and Rating Handlr -->
    <script>
        $('[name="review"]').on('change', function () {
            if ($(this).is(':checked')) {
                $('.review-placeholder').hide()
                $('.review--text').show()
                $('.review-rating-demo').css('opacity', '1')
            } else {
                $('.review-placeholder').show()
                $('.review--text').hide()
                if(!$('[name="rating"]').is(':checked')){
                    $('.review-rating-demo').css('opacity', '0')
                }
            }
        })
        $('[name="rating"]').on('change', function () {
            if ($(this).is(':checked')) {
                $('.rating-text').show()
                $('.review-rating-demo').css('opacity', '1')
            } else {
                $('.rating-text').hide()
                if(!$('[name="review"]').is(':checked')){
                    $('.review-rating-demo').css('opacity', '0')
                }
            }
        })


        $(window).on('load', function () {
            $('[name="review"]').each(function () {
                if ($(this).is(':checked')) {
                    $('.review--text').show()
                } else {
                    $('.review--text').hide()
                    if(!$('[name="rating"]').is(':checked')){
                        $('.review-rating-demo').css('opacity', '0')
                    }
                }
            })
            $('[name="rating"]').each(function () {
                if ($(this).is(':checked')) {
                    $('.rating-text').show()
                } else {
                    $('.rating-text').hide()
                    if(!$('[name="review"]').is(':checked')){
                        $('.review-rating-demo').css('opacity', '0')
                    }
                }
            })
        })
    </script>

<script>
            $(document).on('ready', function() {


        $('#create-add-form').on('submit', function (event) {
            event.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('vendor.advertisement.store') }}',
                data: $('#create-add-form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#loading').hide();
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{ route('vendor.advertisement.index') }}?type=pending&new_ad=1';
                        }, 2000);
                    }
                }
            });
        });



            });

</script>
@endpush
