@php
$schedules = $subscription->schedules()->get();
$order= $subscription->order;
$product_price=0;
$total_addon_price=0;
$restaurant_discount_amount=0;


@endphp
<div class="border rounded p-3 mb-4">
    <ul class="subscription--details-info mb-4">
        <li>
            <span>{{ translate('Status') }}</span>
            @if ( $subscription->status ==  'active')
            <strong class="text-success">{{ translate('messages.Active') }}</strong>
            @elseif($subscription->status == 'canceled')
            <strong class="text-dnager">{{ translate('messages.canceled') }}</strong>
            @elseif($subscription->status == 'expired')
            <strong class="text-primary">{{ translate('messages.expired') }}</strong>
            @elseif($subscription->status == 'paused')
            <strong class="text-warning">{{ translate('messages.paused') }}</strong>
            @endif

        </li>
        <li>
            <span>{{ translate('messages.type') }}</span>
            <strong> {{ translate('messages.'.$subscription->type) }} <span class="font-regular">({{  \App\CentralLogics\Helpers::date_format($subscription->start_at) }} - {{  \App\CentralLogics\Helpers::date_format($subscription->end_at) }})</span></strong>
        </li>
        <li>
            <span>{{ translate('Total_Order') }}</span>
            <strong>{{ $subscription->quantity }}</strong>
        </li>
        <li>
            <span> {{ translate('Delivered') }}</span>
            <strong>{{ $subscription->logs()->whereIn('order_status',['delivered'])->count() }} </strong>
        </li>
        <li>
            <span> {{ translate('Canceled') }}</span>
            <strong>{{ $subscription->logs()->whereIn('order_status',['canceled'])->count() }} </strong>
        </li>
    </ul>
    <div class="p-3 bg--F7F9FD rounded">
        <h4> {{ translate('Subscription_Schedule') }} : <small>{{ translate('You’ll get your order') }} {{ translate('messages.'.$subscription->type) }} {{ translate('messages.at') }}</small> </h4>

            @php
                $days = ['sunday', 'monday', 'tuesday', 'webnesday', 'thursday', 'friday', 'saturday'];
            @endphp
            <div class="subscription-schedules-time">
            @foreach($schedules as $key=>$schedule)
                <div class="item">
                    <div>
                        @if ($schedule->type == 'weekly')
                        {{ translate('messages.'.$days[$schedule->day]) }}
                        @elseif ($schedule->type == 'daily')
                        {{ translate('messages.daily') }}
                        @else
                       {{ translate('messages.Day') }} {{ $schedule->day }}
                        @endif
                    </div>
                    <div>
                        {{  \App\CentralLogics\Helpers::time_format($schedule->time) }}
                    </div>
                </div>
            @endforeach
            </div>
    </div>
</div>

{{-- <div class="card mb-2">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="media">
                    <div class="media-body">
                        <h6 class="card-subtitle">{{translate('messages.Total_delivered_amount')}}</h6>
                        <span class="card-title h3">{{\App\CentralLogics\Helpers::format_currency($subscription->billing_amount)}}</span>
                    </div>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="table-responsive datatable-custom">
    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
        <thead>
            <tr>
                <th>{{translate('messages.sl')}}</th>
                <th>{{translate('messages.food_descriptions')}}</th>
                <th>{{translate('messages.addons')}}</th>
                <th class="text-right">{{translate('messages.Price')}}</th>
            </tr>
        </thead>
        <tbody>
            @if ($subscription->order)
                @foreach ($subscription->order->details as $key => $detail)
                    @php
                        if (isset($detail->food_id))
                        {
                            $detail->food = json_decode($detail->food_details, true);
                            $food = \App\Models\Food::where(['id' => $detail->food['id']])->first();
                        }else{
                            $detail->campaign = json_decode($detail->food_details, true);
                            $campaign = \App\Models\ItemCampaign::where(['id' => $detail->campaign['id']])->first();
                        }
                    @endphp

                <tr>
                    <td>
                        <h5>{{$key+1}}</h5>
                    </td>
                    <td>
                        <a class="media" href="{{isset($detail->food_id) ? route('admin.food.view',[$detail->food['id']]) :  route('admin.campaign.view', ['food', $detail->campaign['id']])}}">
                            <img class="avatar avatar-xl mr-3 onerror-image"
                            @if (isset($detail->food['image']))
                                src="{{ $food['image_full_url'] }}"
                            @else
                                src="{{isset($detail->campaign['image']) ? $campaign['image_full_url'] : dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}"
                            @endif
                            data-onerror-image="{{dynamicAsset('public/assets/admin/img/160x160/img2.jpg')}}" alt="{{isset($detail->food_id) ? $detail->food['name'] : $detail->campaign['name']}} image">
                            <div class="media-body align-self-center">
                                <h5 class="text-hover-primary mb-0">{{isset($detail->food_id) ?  Str::limit($detail->food['name'],30)  : Str::limit($detail->campaign['name'],30) }}</h5>
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
                                <div class="font-size-sm text-body">
                                    <span>{{ translate('Price') }} : </span>
                                    <span class="font-weight-bold">
                                        {{ \App\CentralLogics\Helpers::format_currency($detail['price']) }}
                                    </span>
                                </div>
                                <div class="font-size-sm text-body">
                                    <span>{{ translate('Qty') }} : </span>
                                    <span class="font-weight-bold">
                                        {{ $detail['quantity'] }}
                                    </span>
                                </div>
                            </div>
                        </a>
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
                    <td class="text-right">
                        @php($amount = $detail['price'] * $detail['quantity'])
                        <h6>{{ \App\CentralLogics\Helpers::format_currency($detail['price']) }}</h6>
                    </td>
                </tr>
                @php($product_price += $amount)
                                    @php($restaurant_discount_amount += $detail['discount_on_food'] * $detail['quantity'])
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<?php
     $coupon_discount_amount = $order['coupon_discount_amount'];
                        $total_price = $product_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount -$order['additional_charge'];

                        $tax_a=$order['total_tax_amount'];;
                        if($order->tax_status == 'included'){
                            $tax_a=0;
                        }

                        $subTotal=$order->order_amount * $subscription->quantity;

                      $totalDelivered=  $order->order_amount * $subscription->logs()->whereIn('order_status',['delivered'])->count();
?>
<div class="row justify-content-end mb-3 px-4">
    <div class="col-md-7 col-lg-6 col-xl-5 prices-calc">
        <div class="row g-1">
           <div class="col-6 text-capitalize">{{ translate('messages.items_price') }} :</div>
            <div class="col-6 text-right"> {{ \App\CentralLogics\Helpers::format_currency($product_price) }}</div>

            <div class="col-6">{{ translate('messages.addon_cost') }}:</div>
            <div class="col-6 text-right"> {{ \App\CentralLogics\Helpers::format_currency($total_addon_price) }}</div>

            <div class="col-6"> {{ translate('messages.Food_Discount') }}:</div>
            <div class="col-6 text-right">- {{ \App\CentralLogics\Helpers::format_currency($restaurant_discount_amount) }}</div>

            <div class="col-6">{{ translate('messages.coupon_discount') }}:</div>
            <div class="col-6 text-right">- {{ \App\CentralLogics\Helpers::format_currency($coupon_discount_amount) }}</div>
            @if ($order->tax_status == 'excluded' || $order->tax_status == null  )
            <div class="col-6">VAT/TAX:</div>
            <div class="col-6 text-right">+ {{ \App\CentralLogics\Helpers::format_currency($tax_a) }}</div>
            @else
             <div class="col-6">VAT/TAX: ({{ translate('messages.TAX_Included') }})</div>
            <div class="col-6 text-right"> {{ \App\CentralLogics\Helpers::format_currency(0) }}</div>
            @endif

              @if (\App\CentralLogics\Helpers::get_business_data('additional_charge_status') == 1 || $order['additional_charge'] > 0)
                    @php($additional_charge_status = 1)
                @else
                    @php($additional_charge_status = 0)

                    @endif
                    @if ( $additional_charge_status)
                    <div class="col-6">{{ \App\CentralLogics\Helpers::get_business_data('additional_charge_name')??\App\CentralLogics\Helpers::get_business_data('additional_charge_name')??translate('messages.additional_charge') }}</div>
                    <div class="col-6 text-right">+ {{ \App\CentralLogics\Helpers::format_currency($order['additional_charge']) }}</div>
                    @endif

            <div class="col-6">{{ translate('Delivery_Fee') }}</div>
            <div class="col-6 text-right">+  {{ \App\CentralLogics\Helpers::format_currency($order->delivery_charge) }}</div>
            <div class="col-6">{{ translate('DM_Tips') }}</div>
            <div class="col-6 text-right">+  {{ \App\CentralLogics\Helpers::format_currency($order->dm_tips) }}</div>

            <div class="col-12">
                <div class="bb-dashed"></div>
            </div>

            <strong class="col-6">{{ translate('Order_Amount') }}:</strong>
            <strong class="col-6 text-right">{{ \App\CentralLogics\Helpers::format_currency($order->order_amount) }}</strong>

            <strong class="col-6">{{ translate('Sub_Total') }}:</strong>
            <strong class="col-6 text-right">{{ \App\CentralLogics\Helpers::format_currency($subTotal) }}</strong>

            <strong class="col-6"> {{ translate('Total_Delivered') }} :</strong>
            <strong class="col-6 text-right">{{ \App\CentralLogics\Helpers::format_currency($totalDelivered) }}</strong>

            <strong class="col-6">{{ translate('Due') }}:</strong>
            <strong class="col-6 text-right">{{ \App\CentralLogics\Helpers::format_currency($subTotal-$totalDelivered) }}</strong>
        </div>
    </div>
</div>
