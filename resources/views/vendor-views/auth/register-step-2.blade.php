@extends('layouts.landing.app')
@section('title', translate('messages.restaurant_registration'))
@push('css_or_js')
<link rel="stylesheet" href="{{ dynamicAsset('public/assets/landing') }}/css/style.css" />

@endpush
@section('content')
        <!-- Page Header Gap -->
        <div class="h-148px"></div>
        <!-- Page Header Gap -->

    <section class="m-0 landing-inline-1 section-gap">
        <div class="container">
            <!-- Page Header -->
            <div class="step__header">
                <h4 class="title">{{ translate('messages.Restaurant_registration_application') }}</h4>
                <div class="step__wrapper">
                    <div class="step__item active">
                        <span class="shapes"></span>
                        {{translate('General Information')}}
                    </div>
                    <div class="step__item  current">
                        <span class="shapes"></span>
                        {{translate('Business Plan')}}
                    </div>
                    <div class="step__item">
                        <span class="shapes"></span>
                        {{translate('Complete')}}
                    </div>
                </div>
            </div>
            <!-- End Page Header -->

                    <h4 class="register--title text-center mb-40px"> {{ translate('messages.business_plans') }}</h4>
                    <form action="{{ route('restaurant.business_plan') }}" class="reg-form js-validate" method="post"  >
                        @csrf
                        <input type="hidden" name="restaurant_id" value="{{ $restaurant_id }}" >
                        <div class="card __card mb-3">
                            <h4 class="card-title text-center pt-4">
                                @if (count($packages) > 0 && \App\CentralLogics\Helpers::commission_check())
                                {{ translate('Choose Your Business Plan') }}
                                @elseif (!count($packages) && !\App\CentralLogics\Helpers::commission_check())
                                {{ translate('No business plan is available') }}
                                @else
                                {{ translate('Your Business Plan') }}
                                @endif
                            </h4>
                            <div class="card-body p-4">
                                <div class="row">
                                    @if ( \App\CentralLogics\Helpers::commission_check())

                                    <div class="col-sm-6">
                                        <label class="plan-check-item">
                                            <input type="radio" name="business_plan" value="commission-base" class="d-none" checked>
                                            <div class="plan-check-item-inner">
                                                <h5>{{ translate('Commision_Base') }}</h5>
                                                <p>
                                                    {{ translate('restaurant will pay') }} {{ $admin_commission }}% {{ translate('commission to') }} {{ $business_name }} {{ translate('from each order. You will get access of all the features and options  in restaurant panel , app and interaction with user.') }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                    @endif

                                    @if (count($packages) > 0)
                                    <div class="col-sm-6">
                                        <label class="plan-check-item">
                                            <input type="radio" name="business_plan" value="subscription-base" {{ !\App\CentralLogics\Helpers::commission_check() ? 'checked' : ''  }} class="d-none" >
                                            <div class="plan-check-item-inner">
                                                <h5>{{ translate('Subscription Base') }}</h5>
                                                <p>
                                                {{ translate('Run restaurant by puchasing subsciption packages. You will have access the features of in restaurant panel , app and interaction with user according to the subscription packages.') }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>

                                    @endif


                                    @if ( !\App\CentralLogics\Helpers::commission_check() && !count($packages) )
                                    <div class="col-12">
                                        <div class="empty--data text-center">
                                            <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                                            <h5>
                                            </h5>
                                        </div>
                                    </div>
                                    @endif





                                </div>
                                <div id="subscription-plan">
                                    <br>
                                    <h4 class="card-title text-center">
                                        {{ translate('Choose Subscription Package') }}
                                    </h4>
                                    <div class="card-body">
                                        <div class="plan-slider owl-theme owl-carousel owl-refresh">

                                            @forelse ($packages as $key=> $package)
                                            <label class="__plan-item {{ count($packages) > 4 &&  $key == 2 ||( count($packages) < 5 &&  $key == 1) || count($packages) == 1 ? 'active' : '' }} ">
                                                <input type="radio" name="package_id" {{ count($packages) > 4 &&  $key == 2 ||( count($packages) < 5 &&  $key == 1) || count($packages) == 1 ? 'checked' : '' }}  value="{{ $package->id }}"  class="d-none">
                                                <div class="inner-div">
                                                    <div class="text-center">

                                                        <h3 class="title">{{ $package->package_name }}</h3>
                                                        <h2 class="price">{{ \App\CentralLogics\Helpers::format_currency($package->price)}}</h2>
                                                        <div class="day-count">{{ $package->validity }} {{ translate('messages.days') }}</div>
                                                    </div>
                                                    <ul class="info">

                                                    @if ($package->pos)
                                                    <li>
                                                        <img src="{{dynamicAsset('/public/assets/landing/img/check-1.svg')}}" class="check" alt="">
                                                            <img src="{{dynamicAsset('/public/assets/landing/img/check-2.svg')}}" class="check-white" alt=""> <span>  {{ translate('messages.POS') }} </span>
                                                    </li>
                                                    @endif
                                                    @if ($package->mobile_app)
                                                    <li>
                                                        <img src="{{dynamicAsset('/public/assets/landing/img/check-1.svg')}}" class="check" alt="">
                                                            <img src="{{dynamicAsset('/public/assets/landing/img/check-2.svg')}}" class="check-white" alt=""> <span>  {{ translate('messages.mobile_app') }} </span>
                                                    </li>
                                                    @endif
                                                    @if ($package->chat)
                                                    <li>
                                                        <img src="{{dynamicAsset('/public/assets/landing/img/check-1.svg')}}" class="check" alt="">
                                                            <img src="{{dynamicAsset('/public/assets/landing/img/check-2.svg')}}" class="check-white" alt=""> <span>  {{ translate('messages.chatting_options') }} </span>
                                                    </li>
                                                    @endif
                                                    @if ($package->review)
                                                    <li>
                                                        <img src="{{dynamicAsset('/public/assets/landing/img/check-1.svg')}}" class="check" alt="">
                                                            <img src="{{dynamicAsset('/public/assets/landing/img/check-2.svg')}}" class="check-white" alt=""> <span>  {{ translate('messages.review_section') }} </span>
                                                    </li>
                                                    @endif
                                                    @if ($package->self_delivery)
                                                    <li>
                                                        <img src="{{dynamicAsset('/public/assets/landing/img/check-1.svg')}}" class="check" alt="">
                                                            <img src="{{dynamicAsset('/public/assets/landing/img/check-2.svg')}}" class="check-white" alt=""> <span>  {{ translate('messages.self_delivery') }} </span>
                                                    </li>
                                                    @endif
                                                    @if ($package->max_order == 'unlimited')
                                                    <li>
                                                        <img src="{{dynamicAsset('/public/assets/landing/img/check-1.svg')}}" class="check" alt="">
                                                            <img src="{{dynamicAsset('/public/assets/landing/img/check-2.svg')}}" class="check-white" alt=""> <span>  {{ translate('messages.Unlimited_Orders') }} </span>
                                                    </li>
                                                    @else
                                                    <li>
                                                        <img src="{{dynamicAsset('/public/assets/landing/img/check-1.svg')}}" class="check" alt="">
                                                            <img src="{{dynamicAsset('/public/assets/landing/img/check-2.svg')}}" class="check-white" alt=""> <span>  {{ $package->max_order }} {{ translate('messages.Orders') }} </span>
                                                    </li>
                                                    @endif
                                                    @if ($package->max_product == 'unlimited')
                                                    <li>
                                                        <img src="{{dynamicAsset('/public/assets/landing/img/check-1.svg')}}" class="check" alt="">
                                                            <img src="{{dynamicAsset('/public/assets/landing/img/check-2.svg')}}" class="check-white" alt=""> <span>  {{ translate('messages.Unlimited_uploads') }} </span>
                                                    </li>
                                                    @else
                                                    <li>
                                                        <img src="{{dynamicAsset('/public/assets/landing/img/check-1.svg')}}" class="check" alt="">
                                                            <img src="{{dynamicAsset('/public/assets/landing/img/check-2.svg')}}" class="check-white" alt=""> <span>  {{ $package->max_product }} {{ translate('messages.uploads') }} </span>
                                                    </li>
                                                    @endif
                                                    </ul>
                                                </div>
                                            </label>
                                            @empty

                                            @endforelse

                                        </div>
                                    </div>
                                </div>
                                <div class="text-end pt-5 d-flex flex-wrap justify-content-end gap-3">
                                    <button type="submit" {{ !\App\CentralLogics\Helpers::commission_check() && !count($packages) ? 'disabled'  : ''}}  class="btn btn--primary submitBtn">{{ translate('Next')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>

        </div>
    </section>

    @endsection
    @push('script_2')

        <script src="{{ dynamicAsset('public/assets/admin') }}/js/toastr.js"></script>

    <!-- Script For Plan Collapse -->
    <script>
        "use strict";

        // Plan Slider
        $('.plan-slider').owlCarousel({
            loop: false,
            margin: 0,
            responsiveClass: true,
            nav:false,
            dots:false,
            items: 3,
            startPosition: 0,

            responsive:{
                0: {
                    items: 1,
                },
                375: {
                    items: 1,
                },
                576: {
                    items:1.7,
                },
                768: {
                    items:2.2,
                },
                992: {
                    items: 3,
                },
                1200: {
                    items: 4,
                },
            }
        })

    </script>
        <script>
            $(window).on('load', function(){
                $('input[name="business_plan"]').each(function(){
                    if($(this).is(':checked')){
                        if($(this).val() == 'subscription-base'){
                            $('#subscription-plan').show()
                        }else {
                            $('#subscription-plan').hide()
                        }
                    }
                })
                $('input[name="package_id"]').each(function(){
                    if($(this).is(':checked')){
                        $(this).closest('.__plan-item').addClass('active')
                    }
                })
            })
            $('input[name="business_plan"]').on('change', function(){
                if($(this).val() == 'subscription-base'){
                    $('#subscription-plan').slideDown()
                }else {
                    $('#subscription-plan').slideUp()
                }
            })
            $('input[name="package_id"]').on('change', function(){
                $('input[name="package_id"]').each(function(){
                    $(this).closest('.__plan-item').removeClass('active')
                })
                $(this).closest('.__plan-item').addClass('active')
            })
        </script>
    <!-- Script For Plan Collapse -->
    @endpush
