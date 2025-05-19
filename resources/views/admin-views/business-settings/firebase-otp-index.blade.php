@extends('layouts.admin.app')

@section('title', translate('Firebase OTP Verification'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{dynamicAsset('public/assets/admin/img/firebase_auth.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('Firebase OTP Verification')}}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
                <div class="">
                    <div class="text--primary-2  mx-4 d-flex flex-wrap justify-content-end align-items-center" type="button" data-toggle="modal" data-target="#instructionsModal">
                        <strong class="mr-2">{{translate('How it Works')}}</strong>
                        <div class="blinkings">
                            <i class="tio-info-outined"></i>
                        </div>
                    </div>
                </div>
            </div>
        <!-- End Page Header -->



        <form
            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.firebase_otp_update',['recaptcha']):'javascript:'}}"
            method="post">
            @csrf
            <div class="row g-3">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-6 col-sm-6">
                                    @php($firebase_otp_verification = \App\Models\BusinessSetting::where('key', 'firebase_otp_verification')->first())
                                    @php($firebase_otp_verification = $firebase_otp_verification ? $firebase_otp_verification->value : '')
                                    <div class="form-group mb-0">

                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{ translate('Firebase_OTP_Verification_Status') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                      data-toggle="tooltip" data-placement="right"
                                                      data-original-title="{{ translate('If_this_field_is_active_customers_get_the_OTP_through_Firebase.') }}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.firebase_otp_verification') }}"> *
                                                </span>
                                            </span>
                                            <input type="checkbox"
                                                   data-id="firebase_otp_verification"
                                                   data-type="toggle"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/order-delivery-verification-on.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/order-delivery-verification-off.png') }}"
                                                   data-title-on="<strong>{{translate('Want to enable Firebase OTP Verification?')}}</strong>"
                                                   data-title-off="<strong>{{translate('Want to disable Firebase OTP Verification?')}}</strong> "
                                                   data-text-on="<p>{{ translate('With Firebase OTP enabled, verification codes will be sent through Firebase.') .' </p>' .'  <p>   <strong>
                                            Note: ' . translate('Enable Firebase OTP means users will not receive verification codes through Email or SMS Although those methods are activated.') .'</strong>'}}</p>"
                                                   data-text-off="<p>{{ translate('If you disable Firebase OTP, users will no longer receive verification codes via Firebase. You must activate Email or SMS verification as an alternative') }}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox-toggle"
                                                   value="1"
                                                   name="firebase_otp_verification" id="firebase_otp_verification"
                                                {{ $firebase_otp_verification == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    @php($firebase_web_api_key = \App\Models\BusinessSetting::where('key', 'firebase_web_api_key')->first())
                                    <div class="form-group mb-0">
                                        <label class=" input-label text-capitalize"
                                               for="firebase_web_api_key">
                                            <span>
                                                {{ translate('Web_API_key') }}
                                            </span>

                                            {{-- <span class="form-label-secondary"
                                                  data-toggle="tooltip" data-placement="right"
                                                  data-original-title="{{ translate('Enter_the_maximum_cash_amount_stores_can_hold._If_this_number_exceeds,_stores_will_be_suspended_and_not_receive_any_orders.') }}"><img
                                                    src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.dm_cancel_order_hint') }}"></span> --}}
                                        </label>
                                        <input type="text" name="firebase_web_api_key" class="form-control"
                                               id="firebase_web_api_key"
                                               value="{{ $firebase_web_api_key ? $firebase_web_api_key->value : '' }}"  required>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                        class="btn btn--primary call-demo">{{ translate('save_information') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>




    <div class="modal fade" id="instructionsModal" tabindex="-1" aria-labelledby="instructionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-end">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center my-5">
                        <img src="{{ dynamicAsset('public/assets/admin/img/modal/bell.png') }}">
                    </div>

                    <h5 class="modal-title my-3" id="instructionsModalLabel">{{translate('Instructions')}}</h5>
                    <p>{{ translate('For configuring OTP in the Firebase, you must create a Firebase project first.If you haven’t created any project for your application yet, please create a project first.') }}
                    </p>
                    <p>{{ translate('Now go the') }} <a href="https://console.firebase.google.com/" target="_blank">Firebase console </a>{{ translate('and follow the instructions below') }} -</p>
                    <ol class="d-flex flex-column __gap-5px __instructions">
                        <li>{{ translate('Go to your Firebase project.') }}</li>
                        <li>{{ translate('Navigate to the Build menu from the left sidebar and select Authentication.') }}</li>
                        <li>{{ translate('Get started the project and go to the Sign-in method tab.') }}</li>
                        <li>{{ translate('From the Sign-in providers section, select the Phone option.') }}</li>
                        <li>{{ translate('Ensure to enable the method Phone and press save.') }}</li>
                    </ol>
                </div>
            </div>
        </div>
        </div>

    @endsection
