@php use App\CentralLogics\Helpers;use App\Models\ReactOpportunity; @endphp
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
                <div class="steeper-header-title mb-3">
                    <div class="page--header-title">
                        <h1 class="page-header-title">{{ translate('Add_Opportunities') }}</h1>
                    </div>
                </div>
                <div class="card p-sm-4 p-3">
                    <form id="opportunity-form" action="{{ route('admin.business-settings.react-registration-page.opportunity_store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4 justify-content-between">
                            <div class="col-xxl-8 col-lg-7 col-md-6">
                                <div class="px-xl-4 pt-xl-3">
                                    @php($default_lang = str_replace('_', '-', app()->getLocale()))
                                    @if($language)
                                        <ul class="nav nav-tabs mb-4">
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
                                    <input type="hidden" name="lang[]" value="default">
                                    <div class="lang_form" id="default-form">
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="title">{{translate('Title')}}
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>{{ translate('Write_the_title_within_20_characters') }}</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea id="title" class="form-control" name="title[]" rows="1" placeholder="{{translate('Enter_Title')}}" data-maxlength="20"></textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/20</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="sub_title">{{translate('Subtitle')}}
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>{{ translate('Write_the_title_within_100_characters') }}</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea id="sub_title" class="form-control" name="sub_title[]" rows="2" data-maxlength="100" placeholder="{{translate('Enter_Title')}}"></textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/100</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if($language)
                                    @forelse(json_decode($language) as $lang)
                                    <div class="d-none lang_form" id="{{$lang}}-form1">
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="title{{$lang}}">{{translate('Title')}} ({{strtoupper($lang)}})
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>{{ translate('Write_the_title_within_20_characters') }}</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea id="title{{$lang}}" class="form-control" name="title[]" rows="1" placeholder="{{translate('Enter_Title')}}" data-maxlength="20"></textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/20</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="sub_title{{$lang}}">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>{{ translate('Write_the_title_within_100_characters') }}</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea id="sub_title{{$lang}}" class="form-control" name="sub_title[]" rows="2" data-maxlength="100" placeholder="{{translate('Enter_Title')}}"></textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/100</span>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    @endforelse
                                    @endif
                                </div>
                            </div>
                            <div class="col-xxl-4 col-lg-5 col-md-6 d-flex justify-content-md-end justify-content-center">
                                <div>
                                    <div class="registration-upload opportunities-upload p-md-4 mb-lg-5">
                                        <h5 class="text-center mb-4 mt-2">{{ translate('Image/Icon') }}</h5>
                                        <div class="upload-file mb-4">
                                            <input type="file" name="image" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="" required="">
                                            {{-- <button type="button" class="remove_btn bg-transparent border-0 outline-0" style="opacity: 0;">
                                                <i class="tio-clear"></i>
                                            </button> --}}
                                            <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                <div class="upload-file-textbox text-center" style="">
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
                                                        {{ translate('Or_drag_and_drop') }}
                                                    </h6>
                                                </div>
                                                <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="" style="display: none;">
                                            </label>
                                        </div>
                                        <p class="text-center fs-10 image-formate mb-0">{{ translate('JPG,_JPEG,_PNG_Less_Than_5MB') }} <span class="">({{ translate('1:1') }})</span></p>
                                    </div>
                                    <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                        <button type="reset" class="btn btn--reset">{{ translate('reset') }}</button>
                                        <button id="form-submit" type="submit" class="btn btn--primary">{{ translate('add') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card mt-4">
                    <div class="card-header pb-1 pt-3 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">{{ translate('Opportunities_list') }}</h5>
                            <form>
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch" value="{{ request()?->search ?? null }}" type="search" name="search" class="form-control" placeholder="{{ translate('Search_here') }}" aria-label="{{ translate('Search_here') }}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive pt-0">
                        <table class="table table-borderless table-thead-bordered table-align-middle table-nowrap card-table m-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-top-0">{{translate('sl')}}</th>
                                    <th class="border-top-0">{{translate('Image')}}</th>
                                    <th class="border-top-0">{{translate('Title')}}</th>
                                    <th class="border-top-0">{{translate('messages.Subtitle')}}</th>
                                    <th class="border-top-0">{{translate('Status')}}</th>
                                    <th class="text-center border-top-0">{{translate('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($opportunities as $key=>$opportunity)
                                <tr>
                                    <td>{{ $key+$opportunities->firstItem() }}</td>
                                    <td>
                                        <img  src="{{ $opportunity?->image_full_url ?? dynamicAsset('/public/assets/admin/img/upload.png') }}"
                                              data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload.png')}}"
                                              class="__size-105 onerror-image" alt="">
                                    </td>
                                    <td>
                                        <p class="question-pragraph">
                                            {{ $opportunity->title }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="opportunities-pragraph">
                                            {{ \Illuminate\Support\Str::limit($opportunity->sub_title, 50, $end='...')    }}
                                        </p>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm">
                                            <input type="checkbox"
                                                   data-id="opportunity_status_{{$opportunity->id}}"
                                                   data-type="status"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/testimonial-on.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/testimonial-off.png') }}"
                                                   data-title-on="{{translate('Want_to_enable_the_status_of_this ')}} <strong>{{translate('opportunity')}}</strong>"
                                                   data-title-off="{{translate('Want_to_disable_the_status_of_this')}} <strong>{{translate('opportunity')}}</strong>"
                                                   data-text-on="<p>{{translate('If_enabled,_everyone_can_see_it_on_the_landing_page')}}</p>"
                                                   data-text-off="<p>{{translate('If_disabled,_it_will_be_hidden_from_the_landing_page')}}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox"
                                                   id="opportunity_status_{{$opportunity->id}}" {{$opportunity->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.business-settings.react-registration-page.opportunity_status',[$opportunity->id,$opportunity->status?0:1])}}" method="get" id="opportunity_status_{{$opportunity->id}}_form">
                                        </form>
                                    </td>

                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn--primary btn-outline-primary edit-opportunity" data-toggle="modal" data-target="#edit_oportunities-modal_{{$opportunity->id}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            {{-- edit modal --}}
                                            <div class="modal fade" id="edit_oportunities-modal_{{$opportunity->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content p-4">
                                                        <div class="modal-header p-0">
                                                            <h3 class="modal-title" id="exampleModalLabel">{{ translate('Edit_Opportunities') }}  </h3>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        @php($opportunity=  ReactOpportunity::withoutGlobalScope('translate')->with('translations')->find($opportunity->id))
                                                        <form class="opportunity-edit" action="{{ route('admin.business-settings.react-registration-page.opportunity_update',[$opportunity->id]) }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="">
                                                                <div class="registration-upload upload-showing-file p-lg-4 p-md-3 mb-md-5 mb-4">
                                                                    <h5 class="text-center mb-4 mt-2">{{ translate('Image/Icon') }}</h5>
                                                                    <div class="upload-file mb-4">
                                                                        <input type="file" name="image" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="">

                                                                        {{-- @if (isset($opportunity->image))
                                                                        <button type="button" class="remove_btn bg-transparent border-0 outline-0 remove_image_button remove-image" data-id="image"
                                                                        data-title="{{translate('Warning!')}}"
                                                                        data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>" style="opacity: 0;">
                                                                            <i class="tio-clear"></i>
                                                                        </button>
                                                                        @endif --}}
                                                                        <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                                            <div class="upload-file-textbox text-center {{ $opportunity->image ?'d-none':'' }}">
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
                                                                            <img class="upload-file-img" loading="lazy" src="{{ $opportunity?->image_full_url ?? dynamicAsset('/public/assets/admin/img/upload.png') }}" data-original-src="{{ $opportunity->image ? ($opportunity?->image_full_url ?? dynamicAsset('/public/assets/admin/img/upload.png')):'' }}" data-default-src="" alt="" style="display: none">
                                                                        </label>
                                                                    </div>
                                                                    <p class="text-center image-formate mb-0">{{ translate('JPG,_JPEG,_PNG_Less_Than_5MB') }} <span class="">({{ translate('1:1') }})</span></p>
                                                                </div>
                                                                <ul class="nav nav-tabs mb-4">
                                                                    <li class="nav-item">
                                                                        <a class="nav-link update-lang_link add_active active"
                                                                        href="#"

                                                                        id="default-link">{{ translate('Default') }}</a>
                                                                    </li>
                                                                    @if($language)
                                                                        @foreach (json_decode($language) as $lang)
                                                                            <li class="nav-item">
                                                                                <a class="nav-link update-lang_link"
                                                                                href="#"
                                                                                data-opportunity-id="{{$opportunity->id}}"
                                                                                id="{{ $lang }}-link">{{ Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                </ul>
                                                                <input type="hidden" name="opportunity_id" value="{{$opportunity->id}}"/>
                                                                <div class="add_active_2  update-lang_form"
                                                                id="default-form_{{$opportunity->id}}">
                                                                    <div class="form-group">
                                                                        <label class="form-label tooltip-label" for="title{{$opportunity->id}}">{{translate('Title')}}
                                                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                                                <div class='text-start'>{{ translate('Write_the_title_within_20_characters') }}</div>
                                                                                ">
                                                                                <i class="tio-info"></i>
                                                                            </span>
                                                                        </label>
                                                                        <textarea id="title{{$opportunity->id}}" class="form-control" name="title[]" rows="1" placeholder="{{translate('Enter_Title')}}" data-maxlength="20">{{$opportunity?->getRawOriginal('title')}}</textarea>
                                                                        <div class="d-flex justify-content-end">
                                                                            <span class="text-body-light">0/20</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="form-label tooltip-label" for="sub_title{{$opportunity->id}}">{{translate('Subtitle')}}
                                                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                                                <div class='text-start'>{{ translate('Write_the_title_within_100_characters') }}</div>
                                                                                ">
                                                                                <i class="tio-info"></i>
                                                                            </span>
                                                                        </label>
                                                                        <textarea id="sub_title{{$opportunity->id}}" class="form-control" name="sub_title[]" rows="2" data-maxlength="100" placeholder="{{translate('Enter_Title')}}">{{$opportunity?->getRawOriginal('sub_title')}}</textarea>
                                                                        <div class="d-flex justify-content-end">
                                                                            <span class="text-body-light">0/100</span>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="lang[]" value="default">
                                                                </div>
                                                                @if($language)
                                                                    @forelse(json_decode($language) as $lang)
                                                                            <?php
                                                                            if ($opportunity?->translations) {
                                                                                $title_translate = [];
                                                                                foreach ($opportunity?->translations as $t) {
                                                                                    if ($t->locale == $lang && $t->key == "opportunity_title") {
                                                                                        $title_translate[$lang]['opportunity_title'] = $t->value;
                                                                                    }
                                                                                }
                                                                            }
                                                                            if ($opportunity?->translations) {
                                                                                $sub_title_translate = [];
                                                                                foreach ($opportunity?->translations as $t) {
                                                                                    if ($t->locale == $lang && $t->key == "opportunity_sub_title") {
                                                                                        $sub_title_translate[$lang]['opportunity_sub_title'] = $t->value;
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        <div class="d-none update-lang_form"
                                                                            id="{{$lang}}-langform_{{$opportunity->id}}">
                                                                            <div class="form-group">
                                                                                <label class="form-label tooltip-label" for="title{{$opportunity->id}}{{$lang}}">{{translate('Title')}} ({{strtoupper($lang)}})
                                                                                    <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                                                        <div class='text-start'>{{ translate('Write_the_title_within_20_characters') }}</div>
                                                                                        ">
                                                                                        <i class="tio-info"></i>
                                                                                    </span>
                                                                                </label>
                                                                                <textarea id="title{{$opportunity->id}}{{$lang}}" class="form-control" name="title[]" rows="1" placeholder="{{translate('Enter_Title')}}" data-maxlength="20">{{ $title_translate[$lang]['opportunity_title'] ?? null }}</textarea>
                                                                                <div class="d-flex justify-content-end">
                                                                                    <span class="text-body-light">0/20</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="form-label tooltip-label" for="sub_title{{$opportunity->id}}">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                                                                                    <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                                                        <div class='text-start'>{{ translate('Write_the_title_within_100_characters') }}</div>
                                                                                        ">
                                                                                        <i class="tio-info"></i>
                                                                                    </span>
                                                                                </label>
                                                                                <textarea id="sub_title{{$opportunity->id}}" class="form-control" name="sub_title[]" rows="2" data-maxlength="100" placeholder="{{translate('Enter_Title')}}">{{ $sub_title_translate[$lang]['opportunity_sub_title'] ?? null }}</textarea>
                                                                                <div class="d-flex justify-content-end">
                                                                                    <span class="text-body-light">0/100</span>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="lang[]" value="{{$lang}}">
                                                                        </div>
                                                                    @empty
                                                                    @endforelse
                                                                @endif

                                                                <div class="btn--container justify-content-sm-end mt-lg-4 mt-3">
                                                                    <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                                                                    <button type="submit" class="btn btn--primary">{{ translate('Update') }}</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>


                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert-opportunity" href="javascript:"
                                               data-id="opportunity-{{$opportunity['id']}}"
                                               data-message="{{ translate('Want_to_Delete_this_Opportunity') }}"
                                               data-message-2="{{ translate('If_yes,_the_opportunity_will_be_removed_from_this_list') }}" title="{{translate('messages.delete_opportunity')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.business-settings.react-registration-page.opportunity_delete',[$opportunity['id']])}}" method="post" id="opportunity-{{$opportunity['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                        @if(count($opportunities) === 0)
                        <div class="empty--data">
                            <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                    </div>
                    <div class="page-area px-4 pb-3">
                        <div class="d-flex align-items-center justify-content-end">
                            <div>
                                {!! $opportunities->appends(request()->all())->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset('public/assets/admin/js/view-pages/react-registration-opportunity-page.js')}}"></script>
    <script>
        "use strict";
        $(document).on('click', '.form-alert-opportunity', function () {
            Swal.fire({
                title: $(this).data('message'),
                text: $(this).data('message-2'),
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.No') }}',
                confirmButtonText: '{{ translate('messages.Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#' + $(this).data('id')).submit()
                }
            })
        });

        $('#opportunity-form').on('submit', function () {
            $('#form-submit').prop('disabled', true).text("{{ translate('Submitting') }}"+'...');
        });

        $(document).ready(function () {
            $('.opportunity-edit').on('reset', function () {
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