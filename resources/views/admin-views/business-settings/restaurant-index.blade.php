@extends('layouts.admin.app')

@section('title', translate('Restaurant_setup'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title mr-3">
                    <span class="page-header-icon">
                        <img src="{{ dynamicAsset('public/assets/admin/img/business.png') }}" class="w--20" alt="">
                    </span>
                    <span>
                        {{ translate('messages.business_setup') }}
                    </span>
                </h1>
                <div class="d-flex flex-wrap justify-content-end align-items-center flex-grow-1">
                    <div class="blinkings active">
                        <i class="tio-info-outined"></i>
                        <div class="business-notes">
                            <h6><img src="{{dynamicAsset('/public/assets/admin/img/notes.png')}}" alt=""> {{translate('Note')}}</h6>
                            <div>
                                {{translate('Don’t_forget_to_click_the_respective_‘Save_Information’_buttons_below_to_save_changes')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin-views.business-settings.partials.nav-menu')
        </div>
        <form action="{{ route('admin.business-settings.update-restaurant') }}" method="post" enctype="multipart/form-data">
            @csrf
            @php($name = \App\Models\BusinessSetting::where('key', 'business_name')->first())

            <div class="row g-3">
                @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
                @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-4 col-sm-6">
                                    @php($canceled_by_restaurant = \App\Models\BusinessSetting::where('key', 'canceled_by_restaurant')->first())
                                    @php($canceled_by_restaurant = $canceled_by_restaurant ? $canceled_by_restaurant->value : 0)
                                    <div class="form-group mb-0">
                                        <label class="input-label text-capitalize d-flex alig-items-center"><span
                                                class="line--limit-1">{{ translate('Can_a_Restaurant_Cancel_Order') }}
                                            </span><span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('Admin_can_enable/disable_restaurants’_order_cancellation_option.') }}">
                                                <i class="tio-info-outined"></i>
                                            </span></label>
                                        <div class="resturant-type-group border">
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="1"
                                                    name="canceled_by_restaurant" id="canceled_by_restaurant"
                                                    {{ $canceled_by_restaurant == 1 ? 'checked' : '' }}>
                                                <span class="form-check-label">
                                                    {{ translate('yes') }}
                                                </span>
                                            </label>
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="0"
                                                    name="canceled_by_restaurant" id="canceled_by_restaurant2"
                                                    {{ $canceled_by_restaurant == 0 ? 'checked' : '' }}>
                                                <span class="form-check-label">
                                                    {{ translate('no') }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    @php($restaurant_self_registration = \App\Models\BusinessSetting::where('key', 'toggle_restaurant_registration')->first())
                                    @php($restaurant_self_registration = $restaurant_self_registration ? $restaurant_self_registration->value : 0)
                                    <div class="form-group mb-0">

                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{ translate('messages.restaurant_self_registration') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('If_enabled,_a_restaurant_can_send_a_registration_request_through_their_restaurant_or_customer_app,_website,_or_admin_landing_page._The_admin_will_receive_an_email_notification_and_can_accept_or_reject_the_request') }}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.restaurant_self_registration') }}"> *
                                                </span>
                                            </span>
                                            <input type="checkbox"

                                            data-id="restaurant_self_registration1"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/store-self-reg-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/store-self-reg-off.png') }}"
                                            data-title-on="{{ translate('Want_to_enable') }} <strong>{{ translate('restaurant Self Registration') }}</strong> ?"
                                            data-title-off="{{ translate('Want_to_disable') }} <strong>{{ translate('restaurant Self Registration') }}</strong> ?"
                                            data-text-on="<p>{{ translate('If_enabled,_restaurants_can_do_self-registration_from_the_restaurant_or_customer_app_or_website') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_the_restaurant_Self-Registration_feature_will_be_hidden_from_the_restaurant_or_customer_app,_website,_and_admin_landing_page') }}</p>"
                                            class="toggle-switch-input dynamic-checkbox-toggle"

                                            value="1"
                                                name="restaurant_self_registration" id="restaurant_self_registration1"
                                                {{ $restaurant_self_registration == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    @php($restaurant_review_reply = \App\Models\BusinessSetting::where('key', 'restaurant_review_reply')->first())
                                    @php($restaurant_review_reply = $restaurant_review_reply ? $restaurant_review_reply->value : 0)
                                    <div class="form-group mb-0">

                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{ translate('Restaurant Can Reply Review') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('If_enabled,_a_restaurant_can_reply_to_a_review') }}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.restaurant_review_reply') }}">
                                                </span>
                                            </span>
                                            <input type="checkbox"

                                            data-id="restaurant_review_reply1"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/store-self-reg-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/store-self-reg-off.png') }}"
                                            data-title-on="{{ translate('Want_to_enable') }} <strong>{{ translate('restaurant Reply Review') }}</strong> ?"
                                            data-title-off="{{ translate('Want_to_disable') }} <strong>{{ translate('restaurant Reply Review') }}</strong> ?"
                                            data-text-on="<p>{{ translate('If_enabled,_a_restaurant_can_reply_to_a_review') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_a_restaurant_can_not_reply_to_a_review') }}</p>"
                                            class="toggle-switch-input dynamic-checkbox-toggle"

                                            value="1"
                                                name="restaurant_review_reply" id="restaurant_review_reply1"
                                                {{ $restaurant_review_reply == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-4">
                                    @php($extra_packaging_charge = \App\Models\BusinessSetting::where('key', 'extra_packaging_charge')->first())
                                    @php($extra_packaging_charge = $extra_packaging_charge ? $extra_packaging_charge->value : 0)
                                    <div class="form-group mb-0">
                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="align-items-center d-flex flex-grow pr-1 switch--label w-0">
                                                <span class="line--limit-1">
                                                    {{ translate('messages.Restaurant Can Enable Extra Packaging Charge') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                      data-toggle="tooltip" data-placement="right"
                                                      data-original-title="{{ translate('With_this_feature,_restaurant_will_get_the_option_to_offer_extra_packaging_charge_to_the_customer.') }}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.extra_packaging_charge_toggle') }}">
                                                </span>
                                            </span>
                                            <input type="checkbox"

                                                   data-id="extra_packaging_charge"
                                                   data-type="toggle"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/veg-on.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/veg-off.png') }}"
                                                   data-title-on="{{ translate('want_to_enable') }} <strong>{{ translate('extra_packaging_charge') }}</strong>?"
                                                   data-title-off="{{ translate('want_to_disable') }} <strong>{{ translate('extra_packaging_charge') }}</strong>?"
                                                   data-text-on="<p>{{ translate('if_enabled,_restaurant_will_get_the_option_to_offer_extra_packaging_charge_to_the_customer') }}</p>"
                                                   data-text-off="<p>{{ translate('if_disabled,_restaurant_will_not_get_the_option_to_offer_extra_packaging_charge_to_the_customer') }}</p>"
                                                   class="toggle-switch-input dynamic-checkbox-toggle"

                                                   value="1"
                                                   name="extra_packaging_charge" id="extra_packaging_charge"
                                                {{ $extra_packaging_charge == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6">
                                    @php($cash_in_hand_overflow = \App\Models\BusinessSetting::where('key', 'cash_in_hand_overflow_restaurant')->first())
                                    @php($cash_in_hand_overflow = $cash_in_hand_overflow ? $cash_in_hand_overflow->value : 0)
                                    <div class="form-group mb-0">

                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{ translate('messages.Cash_In_Hand_Overflow') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('If_enabled,_restaurants_will_be_automatically_suspended_by_the_system_when_their_‘Cash_in_Hand’_limit_is_exceeded.') }}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.cash_in_hand_overflow') }}"> *
                                                </span>
                                            </span>
                                            <input type="checkbox"

                                            data-id="cash_in_hand_overflow"
                                                data-type="toggle"
                                                data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/show-earning-in-apps-on.png') }}"
                                                data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/show-earning-in-apps-off.png') }}"
                                                data-title-on="{{ translate('Want_to_enable') }} <strong>{{ translate('Cash_In_Hand_Overflow') }}</strong> ?"
                                                data-title-off="{{ translate('Want_to_disable') }} <strong>{{ translate('Cash_In_Hand_Overflow') }}</strong>  ?"
                                                data-text-on="<p>{{ translate('If_enabled,_restaurants_have_to_provide_collected_cash_by_them_self') }}</p>"
                                                data-text-off="<p>{{ translate('If_disabled,_restaurants_do_not_have_to_provide_collected_cash_by_them_self') }}</p>"
                                                class="toggle-switch-input dynamic-checkbox-toggle"


                                            value="1"
                                                name="cash_in_hand_overflow_restaurant" id="cash_in_hand_overflow"
                                                {{ $cash_in_hand_overflow == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>





                                <div class="col-lg-4 col-sm-6">
                                    @php($cash_in_hand_overflow_restaurant_amount = \App\Models\BusinessSetting::where('key', 'cash_in_hand_overflow_restaurant_amount')->first())
                                    <div class="form-group mb-0">
                                        <label class=" text-capitalize"
                                            for="cash_in_hand_overflow_restaurant_amount">
                                            <span>
                                                {{ translate('Maximum_Amount_to_Hold_Cash_in_Hand') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})

                                            </span>

                                            <span class="form-label-secondary"
                                            data-toggle="tooltip" data-placement="right"
                                            data-original-title="{{ translate('Enter_the_maximum_cash_amount_restaurants_can_hold._If_this_number_exceeds,_restaurants_will_be_suspended_and_not_receive_any_orders.') }}"><img
                                                src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                alt="{{ translate('messages.dm_cancel_order_hint') }}"></span>
                                        </label>
                                        <input type="number" name="cash_in_hand_overflow_restaurant_amount" class="form-control"
                                            id="cash_in_hand_overflow_restaurant_amount" min="0" step=".001"
                                            value="{{ $cash_in_hand_overflow_restaurant_amount ? $cash_in_hand_overflow_restaurant_amount->value : '' }}"  {{ $cash_in_hand_overflow  == 1 ? 'required' : 'readonly' }} >
                                    </div>
                                </div>


                                <div class="col-lg-4 col-sm-6">
                                    @php($min_amount_to_pay_restaurant = \App\Models\BusinessSetting::where('key', 'min_amount_to_pay_restaurant')->first())
                                    <div class="form-group mb-0">
                                        <label class=" text-capitalize"
                                            for="min_amount_to_pay_restaurant">
                                            <span>
                                                {{ translate('Minimum_Amount_To_Pay') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                            </span>
                                            <span class="form-label-secondary"
                                            data-toggle="tooltip" data-placement="right"
                                            data-original-title="{{ translate('Enter_the_minimum_cash_amount_restaurants_can_pay') }}"><img
                                                src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                alt="{{ translate('messages.dm_cancel_order_hint') }}"></span>
                                        </label>
                                        <input type="number" name="min_amount_to_pay_restaurant" class="form-control"
                                            id="min_amount_to_pay_restaurant" min="0" step=".001"
                                            value="{{ $min_amount_to_pay_restaurant ? $min_amount_to_pay_restaurant->value : '' }}"  {{ $cash_in_hand_overflow  == 1 ? 'required' : 'readonly' }} >
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

@endsection

