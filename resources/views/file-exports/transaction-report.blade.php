<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Order_Transactions_Report') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('Search_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>

                    {{ translate('zone' )}} - {{ $data['zone']??translate('all') }}
                    <br>
                    {{ translate('restaurant' )}} - {{ $data['restaurant']??translate('all') }}
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
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
            <tr>
                <th>{{ translate('Transaction_Analytics') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Completed_Transactions')  }}- {{  \App\CentralLogics\Helpers::format_currency($data['delivered']) ??translate('N/A') }}
                    <br>
                    {{ translate('Refunded_Transactions')  }}- {{  \App\CentralLogics\Helpers::format_currency($data['canceled']) ??translate('N/A') }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>{{ translate('Earning_Analytics') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Admin_Earnings')  }} - {{  \App\CentralLogics\Helpers::format_currency($data['admin_earned']) ??translate('N/A') }}
                    <br>
                    {{ translate('restaurant_Earnings')  }} - {{  \App\CentralLogics\Helpers::format_currency($data['restaurant_earned']) ??translate('N/A') }}
                    <br>
                    {{ translate('Delivery_Man_Earnings')  }} - {{  \App\CentralLogics\Helpers::format_currency($data['deliveryman_earned']) ??translate('N/A') }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        <tr>
            <th>{{ translate('sl') }}</th>
            <th>{{ translate('messages.Order_Id') }}</th>
            <th>{{ translate('messages.Restaurant') }}</th>
            <th>{{ translate('messages.Customer_Name') }}</th>
            <th>{{ translate('messages.Total_Item_amount') }}</th>
            <th>{{ translate('messages.Item_Discount') }}</th>
            <th>{{ translate('messages.Coupon_Discount') }}</th>
            <th>{{ translate('messages.Referral_Discount') }}</th>
            <th>{{ translate('messages.Discounted_Amount') }}</th>
            <th>{{ translate('messages.vat/tax') }}</th>
            <th>{{ translate('messages.Delivery_Charge') }}</th>
            <th>{{ translate('messages.Order_Amount') }}</th>
            <th>{{ translate('messages.Admin_Discount') }}</th>
            <th>{{ translate('messages.Restaurant_Discount') }}</th>
            <th>{{ translate('messages.Admin_Commission') }}</th>
            <th>{{ \App\CentralLogics\Helpers::get_business_data('additional_charge_name')??translate('messages.Additional_Charge') }}</th>
            <th>{{ translate('messages.extra_packaging_amount') }}</th>
            <th>{{ translate('commission_On_Delivery_Charge') }}</th>
            <th>{{ translate('Admin_Net_Income') }}</th>
            <th>{{ translate('Restaurant_Net_Income') }}</th>
            <th>{{ translate('messages.Amount_Received_By') }}</th>
            <th>{{ translate('messages.Payment_Method') }}</th>
            <th>{{ translate('messages.Payment_Status') }}</th>
        </thead>
        <tbody>



        @foreach($data['order_transactions'] as $key => $ot)
            <tr>
                <td>{{ $key+1}}</td>
                <td>{{ $ot->order_id }}</td>
                <td>
                    @if($ot->order->restaurant)
                        {{Str::limit($ot->order->restaurant->name,25,'...')}}
                    @else
                        {{ translate('messages.not_found') }}
                    @endif
                </td>
                <td>
                    @if ($ot->order->customer)
                        {{  $ot->order->customer['f_name'] . ' ' . $ot->order->customer['l_name']  }}
                    @else
                        {{ translate('messages.not_found') }}
                    @endif
                </td>

                <td>
                    {{ \App\CentralLogics\Helpers::format_currency($ot->order['order_amount']  - $ot?->additional_charge - $ot->order['dm_tips']-$ot->order['delivery_charge'] - $ot['tax'] - $ot->order['extra_packaging_amount'] + $ot->order['coupon_discount_amount'] + $ot->order['restaurant_discount_amount'] + $ot->order['ref_bonus_amount'] ) }}
                </td>

                <td>{{ \App\CentralLogics\Helpers::format_currency($ot?->order?->restaurant_discount_amount) }}</td>
                <td>{{ \App\CentralLogics\Helpers::format_currency($ot->order['coupon_discount_amount']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::format_currency($ot->order['ref_bonus_amount']) }}</td>
                <td>  {{ \App\CentralLogics\Helpers::number_format_short($ot->order['coupon_discount_amount'] + $ot->order['restaurant_discount_amount'] + $ot->order['ref_bonus_amount']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::format_currency($ot->tax) }}</td>
                <td>{{ \App\CentralLogics\Helpers::format_currency($ot['delivery_charge'] + $ot['delivery_fee_comission']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::format_currency($ot->order_amount) }}</td>
                <td>{{ \App\CentralLogics\Helpers::format_currency($ot->admin_expense) }}</td>

                <td>{{ \App\CentralLogics\Helpers::format_currency($ot->discount_amount_by_restaurant) }}</td>

                @php
                    $discount_by_admin = 0;
                    if($ot->order->discount_on_product_by == 'admin'){
                        $discount_by_admin = $ot->order['restaurant_discount_amount'];
                    };
                @endphp
                <td>
                    {{ \App\CentralLogics\Helpers::format_currency($ot->admin_commission - $ot?->additional_charge + $ot?->admin_expens ) }}
                </td>
                <td>{{ \App\CentralLogics\Helpers::format_currency($ot?->additional_charge) }}</td>
                <td>{{ \App\CentralLogics\Helpers::format_currency(($ot->extra_packaging_amount)) }}</td>
                <td>{{ \App\CentralLogics\Helpers::format_currency($ot->delivery_fee_comission) }}</td>
                <td>{{ \App\CentralLogics\Helpers::format_currency($ot['admin_commission'] + $ot->delivery_fee_comission) }}</td>
                <td>{{ \App\CentralLogics\Helpers::format_currency($ot->restaurant_amount - $ot->tax) }}</td>
                @if ($ot->received_by == 'admin')
                    <td>{{ translate('messages.admin') }}</td>
                @elseif ($ot->received_by == 'deliveryman')
                    <td>
                        <div>{{ translate('messages.delivery_man') }}</div>
                        <br>
                        <div>
                            @if (isset($ot->delivery_man) && $ot->delivery_man->earning == 1)
                                {{translate('messages.freelance')}}
                            @elseif (isset($ot->delivery_man) && $ot->delivery_man->earning == 0 && $ot->delivery_man->type == 'restaurant_wise')
                                {{translate('messages.restaurant')}}
                            @elseif (isset($ot->delivery_man) && $ot->delivery_man->earning == 0 && $ot->delivery_man->type == 'zone_wise')
                                {{translate('messages.admin')}}
                            @endif
                        </div>
                    </td>
                @elseif ($ot->received_by == 'restaurant')
                    <td>{{ translate('messages.restaurant') }}</td>
                @endif
                <td>
                        {{ translate(str_replace('_', ' ', $ot->order['payment_method'])) }}
                </td>
                <td>
                    @if ($ot->status)
                        {{translate('messages.refunded')}}
                    @else
                        {{translate('messages.completed')}}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
