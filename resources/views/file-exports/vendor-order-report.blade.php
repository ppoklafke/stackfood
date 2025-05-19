<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('messages.Order_Report') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('filter_criteria') }} -</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('restaurant' )}} - {{ $data['restaurant']??translate('all') }}
                    @if ($data['customer'])
                    <br>
                    {{ translate('customer' )}} - {{ $data['customer']??translate('all') }}
                    @endif
                    @if ($data['from'])
                    <br>
                    {{ translate('from' )}} - {{ $data['from']?Carbon\Carbon::parse($data['from'])->format('d M Y'):'' }}
                    @endif
                    @if ($data['to'])
                    <br>
                    {{ translate('to' )}} - {{ $data['to']?Carbon\Carbon::parse($data['to'])->format('d M Y'):'' }}
                    @endif
                    <br>
                    {{ translate('filter')  }}- {{  translate($data['filter']) }}
                    <br>
                    {{ translate('Search_Bar_Content')  }}- {{ $data['search'] ??translate('N/A') }}

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>{{ translate('messages.sl') }}</th>
                <th>{{ translate('messages.Order_Id') }}</th>
                <th>{{ translate('messages.Restaurant') }}</th>
                <th>{{ translate('messages.Customer_Name') }}</th>
                <th>{{ translate('messages.Total_Item_Amount') }}</th>
                <th>{{ translate('messages.Item_Discount') }}</th>
                <th>{{ translate('messages.Coupon_Discount') }}</th>
                <th>{{ translate('messages.referral_discount') }}</th>
                <th>{{ translate('messages.Discounted_Amount') }}</th>
                <th>{{ translate('messages.Tax') }}</th>
                <th>{{ translate('messages.Delivery_Charge') }}</th>
                <th>{{ \App\CentralLogics\Helpers::get_business_data('additional_charge_name')??translate('messages.Additional_Charge') }}</th>
                <th>{{ translate('messages.extra_packaging_amount') }}</th>
                <th>{{ translate('messages.Order_Amount') }}</th>
                <th>{{ translate('messages.Amount_Received_By') }}</th>
                <th>{{ translate('messages.Payment_Method') }}</th>
                <th>{{ translate('messages.Order_Status') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['orders'] as $key => $order)
        <tr>
            <td >
                {{ $key + 1 }}
            </td>
            <td class="table-column-pl-0">
                {{ $order['id'] }}
            </td>
            <td  class="text-capitalize">
                @if($order->restaurant)
                    {{Str::limit($order->restaurant->name,25,'...')}}
                @else
                   {{ translate('messages.invalid') }}
                @endif
            </td>
            <td>
                @if ($order->customer)

                {{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}
                @else
                   {{ translate('messages.invalid_customer_data') }}
                @endif
            </td>
            <td>
                <div class="text-right mw--85px">
                    <div>
                        {{ \App\CentralLogics\Helpers::number_format_short($order['order_amount'] - $order['additional_charge'] -$order['dm_tips']-$order['total_tax_amount']-$order['delivery_charge'] - $order['extra_packaging_amount'] +$order['coupon_discount_amount'] + $order['restaurant_discount_amount'] + $order['ref_bonus_amount'] ) }}
                    </div>
                    <br>
                    @if ($order->payment_status == 'paid')
                    {{ translate('messages.paid') }}
                    @else
                    {{ translate('messages.unpaid') }}
                    @endif
                </div>
            </td>
            <td class="text-center mw--85px">
                {{ \App\CentralLogics\Helpers::number_format_short($order->details()->sum(DB::raw('discount_on_food * quantity'))) }}
            </td>
            <td class="text-center mw--85px">
                {{ \App\CentralLogics\Helpers::number_format_short($order['coupon_discount_amount']) }}
            </td>
            <td class="text-center mw--85px">
                {{ \App\CentralLogics\Helpers::number_format_short($order['ref_bonus_amount']) }}
            </td>
            <td class="text-center mw--85px">
                {{ \App\CentralLogics\Helpers::number_format_short($order['coupon_discount_amount'] + $order['restaurant_discount_amount'] + $order['ref_bonus_amount']) }}
            </td>
            <td class="text-center mw--85px white-space-nowrap">
                {{ \App\CentralLogics\Helpers::number_format_short($order['total_tax_amount']) }}
            </td>
            <td class="text-center mw--85px">
                {{ \App\CentralLogics\Helpers::number_format_short($order['delivery_charge']) }}
            </td>
            <td class="text-center mw--85px">
                {{ \App\CentralLogics\Helpers::number_format_short($order['additional_charge']) }}
            </td>
            <td class="text-center mw--85px">
                {{ \App\CentralLogics\Helpers::number_format_short($order['extra_packaging_amount']) }}
            </td>
            <td>
                <div class="text-right mw--85px">
                    <div>
                        {{ \App\CentralLogics\Helpers::number_format_short($order['order_amount']) }}
                    </div>
                    <br>
                    @if ($order->payment_status == 'paid')
                        {{ translate('messages.paid') }}
                    @else
                        {{ translate('messages.unpaid') }}
                    @endif
                </div>
            </td>
            <td class="text-center mw--85px text-capitalize">
                {{isset($order->transaction) ? translate(str_replace('_', ' ', $order->transaction->received_by))  : translate('messages.not_received_yet')}}
            </td>
            <td class="text-center mw--85px text-capitalize">
                    {{ translate(str_replace('_', ' ', $order['payment_method'])) }}
            </td>
            <td class="text-center mw--85px text-capitalize">
                @if($order['order_status']=='pending')
                        <span class="badge badge-soft-info">
                          {{translate('messages.pending')}}
                        </span>
                    @elseif($order['order_status']=='confirmed')
                        <span class="badge badge-soft-info">
                          {{translate('messages.confirmed')}}
                        </span>
                    @elseif($order['order_status']=='processing')
                        <span class="badge badge-soft-warning">
                          {{translate('messages.processing')}}
                        </span>
                    @elseif($order['order_status']=='picked_up')
                        <span class="badge badge-soft-warning">
                          {{translate('messages.out_for_delivery')}}
                        </span>
                    @elseif($order['order_status']=='delivered')
                        <span class="badge badge-soft-success">
                            {{$order?->order_type == 'dine_in' ? translate('messages.Completed') : translate('messages.delivered')}}
                        </span>
                    @elseif($order['order_status']=='failed')
                        <span class="badge badge-soft-danger">
                          {{translate('messages.payment_failed')}}
                        </span>
                    @elseif($order['order_status']=='handover')
                        <span class="badge badge-soft-danger">
                          {{translate('messages.handover')}}
                        </span>
                    @elseif($order['order_status']=='canceled')
                        <span class="badge badge-soft-danger">
                          {{translate('messages.canceled')}}
                        </span>
                    @elseif($order['order_status']=='accepted')
                        <span class="badge badge-soft-danger">
                          {{translate('messages.accepted')}}
                        </span>
                    @else
                        <span class="badge badge-soft-danger">
                          {{translate(str_replace('_',' ',$order['order_status']))}}
                        </span>
                    @endif
            </td>

        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
