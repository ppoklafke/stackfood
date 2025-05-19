@extends('layouts.admin.app')

@section('title',translate('login_page_setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap align-items-center justify-content-between">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/app.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('login_setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <ul class="nav nav-tabs border-0 nav--tabs nav--pills mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.login-settings.index') }}">{{translate('Customer_Login')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.login_url.login_url_page') }}">{{translate('panel_login_page_Url')}}</a>
            </li>
        </ul>

        <form action="{{route('admin.login-settings.update')}}" method="post">
            @csrf
            <div class="card">
            <div class="card-header">
                <div>
                    <h4 class="mb-1">
                        {{translate('Setup Login Option')}}
                    </h4>
                    <p class="fs-12 m-0">
                        {{translate('The option you select customer will have the to option to login')}}
                    </p>
                </div>
            </div>
            <div class="card-body pt-3">

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6 col-md-4">
                            <label class="form-check form--check form--check--inline border rounded">
                                <span class="user-select-none form-check-label flex-grow-1">
                                    {{translate('Manual Login')}}
                                    <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ translate('By enabling manual login, customers will get the option to create an account and log in using the necessary credentials & password in the app & website') }}">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </span>
                                <input class="form-check-input login-option-type" type="checkbox" name="manual_login_status" id="customer-manual-login" value="1" {{ (isset($data['manual_login_status']) && $data['manual_login_status'] == '1')? 'checked':'' }}>
                            </label>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-check form--check form--check--inline border rounded">
                                <span class="user-select-none form-check-label flex-grow-1">
                                    {{translate('OTP Login')}}
                                    <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ translate('With OTP Login, customers can log in using their phone number. while new customers can create accounts instantly.') }}">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </span>
                                <input class="form-check-input login-option-type" type="checkbox" name="otp_login_status" id="customer-otp-login" value="1" {{ (isset($data['otp_login_status']) && $data['otp_login_status'] == '1')? 'checked':'' }}>
                            </label>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-check form--check form--check--inline border rounded">
                                <span class="user-select-none form-check-label flex-grow-1">
                                    {{translate('Social Media Login')}}
                                    <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ translate('With Social Login, customers can log in using social media credentials. while new customers can create accounts instantly.') }}">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </span>
                                <input class="form-check-input login-option-type" type="checkbox" name="social_login_status" id="customer-social-login" value="1" {{ (isset($data['social_login_status']) && $data['social_login_status'] == '1')? 'checked':'' }}>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4 social-media-login-container " style="display: {{ (isset($data['social_login_status']) && $data['social_login_status'] == '1')? '':'none' }}" id="social-login-area">
                        <div class="mb-3">
                            <h4 class="mb-1">
                                {{translate('Social media login setup')}}
                            </h4>
                            <a href="{{route('admin.social-login.view')}}" class="fs-12 c1 text-underline fw-semibold" target="_blank">
                                {{translate('Connect 3rd party login system from here')}}
                            </a>
                        </div>
                        <div class="bg-light p-4 rounded">
                            <h4 class="mb-1">
                                {{translate('Choose social media')}}
                            </h4>
                            <div class="row g-3">
                                <div class="col-sm-6 col-md-4">
                                    <label class="form-check form--check form--check--inline border rounded">
                                        <span class="user-select-none form-check-label flex-grow-1">
                                            {{translate('Google')}}
                                            <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ translate('Enabling Google Login, customers can log in to the site using their existing Gmail credentials.') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </span>
                                        <input type="checkbox" name="google_login_status" id="google_login_status" value="1" {{ (isset($data['google_login_status']) && $data['google_login_status'] == '1')? 'checked':'' }} class="form-check-input social-media-status-checkbox">
                                    </label>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <label class="form-check form--check form--check--inline border rounded">
                                        <span class="user-select-none form-check-label flex-grow-1">
                                            {{translate('Facebook')}}
                                            <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ translate('Enabling Facebook Login, customers can log in to the site using their existing Facebook credentials') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </span>
                                        <input type="checkbox" name="facebook_login_status" id="facebook_login_status" value="1" {{ (isset($data['facebook_login_status']) && $data['facebook_login_status'] == '1')? 'checked':'' }} class="form-check-input social-media-status-checkbox">
                                    </label>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <label class="form-check form--check form--check--inline border rounded">
                                        <span class="user-select-none form-check-label flex-grow-1">
                                            {{translate('Apple')}}
                                            <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ translate('Enabling Apple Login, customers can log in to the site using their existing Apple login credentials, Only for Apple devices') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </span>
                                        <input type="checkbox" name="apple_login_status" value="1" {{ (isset($data['apple_login_status']) && $data['apple_login_status'] == '1')? 'checked':'' }} class="form-check-input social-media-status-checkbox">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="mb-3">
                            <h4 class="mb-1">
                                {{translate('Verification')}}
                            </h4>
                            <p class="fs-12">
                                {{translate('The option you select from below will need to verify by customer from customer app/website.')}}
                            </p>
                        </div>
                        <div class="bg-light p-4 rounded">
                            <div class="row g-3">
                                <div class="col-sm-6 col-md-4">
                                    <label class="form-check form--check form--check--inline border rounded">
                                        <span class="user-select-none form-check-label flex-grow-1">
                                            {{translate('Email Verification')}}
                                            <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ translate('If Email verification is on, Customers must verify their email address with an OTP to complete the signup process.') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </span>
                                        <input type="checkbox" name="email_verification_status" value="1" {{ (isset($data['email_verification_status']) && $data['email_verification_status'] == '1')? 'checked':'' }} class="form-check-input social-media-status-checkbox">
                                    </label>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <label class="form-check form--check form--check--inline border rounded">
                                        <span class="user-select-none form-check-label flex-grow-1 me-4 d-block">
                                            {{translate('Phone Number Verification')}}
                                            <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ translate('If Phone Number verification is on, Customers must verify their Phone Number with an OTP to complete the signup process.') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </span>
                                        <input type="checkbox" name="phone_verification_status" id="phone_verification" value="1" {{ (isset($data['phone_verification_status']) && $data['phone_verification_status'] == '1')? 'checked':'' }} class="form-check-input social-media-status-checkbox">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn--reset">{{ translate('reset') }}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('messages.submit')}}</button>
                    </div>

            </div>
        </div>
        </form>
    </div>


    <div class="modal fade" id="select-one-method-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog status-warning-modal text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0"><b></b>
                    <div class="text-center mb-20">
                        <img src="{{dynamicAsset('public/assets/admin/img/package-status-disable.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Important Alert !')}}</h5>
                    </div>
                    <p>{{ translate('At least one login method must remain active for the customer; otherwise, they will be unable to log in to the system') }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a type="button" class="btn btn--primary mw-300px" data-dismiss="modal">{{translate('okay')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sms-config-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog status-warning-modal text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0"><b></b>
                    <div class="text-center mb-20">
                        <img src="{{dynamicAsset('public/assets/admin/img/sms.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Set Up SMS Configuration First')}}</h5>
                    </div>
                    <p>{{ translate('It looks like your SMS configuration is not set up yet. To enable the OTP system, please set up the SMS configuration first.') }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a type="button" class="btn btn--primary w-100 mw-300px" href="{{ route('admin.business-settings.sms-module') }}" target="_blank">{{translate('Go to SMS Configuration')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="select-one-method-android-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog status-warning-modal text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0"><b></b>
                    <div class="text-center mb-20">
                        <img src="{{dynamicAsset('public/assets/admin/img/package-status-disable.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Important Alert !')}}</h5>
                    </div>
                    <p>{{ translate('If you are activating only social login as the login method, you must enable at least one option between Google and Facebook for Android users.') }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a type="button" class="btn btn--primary mw-300px" data-dismiss="modal">{{translate('okay')}}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="select-one-method-social-login-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog status-warning-modal text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0"><b></b>
                    <div class="text-center mb-20">
                        <img src="{{dynamicAsset('public/assets/admin/img/package-status-disable.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Important Alert !')}}</h5>
                    </div>
                    <p>{{ translate('If you are activating social login as the login method, you must enable at least one option between Google, Facebook & Apple.') }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a type="button" class="btn btn--primary mw-300px" data-dismiss="modal">{{translate('okay')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="setup-google-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog status-warning-modal text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0"><b></b>
                    <div class="text-center mb-20">
                        <img src="{{dynamicAsset('public/assets/admin/img/modal/google.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Set Up Google Configuration First')}}</h5>
                    </div>
                    <p>{{ translate('It looks like your Google Login configuration is not set up yet. To enable the Google Login option, please set up the Google configuration first.') }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a type="button" class="btn btn--primary mw-300px" href="{{route('admin.social-login.view')}}" target="_blank">{{translate('Go to Google Configuration')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="setup-facebook-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog status-warning-modal text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0"><b></b>
                    <div class="text-center mb-20">
                        <img src="{{dynamicAsset('public/assets/admin/img/modal/facebook.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Set Up Facebook Configuration First')}}</h5>
                    </div>
                    <p>{{ translate('It looks like your Facebook Login configuration is not set up yet. To enable the Facebook Login option, please set up the Facebook configuration first.') }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a type="button" class="btn btn--primary mw-300px" href="{{route('admin.social-login.view')}}" target="_blank">{{translate('Go to Facebook Configuration')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="setup-apple-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog status-warning-modal text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0"><b></b>
                    <div class="text-center mb-20">
                        <img src="{{dynamicAsset('public/assets/admin/img/modal/apple.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Set Up Apple Configuration First')}}</h5>
                    </div>
                    <p>{{ translate('It looks like your Apple Login configuration is not set up yet. To enable the Apple Login option, please set up the Apple configuration first.') }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a type="button" class="btn btn--primary mw-300px" href="{{route('admin.social-login.view')}}" target="_blank">{{translate('Go to Apple Configuration')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sms-config-verification-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog status-warning-modal text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0"><b></b>
                    <div class="text-center mb-20">
                        <img src="{{dynamicAsset('public/assets/admin/img/sms.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Set Up SMS Configuration First')}}</h5>
                    </div>
                    <p>{{ translate('It looks like your SMS configuration is not set up yet. To enable the phone verification, please set up the SMS configuration first.') }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a type="button" class="btn btn--primary w-100 mw-300px" href="{{ route('admin.business-settings.sms-module') }}" target="_blank">{{translate('Go to SMS Configuration')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mail-config-verification-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog status-warning-modal text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0"><b></b>
                    <div class="text-center mb-20">
                        <img src="{{dynamicAsset('public/assets/admin/img/sms.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Set Up Email Configuration First')}}</h5>
                    </div>
                    <p>{{ translate('It looks like your Email configuration is not set up yet. To enable the email verification, please set up the SMS configuration first.') }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a type="button" class="btn btn--primary w-100 mw-300px" href="{{ route('admin.business-settings.mail-config') }}" target="_blank">{{translate('Go to Mail Configuration')}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script type="text/javascript">
        $(document).ready(function() {
            @if (session('select-one-method'))
            $('#select-one-method-modal').modal('show');
            @endif
            @if (session('sms-config'))
            $('#sms-config-modal').modal('show');
            @endif
            @if (session('select-one-method-android'))
            $('#select-one-method-android-modal').modal('show');
            @endif
            @if (session('select-one-method-social-login'))
            $('#select-one-method-social-login-modal').modal('show');
            @endif
            @if (session('setup-google'))
            $('#setup-google-modal').modal('show');
            @endif
            @if (session('setup-facebook'))
            $('#setup-facebook-modal').modal('show');
            @endif
            @if (session('setup-apple'))
            $('#setup-apple-modal').modal('show');
            @endif
            @if (session('sms-config-verification'))
            $('#sms-config-verification-modal').modal('show');
            @endif
            @if (session('email-config-verification'))
            $('#email-config-verification-modal').modal('show');
            @endif

            $("#customer-social-login").change(function(e) {
                if ($(this).is(':checked')) {
                    $('#social-login-area').show();
                } else {
                    $('#social-login-area').hide();
                }
            });
        });
    </script>
@endpush
