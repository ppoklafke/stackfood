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
            <div class="registration-form-wrapper">
                <div class="card">
                    <div class=" steeper-header-title border-bottom my-4 pb-4 px-4">
                        <div class="page--header-title mb-2">
                            <h1 class="page-header-title">{{ translate('Add_Steeper') }}</h1>
                        </div>
                        <p class="mb-0">{{ translate('Setup_the_information_that_you_want_to_highlight_for_the_restaurants') }}</p>
                    </div>
                    @php($default_lang = str_replace('_', '-', app()->getLocale()))
                    @if($language)
                        <ul class="nav nav-tabs mx-4">
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
                    <div class="row g-3 m-2">
                        {{-- stepper 1 --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="border-bottom py-3 px-4">
                                    <h4 class="mb-0">{{ translate('Step_1') }}</h4>
                                </div>
                                <form class="step1-form" action="{{ route('admin.business-settings.react-registration-page.stepper-update', 'step1') }}" method="post"
                                enctype="multipart/form-data">
                                    @csrf
                                    <div class="p-4">
                                        <div class="lang_form" id="default-form">
                                            <input type="hidden" name="lang[]" value="default">
                                            <div class="form-group">
                                                <label class="form-label tooltip-label" for="step1_title">{{translate('Title')}}
                                                    <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Title')}}" data-title="
                                                        <div class='text-start'>{{ translate('Write_the_title_within_50_characters') }}</div>
                                                        ">
                                                        <i class="tio-info"></i>
                                                    </span>
                                                </label>
                                                <textarea class="form-control" id="step1_title" name="step1_title[]" rows="1" placeholder="{{translate('Enter_Title')}}" data-maxlength="50">{{ $step1_title?->getRawOriginal('value') ?? null}}</textarea>
                                                <div class="d-flex justify-content-end">
                                                    <span class="text-body-light">0/50</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label tooltip-label" for="step1_sub_title">{{translate('Subtitle')}}
                                                    <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Subtitle')}}" data-title="
                                                        <div class='text-start'>{{ translate('Write_the_title_within_100_characters') }}</div>
                                                        ">
                                                        <i class="tio-info"></i>
                                                    </span>
                                                </label>
                                                <textarea class="form-control" id="step1_sub_title" name="step1_sub_title[]" rows="2" data-maxlength="100" placeholder="{{translate('Enter_Subtitle')}}">{{ $step1_sub_title?->getRawOriginal('value') ?? null}}</textarea>
                                                <div class="d-flex justify-content-end">
                                                    <span class="text-body-light">0/100</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($language)
                                            @foreach(json_decode($language) as $lang)
                                                    <?php
                                                    if ($step1_title?->translations && count($step1_title?->translations)) {
                                                        $step1_title_translate = [];
                                                        foreach ($step1_title->translations as $t) {
                                                            if ($t->locale == $lang && $t->key == 'step1_title') {
                                                                $step1_title_translate[$lang]['value'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                    if ($step1_sub_title?->translations && count($step1_sub_title?->translations)) {
                                                        $step1_sub_title_translate = [];
                                                        foreach ($step1_sub_title->translations as $t) {
                                                            if ($t->locale == $lang && $t->key == 'step1_sub_title') {
                                                                $step1_sub_title_translate[$lang]['value'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                    ?>

                                                <div class="d-none lang_form" id="{{$lang}}-form">
                                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                                    <div class="form-group">
                                                        <label class="form-label tooltip-label" for="step1_title{{$lang}}">{{translate('Title')}} ({{strtoupper($lang)}})
                                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Title')}}" data-title="
                                                                <div class='text-start'>{{ translate('Write_the_title_within_50_characters') }}</div>
                                                                ">
                                                                <i class="tio-info"></i>
                                                            </span>
                                                        </label>
                                                        <textarea class="form-control" id="step1_title{{$lang}}" name="step1_title[]" rows="1" placeholder="{{translate('Enter_Title')}}" data-maxlength="50">{{ $step1_title_translate[$lang]['value'] ?? '' }}</textarea>
                                                        <div class="d-flex justify-content-end">
                                                            <span class="text-body-light">0/50</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label tooltip-label" for="step1_sub_title{{$lang}}">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Subtitle')}}" data-title="
                                                                <div class='text-start'>{{ translate('Write_the_title_within_100_characters') }}</div>
                                                                ">
                                                                <i class="tio-info"></i>
                                                            </span>
                                                        </label>
                                                        <textarea class="form-control" id="step1_sub_title{{$lang}}" name="step1_sub_title[]" rows="2" data-maxlength="100" placeholder="{{translate('Enter_Subtitle')}}">{{ $step1_sub_title_translate[$lang]['value'] ?? '' }}</textarea>
                                                        <div class="d-flex justify-content-end">
                                                            <span class="text-body-light">0/100</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="registration-upload registration-upload-cmn p-sm-4 p-3 mb-5 mt-sm-4 mt-3">
                                            <h5 class="text-center mb-4 mt-2">{{ translate('Image/Icon') }}</h5>
                                            <div class="upload-file mb-4">
                                                <input type="file" name="step1_image" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="">

                                                @if (isset($step1_image?->value))
                                                <button type="button" class="remove_btn bg-transparent border-0 outline-0 remove_image_button remove-image" data-id="step1_image"
                                                data-title="{{translate('Warning!')}}"
                                                data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>" style="opacity: 0;">
                                                    <i class="tio-clear"></i>
                                                </button>
                                                @endif
                                                <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                    <div class="upload-file-textbox text-center {{ $step1_image ?'d-none':'' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="35" viewBox="0 0 34 35" fill="none" class="svg replaced-svg">
                                                            <g id="fi_8191581">
                                                            <g id="image-upload">
                                                            <path id="Vector" d="M30.8125 13.2922V24.7927C30.8963 25.5106 30.8165 26.2381 30.5791 26.9207C30.3418 27.6034 29.953 28.2235 29.4419 28.7346C28.9309 29.2456 28.3108 29.6344 27.6281 29.8718C26.9454 30.1091 26.2179 30.1889 25.5 30.1052H8.50004C7.78216 30.1889 7.05463 30.1091 6.37197 29.8718C5.68931 29.6344 5.06922 29.2456 4.55816 28.7346C4.0471 28.2235 3.65832 27.6034 3.42095 26.9207C3.18359 26.2381 3.10379 25.5106 3.18754 24.7927V10.626C3.10379 9.90813 3.18359 9.18061 3.42095 8.49795C3.65832 7.81529 4.0471 7.1952 4.55816 6.68414C5.06922 6.17308 5.68931 5.7843 6.37197 5.54693C7.05463 5.30956 7.78216 5.22977 8.50004 5.31352H20.9667C21.0294 5.31516 21.0909 5.33065 21.1469 5.35888C21.2029 5.38711 21.252 5.42739 21.2906 5.47681C21.3292 5.52624 21.3563 5.58359 21.3701 5.64475C21.384 5.70592 21.3841 5.76939 21.3705 5.8306C21.0362 7.48588 21.3724 9.20615 22.3052 10.6138C23.2379 12.0215 24.6911 13.0016 26.3458 13.3389C27.6554 13.6007 29.0142 13.4447 30.2303 12.8927C30.2951 12.8669 30.3653 12.8574 30.4347 12.8651C30.5041 12.8729 30.5705 12.8976 30.628 12.9371C30.6856 12.9766 30.7325 13.0296 30.7647 13.0915C30.7969 13.1535 30.8133 13.2224 30.8125 13.2922Z" fill="#A6A6A6" fill-opacity="0.35"></path>
                                                            <path id="Vector_2" d="M28.5628 20.7694L22.2558 14.4624C21.9887 14.1963 21.627 14.0469 21.25 14.0469C20.873 14.0469 20.5113 14.1963 20.2442 14.4624L13.2458 21.4607C13.1134 21.5905 12.9354 21.6632 12.75 21.6632C12.5646 21.6632 12.3866 21.5905 12.2542 21.4607L10.9225 20.1291C10.6554 19.863 10.2937 19.7135 9.91667 19.7135C9.53962 19.7135 9.17795 19.863 8.91083 20.1291L5.43717 23.6027C5.35744 23.6823 5.3126 23.7904 5.3125 23.9031V24.7899C5.3125 27.0282 6.26167 27.9774 8.5 27.9774H25.5C27.7383 27.9774 28.6875 27.0282 28.6875 24.7899V21.0697C28.6874 20.957 28.6426 20.849 28.5628 20.7694Z" fill="#999999" fill-opacity="0.7"></path>
                                                            <path id="Vector_3" d="M11.3339 15.2292C11.1013 15.23 10.8709 15.185 10.6557 15.0968C10.4405 15.0086 10.2449 14.8789 10.0798 14.715C9.74653 14.3841 9.55834 13.9344 9.55665 13.4647C9.55496 12.9951 9.73991 12.544 10.0708 12.2107C10.4017 11.8774 10.8515 11.6892 11.3211 11.6875H11.3339C11.8035 11.6875 12.2539 11.8741 12.586 12.2062C12.9181 12.5383 13.1047 12.9887 13.1047 13.4583C13.1047 13.928 12.9181 14.3784 12.586 14.7105C12.2539 15.0426 11.8035 15.2292 11.3339 15.2292Z" fill="#999999" fill-opacity="0.7"></path>
                                                            <path id="Vector_4" d="M30.5009 6.33358L28.3759 4.20858C28.1765 4.00996 27.9066 3.89844 27.6251 3.89844C27.3437 3.89844 27.0737 4.00996 26.8743 4.20858L24.7493 6.33358C24.5616 6.535 24.4594 6.8014 24.4643 7.07666C24.4691 7.35192 24.5806 7.61455 24.7753 7.80922C24.97 8.00388 25.2326 8.11539 25.5079 8.12025C25.7831 8.12511 26.0495 8.02293 26.2509 7.83525L26.5626 7.525V10.6261C26.5626 10.9079 26.6745 11.1781 26.8738 11.3774C27.0731 11.5766 27.3433 11.6886 27.6251 11.6886C27.9069 11.6886 28.1772 11.5766 28.3764 11.3774C28.5757 11.1781 28.6876 10.9079 28.6876 10.6261V7.525L28.9993 7.83525C29.2007 8.02293 29.4671 8.12511 29.7423 8.12025C30.0176 8.11539 30.2802 8.00388 30.4749 7.80922C30.6696 7.61455 30.7811 7.35192 30.7859 7.07666C30.7908 6.8014 30.6886 6.535 30.5009 6.33358Z" fill="#999999" fill-opacity="0.7"></path>
                                                            </g>
                                                            </g>
                                                        </svg>
                                                        <h6 class="m-0 fs-10 fw-medium lh-base text-center">
                                                            <span class="text-info">{{ translate('Click_to_upload') }}</span>
                                                            <br>
                                                            {{ translate('or_drag_and_drop') }}
                                                        </h6>
                                                    </div>
                                                    <img class="upload-file-img" loading="lazy" src="{{ $step1_image? Helpers::get_full_url('step_image', $step1_image?->value,$step1_image?->storage[0]?->value ?? 'public'):''}}" data-original-src="{{ $step1_image? Helpers::get_full_url('step_image', $step1_image?->value,$step1_image?->storage[0]?->value ?? 'public'):''}}" data-default-src="" alt="" style="display: none">
                                                </label>
                                            </div>
                                            <p class="text-center image-formate mb-0">{{ translate('JPG,_JPEG,_PNG_Less_Than_5MB') }} <span class="">({{ translate('1:1') }})</span></p>
                                        </div>
                                        <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                            <button type="reset" class="btn btn--reset">{{ translate('reset') }}</button>
                                            <button type="submit" class="btn btn--primary">{{ translate('update') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- stepper 2 --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="border-bottom py-3 px-4">
                                    <h4 class="mb-0">{{ translate('Step_2') }}</h4>
                                </div>
                                <form class="step2-form" action="{{ route('admin.business-settings.react-registration-page.stepper-update', 'step2') }}" method="post"
                                enctype="multipart/form-data">
                                    @csrf
                                    <div class="p-4">
                                        <div class="lang_form default-form">
                                            <input type="hidden" name="lang[]" value="default">
                                            <div class="form-group">
                                                <label class="form-label tooltip-label" for="step2_title">{{translate('Title')}}
                                                    <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Title')}}" data-title="
                                                        <div class='text-start'>{{ translate('Write_the_title_within_50_characters') }}</div>
                                                        ">
                                                        <i class="tio-info"></i>
                                                    </span>
                                                </label>
                                                <textarea class="form-control" id="step2_title" name="step2_title[]" rows="1" placeholder="{{translate('Enter_Title')}}" data-maxlength="50">{{ $step2_title?->getRawOriginal('value') ?? null}}</textarea>
                                                <div class="d-flex justify-content-end">
                                                    <span class="text-body-light">0/50</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label tooltip-label" for="step2_sub_title">{{translate('Subtitle')}}
                                                    <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Subtitle')}}" data-title="
                                                        <div class='text-start'>{{ translate('Write_the_title_within_100_characters') }}</div>
                                                        ">
                                                        <i class="tio-info"></i>
                                                    </span>
                                                </label>
                                                <textarea class="form-control" id="step2_sub_title" name="step2_sub_title[]" rows="2" data-maxlength="100" placeholder="{{translate('Enter_Subtitle')}}">{{ $step2_sub_title?->getRawOriginal('value') ?? null}}</textarea>
                                                <div class="d-flex justify-content-end">
                                                    <span class="text-body-light">0/100</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($language)
                                            @foreach(json_decode($language) as $lang)
                                                    <?php
                                                    if ($step2_title?->translations && count($step2_title?->translations)) {
                                                        $step2_title_translate = [];
                                                        foreach ($step2_title->translations as $t) {
                                                            if ($t->locale == $lang && $t->key == 'step2_title') {
                                                                $step2_title_translate[$lang]['value'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                    if ($step2_sub_title?->translations && count($step2_sub_title?->translations)) {
                                                        $step2_sub_title_translate = [];
                                                        foreach ($step2_sub_title->translations as $t) {
                                                            if ($t->locale == $lang && $t->key == 'step2_sub_title') {
                                                                $step2_sub_title_translate[$lang]['value'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                    ?>

                                                <div class="d-none lang_form" id="{{$lang}}-form2">
                                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                                    <div class="form-group">
                                                        <label class="form-label tooltip-label" for="step2_title{{$lang}}">{{translate('Title')}} ({{strtoupper($lang)}})
                                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Title')}}" data-title="
                                                                <div class='text-start'>{{ translate('Write_the_title_within_50_characters') }}</div>
                                                                ">
                                                                <i class="tio-info"></i>
                                                            </span>
                                                        </label>
                                                        <textarea class="form-control" id="step2_title{{$lang}}" name="step2_title[]" rows="1" placeholder="{{translate('Enter_Title')}}" data-maxlength="50">{{ $step2_title_translate[$lang]['value'] ?? '' }}</textarea>
                                                        <div class="d-flex justify-content-end">
                                                            <span class="text-body-light">0/50</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label tooltip-label" for="step2_sub_title{{$lang}}">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Subtitle')}}" data-title="
                                                                <div class='text-start'>{{ translate('Write_the_title_within_100_characters') }}</div>
                                                                ">
                                                                <i class="tio-info"></i>
                                                            </span>
                                                        </label>
                                                        <textarea class="form-control" id="step2_sub_title{{$lang}}" name="step2_sub_title[]" rows="2" data-maxlength="100" placeholder="{{translate('Enter_Subtitle')}}">{{ $step2_sub_title_translate[$lang]['value'] ?? '' }}</textarea>
                                                        <div class="d-flex justify-content-end">
                                                            <span class="text-body-light">0/100</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="registration-upload registration-upload-cmn p-sm-4 p-3 mb-5 mt-sm-4 mt-3">
                                            <h5 class="text-center mb-4 mt-2">{{ translate('Image/Icon') }}</h5>
                                            <div class="upload-file mb-4">
                                                <input type="file" name="step2_image" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="">

                                                @if (isset($step2_image?->value))
                                                <button type="button" class="remove_btn bg-transparent border-0 outline-0 remove_image_button remove-image" data-id="step2_image"
                                                data-title="{{translate('Warning!')}}"
                                                data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>" style="opacity: 0;">
                                                    <i class="tio-clear"></i>
                                                </button>
                                                @endif
                                                <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                    <div class="upload-file-textbox text-center {{ $step2_image ?'d-none':'' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="35" viewBox="0 0 34 35" fill="none" class="svg replaced-svg">
                                                            <g id="fi_8191581">
                                                            <g id="image-upload">
                                                            <path id="Vector" d="M30.8125 13.2922V24.7927C30.8963 25.5106 30.8165 26.2381 30.5791 26.9207C30.3418 27.6034 29.953 28.2235 29.4419 28.7346C28.9309 29.2456 28.3108 29.6344 27.6281 29.8718C26.9454 30.1091 26.2179 30.1889 25.5 30.1052H8.50004C7.78216 30.1889 7.05463 30.1091 6.37197 29.8718C5.68931 29.6344 5.06922 29.2456 4.55816 28.7346C4.0471 28.2235 3.65832 27.6034 3.42095 26.9207C3.18359 26.2381 3.10379 25.5106 3.18754 24.7927V10.626C3.10379 9.90813 3.18359 9.18061 3.42095 8.49795C3.65832 7.81529 4.0471 7.1952 4.55816 6.68414C5.06922 6.17308 5.68931 5.7843 6.37197 5.54693C7.05463 5.30956 7.78216 5.22977 8.50004 5.31352H20.9667C21.0294 5.31516 21.0909 5.33065 21.1469 5.35888C21.2029 5.38711 21.252 5.42739 21.2906 5.47681C21.3292 5.52624 21.3563 5.58359 21.3701 5.64475C21.384 5.70592 21.3841 5.76939 21.3705 5.8306C21.0362 7.48588 21.3724 9.20615 22.3052 10.6138C23.2379 12.0215 24.6911 13.0016 26.3458 13.3389C27.6554 13.6007 29.0142 13.4447 30.2303 12.8927C30.2951 12.8669 30.3653 12.8574 30.4347 12.8651C30.5041 12.8729 30.5705 12.8976 30.628 12.9371C30.6856 12.9766 30.7325 13.0296 30.7647 13.0915C30.7969 13.1535 30.8133 13.2224 30.8125 13.2922Z" fill="#A6A6A6" fill-opacity="0.35"></path>
                                                            <path id="Vector_2" d="M28.5628 20.7694L22.2558 14.4624C21.9887 14.1963 21.627 14.0469 21.25 14.0469C20.873 14.0469 20.5113 14.1963 20.2442 14.4624L13.2458 21.4607C13.1134 21.5905 12.9354 21.6632 12.75 21.6632C12.5646 21.6632 12.3866 21.5905 12.2542 21.4607L10.9225 20.1291C10.6554 19.863 10.2937 19.7135 9.91667 19.7135C9.53962 19.7135 9.17795 19.863 8.91083 20.1291L5.43717 23.6027C5.35744 23.6823 5.3126 23.7904 5.3125 23.9031V24.7899C5.3125 27.0282 6.26167 27.9774 8.5 27.9774H25.5C27.7383 27.9774 28.6875 27.0282 28.6875 24.7899V21.0697C28.6874 20.957 28.6426 20.849 28.5628 20.7694Z" fill="#999999" fill-opacity="0.7"></path>
                                                            <path id="Vector_3" d="M11.3339 15.2292C11.1013 15.23 10.8709 15.185 10.6557 15.0968C10.4405 15.0086 10.2449 14.8789 10.0798 14.715C9.74653 14.3841 9.55834 13.9344 9.55665 13.4647C9.55496 12.9951 9.73991 12.544 10.0708 12.2107C10.4017 11.8774 10.8515 11.6892 11.3211 11.6875H11.3339C11.8035 11.6875 12.2539 11.8741 12.586 12.2062C12.9181 12.5383 13.1047 12.9887 13.1047 13.4583C13.1047 13.928 12.9181 14.3784 12.586 14.7105C12.2539 15.0426 11.8035 15.2292 11.3339 15.2292Z" fill="#999999" fill-opacity="0.7"></path>
                                                            <path id="Vector_4" d="M30.5009 6.33358L28.3759 4.20858C28.1765 4.00996 27.9066 3.89844 27.6251 3.89844C27.3437 3.89844 27.0737 4.00996 26.8743 4.20858L24.7493 6.33358C24.5616 6.535 24.4594 6.8014 24.4643 7.07666C24.4691 7.35192 24.5806 7.61455 24.7753 7.80922C24.97 8.00388 25.2326 8.11539 25.5079 8.12025C25.7831 8.12511 26.0495 8.02293 26.2509 7.83525L26.5626 7.525V10.6261C26.5626 10.9079 26.6745 11.1781 26.8738 11.3774C27.0731 11.5766 27.3433 11.6886 27.6251 11.6886C27.9069 11.6886 28.1772 11.5766 28.3764 11.3774C28.5757 11.1781 28.6876 10.9079 28.6876 10.6261V7.525L28.9993 7.83525C29.2007 8.02293 29.4671 8.12511 29.7423 8.12025C30.0176 8.11539 30.2802 8.00388 30.4749 7.80922C30.6696 7.61455 30.7811 7.35192 30.7859 7.07666C30.7908 6.8014 30.6886 6.535 30.5009 6.33358Z" fill="#999999" fill-opacity="0.7"></path>
                                                            </g>
                                                            </g>
                                                        </svg>
                                                        <h6 class="m-0 fs-10 fw-medium lh-base text-center">
                                                            <span class="text-info">{{ translate('Click_to_upload') }}</span>
                                                            <br>
                                                            {{ translate('or_drag_and_drop') }}
                                                        </h6>
                                                    </div>
                                                    <img class="upload-file-img" loading="lazy" src="{{ $step2_image? Helpers::get_full_url('step_image', $step2_image?->value,$step2_image?->storage[0]?->value ?? 'public'):''}}" data-original-src="{{ $step2_image? Helpers::get_full_url('step_image', $step2_image?->value,$step2_image?->storage[0]?->value ?? 'public'):''}}" data-default-src="" alt="" style="display: none">
                                                </label>
                                            </div>
                                            <p class="text-center image-formate mb-0">{{ translate('JPG,_JPEG,_PNG_Less_Than_5MB') }} <span class="">({{ translate('1:1') }})</span></p>
                                        </div>
                                        <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                            <button type="reset" class="btn btn--reset">{{ translate('reset') }}</button>
                                            <button type="submit" class="btn btn--primary">{{ translate('update') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- stepper 3 --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="border-bottom py-3 px-4">
                                    <h4 class="mb-0">{{ translate('Step_3') }}</h4>
                                </div>
                                <form class="step3-form" action="{{ route('admin.business-settings.react-registration-page.stepper-update', 'step3') }}" method="post"
                                enctype="multipart/form-data">
                                    @csrf
                                    <div class="p-4">
                                        <div class="lang_form default-form">
                                            <input type="hidden" name="lang[]" value="default">
                                            <div class="form-group">
                                                <label class="form-label tooltip-label" for="step3_title">{{translate('Title')}}
                                                    <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Title')}}" data-title="
                                                        <div class='text-start'>{{ translate('Write_the_title_within_50_characters') }}</div>
                                                        ">
                                                        <i class="tio-info"></i>
                                                    </span>
                                                </label>
                                                <textarea class="form-control" id="step3_title" name="step3_title[]" rows="1" placeholder="{{translate('Enter_Title')}}" data-maxlength="50">{{ $step3_title?->getRawOriginal('value') ?? null}}</textarea>
                                                <div class="d-flex justify-content-end">
                                                    <span class="text-body-light">0/50</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label tooltip-label" for="step3_sub_title">{{translate('Subtitle')}}
                                                    <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Subtitle')}}" data-title="
                                                        <div class='text-start'>{{ translate('Write_the_title_within_100_characters') }}</div>
                                                        ">
                                                        <i class="tio-info"></i>
                                                    </span>
                                                </label>
                                                <textarea class="form-control" id="step3_sub_title" name="step3_sub_title[]" rows="2" data-maxlength="100" placeholder="{{translate('Enter_Subtitle')}}">{{ $step3_sub_title?->getRawOriginal('value') ?? null}}</textarea>
                                                <div class="d-flex justify-content-end">
                                                    <span class="text-body-light">0/100</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($language)
                                            @foreach(json_decode($language) as $lang)
                                                    <?php
                                                    if ($step3_title?->translations && count($step3_title?->translations)) {
                                                        $step3_title_translate = [];
                                                        foreach ($step3_title->translations as $t) {
                                                            if ($t->locale == $lang && $t->key == 'step3_title') {
                                                                $step3_title_translate[$lang]['value'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                    if ($step3_sub_title?->translations && count($step3_sub_title?->translations)) {
                                                        $step3_sub_title_translate = [];
                                                        foreach ($step3_sub_title->translations as $t) {
                                                            if ($t->locale == $lang && $t->key == 'step3_sub_title') {
                                                                $step3_sub_title_translate[$lang]['value'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                    ?>

                                                <div class="d-none lang_form" id="{{$lang}}-form3">
                                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                                    <div class="form-group">
                                                        <label class="form-label tooltip-label" for="step3_title{{$lang}}">{{translate('Title')}} ({{strtoupper($lang)}})
                                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Title')}}" data-title="
                                                                <div class='text-start'>{{ translate('Write_the_title_within_50_characters') }}</div>
                                                                ">
                                                                <i class="tio-info"></i>
                                                            </span>
                                                        </label>
                                                        <textarea class="form-control" id="step3_title{{$lang}}" name="step3_title[]" rows="1" placeholder="{{translate('Enter_Title')}}" data-maxlength="50">{{ $step3_title_translate[$lang]['value'] ?? '' }}</textarea>
                                                        <div class="d-flex justify-content-end">
                                                            <span class="text-body-light">0/50</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label tooltip-label" for="step3_sub_title{{$lang}}">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="{{translate('Enter_Subtitle')}}" data-title="
                                                                <div class='text-start'>{{ translate('Write_the_title_within_100_characters') }}</div>
                                                                ">
                                                                <i class="tio-info"></i>
                                                            </span>
                                                        </label>
                                                        <textarea class="form-control" id="step3_sub_title{{$lang}}" name="step3_sub_title[]" rows="2" data-maxlength="100" placeholder="{{translate('Enter_Subtitle')}}">{{ $step3_sub_title_translate[$lang]['value'] ?? '' }}</textarea>
                                                        <div class="d-flex justify-content-end">
                                                            <span class="text-body-light">0/100</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="registration-upload registration-upload-cmn p-sm-4 p-3 mb-5 mt-sm-4 mt-3">
                                            <h5 class="text-center mb-4 mt-2">{{ translate('Image/Icon') }}</h5>
                                            <div class="upload-file mb-4">
                                                <input type="file" name="step3_image" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="">

                                                @if (isset($step3_image?->value))
                                                <button type="button" class="remove_btn bg-transparent border-0 outline-0 remove_image_button remove-image" data-id="step3_image"
                                                data-title="{{translate('Warning!')}}"
                                                data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>" style="opacity: 0;">
                                                    <i class="tio-clear"></i>
                                                </button>
                                                @endif
                                                <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                    <div class="upload-file-textbox text-center {{ $step3_image ?'d-none':'' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="35" viewBox="0 0 34 35" fill="none" class="svg replaced-svg">
                                                            <g id="fi_8191581">
                                                            <g id="image-upload">
                                                            <path id="Vector" d="M30.8125 13.2922V24.7927C30.8963 25.5106 30.8165 26.2381 30.5791 26.9207C30.3418 27.6034 29.953 28.2235 29.4419 28.7346C28.9309 29.2456 28.3108 29.6344 27.6281 29.8718C26.9454 30.1091 26.2179 30.1889 25.5 30.1052H8.50004C7.78216 30.1889 7.05463 30.1091 6.37197 29.8718C5.68931 29.6344 5.06922 29.2456 4.55816 28.7346C4.0471 28.2235 3.65832 27.6034 3.42095 26.9207C3.18359 26.2381 3.10379 25.5106 3.18754 24.7927V10.626C3.10379 9.90813 3.18359 9.18061 3.42095 8.49795C3.65832 7.81529 4.0471 7.1952 4.55816 6.68414C5.06922 6.17308 5.68931 5.7843 6.37197 5.54693C7.05463 5.30956 7.78216 5.22977 8.50004 5.31352H20.9667C21.0294 5.31516 21.0909 5.33065 21.1469 5.35888C21.2029 5.38711 21.252 5.42739 21.2906 5.47681C21.3292 5.52624 21.3563 5.58359 21.3701 5.64475C21.384 5.70592 21.3841 5.76939 21.3705 5.8306C21.0362 7.48588 21.3724 9.20615 22.3052 10.6138C23.2379 12.0215 24.6911 13.0016 26.3458 13.3389C27.6554 13.6007 29.0142 13.4447 30.2303 12.8927C30.2951 12.8669 30.3653 12.8574 30.4347 12.8651C30.5041 12.8729 30.5705 12.8976 30.628 12.9371C30.6856 12.9766 30.7325 13.0296 30.7647 13.0915C30.7969 13.1535 30.8133 13.2224 30.8125 13.2922Z" fill="#A6A6A6" fill-opacity="0.35"></path>
                                                            <path id="Vector_2" d="M28.5628 20.7694L22.2558 14.4624C21.9887 14.1963 21.627 14.0469 21.25 14.0469C20.873 14.0469 20.5113 14.1963 20.2442 14.4624L13.2458 21.4607C13.1134 21.5905 12.9354 21.6632 12.75 21.6632C12.5646 21.6632 12.3866 21.5905 12.2542 21.4607L10.9225 20.1291C10.6554 19.863 10.2937 19.7135 9.91667 19.7135C9.53962 19.7135 9.17795 19.863 8.91083 20.1291L5.43717 23.6027C5.35744 23.6823 5.3126 23.7904 5.3125 23.9031V24.7899C5.3125 27.0282 6.26167 27.9774 8.5 27.9774H25.5C27.7383 27.9774 28.6875 27.0282 28.6875 24.7899V21.0697C28.6874 20.957 28.6426 20.849 28.5628 20.7694Z" fill="#999999" fill-opacity="0.7"></path>
                                                            <path id="Vector_3" d="M11.3339 15.2292C11.1013 15.23 10.8709 15.185 10.6557 15.0968C10.4405 15.0086 10.2449 14.8789 10.0798 14.715C9.74653 14.3841 9.55834 13.9344 9.55665 13.4647C9.55496 12.9951 9.73991 12.544 10.0708 12.2107C10.4017 11.8774 10.8515 11.6892 11.3211 11.6875H11.3339C11.8035 11.6875 12.2539 11.8741 12.586 12.2062C12.9181 12.5383 13.1047 12.9887 13.1047 13.4583C13.1047 13.928 12.9181 14.3784 12.586 14.7105C12.2539 15.0426 11.8035 15.2292 11.3339 15.2292Z" fill="#999999" fill-opacity="0.7"></path>
                                                            <path id="Vector_4" d="M30.5009 6.33358L28.3759 4.20858C28.1765 4.00996 27.9066 3.89844 27.6251 3.89844C27.3437 3.89844 27.0737 4.00996 26.8743 4.20858L24.7493 6.33358C24.5616 6.535 24.4594 6.8014 24.4643 7.07666C24.4691 7.35192 24.5806 7.61455 24.7753 7.80922C24.97 8.00388 25.2326 8.11539 25.5079 8.12025C25.7831 8.12511 26.0495 8.02293 26.2509 7.83525L26.5626 7.525V10.6261C26.5626 10.9079 26.6745 11.1781 26.8738 11.3774C27.0731 11.5766 27.3433 11.6886 27.6251 11.6886C27.9069 11.6886 28.1772 11.5766 28.3764 11.3774C28.5757 11.1781 28.6876 10.9079 28.6876 10.6261V7.525L28.9993 7.83525C29.2007 8.02293 29.4671 8.12511 29.7423 8.12025C30.0176 8.11539 30.2802 8.00388 30.4749 7.80922C30.6696 7.61455 30.7811 7.35192 30.7859 7.07666C30.7908 6.8014 30.6886 6.535 30.5009 6.33358Z" fill="#999999" fill-opacity="0.7"></path>
                                                            </g>
                                                            </g>
                                                        </svg>
                                                        <h6 class="m-0 fs-10 fw-medium lh-base text-center">
                                                            <span class="text-info">{{ translate('Click_to_upload') }}</span>
                                                            <br>
                                                            {{ translate('or_drag_and_drop') }}
                                                        </h6>
                                                    </div>
                                                    <img class="upload-file-img" loading="lazy" src="{{ $step3_image? Helpers::get_full_url('step_image', $step3_image?->value,$step3_image?->storage[0]?->value ?? 'public'):''}}" data-original-src="{{ $step3_image? Helpers::get_full_url('step_image', $step3_image?->value,$step3_image?->storage[0]?->value ?? 'public'):''}}" data-default-src="" alt="" style="display: none">
                                                </label>
                                            </div>
                                            <p class="text-center image-formate mb-0">{{ translate('JPG,_JPEG,_PNG_Less_Than_5MB') }} <span class="">({{ translate('1:1') }})</span></p>
                                        </div>
                                        <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                            <button type="reset" class="btn btn--reset">{{ translate('reset') }}</button>
                                            <button type="submit" class="btn btn--primary">{{ translate('update') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
           </div>

        </div>

        <form  id="step1_image_form" action="{{ route('admin.remove_image') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{  $step1_image?->id}}" >
            <input type="hidden" name="model_name" value="DataSetting" >
            <input type="hidden" name="image_path" value="step_image" >
            <input type="hidden" name="field_name" value="value" >
        </form>

        <form  id="step2_image_form" action="{{ route('admin.remove_image') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{  $step2_image?->id}}" >
            <input type="hidden" name="model_name" value="DataSetting" >
            <input type="hidden" name="image_path" value="step_image" >
            <input type="hidden" name="field_name" value="value" >
        </form>

        <form  id="step3_image_form" action="{{ route('admin.remove_image') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{  $step3_image?->id}}" >
            <input type="hidden" name="model_name" value="DataSetting" >
            <input type="hidden" name="image_path" value="step_image" >
            <input type="hidden" name="field_name" value="value" >
        </form>

@endsection

@push('script')
<script>
    $(document).ready(function () {
        $('.step1-form, .step2-form, .step3-form').on('reset', function () {
            let $form = $(this);
            let $img = $form.find('.upload-file-img');
            let $uploadIcon = $form.find('.upload-file-textbox');
            let originalSrc = $img.attr('data-original-src');

            // If there is an original image, reset to that
            if (originalSrc) {
                $img.attr('src', originalSrc).show();
                $uploadIcon.addClass('d-none');
            } else {
                // No original image, show the default upload UI
                $img.hide();
                $uploadIcon.removeClass('d-none');
            }

            // Reset the file input
            $form.find('.single_file_input').val('');

            // Optionally hide the remove button too
            $form.find('.remove-image').css('opacity', 0);
        });
    });
</script>



@endpush