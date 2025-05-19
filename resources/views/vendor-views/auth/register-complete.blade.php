@extends('layouts.landing.app')
@section('title', translate('messages.restaurant_registration'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset('public/assets/landing/css/style.css') }}" />
@endpush
@section('content')
    <!-- Page Header Gap -->
    <div class="h-148px"></div>
    <!-- Page Header Gap -->

    <section class="m-0 landing-inline-1 section-gap">
        <div class="container">
            <!-- Page Header -->
            <div class="section-header">
                <h2 class="title mb-2 text-center">{{ translate('messages.restaurant') }} <span
                        class="text-base">{{ translate('application') }}</span></h2>
            </div>
            <!-- End Page Header -->


            <div class="step__wrapper">
                <div id="show-step1" class="step__item active">
                    <span class="shapes"></span>
                    {{ translate('General Information') }}
                </div>
                <div id="show-step2" class="step__item active">
                    <span class="shapes"></span>
                    {{ translate('Business Plan') }}
                </div>
                <div class="step__item {{ isset($payment_status) && $payment_status == 'fail' ? 'current' : 'active' }}">
                    <span class="shapes"></span>
                    {{ translate('Complete') }}
                </div>
            </div>


                <div class="card __card mb-3 mt-3">
                    <div class="pb-4 text-center pt-5">
                        @if (isset($payment_status) && $payment_status == 'fail')
                            <img src="{{ dynamicAsset('/public/assets/landing/img/Failed.gif') }}" width="40"
                                alt="" class="mb-4">
                            <h4>
                                {{ translate('Transaction Failed!') }}
                            </h4>
                        @else
                            <img src="{{ dynamicAsset('/public/assets/landing/img/Success.gif') }}" width="40"
                                alt="" class="mb-4">
                            <h5 class="card-title text-center">
                                {{ translate('Congratulations!') }}
                            </h5>
                        @endif
                    </div>
                    <div class="card-body p-4 pb-5">
                        <div class="register-congrats-txt">
                            @if (isset($type) && $type == 'commission')
                                {{ translate('You’ve opted for our commission-based plan. Admin will review the details and activate your account shortly. To explore the site.') }}
                                <a href="{{ route('home', ['new_user' => true]) }}"
                                    class="text-base font-bold">{{ translate('visit_here') }}</a>
                            @elseif(isset($payment_status) && $payment_status == 'fail')
                                {{ translate('Sorry, Your Transaction can’t be completed. Please choose another payment method.') }}
                                <a href="{{ route('restaurant.back', ['restaurant_id' => base64_encode($restaurant_id) ?? null]) }}"
                                    class="text-base font-bold">{{ translate('Try_again') }}</a>
                            @else
                                {{ translate('Thank you for your subscription purchase! Your payment was successfully processed. Please note that your subscription will be activated once it has been approved by our Admin Team. To explore the site') }}
                                <a href="{{ route('home', ['new_user' => true]) }}"
                                    class="text-base font-bold">{{ translate('visit_here') }}</a>
                            @endif
                        </div>
                    </div>

            </div>
        </div>
    </section>

@endsection
@push('script_2')
    <script>
        @if (!(isset($payment_status) && $payment_status == 'fail'))
            document.addEventListener("DOMContentLoaded", function() {
                var homeLink = document.getElementById('home-link');
                var newUrl = "{{ route('home', ['new_user' => true]) }}";
                homeLink.setAttribute('href', newUrl);
            });
        @endif
    </script>
@endpush
