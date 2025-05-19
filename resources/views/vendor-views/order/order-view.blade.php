@php
$max_processing_time = explode('-', $order['restaurant']['delivery_time'])[0];
$c_address = null ;
if($order->delivery_address){
    $c_address = json_decode($order->delivery_address, true);
}
@endphp


    @if($order->scheduled == 1)
        @section('scheduled')
    @elseif( in_array($order->order_status, ['confirmed','accepted']) )
        @section('confirmed')
    @else
        @section($order->order_status)
    @endif

    active
    @endsection

@extends('layouts.vendor.app')

@section('title', translate('messages.Order Details'))

@section('content')
    <?php $campaign_order = isset($order->details[0]->campaign) ? true : false;
    $reasons=\App\Models\OrderCancelReason::where('status', 1)->where('user_type' ,'restaurant' )->get();
    $subscription = isset($order->subscription_id) ? true : false;
    $tax_included =0;
    $restaurant =\App\CentralLogics\Helpers::get_restaurant_data();
      ?>

    <div class="content container-fluid item-box-page">

    <div class="page-header d-print-none">

            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{dynamicAsset('public/assets/admin/img/orders.png')}}" alt="public">
                </div>
                <span>
                    {{ translate('messages.Order Details') }}
                </span>
                <div class="d-flex ml-auto">
                    <a class="btn btn-icon btn-sm btn-soft-primary rounded-circle mr-1"
                        href="{{ route('vendor.order.details', [$order['id'] - 1]) }}" data-toggle="tooltip"
                        data-placement="top" title="{{ translate('Previous order') }}">
                        <i class="tio-chevron-left m-0"></i>
                    </a>
                    <a class="btn btn-icon btn-sm btn-soft-primary rounded-circle"
                        href="{{ route('vendor.order.details', [$order['id'] + 1]) }}" data-toggle="tooltip"
                        data-placement="top" title="{{ translate('Next order') }}">
                        <i class="tio-chevron-right m-0"></i>
                    </a>
                </div>
            </h1>
        </div>


        <div class="row g-1" id="printableArea">
            <div class="col-lg-8 order-print-area-left">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-header border-0 align-items-start flex-wrap">
                        <div class="order-invoice-left">
                            <h1 class="page-header-title mt-2">
                                <span>
                                    {{ translate('messages.order') }} #{{ $order['id'] }}
                                </span>

                                @if ($order->edited)
                                    <span class="badge badge-soft-danger text-capitalize px-2 ml-2">
                                        {{ translate('messages.edited') }}
                                    </span>
                                @endif
                                <a class="btn btn--primary m-2 print--btn d-sm-none ml-auto" href="{{ route('vendor.order.generate-invoice', [$order['id']]) }}">
                                    <i class="tio-print mr-1"></i>
                                </a>
                            </h1>
                            {{-- <span class="mt-2 d-block">
                                <i class="tio-date-range"></i>
                                {{ date('d M Y ' . config('timeformat'), strtotime($order['created_at'])) }}
                            </span> --}}

                            <span class="mt-2 d-block text--title">
                                {{ translate('messages.Placed_Date') }} :
                                <span class="font-semibold">
                                    {{ date('d M Y ' . config('timeformat'), strtotime($order['created_at'])) }}</span>
                            </span>
                            @if ($order->order_type == 'dine_in')
                            <span class="mt-2 d-block text--title">
                                {{ translate('messages.Din-in_Date') }} :
                                <span class="font-semibold">
                                    {{ date('d M Y ' . config('timeformat'), strtotime($order['schedule_at'])) }}
                                </span>
                            </span>
                            @endif

                            @if ($subscription)
                            <span>
                                <strong class="text-primary"> {{ translate('messages.subscription_order') }}</strong>
                            </span>
                            <br>
                            @endif
                            @if ($order->schedule_at && ($order->scheduled || $subscription))
                                <span class="text-capitalize d-block mt-1">
                                    {{ translate('messages.scheduled_at') }}
                                    : <label  class="fz-10px badge badge-soft-primary">{{ date('d M Y ' . config('timeformat'), strtotime($order['schedule_at'])) }}</label>
                                </span>
                            @endif
                            @if ($campaign_order)
                            <span class="badge mt-2 badge-soft-primary">
                                {{ translate('messages.campaign_order') }}
                            </span>
                            @endif

                            @if($order['order_note'])
                            <h6>
                                {{ translate('messages.order_note') }} :
                                {{ $order['order_note'] }}
                            </h6>
                            @endif



                            @if($order?->offline_payments && $order?->offline_payments->status == 'denied' && $order?->offline_payments->note )
                            <h6 class="w-100 badge-soft-warning">
                                <span class="text-dark">
                                    {{ translate('messages.Offline_payment_rejection_note') }} :
                                </span>
                                    {{  $order?->offline_payments->note }}
                                </h6>
                            @endif

                            @if ($order['unavailable_item_note'])
                            <h6>
                                {{ translate('messages.if_item_is_not_available') }} :
                                {{ translate($order->unavailable_item_note) }}
                            </h6>
                            @endif
                            @if ($order['delivery_instruction'])
                                <h6>
                                    {{ translate('messages.order_delivery_instruction') }} :
                                    {{ translate($order->delivery_instruction)  }}
                                </h6>
                            @endif

                            @if ($c_address)
                            <div class="hs-unfold mt-2">
                                <button class="btn order--details-btn-sm btn--primary btn-outline-primary btn--sm" data-toggle="modal" data-target="#locationModal"><i
                                        class="tio-poi-outlined"></i> <span class="ml-1">{{ translate('messages.show_locations_on_map') }}</span> </button>
                            </div>
                            @endif

                        </div>
                        <div class="order-invoice-right">
                            <div class="d-none d-sm-flex flex-wrap ml-auto align-items-center justify-content-end m-n-5rem">
                                <a class="btn btn--primary m-2 print--btn" href="{{ route('vendor.order.generate-invoice', [$order['id']]) }}">
                                    <i class="tio-print mr-1"></i> {{ translate('messages.print_invoice') }}
                                </a>
                            </div>
                            <div class="text-right mt-3 order-invoice-right-contents text-capitalize">
                                    @if (isset($order->subscription))
                                            <h6>
                                                <span>{{ translate('messages.Subscription_status') }} :</span>
                                                    @if ($order->subscription->status == 'active')
                                                    <span class="badge badge-soft-success ">
                                                    <span class="legend-indicator bg-success"></span>{{translate('messages.'.$order->subscription->status)}}
                                                    </span>
                                                    @elseif ($order->subscription->status == 'paused')
                                                    <span class="badge badge-soft-primary">
                                                    <span class="legend-indicator bg-danger"></span>{{translate('messages.'.$order->subscription->status)}}
                                                    </span>
                                                    @else
                                                    <span class="badge badge-soft-primary ">
                                                    <span class="legend-indicator bg-info"></span>{{translate('messages.'.$order->subscription->status)}}
                                                    </span>
                                                    @endif
                                            </h6>
                                    @endif

                                <h6>
                                    <span>{{ translate('Status') }} :</span>

                                        @if (isset($order->subscription) && $order->subscription->status != 'canceled' )
                                                @php
                                                    $order->order_status = $order->subscription_log ? $order->subscription_log->order_status : $order->order_status;
                                                @endphp
                                        @endif
                                        @if ($order['order_status'] == 'pending')
                                            <span class="badge badge-soft-info ml-2 ml-sm-3">
                                                {{ translate('messages.pending') }}
                                            </span>
                                        @elseif($order['order_status'] == 'confirmed')
                                            <span class="badge badge-soft-info ml-2 ml-sm-3">
                                                {{ translate('messages.confirmed') }}
                                            </span>
                                        @elseif($order['order_status'] == 'processing')
                                            <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                                {{ translate('messages.cooking') }}
                                            </span>
                                        @elseif($order['order_status'] == 'picked_up')
                                            <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                                {{ translate('messages.out_for_delivery') }}
                                            </span>
                                        @elseif($order['order_status'] == 'delivered')
                                            <span class="badge badge-soft-success ml-2 ml-sm-3">
                                                {{$order?->order_type == 'dine_in' ? translate('messages.Completed') : translate('messages.delivered')}}
                                            </span>
                                        @else
                                            <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                                {{ translate(str_replace('_', ' ', $order['order_status'])) }}
                                            </span>
                                        @endif

                                </h6>
                                <h6>
                                    <span>
                                    {{ translate('messages.payment_method') }} :</span>
                                    <strong>
                                    {{ translate(str_replace('_', ' ', $order['payment_method'])) }}</strong>
                                </h6>

                                <h6>
                                    <span>{{translate('messages.payment_status')}} :</span>
                                    @if ($order['payment_status'] == 'paid')
                                    <strong class="text-success">{{ translate('messages.paid') }}</strong>
                                    @elseif ($order['payment_status'] == 'partially_paid')

                                        @if ($order->payments()->where('payment_status','unpaid')->exists())
                                        <strong class="text-danger">{{ translate('messages.partially_paid') }}</strong>
                                        @else
                                        <strong class="text-success">{{ translate('messages.paid') }}</strong>
                                        @endif
                                    @else
                                        <strong class="text-danger">{{ translate('messages.unpaid') }}</strong>
                                    @endif
                                </h6>



                                @if($order?->offline_payments)
                                @foreach (json_decode($order->offline_payments->payment_info) as $key=>$item)
                                    @if ($key != 'method_id')
                                        <h6 class="">
                                            <div class="d-flex justify-content-sm-end text-capitalize">
                                                <span class="title-color">{{translate($key)}} :</span>
                                                <strong>{{ $item }}</strong>
                                            </div>
                                        </h6>
                                    @endif
                                @endforeach
                                <h6>
                                    <span>{{ translate('Payment_verification') }}</span> <span>:</span>
                                    @if ($order?->offline_payments->status == 'pending')
                                        <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                                            {{ translate('messages.pending') }}
                                        </span>
                                    @elseif ($order?->offline_payments->status == 'verified')
                                        <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                                            {{ translate('messages.verified') }}
                                        </span>
                                    @elseif ($order?->offline_payments->status == 'denied')
                                        <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                                            {{ translate('messages.denied') }}
                                        </span>
                                        @endif
                                    </h6>
                            @endif
                                    <!-- offline_payment -->

                                <h6>
                                    <span>{{ translate('Order Type') }} :</span>
                                    <strong class="text--title">{{ translate(str_replace('_', ' ', $order['order_type'])) }}</strong>
                                </h6>

                                @if ($order->cutlery)
                                <h6>
                                    <span>{{ translate('cutlery') }}</span> <span>:</span>
                                    <strong class="text-success">
                                            {{ translate('messages.yes') }}
                                        </strong>
                                </h6>
                                @else
                                <h6>
                                    <span>{{ translate('cutlery') }}</span> <span>:</span>
                                    <strong class="text-danger">
                                            {{ translate('messages.No') }}
                                        </strong>
                                </h6>

                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body p-0">
                        <?php
                        $total_addon_price = 0;
                        $restaurant_discount_amount = 0;
                        $product_price = 0;
                        $total_addon_price = 0;
                        ?>
                        <div class="table-responsive">
                            <table class="table table-borderless table-thead-bordered table-nowrap card-table dataTable no-footer mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ translate('Item Details') }}</th>
                                        <th>{{ translate('Addons') }}</th>
                                        <th class="text-right">{{ translate('Price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($order->details as $key => $detail)
                                    @if (isset($detail->food_id))
                                        @php($detail->food = json_decode($detail->food_details, true))
                                        @php($food = \App\Models\Food::where(['id' => $detail->food['id']])->first())
                                                <tr>
                                                    <td>
                                                        <div class="media">
                                                            <a class="avatar mr-3 cursor-pointer initial-80"
                                                                href="{{ route('vendor.food.view', $detail->food['id']) }}">
                                                                <img class="img-fluid rounded initial-80 onerror-image"
                                                                     src="{{ $food['image_full_url'] ?? dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}"
                                                                     data-onerror-image="{{ dynamicAsset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                                    alt="Image Description">
                                                            </a>
                                                            <div class="media-body">
                                                                <div>
                                                                    <strong> {{ Str::limit($detail->food['name'], 25, '...') }}</strong><br>

                                                                    @if (count(json_decode($detail['variation'], true)) > 0)
                                                                        @foreach(json_decode($detail['variation'],true) as  $variation)
                                                                            @if ( isset($variation['name'])  && isset($variation['values']))
                                                                                <span class="d-block text-capitalize">
                                                                                        <strong>
                                                                                    {{  $variation['name']}} -
                                                                                        </strong>
                                                                                </span>
                                                                                    @foreach ($variation['values'] as $value)
                                                                                    <span class="d-block text-capitalize">
                                                                                        &nbsp;   &nbsp; {{ $value['label']}} :
                                                                                        <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                                                                    </span>
                                                                                    @endforeach
                                                                            @else
                                                                                @if (isset(json_decode($detail['variation'],true)[0]))
                                                                                    <strong><u> {{  translate('messages.Variation') }} : </u></strong>
                                                                                    @foreach(json_decode($detail['variation'],true)[0] as $key1 =>$variation)
                                                                                        <div class="font-size-sm text-body">
                                                                                            <span>{{$key1}} :  </span>
                                                                                            <span class="font-weight-bold">{{$variation}}</span>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif
                                                                                    @break

                                                                            @endif
                                                                        @endforeach
                                                                    @endif

                                                                    <div>
                                                                        <strong>{{ translate('messages.Price') }} :</strong>
                                                                        {{ \App\CentralLogics\Helpers::format_currency($detail['price']) }}
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ translate('messages.Qty') }} :</strong> {{ $detail['quantity'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                            <div class="font-size-sm text-body">
                                                                <span>{{ Str::limit($addon['name'], 25, '...') }} : </span>
                                                                <span class="font-weight-bold">
                                                                    {{ $addon['quantity'] }} x
                                                                    {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                                </span>
                                                            </div>
                                                            @php($total_addon_price += $addon['price'] * $addon['quantity'])
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <div class="text-right">
                                                            @php($amount = $detail['price'] * $detail['quantity'])
                                                            <h5>
                                                                {{ \App\CentralLogics\Helpers::format_currency($amount) }}
                                                            </h5>
                                                        </div>
                                                    </td>
                                                </tr>
                                        @php($product_price += $amount)
                                        @php($restaurant_discount_amount += $detail['discount_on_food'] * $detail['quantity'])
                                    @elseif(isset($detail->item_campaign_id))
                                        @php($detail->campaign = json_decode($detail->food_details, true))
                                        @php($campaign = \App\Models\ItemCampaign::where(['id' => $detail->campaign['id']])->first())
                                        <tr>
                                            <td>
                                                <div class="media">
                                                    <div class="avatar avatar-xl mr-3">
                                                        <img class="img-fluid rounded initial-80 onerror-image"
                                                             src="{{ $campaign['image_full_url'] ?? dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}"

                                                             data-onerror-image="{{ dynamicAsset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                            alt="Image Description">
                                                    </div>
                                                    <div class="media-body">
                                                        <div>
                                                            <strong>
                                                                {{ Str::limit($detail->campaign['name'], 25, '...') }}</strong><br>
                                                                @if (count(json_decode($detail['variation'], true)) > 0)
                                                                @foreach(json_decode($detail['variation'],true) as  $variation)


                                                                @if ( isset($variation['name'])  && isset($variation['values']))
                                                                    <span class="d-block text-capitalize">
                                                                            <strong>
                                                                        {{  $variation['name']}} -
                                                                            </strong>
                                                                    </span>
                                                                    @foreach ($variation['values'] as $value)
                                                                    <span class="d-block text-capitalize">
                                                                        &nbsp;   &nbsp; {{ $value['label']}} :
                                                                        <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                                                        </span>
                                                                    @endforeach


                                                                @else
                                                                    @if (isset(json_decode($detail['variation'],true)[0]))
                                                                    <strong><u> {{  translate('messages.Variation') }} : </u></strong>
                                                                        @foreach(json_decode($detail['variation'],true)[0] as $key1 =>$variation)
                                                                            <div class="font-size-sm text-body">
                                                                                <span>{{$key1}} :  </span>
                                                                                <span class="font-weight-bold">{{$variation}}</span>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                        @break

                                                                @endif
                                                                        @endforeach
                                                            @endif
                                                            <div>
                                                                <strong>{{ translate('messages.Price') }} : </strong>
                                                                <span>{{ \App\CentralLogics\Helpers::format_currency($detail['price']) }}</span>
                                                            </div>
                                                            <div>
                                                                <strong>{{ translate('messages.Qty') }} : </strong>
                                                                <span>
                                                                    {{ $detail['quantity'] }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                    <div class="font-size-sm text-body">
                                                        <span>{{ Str::limit($addon['name'], 20, '...') }} : </span>
                                                        <span class="font-weight-bold">
                                                            {{ $addon['quantity'] }} x
                                                            {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                        </span>
                                                    </div>
                                                    @php($total_addon_price += $addon['price'] * $addon['quantity'])
                                                @endforeach
                                            </td>
                                            <td>
                                                @php($amount = $detail['price'] * $detail['quantity'])
                                                <h5 class="text-right">{{ \App\CentralLogics\Helpers::format_currency($amount) }}</h5>
                                            </td>
                                        </tr>
                                        @php($product_price += $amount)
                                        @php($restaurant_discount_amount += $detail['discount_on_food'] * $detail['quantity'])
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <?php

                        $coupon_discount_amount = $order['coupon_discount_amount'];

                        $total_price = $product_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount;

                        $total_tax_amount = $order['total_tax_amount'];

                        $restaurant_discount_amount = $order['restaurant_discount_amount'];
                        $tax_included = \App\Models\BusinessSetting::where(['key'=>'tax_included'])->first() ?  \App\Models\BusinessSetting::where(['key'=>'tax_included'])->first()->value : 0;
                        ?>
                        <div class="">
                            <div class="row md-row-reverse justify-content-between align-items-center px-4 mb-3">
                                <div class="col-md-6 col-lg-5">
                                    {{-- <div class="alert alert-primary-light text-dark mb-0 mt-3 mt-md-0" role="alert">
                                        Please bring $5000 in change for the customer when making the delivery
                                    </div> --}}
                                </div>
                                <div class="col-md-6 col-lg-5">
                                    <dl class="row">
                                        <dt class="col-6">{{ translate('messages.items_price') }}:
                                        </dt>
                                        <dd class="col-6 text-right">
                                            {{ \App\CentralLogics\Helpers::format_currency($product_price) }}</dd>
                                        <dt class="col-6">{{ translate('messages.addon_cost') }}:
                                        </dt>
                                        <dd class="col-6 text-right">
                                            {{ \App\CentralLogics\Helpers::format_currency($total_addon_price) }}
                                        </dd>

                                        <dt class="col-6">{{ translate('messages.subtotal') }}
                                    @if ($order->tax_status == 'included' ||  $tax_included ==  1)
                                    ({{ translate('messages.TAX_Included') }})
                                    @endif
                                            :</dt>
                                        <dd class="col-6 text-right">
                                            {{ \App\CentralLogics\Helpers::format_currency($product_price + $total_addon_price) }}
                                        </dd>
                                        <dt class="col-6">{{ translate('messages.discount') }}:</dt>
                                        <dd class="col-6 text-right">
                                            - {{ \App\CentralLogics\Helpers::format_currency($restaurant_discount_amount) }}
                                        </dd>
                                        <dt class="col-6">{{ translate('messages.coupon_discount') }}:
                                        </dt>
                                        <dd class="col-6 text-right">
                                            - {{ \App\CentralLogics\Helpers::format_currency($coupon_discount_amount) }}</dd>

                                        @if ($order['ref_bonus_amount'] > 0)
                                        <dt class="col-6">{{ translate('messages.Referral_Discount') }}:</dt>
                                        <dd class="col-6 text-right">
                                            - {{ \App\CentralLogics\Helpers::format_currency($order['ref_bonus_amount'] ) }}</dd>
                                        @endif

                                        @if ($order->tax_status == 'excluded' || $order->tax_status == null  )
                                        <dt class="col-6">{{ translate('messages.vat/tax') }}:</dt>
                                        <dd class="col-6 text-right">
                                            +
                                            {{ \App\CentralLogics\Helpers::format_currency($total_tax_amount) }}
                                        </dd>
                                        @endif

                                        <dt class="col-6">{{ translate('messages.delivery_man_tips') }}

                                        </dt>
                                        <dd class="col-6 text-right">
                                            @php($dm_tips = $order['dm_tips'])
                                            + {{ \App\CentralLogics\Helpers::format_currency($dm_tips) }}

                                        </dd>
                                        <dt class="col-6">{{ translate('messages.delivery_fee') }}:
                                        </dt>
                                        <dd class="col-6 text-right">
                                            @php($del_c = $order['delivery_charge'])
                                            + {{ \App\CentralLogics\Helpers::format_currency($del_c) }}

                                            @if (\App\CentralLogics\Helpers::get_business_data('additional_charge_status') == 1 || $order['additional_charge'] > 0)
                                                @php($additional_charge_status = 1)
                                            @else
                                                @php($additional_charge_status = 0)
                                                <hr>
                                            @endif
                                        </dd>

                                        @if ($additional_charge_status  )
                                            <dt class="col-6">
                                                {{ \App\CentralLogics\Helpers::get_business_data('additional_charge_name')??\App\CentralLogics\Helpers::get_business_data('additional_charge_name')??translate('messages.additional_charge') }}
                                                <hr>
                                            </dt>
                                            <dd class="col-6 text-right">
                                                + {{ \App\CentralLogics\Helpers::format_currency($order['additional_charge']) }}
                                                <hr>
                                            </dd>

                                        @endif
                                        @if ($order['extra_packaging_amount'] > 0)
                                        <dt class="col-6">{{ translate('messages.Extra_Packaging_Amount') }}:</dt>
                                        <dd class="col-6 text-right">
                                            + {{ \App\CentralLogics\Helpers::format_currency($order['extra_packaging_amount'] ) }}
                                        </dd>
                                        @endif
                                        {{-- <dt class="col-sm-6">{{ translate('messages.total') }}:</dt>
                                        <dd class="col-sm-6 text-right">
                                            {{ \App\CentralLogics\Helpers::format_currency( $order['order_amount']) }}
                                        </dd> --}}

                                        @if ($order?->payments)
                                        @foreach ($order?->payments as $payment)
                                            @if ($payment->payment_status == 'paid')
                                                @if ( $payment->payment_method == 'cash_on_delivery')

                                                <dt class="col-6">{{ translate('messages.Paid_with_Cash') }} ({{  translate('COD')}}) :</dt>
                                                @else

                                                <dt class="col-6">{{ translate('messages.Paid_by') }} {{  translate($payment->payment_method)}} :</dt>
                                                @endif
                                            @else

                                            <dt class="col-6">{{ translate('Due_Amount') }} ({{  $payment->payment_method == 'cash_on_delivery' ?  translate('messages.COD') : translate($payment->payment_method) }}) :</dt>
                                            @endif
                                        <dd class="col-6 text-right">
                                            {{ \App\CentralLogics\Helpers::format_currency($payment->amount) }}
                                        </dd>
                                        @endforeach
                                    @endif
                                    </dl>
                                    <!-- End Row -->
                                </div>
                            </div>

                            <div id="stickyDiv" class="bg-white pb-1 mt-n25 py-2 mb-3 {{ $order->bring_change_amount > 0 ? 'position-sticky bottom-0' : '' }}">
                                <div class="row  md-row-reverse justify-content-between align-items-center mb-3 px-4">
                                    <div class="col-md-6 col-lg-5">
                                        @if($order->bring_change_amount > 0)
                                            <div class="alert fs-12 p-2 alert-primary-light text-dark mb-0 mt-3 mt-md-0 row" role="alert">
                                                {{translate('Please instruct the delivery man to collect '.\App\CentralLogics\Helpers::format_currency($order->bring_change_amount).' in change upon delivery')}}
                                           </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 col-lg-5">
                                        <dl class="row align-items-center font-weight-bold text--title">
                                            <dd class="col-6 mb-0">{{ translate('messages.total') }}:</dd>
                                            <dt class="col-6 text-right">
                                                {{ \App\CentralLogics\Helpers::format_currency( $order['order_amount']) }}
                                            </dt>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
            <div class="col-lg-4 order-print-area-right">
                <!-- Card -->
                @if ($order['order_status'] != 'delivered')
                <div class="card mb-2">
                    <!-- Header -->
                    <div class="card-header border-0 py-0">
                        <h4 class="card-header-title border-bottom py-3 m-0  w-100 text-center">{{ translate('Order Setup') }}</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->


                    <div class="card-body">
                        <!-- Unfold -->
                        @php($order_delivery_verification = (bool) \App\Models\BusinessSetting::where(['key' => 'order_delivery_verification'])->first()->value)
                        <div class="order-btn-wraper">
                            @if ($order['order_status'] == 'pending')
                                <a class="btn w-100 mb-3 btn-sm btn--primary order-status-change-alert"
                                    data-url="{{ route('vendor.order.status', ['id' => $order['id'], 'order_status' => 'confirmed']) }}" data-message="{{ translate('Change status to confirmed ?') }}"
                                    href="javascript:">
                                    {{ translate('Confirm Order') }}
                                </a>
                                @if (config('canceled_by_restaurant'))
                                    <a class="btn w-100 mb-3 btn-sm btn-outline-danger btn--danger mt-3 cancelled-status">{{ translate('Cancel Order') }}</a>
                                @endif
                            @elseif ($order['order_status'] == 'confirmed' || $order['order_status'] == 'accepted')
                                <a class="btn btn-sm btn--primary w-100 mb-3 order-status-change-alert"
                                   data-url="{{ route('vendor.order.status', ['id' => $order['id'], 'order_status' => 'processing']) }}" data-message="{{ translate('Change status to cooking ?') }}" data-verification="false" data-processing-time="{{ $max_processing_time }}"
                                    href="javascript:">{{ translate('messages.Proceed_for_cooking') }}</a>
                            @elseif ($order['order_status'] == 'processing')
                                <a class="btn btn-sm btn--primary w-100 mb-3 order-status-change-alert"
                                    data-url="{{ route('vendor.order.status', ['id' => $order['id'], 'order_status' => 'handover']) }}" data-message="{{ translate('Change status to ready for handover ?') }}"
                                    href="javascript:">{{ translate('messages.make_ready_for_handover') }}</a>

                            @elseif ($order['order_status'] == 'handover' && (in_array($order['order_type'],['dine_in','take_away']) ||

                            (($restaurant->restaurant_model == 'commission' && $restaurant->self_delivery_system ) ||
                            ($restaurant->restaurant_model == 'subscription'  && $restaurant?->restaurant_sub?->self_delivery == 1))
                            ))
                                <a class="btn btn-sm btn--primary w-100 mb-3 order-status-change-alert"
                                    data-url="{{ route('vendor.order.status', ['id' => $order['id'], 'order_status' => 'delivered']) }}" data-message="{{ translate('Change status to delivered (payment status will be paid if not) ?') }}" data-verification="{{ $order_delivery_verification ? 'true' : 'false' }}"
                                    href="javascript:">{{  $order?->order_type == 'dine_in' ? translate('messages.Make_Completed') : translate('messages.make_delivered') }}</a>
                            @endif
                        </div>

                        @if ($order->order_status == 'canceled')
                            <ul class="delivery--information-single mt-3">
                                <li>
                                    <span class=" badge badge-soft-danger "> {{ translate('messages.Cancel_Reason') }} :</span>
                                    <span class="info">  {{ $order->cancellation_reason }} </span>
                                </li>

                                <li>
                                    <span class="name">{{ translate('Cancel_Note') }} </span>
                                    <span class="info">  {{ $order->cancellation_note ?? translate('messages.N/A')}} </span>
                                </li>
                                <li>
                                    <span class="name">{{ translate('Canceled_By') }} </span>
                                    <span class="info">  {{ translate($order->canceled_by) }} </span>
                                </li>
                                @if ($order->payment_status == 'paid' || $order->payment_status == 'partially_paid' )
                                        @if ( $order?->payments)
                                            @php( $pay_infos =$order->payments()->where('payment_status','paid')->get())
                                            @foreach ($pay_infos as $pay_info)
                                                <li>
                                                    <span class="name">{{ translate('Amount_paid_by') }} {{ translate($pay_info->payment_method) }} </span>
                                                    <span class="info">  {{ \App\CentralLogics\Helpers::format_currency($pay_info->amount)  }} </span>
                                                </li>
                                            @endforeach
                                        @else
                                        <li>
                                            <span class="name">{{ translate('Amount_paid_by') }} {{ translate($order->payment_method) }} </span>
                                            <span class="info ">  {{ \App\CentralLogics\Helpers::format_currency($order->order_amount)  }} </span>
                                        </li>
                                        @endif
                                @endif

                                @if ($order->payment_status == 'paid' || $order->payment_status == 'partially_paid')
                                    @if ( $order?->payments)
                                        @php( $amount =$order->payments()->where('payment_status','paid')->sum('amount'))
                                            <li>
                                                <span class="name">{{ translate('Amount_Returned_To_Wallet') }} </span>
                                                <span class="info">  {{ \App\CentralLogics\Helpers::format_currency($amount)  }} </span>
                                            </li>
                                    @else
                                    <li>
                                        <span class="name">{{ translate('Amount_Returned_To_Wallet') }} </span>
                                        <span class="info">  {{ \App\CentralLogics\Helpers::format_currency($order->order_amount)  }} </span>
                                    </li>
                                    @endif
                                @endif
                            </ul>
                            <hr class="w-100">
                        @endif


                        <!-- End Unfold -->

                    </div>

                </div>
                @endif
                @if ($order->order_type == 'dine_in')
                <form action="{{ route('vendor.order.add_dine_in_table_number', [$order['id']]) }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mb-20">
                                <label class="form-label">{{translate('Table_Number')}}</label>
                                <div class="d-flex">
                                    <input type="text"  @readonly(in_array($order['order_status'],['failed','delivered','refund_requested','canceled','refunded','refund_request_canceled'])) maxlength="20" value="{{ $order?->OrderReference?->table_number }}"  name="table_number" class="form-control" placeholder="Ex: 10">
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label class="form-label">{{translate('Token_Number')}}</label>
                                <div class="d-flex">
                                    <input type="text" @readonly(in_array($order['order_status'],['failed','delivered','refund_requested','canceled','refunded','refund_request_canceled'])) maxlength="20" value="{{ $order?->OrderReference?->token_number }}" name="token_number" class="form-control" placeholder="Ex: 32">
                                </div>
                            </div>
                            @if (!in_array($order['order_status'],['failed','delivered','refund_requested','canceled','refunded','refund_request_canceled']))
                            <div class="w-100 text-center mt-4">
                                <button type="submit" class="btn w-100 btn-primary font-regular">{{ translate('messages.Save') }}
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
                @endif

                @if (!in_array($order['order_type'],['dine_in','take_away']))
                <div class="card mb-2 mt-2">
                    <div class="card-header border-0 text-center pb-0">
                    @if ($order->delivery_man)
                        <h5 class="card-title mb-3 w-100 justify-content-between">
                            <div>
                                <span class="card-header-icon">
                                    <i class="tio-user"></i>
                                </span>
                                <span>
                                    {{ translate('Delivery Man Information') }}
                                </span>
                            </div>
                            @if ( !in_array($order['order_status'], ['handover','delivered','refund_requested','canceled','refunded','refund_request_canceled']) &&
                            (isset($order->restaurant) && ($order->restaurant->restaurant_model == 'commission'
                                    && $order->restaurant->self_delivery_system ) ||  ($order->restaurant->restaurant_model == 'subscription'
                                        && isset($order->restaurant->restaurant_sub) && $order->restaurant->restaurant_sub->self_delivery == 1)))

                                <span class="ml-auto text--primary position-relative pl-2 cursor-pointer" data-toggle="modal" data-target="#myModal">
                                    {{ translate('messages.change') }}
                                </span>
                            @endif
                        </h5>
                    </div>

                <div class="card-body">
                        <div class="media align-items-center deco-none customer--information-single ml-3 border-0 text-center pb-0" href="javascript:">
                            <div class="avatar avatar-circle">
                                <img class="avatar-img  initial-81 onerror-image"
                                     data-onerror-image="{{ dynamicAsset('public/assets/admin/img/160x160/img3.jpg') }}"
                                     src="{{ $order->delivery_man?->image_full_url ?? dynamicAsset('public/assets/admin/img/160x160/img3.jpg') }}"
                                    alt="Image Description">
                            </div>
                            <div class="media-body">
                                <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                    {{ $order->delivery_man['f_name'] . ' ' . $order->delivery_man['l_name'] }}
                                </span>
                                <span class="d-block">
                                    <strong class="text--title font-semibold">
                                        {{ $order->delivery_man->orders_count }}
                                    </strong>
                                    {{ translate('messages.orders') }}
                                </span>
                                <span class="d-block">
                                    <a class="text--title font-semibold" href="tel:{{ $order->delivery_man['phone'] }}">
                                        <strong>
                                            {{ $order->delivery_man['email'] }}
                                        </strong>
                                    </a>
                                </span>
                                <span class="d-block">
                                    <strong class="text--title font-semibold">
                                    </strong>
                                    {{ $order->delivery_man['phone'] }}
                                </span>
                            </div>
                        </div>

                        @if (!in_array($order['order_type'],['dine_in','take_away']))
                            <hr>
                            @php($address = $order->dm_last_location)
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{ translate('messages.last_location') }}</h5>
                            </div>
                            @if (isset($address))
                                <span class="d-block mb-2">
                                    <a target="_blank"
                                        href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $address['latitude'] }}+{{ $address['longitude'] }}">
                                        <i class="tio-poi"></i> {{ $address['location'] }}<br>
                                    </a>
                                </span>
                            @else
                                <span class="d-block text-lowercase qcont mb-2">
                                    {{ translate('messages.location_not_found') }}
                                </span>
                            @endif
                        @endif
                    @else


                    @if (!$order->delivery_man && !in_array($order['order_status'], ['handover','delivered','take_away','refund_requested','canceled','refunded','refund_request_canceled']) && (isset($order->restaurant) && ($order->restaurant->restaurant_model == 'commission'  && $order->restaurant->self_delivery_system ) ||
                     ($order->restaurant->restaurant_model == 'subscription' && isset($order->restaurant->restaurant_sub) && $order->restaurant->restaurant_sub->self_delivery == 1)))
                    <div class="w-100 text-center mr-2 mt-4 mb-4">
                        <button type="button" class="btn w-100 btn-primary font-regular" data-toggle="modal"
                            data-target="#myModal" data-lat='21.03' data-lng='105.85'>
                            <i class="tio-bike"></i> {{ translate('messages.assign_delivery_man') }}
                        </button>
                    </div>

                    @endif
                    @endif
                </div>
            </div>
                @endif


                @if ($order->order_type != 'dine_in')
                    <!-- order proof -->
                    <div class="card mb-2 mt-2">
                        <div class="card-header border-0 text-center pb-0">
                                <h5 class="card-title mb-3">
                                        <span class="card-header-icon">
                                            <i class="tio-user"></i>
                                        </span>
                                        <span>
                                            {{ translate('messages.Delivery_Proof')  }}
                                        </span>
                                    </h5>
                            @if (($restaurant->restaurant_model == 'commission' && $restaurant->self_delivery_system ) ||
                            ($restaurant->restaurant_model == 'subscription'  && $restaurant?->restaurant_sub?->self_delivery == 1))

                            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target=".order-proof-modal"> {{ translate('messages.add') }}</button>
                            @endif
                        </div>
                        @php($data = isset($order->order_proof) ? json_decode($order->order_proof, true) : 0)
                        <div class="card-body pt-2">
                            @if ($data)
                            <label class="input-label"
                                for="order_proof">{{ translate('messages.image') }} : </label>
                            <div class="row g-3">
                                    @foreach ($data as $key => $img)
                                    @php($img = is_array($img)?$img:['img'=>$img,'storage'=>'public'])
                                        <div class="col-3">
                                            <img class="img__aspect-1 rounded border w-100 onerror-image" data-toggle="modal"
                                                data-target="#imagemodal{{ $key }}"
                                                data-onerror-image="{{ dynamicAsset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                src="{{\App\CentralLogics\Helpers::get_full_url('order',$img['img'],$img['storage']) }}">
                                        </div>
                                        <div class="modal fade" id="imagemodal{{ $key }}" tabindex="-1"
                                            role="dialog" aria-labelledby="order_proof_{{ $key }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"
                                                            id="order_proof_{{ $key }}">
                                                            {{ translate('order_proof_image') }}</h4>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal"><span
                                                                aria-hidden="true">&times;</span><span
                                                                class="sr-only">{{ translate('messages.cancel') }}</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img src="{{\App\CentralLogics\Helpers::get_full_url('order',$img['img'],$img['storage']) }}"
                                                            class="initial--22 w-100">
                                                    </div>
                                                    @php($storage = $img['storage'] ?? 'public')
                                                    @php($file = $storage == 's3'?base64_encode('order/' . $img['img']):base64_encode('public/order/' . $img['img']))
                                                    <div class="modal-footer">
                                                        <a class="btn btn-primary"
                                                            href="{{ route('vendor.file-manager.download', [$file,$storage]) }}"><i
                                                                class="tio-download"></i>
                                                            {{ translate('messages.download') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                        </div>
                    </div>
                @endif

                <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <span class="card-header-icon">
                                <i class="tio-user"></i>
                            </span>
                            <span>
                                {{ translate('messages.customer_info') }}
                            </span>
                        </h5>
                        @if ($order->customer && $order->is_guest == 0)
                            <div class="media align-items-center deco-none customer--information-single" href="javascript:">
                                <div class="avatar avatar-circle">
                                    <img class="avatar-img  initial-81 onerror-image "
                                         data-onerror-image="{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
                                         src="{{ $order->customer?->image_full_url ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
                                        alt="Image Description">
                                </div>
                                <div class="media-body">
                                    <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                        {{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}
                                    </span>
                                    <span class="d-block">
                                        <strong class="text--title font-semibold">
                                        {{ $order->customer->orders_count }}
                                        </strong>
                                        {{ translate('Orders') }}
                                    </span>
                                    <span class="d-block">
                                        <a class="text--title font-semibold" href="tel:{{ $order->customer['phone'] }}">
                                            <strong>
                                                {{ $order->customer['phone'] }}
                                            </strong>
                                        </a>
                                    </span>
                                    <span class="d-block">
                                        <strong class="text--title font-semibold">
                                        </strong>
                                        {{ $order->customer['email'] }}
                                    </span>
                                </div>
                            </div>
                            @elseif($order->is_guest)
                            <span class="badge badge-soft-success py-2 d-block qcont">
                                {{ translate('Guest_user') }}
                            </span>

                        @else
                        {{translate('messages.customer_not_found')}}
                        @endif
                    </div>
                </div>

                @if ($order->delivery_address)

                    <div class="card mt-2">
                        <div class="card-body">
                            @php($address = json_decode($order->delivery_address, true))
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">
                                    <span class="card-header-icon">
                                        <i class="tio-user"></i>
                                    </span>
                                    <span>
                                        {{ $order->order_type == 'dine_in' ? translate('Order_Info') : translate('delivery_info') }}
                                    </span>
                                </h5>
                            </div>
                            @if (isset($address))
                            <span class="delivery--information-single mt-3">
                                <span class="name">{{ translate('messages.name') }}:</span>
                                <span class="info">{{ $address['contact_person_name'] }}</span>
                                <span class="name">{{ translate('messages.contact') }}:</span>
                                <a class="info" href="tel:{{ $address['contact_person_number'] }}">
                                    {{ $address['contact_person_number'] }}
                                </a>

                                @if ($order->order_type != 'dine_in')
                                    <span class="name">{{ translate('Road') }}:</span>
                                    <span class="info">{{ isset($address['road']) ? $address['road'] : '' }}</span>
                                    <span class="name">{{ translate('House') }}:</span>
                                    <span class="info">{{ isset($address['house']) ? $address['house'] : '' }}</span>
                                    <span class="name">{{ translate('Floor') }}:</span>
                                    <span class="info">{{ isset($address['floor']) ? $address['floor'] : '' }}</span>
                                    <span class="mt-2 d-flex w-100">
                                        <span><i class="tio-poi text--title"></i></span>
                                        @if (!in_array($order['order_type'],['dine_in','take_away']) && isset($address['address']))
                                            @if (isset($address['latitude']) && isset($address['longitude']))
                                                <a target="_blank"
                                                class="info pl-2"
                                                    href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $address['latitude'] }}+{{ $address['longitude'] }}">
                                                    {{ $address['address'] }}
                                                </a>
                                            @else
                                                <span class="info pl-2">
                                                    {{ $address['address'] }}
                                                </span>
                                            @endif
                                        @endif
                                    </span>
                                @endif
                            </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- End Row -->
    </div>



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

                @php($address = \App\Models\CustomerAddress::find($order['delivery_address_id']))
                @if (isset($address))
                    <form action="{{ route('vendor.order.update-shipping', [$order['delivery_address_id']]) }}"
                        method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.type') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control h--45px" name="address_type"
                                        value="{{ isset($address['address_type']) ? $address['address_type'] : '' }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.contact') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control h--45px" name="contact_person_number"
                                        value="{{ $address['contact_person_number'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.name') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control h--45px" name="contact_person_name"
                                        value="{{ $address['contact_person_name'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.address') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control h--45px" name="address"
                                        value="{{ $address['address'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.latitude') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control h--45px" name="latitude"
                                        value="{{ $address['latitude'] }}" required>
                                </div>
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.longitude') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control h--45px" name="longitude"
                                        value="{{ $address['longitude'] }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn--reset"
                                data-dismiss="modal">{{ translate('messages.close') }}</button>
                            <button type="submit" class="btn btn--primary">{{ translate('messages.save_changes') }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!-- End Content -->
    <!-- Modal -->
    <div class="modal fade order-proof-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="mySmallModalLabel">{{ translate('messages.add_delivery_proof') }}</h5>
                    <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal"
                        aria-label="Close">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>

                <form action="{{ route('vendor.order.add-order-proof', [$order['id']]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Input Group -->
                        <div class="flex-grow-1 mx-auto">
                            <div class="d-flex flex-wrap __gap-12px __new-coba" id="coba">
                                @php($proof = isset($order->order_proof) ? json_decode($order->order_proof, true) : 0)
                                @if ($proof)

                                @foreach ($proof as $key => $photo)
                                @php($photo =  is_string($photo) ? ['img' => $photo, 'storage' => 'public'] :  $photo )

                                            <div class="spartan_item_wrapper min-w-100px max-w-100px">
                                                <img class="img--square"
                                                    src="{{ $order->order_proof_full_url[$key] }}"
                                                    alt="order image">
                                                <a href="{{ route('vendor.order.remove-proof-image', ['id' => $order['id'], 'name' => $photo['img']]) }}"
                                                    class="spartan_remove_row"><i class="tio-add-to-trash"></i></a>
                                            </div>
                                        @endforeach
                                @endif
                            </div>
                        </div>
                        <!-- End Input Group -->
                        <div class="text-right mt-2">
                            <button class="btn btn--primary">{{ translate('messages.submit') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Dm assign Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{ $selected_delivery_man != [] ?  translate('messages.Change_Delivery_Men') :  translate('messages.assign_deliveryman') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 my-2">
                            <ul class="list-group overflow-auto max-height-400">
                                @if ($selected_delivery_man != [] )

                                <li class="list-group-item">
                                    <div class="d-flex align-items-center gap-2 justify-content-between">
                                            <div class="dm_list_selected media gap-2" data-id="{{ $selected_delivery_man['id'] }}">
                                                <img class="avatar avatar-60 rounded-10 onerror-image"
                                                     data-onerror-image="{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
                                                     src="{{ $selected_delivery_man['image_full_url'] ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
                                                    alt="{{ $selected_delivery_man['name'] }}">
                                                    <div class="media-body d-flex gap-1 flex-column">
                                                        <h6 class="mb-1">  {{ $selected_delivery_man['name'] }}</h6>
                                                        <div class="fs-12 text-muted">
                                                            {{ translate('Active_Orders') }} : {{ $selected_delivery_man['current_orders'] }}
                                                        </div>

                                                        <div class="fs-12 text-muted">
                                                            {{ $selected_delivery_man['distance']}} {{  translate('away_from_restaurant') }}
                                                        </div>
                                                    </div>
                                            </div>
                                            <span class="badge __badge badge-success __badge-abs">
                                                {{  translate('Currently_Assigned') }}
                                            </span>
                                    </div>
                                </li>
                                @endif

                                <p class="mb-2 text-center text-danger {{$order->delivery_man_id ? 'mt-2' : ' ' }}"  >{{  count($deliveryMen) > 0  ?  count($deliveryMen) - ($order->delivery_man_id ? 1 : 0) : 0 }}  {{  translate('Delivery_Man_Available') }}</p>

                                @foreach ($deliveryMen as $dm)
                                    @if ($dm['id'] != $order->delivery_man_id )
                                        <li class="list-group-item">
                                            <div class="d-flex align-items-center gap-2 justify-content-between">
                                                    <div class="dm_list media gap-2" data-id="{{ $dm['id'] }}">
                                                        <img class="avatar avatar-60 rounded-10 onerror-image"
                                                             data-onerror-image="{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
                                                             src="{{ $dm['image_full_url'] ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
                                                            alt="{{ $dm['name'] }}">
                                                            <div class="media-body d-flex gap-1 flex-column">
                                                                <h6 class="mb-1">  {{ $dm['name'] }}</h6>
                                                                <div class="fs-12 text-muted">
                                                                    {{ translate('Active_Orders') }} : {{ $dm['current_orders'] }}
                                                                </div>

                                                                <div class="fs-12 text-muted">
                                                                    {{ $dm['distance']}} {{  translate('away_from_restaurant') }}
                                                                </div>
                                                            </div>
                                                    </div>

                                                    @if ($order->delivery_man_id )
                                                        <a class="btn btn-primary btn-xs float-right add-delivery-man"
                                                        data-id="{{ $dm['id'] }}">{{ translate('messages.Reassign') }}</a>
                                                    @else
                                                        <a class="btn btn-primary btn-xs float-right add-delivery-man"
                                                        data-id="{{ $dm['id'] }}">{{ translate('messages.assign') }}</a>
                                                    @endif
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-7 modal_body_map">
                            <div class="location-map" id="dmassign-map">
                                <div id="map_canvas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

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






@endsection
@push('script_2')
    <script
    src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places&callback=initMap&v=3.45.8">
    </script>
    <script src="{{ dynamicAsset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        $(document).ready(function () {
            let $stickyDiv = $("#stickyDiv");

            function checkSticky() {
                let rect = $stickyDiv[0].getBoundingClientRect();

                if (rect.bottom >= window.innerHeight && rect.top < window.innerHeight) {
                    $stickyDiv.addClass("total_price_sticky_wrapper");
                } else {
                    $stickyDiv.removeClass("total_price_sticky_wrapper");
                }
            }

            $(window).on("scroll resize", checkSticky);

            if ({{ $order->bring_change_amount > 0 ? 'true' : 'false' }}){
                checkSticky();
            }
        });
    </script>
    <script>
        "use strict";

        $('.cancelled-status').on('click',function (){
            Swal.fire({
                title: '{{ translate('messages.are_you_sure_?') }}',
                text: '{{ translate('messages.You_want_to_cancel_this_order_?') }}',
                type: 'warning',
                html:
                    `<select class="form-control js-select2-custom mx-1" name="reason" id="reason">
                        <option value=" ">
                            {{  translate('select_cancellation_reason') }}
                        </option>
                    @foreach ($reasons as $r)
                        <option value="{{ $r->reason }}">
                            {{ $r->reason }}
                        </option>
                    @endforeach
                    </select>`,
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true,
                onOpen: function () {
                        $('.js-select2-custom').select2({
                            minimumResultsForSearch: 5,
                            width: '100%',
                            placeholder: "Select Reason",
                            language: "en",
                        });
                    }
            }).then((result) => {
                if (result.value) {
                    let reason = document.getElementById('reason').value;
                    location.href = '{!! route('vendor.order.status', ['id' => $order['id'],'order_status' => 'canceled']) !!}&reason='+reason,'{{ translate('Change status to canceled ?') }}';
                }
            })
        })


        $('.order-status-change-alert').on('click',function (){
            let route = $(this).data('url');
            let message = $(this).data('message');
            let verification = $(this).data('verification');
            let processing = $(this).data('processing-time') ?? false;
            if (verification) {
                Swal.fire({
                    title: '{{ translate('Enter order verification code') }}',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    confirmButtonText: '{{ translate('messages.submit') }}',
                    showLoaderOnConfirm: true,
                    preConfirm: (otp) => {
                        location.href = route + '&otp=' + otp;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })

            } else if (processing) {
                Swal.fire({
                    //text: message,
                    title: '{{ translate('messages.Are you sure ?') }}',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: '{{ translate('messages.Cancel') }}',
                    confirmButtonText: '{{ translate('messages.submit') }}',
                    inputPlaceholder: "{{ translate('Enter processing time') }}",
                    input: 'text',
                    html: message + '<br/>'+'<label>{{ translate('Enter Processing time in minutes') }}</label>',
                    inputValue: processing,
                    preConfirm: (processing_time) => {
                        location.href = route + '&processing_time=' + processing_time;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })
            } else {
                Swal.fire({
                    title: '{{ translate('messages.Are you sure ?') }}',
                    text: message,
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: '{{ translate('messages.No') }}',
                    confirmButtonText: '{{ translate('messages.Yes') }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        location.href = route;
                    }
                })
            }
        })

        function last_location_view() {
            toastr.warning('{{ translate('Only available when order is out for delivery!') }}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        $('.add-delivery-man').on('click',function (){
            let id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: '{{ url('/') }}/restaurant-panel/order/add-delivery-man/{{ $order['id'] }}/' + id,
                success: function(data) {

                    toastr.success('{{ translate('Successfully_added') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    location.reload();
                },
                error: function(response) {
                    console.log(response);
                    toastr.error(response.responseJSON.message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        })

        let deliveryMan = <?php echo json_encode($deliveryMen); ?>;
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

        function initializeGMap() {

            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

            let infowindow = new google.maps.InfoWindow();
            let dm_default_image = null;

            @if ($order->restaurant)
                let Restaurantmarker = new google.maps.Marker({
                    position: new google.maps.LatLng(
                        {{ isset($order->restaurant) ? $order->restaurant->latitude : 0 }},
                        {{ isset($order->restaurant) ? $order->restaurant->longitude : 0 }}),
                    map: map,
                    title: "{{ isset($order->restaurant) ? Str::limit($order->restaurant->name, 15, '...') : '' }}",
                    icon: "{{ dynamicAsset('public/assets/admin/img/restaurant_map_1.png') }}"
                });

                google.maps.event.addListener(Restaurantmarker, 'click', (function(Restaurantmarker) {
                    return function() {
                        infowindow.setContent(
                            `<div class='float--left'><img class='js--design-1' onerror="this.src='{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}'"  src='{{ dynamicStorage('storage/app/public/restaurant/' . $order->restaurant->logo) }}'></div><div class='text-break float--right p--10px'><b>{{ Str::limit($order->restaurant->name, 15, '...') }}</b><br/> {{ $order->restaurant->address }}</div>`
                        );
                        infowindow.open(map, Restaurantmarker);
                    }
                })(Restaurantmarker));
            @endif
            map.fitBounds(dmbounds);
            for (let i = 0; i < deliveryMan.length; i++) {
                if (deliveryMan[i].lat) {
                    let point = new google.maps.LatLng(deliveryMan[i].lat, deliveryMan[i].lng);
                    dmbounds.extend(point);
                    map.fitBounds(dmbounds);
                    let d_man = "{{ $order?->delivery_man_id ?? 0 }}" ;
                    if ( deliveryMan[i].id ==  d_man){
                        dm_default_image=  "{{ dynamicAsset('public/assets/admin/img/delivery_boy_map_2.png') }}";
                    } else {
                        dm_default_image=  "{{ dynamicAsset('public/assets/admin/img/delivery_boy_map_1.png') }}";
                    }

                    let marker = new google.maps.Marker({
                        position: point,
                        map: map,
                        title: deliveryMan[i].location,
                        icon:  dm_default_image


                    });
                    dmMarkers[deliveryMan[i].id] = marker;
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infowindow.setContent(
                                `<div class='float--left'><img class='js--design-1 mt-2' onerror="this.src='{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}'"  src='{{ dynamicStorage('storage/app/public/delivery-man') }}/` +
                                deliveryMan[i].image +
                                "'></div><div class='float--right p--10px'><b>" + deliveryMan[i]
                                .name + "</b><br/> <div> {{ translate('messages.Active_Orders') }} :" + deliveryMan[i].current_orders + "</div> </b>" + deliveryMan[i].location + "</div>");
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }

            };
        }

        $(document).ready(function() {

            // Re-init map before show modal
            $('#myModal').on('shown.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                $("#dmassign-map").css("width", "100%");
                $("#map_canvas").css("width", "100%");
            });

            // Trigger map resize event after modal shown
            $('#myModal').on('shown.bs.modal', function() {
                initializeGMap();
                google.maps.event.trigger(map, "resize");
                map.setCenter(myLatlng);
            });

            $('#shipping-address-modal').on('shown.bs.modal', function() {
                initMap();
            });


            function initializegLocationMap() {
                map = new google.maps.Map(document.getElementById("location_map_canvas"), myOptions);

                let infowindow = new google.maps.InfoWindow();


                @if (isset($c_address) && isset($c_address['latitude']) && isset($c_address['longitude']) )
                    let marker = new google.maps.Marker({
                        position: new google.maps.LatLng({{ $c_address['latitude'] }},
                            {{ $c_address['longitude'] }}),
                        map: map,
                        title: "{{ $order->customer ? $order->customer->f_name .' '. $order->customer->l_name : $c_address['contact_person_name'] }}",
                        icon: "{{ dynamicAsset('public/assets/admin/img/customer_location.png') }}"
                    });

                    google.maps.event.addListener(marker, 'click', (function(marker) {
                        return function() {
                            infowindow.setContent(
                                `<div class='float--left'><img class='js--design-1' onerror="this.src='{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}'"  src='{{ $order->customer ? dynamicStorage('storage/app/public/profile/' . $order?->customer?->image) : dynamicAsset('public/assets/admin/img/160x160/img3.png') }}'></div><div class='float--right p--10px'><b>{{ $order->customer ? $order->customer->f_name .' '. $order->customer->l_name : $c_address['contact_person_name'] }}</b><br/>{{ $c_address['address'] }}</div>`
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
                                `<div class='float--left'><img class='js--design-1 mt-2' onerror="this.src='{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}'"  src='{{ dynamicStorage('storage/app/public/delivery-man/' . $order->delivery_man->image) }}'></div><div class='float--right p--10px'><b>{{ $order->delivery_man->f_name }}  {{ $order->delivery_man->l_name }}</b><br/>  <div> {{ translate('messages.Active_Orders') }} : {{ $order->delivery_man->current_orders }} </div> </b>{{ $order->dm_last_location['location'] }}</div>`
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

            // Re-init map before show modal
            $('#locationModal').on('shown.bs.modal', function(event) {
                initializegLocationMap();
            });



            $('.dm_list').on('click', function() {
                let id = $(this).data('id');
                map.panTo(dmMarkers[id].getPosition());
                map.setZoom(13);
                dmMarkers[id].setAnimation(google.maps.Animation.BOUNCE);
                window.setTimeout(() => {
                    dmMarkers[id].setAnimation(null);
                }, 3);
            });




            $('.dm_list_selected').on('click', function() {
                let id = $(this).data('id');
                map.panTo(dmMarkers[id].getPosition());
                map.setZoom(13);
                dmMarkers[id].setAnimation(google.maps.Animation.BOUNCE);
                window.setTimeout(() => {
                    dmMarkers[id].setAnimation(null);
                }, 3);

            });

        })

        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'order_proof[]',
                maxCount: 6-{{ ($order->order_proof && is_array($order->order_proof))?count(json_decode($order->order_proof)):0 }},
                rowHeight: '100px !important',
                groupClassName: 'spartan_item_wrapper min-w-100px max-w-100px',
                maxFileSize: '',
                placeholderImage: {
                    image: "{{ dynamicAsset('public/assets/admin/img/upload.png') }}",
                    width: '100px'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error(
                        "{{ translate('messages.please_only_input_png_or_jpg_type_file') }}", {
                            CloseButton: true,
                            ProgressBar: true
                        });
                },
                onSizeErr: function(index, file) {
                    toastr.error("{{ translate('messages.file_size_too_big') }}", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>


@endpush
