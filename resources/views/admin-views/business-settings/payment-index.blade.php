@extends('layouts.admin.app')

@section('title',translate('messages.Payment Method'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        @php
        $currency= \App\Models\BusinessSetting::where('key','currency')->first()?->value?? 'USD';
        $checkCurrency = \App\CentralLogics\Helpers::checkCurrency($currency);
        $currency_symbol =\App\CentralLogics\Helpers::currency_symbol();

    @endphp
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{dynamicAsset('/public/assets/admin/img/payment.png')}}" class="w--22" alt="">
                </span>
                <span>
                    {{translate('messages.payment_gateway_setup')}}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
            <div class="d-flex flex-wrap justify-content-end align-items-center flex-grow-1">
                <div class="blinkings trx_top active">
                    <i class="tio-info-outined"></i>
                    <div class="business-notes">
                        <h6><img src="{{dynamicAsset('/public/assets/admin/img/notes.png')}}" alt=""> {{translate('Note')}}</h6>
                        <div>
                            {{translate('Without configuring this section functionality will not work properly. Thus the whole system will not work as it planned')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card border-0">
            <div class="card-header card-header-shadow">
                <h5 class="card-title align-items-center">
                    <img src="{{dynamicAsset('/public/assets/admin/img/payment-method.png')}}" class="mr-1" alt="">
                    {{translate('Payment Method')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('cash_on_delivery'))
                        <form action="{{route('admin.business-settings.payment-method-update',['cash_on_delivery'])}}"
                            method="post" id="cash_on_delivery_status_form">
                            @csrf
                            <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                <span class="pr-1 d-flex align-items-center switch--label">
                                    <span class="line--limit-1">
                                        {{translate('Cash On Delivery')}}
                                    </span>
                                    <span class="form-label-secondary text-danger d-flex" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If_enabled_Customers_will_be_able_to_select_COD_as_a_payment_method_during_checkout')}}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="Veg/non-veg toggle"> * </span>
                                </span>
                                <input type="hidden" name="toggle_type" value="cash_on_delivery">
                                <input
                                    type="checkbox" id="cash_on_delivery_status"
                                    data-id="cash_on_delivery_status"
                                    data-type="status"
                                    data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/digital-payment-on.png') }}"
                                    data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/digital-payment-off.png') }}"
                                    data-title-on="{{ translate('By Turning ON Cash On Delivery Option') }}"
                                    data-title-off="{{ translate('By Turning OFF Cash On Delivery Option') }}"
                                    data-text-on="<p>{{ translate('Customers will not be able to select COD as a payment method during checkout. Please review your settings and enable COD if you wish to offer this payment option to customers.') }}</p>"
                                    data-text-off="<p>{{ translate('Customers will be able to select COD as a payment method during checkout.') }}</p>"
                                    class="status toggle-switch-input dynamic-checkbox"
                                       name="status" value="1" {{$config?($config['status']==1?'checked':''):''}}>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </form>
                    </div>
                    <div class="col-md-4">
                        @php($digital_payment=\App\CentralLogics\Helpers::get_business_settings('digital_payment'))
                        <form action="{{route('admin.business-settings.payment-method-update',['digital_payment'])}}"
                            method="post" id="digital_payment_status_form">
                            @csrf
                            <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                <span class="pr-1 d-flex align-items-center switch--label">
                                    <span class="line--limit-1">
                                        {{translate('digital payment')}}
                                    </span>
                                    <span class="form-label-secondary text-danger d-flex" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If_enabled_Customers_will_be_able_to_select_digital_payment_as_a_payment_method_during_checkout')}}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="Veg/non-veg toggle"> * </span>
                                </span>
                                <input type="hidden" name="toggle_type" value="digital_payment">
                                <input  type="checkbox"
                                id="digital_payment_status"
                                       data-id="digital_payment_status"
                                       data-type="status"
                                       data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/digital-payment-on.png') }}"
                                       data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/digital-payment-off.png') }}"
                                       data-title-on="{{ translate('By Turning ON Digital Payment Option') }}"
                                       data-title-off="{{ translate('By Turning OFF Digital Payment Option') }}"
                                       data-text-on="<p>{{ translate('Customers will not be able to select digital payment as a payment method during checkout. Please review your settings and enable digital payment if you wish to offer this payment option to customers.') }}</p>"
                                       data-text-off="<p>{{ translate('Customers will be able to select digital payment as a payment method during checkout.') }}</p>"
                                       class="status toggle-switch-input dynamic-checkbox"
                                       name="status" value="1" {{$digital_payment?($digital_payment['status']==1?'checked':''):''}}>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </form>
                    </div>
                    <div class="col-md-4">
                        @php($Offline_Payment=\App\CentralLogics\Helpers::get_business_settings('offline_payment_status'))
                        <form action="{{route('admin.business-settings.payment-method-update',['offline_payment_status'])}}"
                            method="post" id="offline_payment_status_form">
                            @csrf
                            <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                <span class="pr-1 d-flex align-items-center switch--label">
                                    <span class="line--limit-1">
                                        {{translate('Offline_Payment')}}
                                    </span>
                                    <span class="form-label-secondary text-danger d-flex" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If_enabled_Customers_will_be_able_to_select_offline_payment_as_a_payment_method_during_checkout')}}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="Veg/non-veg toggle"> * </span>
                                </span>
                                <input type="hidden" name="toggle_type" value="offline_payment_status" >
                                <input  type="checkbox" id="offline_payment_status"
                                        data-id="offline_payment_status"
                                        data-type="status"
                                        data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/digital-payment-on.png') }}"
                                        data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/digital-payment-off.png') }}"
                                        data-title-on="{{ translate('By_Turning_ON_Offline_Payment_Option') }}"
                                        data-title-off="{{ translate('By_Turning_OFF_Offline_Payment_Option') }}"
                                        data-text-on="<p>{{ translate('Customers_will_be_able_to_select_Offline_Payment_as_a_payment_method_during_checkout') }}</p>"
                                        data-text-off="<p>{{ translate('Customers_will_not_be_able_to_select_Offline_Payment_as_a_payment_method_during_checkout._Please_review_your_settings_and_enable_Offline_Payment_if_you_wish_to_offer_this_payment_option_to_customers.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox"

                                        name="status" value="1" {{$Offline_Payment == 1?'checked':''}}>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if($published_status == 1)
        <br>
        <div>
            <div class="card">
                <div class="card-body d-flex flex-wrap justify-content-around">
                    <h4 class="w-50 flex-grow-1 module-warning-text">
                        <i class="tio-info-outined"></i>
                    {{ translate('Your_current_payment_settings_are_disabled,_because_you_have_enabled_payment_gateway_addon,_To_visit_your_currently_active_payment_gateway_settings_please_follow_the_link.') }}</h4>
                    <div>
                        <a href="{{!empty($payment_url) ? $payment_url : ''}}" class="btn btn-outline-primary"> <i class="tio-settings"></i> {{translate('Settings')}}</a>
                    </div>
                </div>
            </div>
        </div>
        @endif


        @if($checkCurrency !== true )
        <br>
        <div>
            <div class="card">
                <div class="bg--3 px-5 pb-2 card-body d-flex flex-wrap justify-content-around">
                    <p class="w-50 fs-15 text-danger flex-grow-1 ">
                        <i class="tio-info-outined"></i>
                    {{ translate($checkCurrency).' '. translate('Does_not_support_your_current') }}   {{ $currency }}({{$currency_symbol  }}) {{ translate('Currency,_thus_users_cannot_view_digital_payment_options_in_their_websites_and_apps.') }}</p>

                </div>
            </div>
        </div>
        @elseif ($data_values->where('is_active',1  )->count()  == 0)
        <br>
        <div>
            <div class="card">
                <div class="bg--3 px-5 pb-2 card-body d-flex flex-wrap justify-content-around">
                    <p class="w-50 fs-15 text-danger flex-grow-1 ">
                        <i class="tio-info-outined"></i>
                    {{ translate('Currently,_there_is_no_digital_payment_method_is_set_up_that_supports_') }}   {{ $currency }}({{$currency_symbol  }}),{{ translate('_thus_users_cannot_view_digital_payment_options_in_their_websites_and_apps_._You_must_activate_at_least_one_digital_payment_method_that_supports_') }}   {{ $currency }}({{$currency_symbol  }}) {{ translate('_otherwise,_all_users_will_be_unable_to_pay_via_digital_payments.') }}</p>

                </div>
            </div>
        </div>

        @endif
        @php($is_published = $published_status == 1 ? 'inactive' : '')
        <!-- Tab Content -->
        <div class="row digital_payment_methods  {{ $is_published }} mt-3 g-3">
            @foreach($data_values->sortByDesc('is_active') as $payment_key => $payment)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update'):'javascript:'}}" method="POST"
                              id="{{$payment->key_name}}-form" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header d-flex flex-wrap align-content-around">
                                <h5>
                                    <span class="text-uppercase">{{str_replace('_',' ',$payment->key_name)}}</span>
                                </h5>
                                <label id="span_on_{{ $payment->key_name }}"  class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                    <span  class="mr-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('on') }}</span>
                                    <span  class="mr-2 switch--custom-label-text off text-uppercase">{{ translate('off') }}</span>
                                    <input id="add_check_{{ $payment->key_name }}" type="checkbox" name="status" value="1" data-gateway="{{ $payment->key_name }}"
                                           class="toggle-switch-input  {{ \App\CentralLogics\Helpers::checkCurrency($payment->key_name , 'payment_gateway') === true  ? 'open-warning-modal' : ''}}"
                                           {{$payment['is_active']==1?'checked':''}}>
                                    <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                </label>
                            </div>

                            @php($additional_data = $payment['additional_data'] != null ? json_decode($payment['additional_data']) : [])
                            <div class="card-body">
                                <div class="payment--gateway-img">
                                    <img  id="{{$payment->key_name}}-image-preview" class="__height-80 onerror-image"
                                    data-onerror-image="{{dynamicAsset('/public/assets/admin/img/blank3.png')}}"

                                @if ($additional_data != null)
                                    src="{{ \App\CentralLogics\Helpers::get_full_url('payment_modules/gateway_image',$additional_data?->gateway_image,$additional_data?->storage ?? 'public') }}"

                                @else
                                src="{{dynamicAsset('/public/assets/admin/img/blank3.png')}}"
                                @endif
                                alt="public">
                                </div>

                                <input name="gateway" value="{{$payment->key_name}}" class="d-none">

                                @php($mode=$data_values->where('key_name',$payment->key_name)->first()->live_values['mode'])
                                <div class="form-floating mb-2" >
                                    <select class="js-select form-control theme-input-style w-100" name="mode">
                                        <option value="live" {{$mode=='live'?'selected':''}}>{{ translate('Live') }}</option>
                                        <option value="test" {{$mode=='test'?'selected':''}}>{{ translate('Test') }}</option>
                                    </select>
                                </div>

                                @php($skip=['gateway','mode','status'])
                                @foreach($data_values->where('key_name',$payment->key_name)->first()->live_values as $key=>$value)
                                    @if(!in_array($key,$skip))
                                        <div class="form-floating mb-2" >
                                            <label for="{{$payment_key}}-{{$key}}"
                                                   class="form-label">{{ucwords(str_replace('_',' ',$key))}}
                                                *</label>
                                            <input id="{{$payment_key}}-{{$key}}" type="text" class="form-control"
                                                   name="{{$key}}"
                                                   placeholder="{{ucwords(str_replace('_',' ',$key))}} *"
                                                   value="{{env('APP_ENV')=='demo'?'':$value}}">
                                        </div>
                                    @endif
                                @endforeach

                                @if($payment['key_name'] == 'paystack')
                                    <div class="form-floating mb-2" >
                                        <label for="Callback_Url" class="form-label">{{translate('Callback Url')}}</label>
                                        <input id="Callback_Url" type="text"
                                               class="form-control"
                                               placeholder="{{translate('Callback Url')}} *"
                                               readonly
                                               value="{{env('APP_ENV')=='demo'?'': route('paystack.callback')}}" {{$is_published}}>
                                    </div>
                                @endif

                                <div class="form-floating mb-2" >
                                    <label for="payment_gateway_title-{{$payment_key}}"
                                           class="form-label">{{translate('payment_gateway_title')}}</label>
                                    <input type="text" class="form-control"
                                           name="gateway_title" id="payment_gateway_title-{{$payment_key}}"
                                           placeholder="{{translate('payment_gateway_title')}}"
                                           value="{{$additional_data != null ? $additional_data->gateway_title : ''}}">
                                </div>

                                <div class="form-floating mb-2" >
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{translate('Choose_logo')}}</label>
                                    <input type="file" class="form-control" name="gateway_image" id="{{$payment->key_name}}-image" accept=".jpg, .png, .jpeg|image/*">
                                </div>

                                <div class="text-right mt-2 "  >
                                    <button type="submit" class="btn btn-primary px-5">{{translate('save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- End Tab Content -->
    </div>


    <div class="modal fade" id="payment-gateway-warning-modal">
        <div class="modal-dialog modal-dialog-centered status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="max-349 mx-auto mb-20">
                        <div>
                            <div class="text-center">
                                <img width="80" src="{{  dynamicAsset('public/assets/admin/img/modal/gateway.png') }}" class="mb-20">
                                <h5 class="modal-title"></h5>
                            </div>
                            <div class="text-center" >
                                <h3 > {{ translate('Are_you_sure,_want_to_turn_Off')}} <span id="gateway_name"></span> {{ translate('_as_the_Digital_Payment_method?') }}</h3>
                                <div > <p>{{ translate('You_must_active_at_least_one_digital_payment_method_that_support')}} &nbsp; {{ $currency }}  {{ translate('._Otherwise_customers_cannot_pay_via_digital_payments_from_the_app_and_websites._And_Also_restaurants_cannot_pay_you_digitally.') }}</h3></p></div>
                            </div>

                            <div class="text-center mb-4" >
                                <a class="text--underline" href="{{ route('admin.business-settings.business-setup') }}"> {{ translate('View_Currency_Settings.') }}</a>
                            </div>
                            </div>

                        <div class="btn--container justify-content-center">
                            <button data-dismiss="modal"  class="btn btn--cancel min-w-120" >{{translate("Cancel")}}</button>
                            <button data-dismiss="modal"  id="confirm-currency-change" type="button"  class="btn btn--primary min-w-120">{{translate('OK')}}</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('script_2')
    <script src="{{dynamicAsset('public/assets/admin/js/view-pages/business-settings-payment-page.js')}}"></script>
<script>

    $(document).on('click', '.open-warning-modal', function(event) {
    let gateway = $(this).data('gateway');
    if ($(this).is(':checked') === false) {
        event.preventDefault();
        $('#payment-gateway-warning-modal').modal('show');
        $('#gateway_name').html(gateway);
        $(this).data('originalEvent', event);
    }
});

$(document).on('click', '#confirm-currency-change', function() {
    var gatewayName = $('#gateway_name').text();
    if (gatewayName) {
        $('#span_on_' + gatewayName).removeClass('checked');
    }

    var originalEvent = $('.open-warning-modal[data-gateway="' + gatewayName + '"]').data('originalEvent');
    if (originalEvent) {
        var newEvent = $.Event(originalEvent);
        $(originalEvent.target).trigger(newEvent);
    }

    $('#payment-gateway-warning-modal').modal('hide');
});

    "use strict";
    @if(!isset($digital_payment) || $digital_payment['status']==0)
        $('.digital_payment_methods').hide();
    @endif
</script>
@endpush
