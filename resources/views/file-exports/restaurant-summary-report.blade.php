<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Restaurant_Reports') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('Search_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('filter')  }}- {{  translate($data['filter']) }}
                    <br>
                    {{ translate('Zone')  }}- {{  $data['zone'] ?? translate('All') }}
                    <br>
                    {{ translate('restaurant_model')  }}- {{  $data['restaurant_model'] ?? translate('All') }}
                    <br>
                    {{ translate('Search_Bar_Content')  }}- {{ $data['search'] ??translate('N/A') }}

                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
            <tr>
                <th>{{ translate('Analytics') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('restaurant')  }}- {{ $data['total_restaurants'] ??translate('N/A') }}
                    <br>
                    {{ translate('total_orders')  }}- {{ $data['orders'] ??translate('N/A') }}
                    <br>
                    {{ translate('total_order_amount')  }}- {{ \App\CentralLogics\Helpers::number_format_short($data['restaurants']->sum('transaction_sum_order_amount')) ??translate('N/A') }}
                    <br>
                    {{ translate('completed_orders')  }}- {{ $data['restaurants']->sum('without_refund_total_orders_count') ??translate('N/A') }}
                    <br>
                    {{ translate('incomplete_orders')  }}- {{ $data['total_ongoing'] ??translate('N/A') }}
                    <br>
                    {{ translate('canceled_orders')  }}- {{ $data['total_canceled'] ??translate('N/A') }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>{{ translate('Payment_Statistics') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('cash_payments')  }} - {{ $data['cash_payments'] ??translate('N/A') }}
                    <br>
                    {{ translate('digital_payments')  }} - {{ $data['digital_payments'] ??translate('N/A') }}
                    <br>
                    {{ translate('wallet_payments')  }} - {{ $data['wallet_payments'] ??translate('N/A') }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        <tr>
            <th>{{ translate('sl') }}</th>
            <th class="w--2">{{ translate('messages.Restaurant') }}</th>
            <th>{{ translate('messages.Total_Food') }}</th>
            <th>{{ translate('messages.Total_Order') }}</th>
            <th>{{ translate('messages.Total_Order_Amount') }}</th>
            <th>{{ translate('messages.Total_Discount_Given') }}</th>
            <th>{{ translate('messages.Total_Admin_Commission') }}</th>
            <th>{{ translate('messages.Total_Vat_Tax') }}</th>
            <th>{{ translate('messages.Average_Ratings') }}</th>
            <th>{{ translate('messages.Rating_Given') }}</th>
        </thead>
        <tbody>
        @foreach($data['restaurants'] as $key => $restaurant)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>
                {{ Str::limit($restaurant->name, 20, '...') }}<br>
            </td>

            <td>
                {{ $restaurant->foods_count }}
            </td>
            <td>
                {{$restaurant->without_refund_total_orders_count }}
            </td>
            <td>
                {{ \App\CentralLogics\Helpers::format_currency($restaurant->transaction_sum_order_amount) }}
            </td>
            <td>
                {{\App\CentralLogics\Helpers::format_currency( $restaurant->transaction_sum_restaurant_expense)}}
            </td>
            <td>
                {{\App\CentralLogics\Helpers::format_currency( $restaurant->transaction_sum_admin_commission)}}
            </td>
            <td>
                {{\App\CentralLogics\Helpers::format_currency( $restaurant->transaction_sum_tax)}}
            </td>
            <td>

                    @if ($restaurant->reviews_count)
                    @php($reviews_count = $restaurant->reviews_count)
                    @php($reviews = $reviews_count)
                    @else
                    @php($reviews = 0)
                    @php($reviews_count = 1)
                    @endif
                    {{ round($restaurant->reviews_sum_rating /$reviews_count,1) }}
            </td>
            <td>{{ $reviews }} </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
