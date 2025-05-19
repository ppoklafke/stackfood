@extends('layouts.landing.app')
@section('title', translate('messages.restaurant_registration'))
@push('css_or_js')
<link rel="stylesheet" href="{{ dynamicAsset('public/assets/landing/css/style.css') }}" />
<link href="{{ dynamicAsset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    <!-- Page Header Gap -->
    <div class="h-148px"></div>
    <!-- Page Header Gap -->

    <section class="m-0 landing-inline-1 section-gap">
        <div class="container">
            <!-- Page Header -->
            <div class="section-header">
                <h4 class="title mb-2 text-center">{{ translate('messages.restaurant') }} <span class="text-base">{{translate('application')}}</span></h4>
            </div>

            <!-- End Page Header -->

            <!-- Stepper -->
            <div class="step__wrapper">
                <div id="show-step1" class="step__item active">
                    <span class="shapes"></span>
                    {{ translate('General Information') }}
                </div>
                <div id="show-step2" class="step__item current">
                    <span class="shapes"></span>
                    {{ translate('Business Plan') }}
                </div>
                <div class="step__item">
                    <span class="shapes"></span>
                    {{ translate('Complete') }}
                </div>
            </div>
            <!-- Stepper -->


            <form class="reg-form js-validate" id="myForm" action="{{ route('restaurant.payment') }}" method="post">
                @csrf
                @method('post')
                <input type="hidden" name="restaurant_id" value="{{ $restaurant_id }}" >
                <input type="hidden" name="package_id" value="{{ $package_id }}" >
                <div class="card __card mt-3 mb-3 pt-4">
                    <div class="pt-3 pb-4">
                        <h5 class="card-title text-center">
                            {{ translate('Make Payment For Your Business Plan') }}
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">

                        <?php
                        if( data_get($free_trial_settings, 'subscription_free_trial_type') == 'year'){
                                $trial_period =data_get($free_trial_settings, 'subscription_free_trial_days') > 0 ? data_get($free_trial_settings, 'subscription_free_trial_days')  / 365 : 0;
                            } else if( data_get($free_trial_settings, 'subscription_free_trial_type') == 'month'){
                                $trial_period =data_get($free_trial_settings, 'subscription_free_trial_days') > 0 ? data_get($free_trial_settings, 'subscription_free_trial_days')  / 30 : 0;
                            } else{
                                $trial_period =data_get($free_trial_settings, 'subscription_free_trial_days') > 0 ? data_get($free_trial_settings, 'subscription_free_trial_days') : 0 ;
                            }
                        ?>
                        @if (data_get($free_trial_settings,'subscription_free_trial_status') == 1 && data_get($free_trial_settings,'subscription_free_trial_days') > 0 )
                            <label class="payment-item">
                                <input type="radio" class="d-none" checked value="free_trial" name="payment">
                                <div class="payment-item-inner justify-content-center">
                                    <div class="check">
                                        {{-- <img src="{{dynamicAsset('public/assets/admin/img/check-1.png')}}" class="uncheck" alt=""> --}}
                                        <img src="{{dynamicAsset('public/assets/admin/img/check-2.png')}}" class="check" alt="">
                                    </div>
                                    <span>{{ translate('Continue with') }} {{ $trial_period }}  {{ data_get($free_trial_settings, 'subscription_free_trial_type') }} {{ translate('Free_Trial') }}</span>
                                </div>
                            </label>
                        @endif


                        <br>
                        <br>
                        <h6 class="text-16 mb-4">{{ translate('Pay Via Online') }} <span class="font-regular text-body">({{ translate('Faster & secure way to pay bill') }})</span></h6>
                        <div class="row g-3">


                            @foreach ($payment_methods as $item)
                            <div class="col-md-6 col-lg-4">
                                <label class="payment-item">
                                    <input type="radio" class="d-none" value="{{ $item['gateway'] }}" name="payment">
                                    <div class="payment-item-inner">
                                        <div class="check">
                                            <img src="{{dynamicAsset('public/assets/admin/img/check-1.png')}}" class="uncheck" alt="">
                                            <img src="{{dynamicAsset('public/assets/admin/img/check-2.png')}}" class="check" alt="">
                                        </div>
                                        <span>{{ $item['gateway_title'] }}</span>
                                        <img class="ms-auto" height="30"


                                            src="{{ \App\CentralLogics\Helpers::get_full_url('payment_modules/gateway_image',$item['gateway_image'],$item['storage'] ?? 'public') }}"


                                        width="60" alt="">
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-end pt-5 d-flex flex-wrap justify-content-end gap-3">
                            <a  href="{{ route('restaurant.back',['restaurant_id' => base64_encode($restaurant_id)] ) }}" type="button" class="btn btn--reset">{{ translate('Back')
                                }}</a>
                            <button type="submit" class="btn btn--primary submitBtn">{{ translate('Next')
                                }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @endsection
    @push('script_2')
    <script>
        document.getElementById('myForm').addEventListener('submit', function(event) {
          const checkboxes = document.querySelectorAll('input[type="radio"]');
          const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

          if (!isAnyChecked) {
            event.preventDefault(); // Prevent form submission
            toastr.error('{{ translate('messages.You_must_select_a_payment_method') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
          }
        });
      </script>
    @endpush
