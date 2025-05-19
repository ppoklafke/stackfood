@extends('layouts.admin.app')

@section('title',$restaurant->name."'s ".translate('messages.business_plan'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{dynamicAsset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">

@endpush

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h1 class="page-header-title text-break">
                <i class="tio-museum"></i> <span>{{$restaurant->name}}</span>
            </h1>
        </div>
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <span class="hs-nav-scroller-arrow-prev initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-left"></i>
                </a>
            </span>

            <span class="hs-nav-scroller-arrow-next initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-right"></i>
                </a>
            </span>

            <!-- Nav -->
            @include('admin-views.vendor.view.partials._header',['restaurant'=>$restaurant])

            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
    </div>
    @if ($restaurant->restaurant_model == 'commission' &&  \App\CentralLogics\Helpers::commission_check())

    <div class="mt-3 card mb-3">
        <div class="card-header border-0 align-items-center">
            <h4 class="card-title align-items-center gap-2">
                <span class="card-header-icon">
                    <img width="25" src="{{dynamicAsset('public/assets/admin/img/subscription-plan/subscribed-user.png')}}" alt="">
                </span>
                <span>{{ translate('Overview') }}</span>
            </h4>
        </div>
        <div class="card-body pt-0">
            <div class="__bg-F8F9FC-card __plan-details">
                <div class="d-flex flex-wrap flex-md-nowrap justify-content-between __plan-details-top">
                    <div class="w-100">
                        <h2 class="name text--primary">{{ translate('Commission Base Plan') }}</h2>
                        <h4 class="title mt-2"><span class="text-180">{{ $restaurant->comission > 0 ?  $restaurant->comission :  $admin_commission }} %</span> {{ translate('messages.Commission_per_order') }}</h4>
                        <div class="info-text ">
                            {{ translate('Restaurant will pay') }} {{ $restaurant->comission > 0 ?  $restaurant->comission :  $admin_commission }}% {{ translate('commission to') }} <strong>{{ $business_name }}</strong> {{ translate('from each order. You will get access of all the features and options  in restaurant panel , app and interaction with user.') }}
                        </div>
                                <div class="mt-3">
                                    <form action="{{route('admin.restaurant.update-settings',[$restaurant['id'] , 'tab' => 'business_plan'])}}" method="post">
                                        @csrf
                                        @method("post")
                                        <div class="row">
                                            <div class="col-xl-6 col-xxl-5">
                                                <div>
                                                    <label class="d-flex mb-1 justify-content-between toggle-switch-sm text-dark text-capitalize" for="comission_status">
                                                        <span>{{translate('messages.Change_Commission_Rate')}}(%) <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('When_enabled,_admin_will_only_receive_the_certain_commission_percentage_he_set_for_this_restaurant._Otherwise,_the_system_default_commission_will_be_applied.')}}"><img src="{{dynamicAsset('/public/assets/admin/img/info-circle.svg')}}" alt="{{translate('When_enabled,_admin_will_only_receive_the_certain_commission_percentage_he_set_for_this_restaurant._Otherwise,_the_system_default_commission_will_be_applied.')}}"></span></span>
                                                        <input type="checkbox" class="toggle-switch-input" name="comission_status" id="comission_status" value="1" {{isset($restaurant->comission)?'checked':''}}>
                                                        <span class="toggle-switch-label">
                                                            <span class="toggle-switch-indicator"></span>
                                                        </span>
                                                    </label>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        <input type="number" id="comission" min="0" max="10000" step="0.01" name="comission" class="form-control w-200px flex-grow-1 bg-white" required value="{{$restaurant->comission??'0'}}" {{isset($restaurant->comission)?'':'readonly'}}>
                                                        <button type="submit" class="btn btn--primary h--45px">{{ translate('Change') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                    </div>
                </div>

            </div>
            @if (\App\CentralLogics\Helpers::subscription_check() )
                <div class="btn--container justify-content-end mt-3">
                    <button type="button" data-toggle="modal" data-target="#plan-modal" class="btn btn--primary">{{ translate('Change Business Plan') }}</button>
                </div>
            @endif
        </div>
    </div>

    @elseif (in_array($restaurant->restaurant_model,[ 'subscription' ,'unsubscribed']) && $restaurant?->restaurant_sub_update_application)

                <div class="card mb-20">
                    <div class="card-header border-0 align-items-center">
                        <h4 class="card-title align-items-center gap-2">
                            <span class="card-header-icon">
                                <img src="{{dynamicAsset('public/assets/admin/img/subscription/billing.png')}}" alt="">
                            </span>
                            <span class="text-title">{{ translate('Billing') }}</span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6 col-lg-4">
                                <a class="__card-2 __bg-1 flex-row align-items-center gap-4" href="#">
                                    <img src="{{dynamicAsset('public/assets/admin/img/subscription/expiring.png')}}" alt="report/new" class="w-60px">
                                    <div class="w-0 flex-grow-1 py-md-3">
                                        <span class="text-body">{{ translate('Expire Date') }}</span>
                                        <h4 class="title m-0">{{  \App\CentralLogics\Helpers::date_format($restaurant?->restaurant_sub_update_application?->expiry_date_parsed) }}</h4>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <a class="__card-2 __bg-8 flex-row align-items-center gap-4" href="#">
                                    <img src="{{dynamicAsset('public/assets/admin/img/subscription/total-bill.png')}}" alt="report/new" class="w-60px">
                                    <div class="w-0 flex-grow-1 py-md-3">
                                        <span class="text-body">{{ translate('Total_Bill') }}</span>
                                        <h4 class="title m-0">{{  \App\CentralLogics\Helpers::format_currency($restaurant?->restaurant_sub_update_application?->last_transcations?->price ?? $restaurant?->restaurant_sub_update_application?->package?->price * ($restaurant?->restaurant_sub_update_application?->total_package_renewed + 1) ) }}</h4>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <a class="__card-2 __bg-4 flex-row align-items-center gap-4" href="#">
                                    <img src="{{dynamicAsset('public/assets/admin/img/subscription/number.png')}}" alt="report/new" class="w-60px">
                                    <div class="w-0 flex-grow-1 py-md-3">
                                        <span class="text-body">{{ translate('Number of Uses') }}</span>
                                        <h4 class="title m-0">{{ $restaurant?->restaurant_sub_update_application?->total_package_renewed + 1 }}</h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header border-0 align-items-center">
                        <h4 class="card-title align-items-center gap-2">
                            <span class="card-header-icon">
                                <img width="25" src="{{dynamicAsset('public/assets/admin/img/subscription-plan/subscribed-user.png')}}" alt="">
                            </span>
                            <span>{{ translate('Package Overview') }}

                                @if($restaurant?->status == 0 &&  $restaurant?->vendor?->status == 0)
                                <span class=" badge badge-pill badge-info">  &nbsp; {{ translate('Approval_Pending') }}  &nbsp; </span>
                                @elseif($restaurant?->restaurant_sub_update_application?->status == 0)
                                <span class=" badge badge-pill badge-danger">  &nbsp; {{ translate('Expired') }}  &nbsp; </span>
                                @elseif ($restaurant?->restaurant_sub_update_application?->is_canceled == 1)
                                <span class=" badge badge-pill badge-warning">  &nbsp; {{ translate('canceled') }}  &nbsp; </span>
                                @elseif($restaurant?->restaurant_sub_update_application?->status == 1)
                                <span class=" badge badge-pill badge-success">  &nbsp; {{ translate('Active') }}  &nbsp; </span>
                                @endif

                            </span>
                        </h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="__bg-F8F9FC-card __plan-details">
                            <div class="d-flex flex-wrap flex-md-nowrap justify-content-between __plan-details-top">
                                <div class="left">
                                    <h3 class="name">{{ $restaurant?->restaurant_sub_update_application?->package?->package_name }}</h3>
                                    <div class="font-medium text--title">{{ $restaurant?->restaurant_sub_update_application?->package?->text }}</div>
                                </div>
                                <h3 class="right">{{ \App\CentralLogics\Helpers::format_currency($restaurant?->restaurant_sub_update_application?->last_transcations?->price?? $restaurant?->restaurant_sub_update_application?->package?->price) }} /<small class="font-medium text--title">{{ $restaurant?->restaurant_sub_update_application?->last_transcations?->validity ?? $restaurant?->restaurant_sub_update_application?->package?->validity}} {{ translate('messages.Days') }}</small></h3>
                            </div>


                            <div class="check--grid-wrapper mt-3 max-w-850px">


                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                                        @if ( $restaurant?->restaurant_sub_update_application?->max_order == 'unlimited' )
                                        <span class="form-check-label text-dark">{{ translate('messages.unlimited_orders') }}</span>
                                        @else
                                        <span class="form-check-label text-dark"> {{ $restaurant?->restaurant_sub_update_application?->package?->max_order }} {{
                                            translate('messages.Orders') }} <small>({{ $restaurant?->restaurant_sub_update_application?->max_order }} {{ translate('left') }}) </small> </span>
                                        @endif
                                    </div>
                                </div>


                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ( $restaurant?->restaurant_sub_update_application?->pos == 1 )
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                                        @else
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check-1.png')}}" alt="">
                                        @endif
                                        <span class="form-check-label text-dark">{{ translate('messages.POS') }}</span>
                                    </div>
                                </div>

                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ( $restaurant?->restaurant_sub_update_application?->mobile_app == 1 )
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                                        @else
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check-1.png')}}" alt="">
                                        @endif
                                        <span class="form-check-label text-dark">{{ translate('messages.Mobile_App') }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ( $restaurant?->restaurant_sub_update_application?->self_delivery == 1 )
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                                        @else
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check-1.png')}}" alt="">
                                        @endif
                                        <span class="form-check-label text-dark">{{ translate('messages.self_delivery') }}</span>
                                    </div>
                                </div>

                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                                        @if ( $restaurant?->restaurant_sub_update_application?->max_product == 'unlimited' )
                                        <span class="form-check-label text-dark">{{ translate('messages.unlimited_item_Upload')
                                            }}</span>
                                        @else
                                        <span class="form-check-label text-dark"> {{ $restaurant?->restaurant_sub_update_application?->max_product }} {{
                                            translate('messages.product_Upload') }} <small>({{ $restaurant?->restaurant_sub_update_application?->max_product  - $restaurant->foods_count > 0 ? $restaurant?->restaurant_sub_update_application?->max_product  - $restaurant->foods_count : 0 }} {{ translate('left') }}) </small></span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ( $restaurant?->restaurant_sub_update_application?->review == 1 )
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                                        @else
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check-1.png')}}" alt="">
                                        @endif
                                        <span class="form-check-label text-dark">{{ translate('messages.review') }}</span>
                                    </div>
                                </div>

                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ( $restaurant?->restaurant_sub_update_application?->chat == 1 )
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                                        @else
                                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/check-1.png')}}" alt="">
                                        @endif
                                        <span class="form-check-label text-dark">{{ translate('messages.chat') }}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="btn--container justify-content-end mt-3">
                            @if ( $restaurant?->restaurant_sub_update_application?->is_canceled == 0 && $restaurant?->restaurant_sub_update_application?->status == 1  )
                            <button type="button"  data-url="{{route('admin.subscription.cancelSubscription',$restaurant?->id)}}" data-message="{{translate('If_you_cancel_the_subscription,_after_')}} {{  Carbon\Carbon::now()->diffInDays($restaurant?->restaurant_sub_update_application?->expiry_date_parsed->format('Y-m-d'), false); }} {{ translate('days_the_vendor_will_no_longer_be_able_to_run_the_business_before_subscribe_a_new_plan.') }}"
                                class="btn btn--danger text-white status_change_alert">{{ translate('Cancel Subscription') }}</button>
                            @endif

                            <button type="button" data-toggle="modal" data-target="#plan-modal" class="btn btn--primary">{{ translate('Change / Renew Subscription Plan') }}</button>

                        </div>
                    </div>
                </div>

        @else


        <div class="card">
            <div class="card-body text-center py-5">
                <div class="max-w-542 mx-auto py-sm-5 py-4">
                    <img class="mb-4" src="{{dynamicAsset('/public/assets/admin/img/empty-subscription.svg')}}" alt="img">
                    <h4 class="mb-3">{{translate('Chose Business Plan')}}</h4>
                    <p class="mb-4">
                        {{translate('Chose a Business Plan from the list. So that Restaurants get more options to join the business for the growth and success.')}}<br>
                    </p>
                    <button type="button" data-toggle="modal" data-target="#plan-modal" class="btn btn--primary">{{ translate('Chose Business Plan') }}</button>
                </div>
            </div>
        </div>
    @endif



    <div class="modal fade show" id="plan-modal">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header px-3 pt-3">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body px-4 pt-0">
                    <div>
                        <div class="text-center">
                            <h2 class="modal-title">{{ translate('Chose a Business') }}</h2>
                        </div>
                        <div class="text-center text-14 mb-4 pb-3">
                           {{ translate('Chose a Business plan to get better experience!') }}
                        </div>
                        <div class="plan-slider owl-theme owl-carousel owl-refresh">
                            @if (\App\CentralLogics\Helpers::commission_check())
                            <div class="__plan-item hover {{ $restaurant->restaurant_model == 'commission'  ? 'active' : ''}} ">
                                <div class="inner-div">
                                    <div class="text-center">
                                        <h3 class="title">{{ translate('Commission Base') }}</h3>
                                        <h2 class="price">{{ $admin_commission }}%</h2>
                                    </div>
                                    <div class="py-5 mt-4">
                                        <div class="info-text text-center">
                                            {{ translate('Restaurant will pay') }} {{ $admin_commission }}% {{ translate('commission to') }} {{ $business_name }} {{ translate('from each order. You will get access of all the features and options  in restaurant panel , app and interaction with user.') }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        @if ($restaurant->restaurant_model == 'commission')
                                        <button type="button" class="btn btn--secondary">{{ translate('Current_Plan') }}</button>
                                        @else
                                        @php
                                        $cash_backs= \App\CentralLogics\Helpers::calculateSubscriptionRefundAmount(restaurant:$restaurant ,return_data:true);
                                        @endphp

                                        <button type="button" data-url="{{route('admin.subscription.switchToCommission',$restaurant->id)}}" data-message="{{translate('You_Want_To_Migrate_To_Commission.')}} {{ data_get($cash_backs,'back_amount') > 0  ?  translate('You will get').' '. \App\CentralLogics\Helpers::format_currency(data_get($cash_backs,'back_amount')) .' '.translate('to_your_wallet_for_remaining') .' '.data_get($cash_backs,'days').' '.translate('messages.days_subscription_plan') : '' }}"  class="btn btn--primary shift_to_commission">{{ translate('Shift in this plan') }}</button>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            @endif

                            @forelse ($packages as $package)

                            <div class="__plan-item hover {{ $restaurant?->restaurant_sub_update_application?->package_id == $package->id  && $restaurant->restaurant_model != 'commission'  ? 'active' : ''}}">
                                <div class="inner-div">
                                    <div class="text-center">
                                        <h3 class="title">{{ $package->package_name }}</h3>
                                        <h2 class="price">{{ \App\CentralLogics\Helpers::format_currency($package->price)}}</h2>
                                        <div class="day-count">{{ $package->validity }} {{ translate('messages.days') }}</div>
                                    </div>
                                    <ul class="info">

                                        @if ($package->pos)
                                        <li>
                                            <i class="tio-checkmark-circle"></i> <span>  {{ translate('messages.POS') }} </span>
                                        </li>
                                        @endif
                                        @if ($package->mobile_app)
                                        <li>
                                            <i class="tio-checkmark-circle"></i> <span>  {{ translate('messages.mobile_app') }} </span>
                                        </li>
                                        @endif
                                        @if ($package->chat)
                                        <li>
                                            <i class="tio-checkmark-circle"></i> <span>  {{ translate('messages.chatting_options') }} </span>
                                        </li>
                                        @endif
                                        @if ($package->review)
                                        <li>
                                            <i class="tio-checkmark-circle"></i> <span>  {{ translate('messages.review_section') }} </span>
                                        </li>
                                        @endif
                                        @if ($package->self_delivery)
                                        <li>
                                            <i class="tio-checkmark-circle"></i> <span>  {{ translate('messages.self_delivery') }} </span>
                                        </li>
                                        @endif
                                        @if ($package->max_order == 'unlimited')
                                        <li>
                                            <i class="tio-checkmark-circle"></i> <span>  {{ translate('messages.Unlimited_Orders') }} </span>
                                        </li>
                                        @else
                                        <li>
                                            <i class="tio-checkmark-circle"></i> <span>  {{ $package->max_order }} {{ translate('messages.Orders') }} </span>
                                        </li>
                                        @endif
                                        @if ($package->max_product == 'unlimited')
                                        <li>
                                            <i class="tio-checkmark-circle"></i> <span>  {{ translate('messages.Unlimited_uploads') }} </span>
                                        </li>
                                        @else
                                        <li>
                                            <i class="tio-checkmark-circle"></i> <span>  {{ $package->max_product }} {{ translate('messages.uploads') }} </span>
                                        </li>
                                        @endif

                                    </ul>
                                    <div class="text-center">

                                        @if ( $restaurant?->restaurant_model == 'none' ||  ($restaurant?->restaurant_model == 'unsubscribed' &&  !$restaurant?->restaurant_sub_update_application))
                                        <button data-id="{{ $package->id }}"  data-url="{{route('admin.subscription.packageView',[$package->id,$restaurant->id ])}}"
                                            data-target="#package_detail" id="package_detail" type="button" class="btn btn--primary text-white renew-btn package_detail">{{ translate('messages.Purchase') }}</button>
                                        @elseif ( $restaurant?->restaurant_model != 'commission'  && $restaurant?->restaurant_sub_update_application?->package_id == $package->id)
                                        <button data-id="{{ $package->id }}"  data-url="{{route('admin.subscription.packageView',[$package->id,$restaurant->id ])}}"
                                            data-target="#package_detail" id="package_detail" type="button" class="btn btn--warning text-white renew-btn package_detail">{{ translate('messages.Renew') }}</button>
                                        @else
                                        <button data-id="{{ $package->id }}" data-url="{{route('admin.subscription.packageView',[$package->id,$restaurant->id ])}}"
                                            data-target="#package_detail" id="package_detail" type="button" class="btn btn--primary shift-btn package_detail">{{ translate('messages.Shift_in_this_plan') }}</button>
                                        @endif


                                    </div>
                                </div>
                            </div>
                            @empty

                            @endforelse
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- subscription Plan Modal 2 -->
    <div class="modal fade __modal" id="subscription-renew-modal">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body px-4 pt-0">
                    <div class="data_package" id="data_package">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="product_warning">
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
                                <img src="{{dynamicAsset('/public/assets/admin/img/subscription-plan/package-status-disable.png')}}" class="mb-20">
                                <h5 class="modal-title" ></h5>
                            </div>
                            <div class="text-center">
                                <h3>{{ translate('Are_You_Sure_You_want_To_switch_to_this_plan?') }}</h3>
                                <p>{{ translate('You_are_about_to_downgrade_your_plan.After_subscribing_to_this_plan_your_oldest_') }} <span id="disable_item_count"></span> {{ translate('Items_will_be_inactivated.') }} </p>
                            </div>
                        </div>
                        <div class="btn--container justify-content-center">
                            <button  id="continue_btn" class="btn btn-outline-primary min-w-120" data-dismiss="modal" >
                                {{translate("Continue")}}
                            </button>
                            <button  class="btn btn--primary min-w-120  shift_package"  id="back_to_planes" data-dismiss="modal" >{{translate('Go_Back')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@push('script_2')
    <script>
        $('.plan-slider').owlCarousel({
            loop: false,
            margin: 0,
            responsiveClass:true,
            nav:false,
            dots:false,
            items: 3,
            startPosition: '{{ $index }}',

            responsive:{
                0: {
                    items:1.1,
                },
                375: {
                    items:1.3,
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
                }
            }
        })

        "use strict";
            $('.status_change_alert').on('click', function (event) {
            let url = $(this).data('url');
            let message = $(this).data('message');
            status_change_alert(url, message, event)
        })

        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are_you_sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: url,
                        data: {
                            id: '{{ $restaurant->id }}',
                            subscription_id:'{{ $restaurant?->restaurant_sub_update_application?->id }}',
                        },
                        beforeSend: function () {
                            $('#loading').show()
                        },
                        success: function (data) {
                            toastr.success('{{ translate('Successfully_canceled_the_subscription') }}!');
                        },
                        complete: function () {
                            $('#loading').hide();
                            location.reload();
                        }
                    });
                }
            })
        }

        $('.shift_to_commission').on('click', function (event) {
            let url = $(this).data('url');
            let message = $(this).data('message');
            shift_to_commission(url, message, event)
        })

        function shift_to_commission(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are_you_sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: url,
                        data: {
                            id: '{{ $restaurant->id }}',
                        },
                        beforeSend: function () {
                            $('#loading').show()
                        },
                        success: function (data) {
                            toastr.success('{{ translate('Successfully_Switched_To_Commission') }}!');
                        },
                        complete: function () {
                            $('#loading').hide();
                            location.reload();
                        }
                    });
                }
            })
        }

        $(document).on('click', '.package_detail', function () {
            var url = $(this).attr('data-url');
            $.ajax({
                url: url,
                method: 'get',
                beforeSend: function() {
                            $('#loading').show();
                            $('#plan-modal').modal('hide')
                            },
                success: function(data){
                    $('#data_package').html(data.view);
                    if(data.disable_item_count !== null && data.disable_item_count > 0){
                        $('#product_warning').modal('show')
                        $('#disable_item_count').text(data.disable_item_count)
                    } else{
                        $('#subscription-renew-modal').modal('show')
                    }
                },
                complete: function() {
                        $('#loading').hide();
                    },

            });
        });
        $(document).on('click', '#continue_btn', function () {
            $('#subscription-renew-modal').modal('show')
        });

        $(document).on('click', '#back_to_planes', function () {
            $('#plan-modal').modal('show')
        });

        $("#comission_status").on('change', function(){
                if($("#comission_status").is(':checked')){
                    $('#comission').removeAttr('readonly');
                } else {
                    $('#comission').attr('readonly', true);
                    $('#comission').val('0');
                }
            });
    </script>
@endpush
