@extends('layouts.admin.app')

@section('title',translate('messages.subscription_preview'))

@section('content')
@php
    $reasons=\App\Models\OrderCancelReason::where('status', 1)->where('user_type' ,'admin' )->get();
@endphp
    <div class="content container-fluid">
@php
    $order = $subscription->order;
    $address = json_decode($order->delivery_address, true);
@endphp
        <div class="row">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="card">
                    <div class="card-header pb-1">
                        <div class="w-100">
                            <!-- Page Header -->
                            <div class="d-print-none pb-2">
                                <div class="page-header">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center __gap-15px">
                                        <h1 class="page-header-title">
                                            <img src="{{dynamicAsset('public/assets/admin/img/orders.png')}}" class="mr-1" alt=""> {{translate('messages.subscription_order_details')}}
                                        </h1>
                                    </div>
                                </div>
                                <div class="d-sm-flex align-items-sm-center">
                                        <h1 class="page-header-title">{{translate('messages.subscription_id_#')}} <a href="{{route('admin.order.details',['id'=>$subscription->order->id])}}">{{$subscription->order->id}}</a></h1>
                                        <span class="badge badge-primary ml-sm-3 p-1">
                                            {{ translate('messages.'.$subscription->type) }}
                                        </span>
                                    @if (in_array($subscription->status, ['paused', 'canceled']))
                                        <span class="badge badge-{{$subscription->status=='canceled'?'danger':'warning'}} ml-sm-3 p-1">
                                            {{ translate('messages.'.$subscription->status) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    {{translate('messages.placed_on')}}
                                    <strong>
                                        {{  \App\CentralLogics\Helpers::date_format($subscription->order->created_at) }}
                                    </strong>


                                </div>
                                <div class="d-flex flex-wrap g-1 justify-content-between mt-3">
                                    <div class="d-flex gap-1 align-items-center">
                                        <img src="{{dynamicAsset('public/assets/admin/img/store.png')}}"  width="22" alt=""> <strong>{{ translate('Restaurant') }} :</strong> <a href="{{route('admin.restaurant.view', $subscription->restaurant_id)}}" class="text-primary font-semibold">{{ $subscription->restaurant->name }}</a>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <span> {{ translate('Payment_Method') }}   :</span>
                                        <strong>{{ translate($subscription->order->payment_method)}}</strong>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap flex-sm-nowrap  g-1 gap-2 justify-content-between mt-3 align-items-center">
                                <div>

                                    @if ($subscription->order->order_note)
                                    <div class="d-flex gap-2">
                                        <strong>{{ translate('Note') }} : </strong>
                                        <span>{{ $subscription->order->order_note }}</span>
                                    </div>
                                    @endif
                                    @if ($subscription->note)
                                    <div class="d-flex gap-2">
                                        <strong>{{ translate('Subscription_Note') }} : </strong>
                                        <span>{{ $subscription->note }}</span>
                                    </div>
                                    @endif
                                </div>
                                    <a href="#" class="btn btn-outline-primary font-semibold map-view-button text-nowrap px-2 rounded" data-toggle="modal" data-target="#locationModal">
                                        <img src="{{dynamicAsset('public/assets/admin/img/mapview.png')}}" class="rounded px-1" alt="">{{ translate('Open_Map_View') }}
                                    </a>
                                </div>

                            </div>
                            <div class="page-header pt-3 pt-sm-2">
                                <!-- Nav Scroller -->
                                <div class="js-nav-scroller hs-nav-scroller-horizontal">
                                    <span class="hs-nav-scroller-arrow-prev d-none">
                                        <a class="hs-nav-scroller-arrow-link" href="javascript:">
                                            <i class="tio-chevron-left"></i>
                                        </a>
                                    </span>

                                    <span class="hs-nav-scroller-arrow-next d-none">
                                        <a class="hs-nav-scroller-arrow-link" href="javascript:">
                                            <i class="tio-chevron-right"></i>
                                        </a>
                                    </span>
                                <div class="subscription-tabs-group">
                                    <!-- Nav -->
                                        <ul class="nav nav-tabs page-header-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link {{$tab=='info'?'active':''}}" href="{{route('admin.order.subscription.show', ['subscription'=>$subscription->id])}}">{{translate('messages.details') }}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{$tab=='delivery-log'?'active':''}}" href="{{route('admin.order.subscription.show', ['subscription'=>$subscription->id])}}?tab=delivery-log"  aria-disabled="true">{{translate('messages.delivery_log')}}
                                                    <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('See_all_completed_subscription_deliveries_of_this_order_ID.')}}" class="input-label-secondary"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="i"></span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{$tab=='pause-log'?'active':''}}" href="{{route('admin.order.subscription.show', ['subscription'=>$subscription->id])}}?tab=pause-log"  aria-disabled="true">{{translate('messages.pause_log')}}  <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('See_all_paused_subscription_deliveries_of_this_order_ID_and_who_paused_it.')}}" class="input-label-secondary"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="i"></span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- End Nav -->
                                </div>
                                <!-- End Nav Scroller -->
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include("admin-views.order-subscription.partials._{$tab}")
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-2">
                    <div class="card-header border-0 justify-content-center pt-4 pb-0">
                        <h4 class="card-header-title">{{translate('messages.subscription_setup')}}</h4>
                    </div>
                    <div class="card-body">
                        <label class="form-label">{{translate('change_subscription_status')}}</label>
                        <div>
                            <div class="dropdown">
                                <button class="form-control h--45px dropdown-toggle d-flex justify-content-between align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{translate("messages.{$subscription->status}")}}</button>
                                <div class="dropdown-menu text-capitalize w-100" aria-labelledby="dropdownMenuButton">
                                    <button class="dropdown-item {{$subscription->status=='canceled'?'update-subscription-status':''}} {{$subscription->status=='active' ? 'active' : ''}}" type="button" @if($subscription->status=='canceled') data-status="active" @else disabled @endif>{{translate('messages.Active')}}</button>
                                    <button class="dropdown-item {{$subscription->status=='active'?'update-subscription-status':''}} {{$subscription->status=='canceled' ? 'active' : ''}}" type="button" @if($subscription->status=='active') data-status="canceled" @else disabled @endif>{{translate('messages.cancel')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                $logs = $subscription->pause()->latest()->get();
                @endphp
                @if ( ($subscription->status != 'active' && count($logs) > 0) || $subscription->status == 'active')

                <div class="card mb-2">
                    <div class="card-header border-0 justify-content-center pt-4 pb-0">
                        <h4 class="card-header-title">{{translate('messages.pause_for_specific_days')}}</h4>
                    </div>
                    <div class="card-body">
                        @if ($subscription->status != 'expired' && $subscription->status != 'canceled')

                        <form id="subs_pause" action="{{route('admin.order.subscription.update',['subscription'=>$subscription->id])}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="date--input">
                                <input type="hidden" name="status" value="paused" >
                                <input type="hidden"  id="startDate" name="start_date" value="" >
                                <input type="hidden" id="endDate" name="end_date" value="" >

                                <input type="text" required name="date" readonly placeholder="dd/mm/yyyy - dd/mm/yyyy" id="swal-input2" class="form-control swal-input2  h-45px">


                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.8337 3.33366H14.167V2.50033C14.167 2.27931 14.0792 2.06735 13.9229 1.91107C13.7666 1.75479 13.5547 1.66699 13.3337 1.66699C13.1126 1.66699 12.9007 1.75479 12.7444 1.91107C12.5881 2.06735 12.5003 2.27931 12.5003 2.50033V3.33366H7.50033V2.50033C7.50033 2.27931 7.41253 2.06735 7.25625 1.91107C7.09997 1.75479 6.88801 1.66699 6.66699 1.66699C6.44598 1.66699 6.23402 1.75479 6.07774 1.91107C5.92146 2.06735 5.83366 2.27931 5.83366 2.50033V3.33366H4.16699C3.50395 3.33366 2.86807 3.59705 2.39923 4.06589C1.93038 4.53473 1.66699 5.17062 1.66699 5.83366V15.8337C1.66699 16.4967 1.93038 17.1326 2.39923 17.6014C2.86807 18.0703 3.50395 18.3337 4.16699 18.3337H15.8337C16.4967 18.3337 17.1326 18.0703 17.6014 17.6014C18.0703 17.1326 18.3337 16.4967 18.3337 15.8337V5.83366C18.3337 5.17062 18.0703 4.53473 17.6014 4.06589C17.1326 3.59705 16.4967 3.33366 15.8337 3.33366ZM16.667 15.8337C16.667 16.0547 16.5792 16.2666 16.4229 16.4229C16.2666 16.5792 16.0547 16.667 15.8337 16.667H4.16699C3.94598 16.667 3.73402 16.5792 3.57774 16.4229C3.42146 16.2666 3.33366 16.0547 3.33366 15.8337V10.0003H16.667V15.8337ZM16.667 8.33366H3.33366V5.83366C3.33366 5.61264 3.42146 5.40068 3.57774 5.2444C3.73402 5.08812 3.94598 5.00033 4.16699 5.00033H5.83366V5.83366C5.83366 6.05467 5.92146 6.26663 6.07774 6.42291C6.23402 6.57919 6.44598 6.66699 6.66699 6.66699C6.88801 6.66699 7.09997 6.57919 7.25625 6.42291C7.41253 6.26663 7.50033 6.05467 7.50033 5.83366V5.00033H12.5003V5.83366C12.5003 6.05467 12.5881 6.26663 12.7444 6.42291C12.9007 6.57919 13.1126 6.66699 13.3337 6.66699C13.5547 6.66699 13.7666 6.57919 13.9229 6.42291C14.0792 6.26663 14.167 6.05467 14.167 5.83366V5.00033H15.8337C16.0547 5.00033 16.2666 5.08812 16.4229 5.2444C16.5792 5.40068 16.667 5.61264 16.667 5.83366V8.33366Z" fill="#334257" fill-opacity="0.75"/>
                                </svg>
                            </div>
                            <button class="btn date-submit-btn w-100 mt-3" type="submit">{{ translate('Submit') }}</button>
                        </form>
                        @endif



                            @if (count($logs) > 0)
                                <div class="mt-4 mb-2">{{ translate('Paused_date') }}</div>
                                    <div class="resume-dates {{  count($logs) > 3 ? 'h-200px' : 'h-auto' }} overflow-hidden scroll-bar">
                                    @foreach($logs as $key=>$log)
                                    <div class="d-flex align-items-center mb-2 gap-2">
                                        <div class="btn cursor-default text-danger bg-soft-danger flex-grow-1 w-0">{{  \App\CentralLogics\Helpers::date_format($log->from) }} - {{  \App\CentralLogics\Helpers::date_format($log->to) }}</div>
                                        @php
                                        $current_date = date('Y-m-d');
                                        $from = Carbon\Carbon::parse($log->from);
                                    @endphp
                                        @if ( $from->gt($current_date) && ($subscription->status != 'expired' && $subscription->status != 'canceled'))
                                            <a class="btn py-2 text--primary form-alert" href="javascript:"
                                            data-id="role-{{$log['id']}}" data-message="{{translate('messages.Want_to_Resume_the_subscription_?')}}" title="{{translate('messages.Resume')}}">
                                            {{ translate('Resume') }}
                                            </a>
                                            <form action="{{route('admin.order.subscription.pause_log_delete',[$log['id']])}}"
                                            method="post" id="role-{{$log['id']}}">
                                        @csrf @method('delete')
                                        </form>
                                        @else
                                        <button class="btn py-2" title="{{ translate('resume_period_is_over')}} "  disabled="disabled">{{ translate('Resume') }}</button>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            @endif

                    </div>
                </div>
                @endif

                <div class="row g-1">
                    <div class="col-12">
                        <!-- Customer Card -->
                        <div class="card">
                            <div class="card-body pt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">
                                        <span class="card-header-icon">
                                            <i class="tio-user"></i>
                                        </span>
                                        <span>{{ translate('Delivery_info') }}</span>
                                    </h5>
                                    <a class="link" data-toggle="modal" data-target="#shipping-address-modal" href="javascript:"><i
                                            class="tio-edit"></i></a>
                                </div>
                                <span class="delivery--information-single mt-3">
                                    <span class="name">{{ translate('Name') }}</span>
                                    <span class="info">{{ data_get($address,'contact_person_name') ?? $subscription?->customer?->f_name.' '.$subscription?->customer?->l_name  }}</span>
                                    <span class="name">{{ translate('Contact') }}</span>
                                    <a class="deco-none info" href="tel:+8801677696277">
                                        <i class="tio-call-talking-quiet"></i>
                                        {{ data_get($address,'contact_person_number') ?? $subscription?->customer?->phone  }}</a>
                                        @if (data_get($address,'road'))
                                    <span class="name">{{ translate('Road') }} #</span>
                                    <span class="info">{{ data_get($address,'road') }}</span>
                                    @endif

                                    @if (data_get($address,'house'))
                                    <span class="name">{{ translate('House') }} #</span>
                                    <span class="info">
                                        {{ data_get($address,'house') }}
                                    </span>
                                    @endif
                                    @if (data_get($address,'floor'))
                                    <span class="name">{{ translate('Floor') }}</span>
                                    <span class="info">{{ data_get($address,'floor') }}</span>
                                    @endif

                                    @if ( data_get($address,'address') )
                                    @if (data_get($address,'latitude')  && data_get($address,'longitude'))
                                        <div class="mt-2 d-flex w-100">
                                            <a target="_blank"
                                                href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $address['latitude'] }}+{{ $address['longitude'] }}">
                                                <span><i class="tio-poi text--title"></i></span>
                                                <span class="info pl-2">{{ $address['address'] }}</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="mt-2 d-flex w-100">
                                            <span><i class="tio-poi text--title"></i></span>
                                            <span class="info pl-2">{{ $address['address'] }}</span>
                                        </div>
                                    @endif
                                @endif
                                </span>
                            </div>
                            <!-- End Body -->
                        </div>
                    </div>
                    <div class="col-12">
                        <!-- Customer Card -->
                        <div class="card">
                            <div class="card-body pt-3">
                                <!-- Header -->
                                <h5 class="card-title mb-3">
                                    <span class="card-header-icon">
                                        <i class="tio-user"></i>
                                    </span>
                                    <span>{{ translate('Customer_info') }}</span>
                                </h5>
                                <!-- End Header -->
                                <a class="media align-items-center deco-none customer--information-single"
                                    href="{{ route('admin.customer.view', [$subscription?->customer['id']]) }}">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img onerror-image"
                                        src="{{ $subscription?->customer?->image_full_url ?? dynamicAsset('public/assets/admin/img/160x160/img1.png') }}"
                                            alt="Image Description"
                                            data-onerror-image="{{ dynamicAsset('public/assets/admin/img/160x160/img1.png') }}">

                                    </div>
                                    <div class="media-body">
                                        <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                            {{ $subscription?->customer?->f_name.' '.$subscription?->customer?->l_name }}
                                        </span>
                                        <span>
                                            <strong class="text--title font-semibold">{{  $subscription?->customer?->order_count }}</strong>
                                            {{ translate('Completed_Orders') }}
                                        </span>
                                        <span class="text--title font-semibold d-block">
                                            <i class="tio-call-talking-quiet"></i> {{  $subscription?->customer?->phone }}
                                        </span>
                                        <span class="text--title">
                                            <i class="tio-email"></i> {{  $subscription?->customer?->email }}
                                        </span>
                                    </div>

                                </a>
                            </div>
                            <!-- End Body -->
                        </div>
                    </div>
                        <div class="col-12">
                            <div class="card">
                                <!-- Body -->
                                <div class="card-body">
                                    <!-- Header -->
                                    <h5 class="card-title mb-3">
                                        <span class="card-header-icon">
                                            <i class="tio-shop"></i>
                                        </span>
                                        <span>{{ translate('Restaurant_info') }}</span>
                                    </h5>
                                    <!-- End Header -->
                                    <a class="media align-items-center deco-none resturant--information-single" href="{{route('admin.restaurant.view', $subscription->restaurant_id)}}">
                                        <div class="avatar avatar-circle">
                                                <img class="avatar-img w-75px" src="{{ $subscription?->restaurant?->logo_full_url ?? dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}" alt="image">

                                        </div>
                                        <div class="media-body">
                                            <span class="text-body text-hover-primary text-break"></span>
                                            <span></span>


                                            <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                               {{ $subscription?->restaurant?->name }}
                                            </span>
                                            <span>
                                                <strong class="text--title font-semibold">
                                                    {{ $subscription?->restaurant?->total_order }}
                                                </strong>
                                                {{ translate('Orders_served') }}
                                            </span>
                                                <span class="text--title fs-12 font-semibold d-block">
                                                     <i class="tio-call-talking-quiet"></i>  {{ $subscription?->restaurant?->phone }}
                                                </span>
                                            <span class="text--title">
                                                <i class="tio-poi"></i> {{ $subscription?->restaurant?->address }}
                                            </span>
                                        </div>
                                    </a>
                                </div>

                            </div>

                    </div>
                </div>



            </div>
        </div>
        <!-- End Row -->
    </div>

    @php
        $subscription_pause_log_overlap= session('subscription_pause_log_overlap') ?? null;
    @endphp
@if (isset($subscription_pause_log_overlap))

<div id="subscription_pause_log_overlap" class="alert alert-warning pause-overlap-warning">
    <div class="d-flex gap-3 align-items-center">
    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path opacity="0.2" d="M20.5563 4H11.4437C11.3123 4 11.1823 4.02587 11.061 4.07612C10.9396 4.12638 10.8294 4.20004 10.7365 4.29289L4.29289 10.7365C4.20004 10.8294 4.12638 10.9396 4.07612 11.061C4.02587 11.1823 4 11.3123 4 11.4437V20.5563C4 20.6877 4.02587 20.8177 4.07612 20.939C4.12638 21.0604 4.20004 21.1706 4.29289 21.2635L10.7365 27.7071C10.8294 27.8 10.9396 27.8736 11.061 27.9239C11.1823 27.9741 11.3123 28 11.4437 28H20.5563C20.6877 28 20.8177 27.9741 20.939 27.9239C21.0604 27.8736 21.1706 27.8 21.2635 27.7071L27.7071 21.2635C27.8 21.1706 27.8736 21.0604 27.9239 20.939C27.9741 20.8177 28 20.6877 28 20.5563V11.4437C28 11.3123 27.9741 11.1823 27.9239 11.061C27.8736 10.9396 27.8 10.8294 27.7071 10.7365L21.2635 4.29289C21.1706 4.20004 21.0604 4.12638 20.939 4.07612C20.8177 4.02587 20.6877 4 20.5563 4Z" fill="#E64545"/>
        <path d="M16 10V17" stroke="#E64545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M20.5563 4H11.4437C11.3123 4 11.1823 4.02587 11.061 4.07612C10.9396 4.12638 10.8294 4.20004 10.7365 4.29289L4.29289 10.7365C4.20004 10.8294 4.12638 10.9396 4.07612 11.061C4.02587 11.1823 4 11.3123 4 11.4437V20.5563C4 20.6877 4.02587 20.8177 4.07612 20.939C4.12638 21.0604 4.20004 21.1706 4.29289 21.2635L10.7365 27.7071C10.8294 27.8 10.9396 27.8736 11.061 27.9239C11.1823 27.9741 11.3123 28 11.4437 28H20.5563C20.6877 28 20.8177 27.9741 20.939 27.9239C21.0604 27.8736 21.1706 27.8 21.2635 27.7071L27.7071 21.2635C27.8 21.1706 27.8736 21.0604 27.9239 20.939C27.9741 20.8177 28 20.6877 28 20.5563V11.4437C28 11.3123 27.9741 11.1823 27.9239 11.061C27.8736 10.9396 27.8 10.8294 27.7071 10.7365L21.2635 4.29289C21.1706 4.20004 21.0604 4.12638 20.939 4.07612C20.8177 4.02587 20.6877 4 20.5563 4Z" stroke="#E64545" stroke-width="2" stroke-miterlimit="10"/>
        <path d="M16 23C16.8284 23 17.5 22.3284 17.5 21.5C17.5 20.6716 16.8284 20 16 20C15.1716 20 14.5 20.6716 14.5 21.5C14.5 22.3284 15.1716 23 16 23Z" fill="#E64545"/>
    </svg>
        <div class="w-0 flex-grow-1">
            <h4 class="m-0">{{ translate('Subscription_Pause_Overlap') }}</h4>
            <div>
                {{ translate('Please_choose_a_different_pause_date') }}
            </div>
        </div>
    </div>
</div>
@endif


        <!--Show locations on map Modal -->
        <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="locationModalLabel">{{ translate('messages.location_data') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 modal_body_map">
                                <div class="location-map" id="location-map">
                                    <div id="location_map_canvas"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->

            <!-- Modal -->
    <div id="shipping-address-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalTopCoverTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-top-cover bg-dark text-center">
                    <figure class="position-absolute right-0 bottom-0 left-0 mb-n-1">
                        <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                            viewBox="0 0 1920 100.1">
                            <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z" />
                        </svg>
                    </figure>

                    <div class="modal-close">
                        <button type="button" class="btn btn-icon btn-sm btn-ghost-light" data-dismiss="modal"
                            aria-label="Close">
                            <svg width="16" height="16" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor"
                                    d="M11.5,9.5l5-5c0.2-0.2,0.2-0.6-0.1-0.9l-1-1c-0.3-0.3-0.7-0.3-0.9-0.1l-5,5l-5-5C4.3,2.3,3.9,2.4,3.6,2.6l-1,1 C2.4,3.9,2.3,4.3,2.5,4.5l5,5l-5,5c-0.2,0.2-0.2,0.6,0.1,0.9l1,1c0.3,0.3,0.7,0.3,0.9,0.1l5-5l5,5c0.2,0.2,0.6,0.2,0.9-0.1l1-1 c0.3-0.3,0.3-0.7,0.1-0.9L11.5,9.5z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- End Header -->

                <div class="modal-top-cover-icon">
                    <span class="icon icon-lg icon-light icon-circle icon-centered shadow-soft">
                        <i class="tio-location-search"></i>
                    </span>
                </div>

                @if (isset($address))
                    <form action="{{ route('admin.order.update-shipping', [$order['id']]) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.type') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address_type"
                                        value="{{ isset($address['address_type']) ? $address['address_type'] : '' }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.contact') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_number"
                                        value="{{ $address['contact_person_number'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.name') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_name"
                                        value="{{ $address['contact_person_name'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('House') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="house"
                                        value="{{ isset($address['house']) ? $address['house'] : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('Floor') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="floor"
                                        value="{{ isset($address['floor']) ? $address['floor'] : '' }}" >
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('Road') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="road"
                                        value="{{ isset($address['road']) ? $address['road'] : '' }}" >
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('address') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="{{ $address['address'] }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('latitude') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="latitude" id="latitude"
                                        value="{{ $address['latitude'] }}">
                                </div>
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.longitude') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="longitude" id="longitude"
                                        value="{{ $address['longitude'] }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <input id="pac-input" class="controls rounded initial-8"
                                title="{{ translate('messages.search_your_location_here') }}" type="text"
                                placeholder="{{ translate('messages.search_here') }}" />
                            <div class="mb-2 h-200px" id="map"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white"
                                data-dismiss="modal">{{ translate('messages.close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ translate('messages.save_changes') }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

<!-- End Modal -->
@endsection
@push('script_2')
    <script
            src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places&callback=initMap&v=3.45.8">
    </script>
    <script>
        "use strict";

            $('#shipping-address-modal').on('shown.bs.modal', function() {
                initMap();
            });
            $('#locationModal').on('shown.bs.modal', function(event) {
                initializegLocationMap();
            });
            let map = null;

            let myLatlng = new google.maps.LatLng({{ isset($order->restaurant) ? $order->restaurant->latitude : 0 }},
                {{ isset($order->restaurant) ? $order->restaurant->longitude : 0 }});
            let dmbounds = new google.maps.LatLngBounds(null);
            let locationbounds = new google.maps.LatLngBounds(null);
            let dmMarkers = [];
            dmbounds.extend(myLatlng);
            locationbounds.extend(myLatlng);
            let myOptions = {
                center: myLatlng,
                zoom: 13,
                mapTypeId: google.maps.MapTypeId.ROADMAP,

                panControl: true,
                mapTypeControl: false,
                panControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE,
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                scaleControl: false,
                streetViewControl: false,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                }
            };
            function initializegLocationMap() {
                map = new google.maps.Map(document.getElementById("location_map_canvas"), myOptions);

                let infowindow = new google.maps.InfoWindow();
                @if (isset($address) && isset($address['latitude']) && isset($address['longitude']) )
                    let marker = new google.maps.Marker({
                        position: new google.maps.LatLng({{ $address['latitude'] }},
                            {{ $address['longitude'] }}),
                        map: map,
                        title: "{{ $order->customer ? $order->customer->f_name .' '. $order->customer->l_name : $address['contact_person_name'] }}",
                        icon: "{{ dynamicAsset('public/assets/admin/img/customer_location.png') }}"
                    });

                    google.maps.event.addListener(marker, 'click', (function(marker) {
                        return function() {
                            infowindow.setContent(
                                `<div class='float--left'><img class='js--design-1' onerror="this.src='{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}'"  src='{{ $order->customer ? dynamicStorage('storage/app/public/profile/' . $order?->customer?->image) : dynamicAsset('public/assets/admin/img/160x160/img3.png') }}'></div><div class='float--right p--10px'><b>{{ $order->customer ? $order->customer->f_name .' '. $order->customer->l_name : $address['contact_person_name'] }}</b><br/>{{ $address['address'] }}</div>`
                            );
                            infowindow.open(map, marker);
                        }
                    })(marker));
                    locationbounds.extend(marker.getPosition());
                @endif
                @if ($order->delivery_man && $order->dm_last_location)
                    let dmmarker = new google.maps.Marker({
                        position: new google.maps.LatLng({{ $order->dm_last_location['latitude'] }},
                            {{ $order->dm_last_location['longitude'] }}),
                        map: map,
                        title: "{{ $order->delivery_man->f_name }}  {{ $order->delivery_man->l_name }}",
                        icon: "{{ dynamicAsset('public/assets/admin/img/delivery_boy_map_2.png') }}"
                    });

                    google.maps.event.addListener(dmmarker, 'click', (function(dmmarker) {
                        return function() {
                            infowindow.setContent(
                                `<div class='float--left'><img class='js--design-1 mt-2' onerror="this.src='{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}'"  src='{{ dynamicStorage('storage/app/public/delivery-man/' . $order->delivery_man->image) }}'></div><div class='float--right p--10px'><b>{{ $order->delivery_man->f_name }}  {{ $order->delivery_man->l_name }}</b><br/>  <div> {{ translate('messages.Active_Orders') }} : {{ $order->delivery_man->current_orders }} </div> </b> {{ $order->dm_last_location['location'] }}</div>`
                            );
                            infowindow.open(map, dmmarker);
                        }
                    })(dmmarker));
                    locationbounds.extend(dmmarker.getPosition());
                @endif

                @if ($order->restaurant)
                    let Retaurantmarker = new google.maps.Marker({
                        position: new google.maps.LatLng({{ $order->restaurant->latitude }},
                            {{ $order->restaurant->longitude }}),
                        map: map,
                        title: "{{ Str::limit($order->restaurant->name, 15, '...') }}",
                        icon: "{{ dynamicAsset('public/assets/admin/img/restaurant_map_1.png') }}"
                    });

                    google.maps.event.addListener(Retaurantmarker, 'click', (function(Retaurantmarker) {
                        return function() {
                            infowindow.setContent(
                                `<div class='float--left'><img class='js--design-1' onerror="this.src='{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}'"  src='{{ dynamicStorage('storage/app/public/restaurant/' . $order->restaurant->logo) }}'></div><div class='float--right p--10px'><b>{{ Str::limit($order->restaurant->name, 15, '...') }}</b><br/> {{ $order->restaurant->address }}</div>`
                            );
                            infowindow.open(map, Retaurantmarker);
                        }
                    })(Retaurantmarker));
                    locationbounds.extend(Retaurantmarker.getPosition());
                @endif

                google.maps.event.addListenerOnce(map, 'idle', function() {
                    map.fitBounds(locationbounds);
                });
            }

            function initMap() {
                let zonePolygon = null;
                //get current location block
                let infoWindow = new google.maps.InfoWindow();
                // Try HTML5 geolocation.
                let map = new google.maps.Map(document.getElementById("map"), myOptions);

                @if (isset($address) && isset($address['latitude']) && isset($address['longitude']) )
                let marker = new google.maps.Marker({
                    position: new google.maps.LatLng({{ $address['latitude'] }},
                        {{ $address['longitude'] }}),
                    map: map,
                    title: "{{ $order->customer ? $order->customer->f_name .' '. $order->customer->l_name : $address['contact_person_name'] }}",
                    icon: "{{ dynamicAsset('public/assets/admin/img/customer_location.png') }}"
                });

                google.maps.event.addListener(marker, 'click', (function(marker) {
                    return function() {
                        infowindow.setContent(
                            `<div class='float--left'><img class='js--design-1' onerror="this.src='{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}'"  src='{{ $order->customer ? dynamicStorage('storage/app/public/profile/' . $order?->customer?->image) : dynamicAsset('public/assets/admin/img/160x160/img3.png') }}'></div><div class='float--right p--10px'><b>{{ $order->customer ? $order->customer->f_name .' '. $order->customer->l_name : $address['contact_person_name'] }}</b><br/>{{ $address['address'] }}</div>`
                        );
                        infowindow.open(map, marker);
                    }
                })(marker));
                locationbounds.extend(marker.getPosition());
                @endif
                //-----end block------
                const input = document.getElementById("pac-input");
                const searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
                let markers = [];
                const bounds = new google.maps.LatLngBounds();
                searchBox.addListener("places_changed", () => {
                    const places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }
                    // Clear out the old markers.
                    markers.forEach((marker) => {
                        marker.setMap(null);
                    });
                    markers = [];
                    // For each place, get the icon, name and location.
                    places.forEach((place) => {
                        if (!place.geometry || !place.geometry.location) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        console.log(place.geometry.location);
                        if(!google.maps.geometry.poly.containsLocation(
                            place.geometry.location,
                            zonePolygon
                        )){
                            toastr.error('{{ translate('messages.out_of_coverage') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            return false;
                        }

                        document.getElementById('latitude').value = place.geometry.location.lat();
                        document.getElementById('longitude').value = place.geometry.location.lng();


                        const icon = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25),
                        };
                        // Create a marker for each place.
                        markers.push(
                            new google.maps.Marker({
                                map,
                                icon,
                                title: place.name,
                                position: place.geometry.location,
                            })
                        );

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });
                @if ($order->restaurant)
                    $.get({
                        url: '{{ url('/') }}/admin/zone/get-coordinates/{{ $order->restaurant->zone_id }}',
                        dataType: 'json',
                        success: function(data) {
                            zonePolygon = new google.maps.Polygon({
                                paths: data.coordinates,
                                strokeColor: "#FF0000",
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: 'white',
                                fillOpacity: 0,
                            });
                            zonePolygon.setMap(map);
                            zonePolygon.getPaths().forEach(function(path) {
                                path.forEach(function(latlng) {
                                    bounds.extend(latlng);
                                    map.fitBounds(bounds);
                                });
                            });
                            map.setCenter(data.center);
                            google.maps.event.addListener(zonePolygon, 'click', function(mapsMouseEvent) {
                                infoWindow.close();
                                // Create a new InfoWindow.
                                infoWindow = new google.maps.InfoWindow({
                                    position: mapsMouseEvent.latLng,
                                    content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null,
                                        2),
                                });
                                let coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                                coordinates = JSON.parse(coordinates);

                                document.getElementById('latitude').value = coordinates['lat'];
                                document.getElementById('longitude').value = coordinates['lng'];
                                infoWindow.open(map);
                            });
                        },
                    });
                @endif

            }
        initMap();

        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
        $('.update-subscription-status').on('click',function (){
            let status = $(this).data('status');
            update_subscription_status(status);
        })
        function update_subscription_status(status)
        {
            if(status == 'canceled'){
                Swal.fire( {
                    title: "{{translate('messages.please_select_reason_for_cancellation')}}",
                    html:`
                    <select class="form-control js-select2-custom mx-1 swal2-input"  name="reason" id="reason">
                    <option value="">
                            {{  translate('select_cancellation_reason') }}
                        </option>
                    @foreach ($reasons as $r)
                        <option value="{{ $r->reason }}">
                            {{ $r->reason }}
                        </option>
                    @endforeach
                    </select>
                    <textarea name="note" id="note" class="swal2-input form-control  text-center" placeholder="{{ translate('Add_a_note') }}"></textarea>
                    `,
                    confirmButtonText: "{{translate('messages.Submit')}}",

                    preConfirm: () => {
                        if(document.getElementById('reason').value == "" ){
                            Swal.showValidationMessage(`{{translate('messages.please_select_a_cencellation_reason')}}`)
                        }
                    }
                }).then((result) => {
                    console.log(result, result.value);
                    if (result.value) {

                        $(`<form action="{{route('admin.order.subscription.update',['subscription'=>$subscription->id])}}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="` + status + `" >
                        <input type="hidden" name="reason" value="` + document.getElementById('reason').value + `" >
                        <input type="hidden" name="note" value="` + document.getElementById('note').value + `" >
                        </form>`).appendTo('body').submit();
                    }
                })

            }

            else {
                Swal.fire({
                    title: "{{translate('messages.are_you_sure?')}}",
                    text: status=='active' ? "{{translate('you_want_to_active_this_subscription_?')}}" : "{{translate('you_want_to_cancel_this_subscription_?')}}" ,
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: '{{translate('messages.no')}}',
                    confirmButtonText: '{{translate('messages.Yes')}}',
                    reverseButtons: true
                }).then((result) => {
                    console.log(result, result.value);
                    if (result.value) {
                        $(`<form action="{{route('admin.order.subscription.update',['subscription'=>$subscription->id])}}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="` + status + `" >
                        </form>`).appendTo('body').submit();
                    }
                })
            }

        }

        $('#swal-input2').daterangepicker({
            minDate: new Date(),
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'

            }
        });

        $('#swal-input2').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD MMM YYYY') + ' - ' + picker.endDate.format('DD MMM YYYY'));

        document.getElementById('startDate').value = picker.startDate.format('YYYY-MM-DD');
        document.getElementById('endDate').value = picker.endDate.format('YYYY-MM-DD');
        });

        $('#swal-input2').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('#subs_pause').on('submit', function (e) {
            if(document.getElementById('swal-input2').value == '' ){
             e.preventDefault();
            toastr.error('{{ translate('messages.Select_pause_date_range') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
         }
        });
        @if (isset($subscription_pause_log_overlap))
        setTimeout(close_popup, 5000);
        @endif
        function close_popup() {
            $('#subscription_pause_log_overlap').addClass('d-none');
        }


    </script>
@endpush
