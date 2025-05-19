@extends('layouts.admin.app')

@section('title',translate('SMS Module Setup'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{dynamicAsset('public/assets/admin/img/sms.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.sms_gateway_setup')}}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
        </div>
        <!-- End Page Header -->

        <div class="row g-3">

            @if ($published_status == 1)
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body  d-flex flex-wrap  justify-content-around">
                            <h4  class="w-50 flex-grow-1 module-warning-text">
                                <i class="tio-info-outined"></i>
                                {{ translate('Your_current_sms_settings_are_disabled,_because_you_have_enabled_sms_gateway_addon,_To_visit_your_currently_active_sms_gateway_settings_please_follow_the_link.') }}
                            </h4>
                            <div>
                                <a href="{{!empty($payment_url) ? $payment_url : ''}}" class="btn btn-outline-primary"> <i class="tio-settings"></i> {{translate('settings')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @php($is_published = $published_status == 1 ? 'inactive' : '')
            @foreach($data_values as $gateway)
                <div class="col-md-6 digital_payment_methods  {{ $is_published }} mb-3" >
                    <div class="card">
                        <div class="card-header">
                            <h4 class="page-title text-capitalize">{{translate($gateway->key_name)}}</h4>
                        </div>
                        <div class="card-body p-30">
                            <form action="{{route('admin.business-settings.sms-module-update',[$gateway->key_name])}}" method="POST"
                                  id="{{$gateway->key_name}}-form" enctype="multipart/form-data">
                                @csrf
                                @method('post')
                                <div class="discount-type">
                                    <div class="d-flex align-items-center gap-4 gap-xl-5 mb-30">
                                        <div class="custom-radio">
                                            <input type="radio" id="{{$gateway->key_name}}-active"
                                                   name="status"
                                                   value="1" {{$data_values->where('key_name',$gateway->key_name)->first()->live_values['status']?'checked':''}}>
                                            <label
                                                for="{{$gateway->key_name}}-active"> {{ translate('messages.Active') }}</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input type="radio" id="{{$gateway->key_name}}-inactive"
                                                   name="status"
                                                   value="0" {{$data_values->where('key_name',$gateway->key_name)->first()->live_values['status']?'':'checked'}}>
                                            <label
                                                for="{{$gateway->key_name}}-inactive"> {{ translate('messages.Inactive') }}</label>
                                        </div>
                                    </div>

                                    <input name="gateway" value="{{$gateway->key_name}}" class="d-none">
                                    <input name="mode" value="live" class="d-none">

                                    @php($skip=['gateway','mode','status'])
                                    @foreach($data_values->where('key_name',$gateway->key_name)->first()->live_values as $key=>$value)
                                        @if(!in_array($key,$skip))
                                            <div class="form-floating mb-30 mt-30 text-capitalize">
                                                <label for="{{$key}}" class="form-label">{{translate($key)}}  {{ $gateway->key_name == 'alphanet_sms' &&  $key == 'sender_id'? '('. translate('messages.Optional') .')' : '*'}}  </label>
                                                <input id="{{$key}}" type="text" class="form-control"
                                                       name="{{$key}}"
                                                       placeholder=" {{ $key == 'otp_template' ?  translate('Your_Security_Pin_is'). ' #OTP#' : translate($key) .' *'   }}    "
                                                       value="{{env('APP_ENV')=='demo'?'':$value}}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn--primary demo_check">
                                        {{ translate('messages.Update') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection


