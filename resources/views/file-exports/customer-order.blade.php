<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('messages.Customer_Order_List') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('Customer_Information') }} -</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Customer_Id' )}} : {{ $data['customer_id'] }}
                    <br>
                    {{ translate('name' )}} : {{ $data['customer_name'] }}
                    <br>
                    {{ translate('Phone' )}} : {{ $data['customer_phone'] }}
                    <br>
                    {{ translate('email' )}} : {{ $data['customer_email'] }}
                    <br>
                    {{ translate('Total_Orders' )}} : {{ $data['orders']->count() }}

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>{{ translate('messages.sl') }}</th>
                <th>{{ translate('messages.Order_Id') }}</th>
                <th>{{ translate('messages.Order_Date') }}</th>
                <th>{{ translate('messages.Total_Items') }}</th>
                <th>{{ translate('messages.Restaurant_Name') }}</th>
                <th>{{ translate('messages.Food_Price') }}</th>
                <th>{{ translate('messages.Food_Discount') }}</th>
                <th>{{ translate('messages.Coupon_Discount') }}</th>
                <th>{{ translate('messages.Discounted_Amount') }}</th>
                <th>{{ translate('messages.Tax') }}</th>
                <th>{{ translate('messages.Total_Amount') }}</th>
                <th>{{ translate('messages.Payment_Status') }}</th>
                <th>{{ translate('messages.Order_Status') }}</th>
                <th>{{ translate('messages.Order_Type') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['orders'] as $key => $order)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $order->id }}</td>
                <td>{{ \App\CentralLogics\Helpers::date_format($order->created_at) }}</td>
                <td>{{ $order->details->count() }}</td>
                <td> {{$order?->restaurant?->name ??  translate('messages.not_found')}} </td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['order_amount']-$order['dm_tips']-$order['total_tax_amount']-$order['delivery_charge']+$order['coupon_discount_amount'] + $order['restaurant_discount_amount']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order->details()->sum(DB::raw('discount_on_food * quantity'))) }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['coupon_discount_amount']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['coupon_discount_amount'] + $order['restaurant_discount_amount']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['total_tax_amount']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['order_amount']) }}</td>
                <td>{{ translate($order->payment_status) }}</td>
                <td>{{ translate($order->order_status) }}</td>
                <td>{{ translate($order->order_type) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
