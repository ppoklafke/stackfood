@extends('layouts.admin.app')

@section('title',translate('messages.profile_settings'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <span class="page-header-icon"><i class="tio-settings"></i></span>
                        <span>{{translate('messages.settings')}}</span>
                    </h1>
                </div>
                <div>
                    <a class="btn btn--primary" href="{{route('admin.dashboard')}}">
                        <i class="tio-dashboard-vs ml-xl-2"></i> {{translate('messages.dashboard')}}
                    </a>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-lg-3">
                <!-- Navbar -->
                <div class="navbar-vertical navbar-expand-lg mb-3 mb-lg-5 profile-sidebar-sticky">
                    <!-- Navbar Toggle -->
                    <button type="button" class="navbar-toggler btn btn-block btn-white mb-3"
                            aria-label="Toggle navigation" aria-expanded="false" aria-controls="navbarVerticalNavMenu"
                            data-toggle="collapse" data-target="#navbarVerticalNavMenu">
                <span class="d-flex justify-content-between align-items-center">
                  <span class="h5 mb-0">{{translate('messages.nav_menu')}}</span>

                  <span class="navbar-toggle-default">
                    <i class="tio-menu-hamburger"></i>
                  </span>

                  <span class="navbar-toggle-toggled">
                    <i class="tio-clear"></i>
                  </span>
                </span>
                    </button>
                    <!-- End Navbar Toggle -->

                    <div id="navbarVerticalNavMenu" class="collapse navbar-collapse">
                        <!-- Navbar Nav -->
                        <ul id="navbarSettings"
                            class="js-sticky-block js-scrollspy navbar-nav navbar-nav-lg nav-tabs card card-navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active text-dark" href="javascript:" id="generalSection">
                                    <i class="tio-user-outlined nav-icon"></i> {{translate('messages.basic_information')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="javascript:" id="passwordSection">
                                    <i class="tio-lock-outlined nav-icon"></i> {{translate('messages.password')}}
                                </a>
                            </li>
                        </ul>
                        <!-- End Navbar Nav -->
                    </div>
                </div>
                <!-- End Navbar -->
            </div>

            <div class="col-lg-9">
                <form action="{{env('APP_MODE')!='demo'?route('admin.settings'):'javascript:'}}" method="post" enctype="multipart/form-data" id="admin-settings-form">
                @csrf
                <!-- Card -->
                    <div class="card mb-3" id="generalDiv">
                        <!-- Profile Cover -->
                        <div class="profile-cover">
                            <div class="profile-cover-img-wrapper"></div>
                        </div>
                        <!-- End Profile Cover -->

                        <!-- Avatar -->
                        <label
                            class="avatar avatar-xxl avatar-circle avatar-border-lg avatar-uploader profile-cover-avatar"
                            for="avatarUploader">
                            <img id="viewer"
                                 data-onerror-image="{{dynamicAsset('public/assets/admin/img/160x160/img1.jpg')}}"
                                 class="avatar-img onerror-image"
                                 src="{{ auth('admin')->user()->image_full_url }}"
                                 alt="Image">

                            <input type="file" name="image" class="js-file-attach avatar-uploader-input"
                                   id="customFileEg1"
                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="avatar-uploader-trigger" for="customFileEg1">
                                <i class="tio-edit avatar-uploader-icon shadow-soft"></i>
                            </label>
                        </label>
                        <!-- End Avatar -->
                    </div>
                    <!-- End Card -->

                    <!-- Card -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h2 class="card-title h4">
                                <i class="tio-user-outlined"></i> &nbsp; {{translate('messages.basic_information')}}
                            </h2>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Form -->
                            <!-- Form Group -->
                            <div class="row form-group">
                                <label for="firstNameLabel" class="col-sm-3 col-form-label input-label">{{translate('messages.full_name')}}</label>

                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control h--45px" name="f_name" id="firstNameLabel"
                                               placeholder="{{ translate('messages.Ex:_Jhon') }} " aria-label="{{translate('messages.your_first_name')}}"
                                               value="{{auth('admin')->user()->f_name}}">
                                        <input type="text" class="form-control h--45px" name="l_name" id="lastNameLabel"
                                               placeholder="{{ translate('messages.Ex:_Doe') }} " aria-label="{{translate('messages.your_last_name')}}"
                                               value="{{auth('admin')->user()->l_name}}">
                                    </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="row form-group">
                                <label for="phoneLabel" class="col-sm-3 col-form-label input-label">{{translate('messages.phone')}} <span
                                        class="input-label-secondary">({{translate('messages.optional')}})</span></label>

                                <div class="col-sm-9">
                                    <input type="tel" class="js-masked-input form-control h--45px" name="phone" id="phoneLabel"
                                           placeholder="{{ translate('messages.Ex:_+x(xxx)xxx-xx-xx') }} " aria-label="+(xxx)xx-xxx-xxxxx"
                                           value="{{auth('admin')->user()->phone}}"
                                           data-hs-mask-options='{
                                           "template": "+(880)00-000-00000"
                                         }'>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <div class="row form-group">
                                <label for="newEmailLabel" class="col-sm-3 col-form-label input-label">{{translate('messages.email')}}</label>

                                <div class="col-sm-9">
                                    <input type="email" class="form-control h--45px" name="email" id="newEmailLabel"
                                           value="{{auth('admin')->user()->email}}"
                                           placeholder="{{ translate('messages.Ex:_jhone@company.com') }} " aria-label="{{translate('messages.enter_new_email_address')}}">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" data-id="admin-settings-form" data-message="{{translate('Want to update admin info ?')}}" class="btn btn-primary {{env('APP_MODE')!='demo'?"form-alert":"call-demo"}}">{{translate('messages.save')}}</button>
                            </div>

                            <!-- End Form -->
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- End Card -->
                </form>

                <!-- Card -->
                <div id="passwordDiv" class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title"><i class="tio-lock-outlined"></i>  &nbsp; {{translate('messages.change_your_password')}}</h4>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <!-- Form -->
                        <form id="changePasswordForm" class="js-validate" action="{{env('APP_MODE')!='demo'?route('admin.settings-password'):'javascript:'}}" method="post"
                              enctype="multipart/form-data">
                        @csrf

                        <!-- Form Group -->
                            <div class="row js-form-message form-group">
                                <label for="signupSrPassword" class="col-sm-3 col-form-label input-label">{{translate('messages.new_password')}}
                                    <span class="input-label-secondary ps-1" data-toggle="tooltip" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                </label>

                                <div class="col-sm-9">
                                         <input type="password" class="js-toggle-password form-control h--45px" name="password"
                                         id="signupSrPassword"
                                         pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                            required
                                         placeholder="{{translate('messages.password_length_8+')}}"
                                         aria-label="8+ characters required"
                                         data-msg="Your password is invalid. Please try again."
                                         data-hs-toggle-password-options='{
                                                        "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                                        }'>
                                    <p id="passwordStrengthVerdict" class="form-text mb-2"></p>

                                    <div id="passwordStrengthProgress"></div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="row js-form-message form-group">
                                <label for="confirmNewPasswordLabel" class="col-sm-3 col-form-label input-label">{{translate('messages.confirm_password')}}</label>

                                <div class="col-sm-9">
                                    <div class="mb-3">


                                            <input type="password" class="js-toggle-password form-control h--45px"
                                            name="confirm_password" id="signupSrConfirmPassword"
                                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                            required
                                            placeholder="{{translate('messages.password_length_8+')}}"
                                            aria-label="8+ characters required"
                                            data-msg="Password does not match the confirm password."
                                            data-hs-toggle-password-options='{
                                                                "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                                                "defaultClass": "tio-hidden-outlined",
                                                                "showClass": "tio-visible-outlined",
                                                                "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                                }'>
                                        </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <div class="d-flex justify-content-end">
                                @if (env('APP_MODE')!='demo')
                                    <button type="submit" class="btn btn--primary">{{translate('messages.save')}}</button>
                                @else
                                    <button type="button" class="btn btn--primary call-demo">{{translate('messages.save')}}</button>

                                @endif
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->

                <!-- Sticky Block End Point -->
                <div id="stickyBlockEndPoint"></div>
            </div>
        </div>
        <!-- End Row -->
    </div>
    <!-- End Content -->
@endsection

@push('script_2')
    <script>
        "use strict";
        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });

        $("#generalSection").click(function() {
            $("#passwordSection").removeClass("active");
            $("#generalSection").addClass("active");
            $('html, body').animate({
                scrollTop: $("#generalDiv").offset().top - 60
            }, 600);
        });

        $("#passwordSection").click(function() {
            $("#generalSection").removeClass("active");
            $("#passwordSection").addClass("active");
            $('html, body').animate({
                scrollTop: $("#passwordDiv").offset().top - 100
            }, 600);
        });
    </script>
@endpush
