
<div class="row">
    <div class="col-lg-12 text-center "><h1 > {{translate('Restaurant_Order_Transactions')}}
    </h1></div>
    <div class="col-lg-12">

    <table>
        <thead>
            <tr>
                <th>{{ translate('Filter_Criteria') }}</th>
                <th></th>
                <th>
                    {{ translate('Search_Bar_Content')  }}: {{ $data['search'] ?? translate('N/A') }}
                </th>
                <th> </th>
                </tr>
        <tr>
            <th>{{ translate('messages.sl') }}</th>
            <th>{{translate('messages.Order_Id')}}</th>
            <th>{{translate('messages.Order_Time')}}</th>
            <th>{{translate('messages.Total_Order_Amount')}}</th>
            <th>{{translate('messages.Restaurant_Earned')}}</th>
            <th>{{translate('messages.Admin_Earned')}}</th>
            <th>{{translate('messages.Delivery_Fee')}}</th>
            <th>{{translate('messages.vat/tax')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data['data'] as $key => $dt)
            <tr>
                <td>{{ $loop->index+1}}</td>
                <td>{{$dt->order_id}}</td>
                <td>{{ \App\CentralLogics\Helpers::time_date_format($dt?->created_a) }}</td>
                    <td>{{\App\CentralLogics\Helpers::format_currency($dt->order_amount)}}</td>
                    <td>{{\App\CentralLogics\Helpers::format_currency($dt->restaurant_amount - $dt->tax)}}</td>
                    <td>{{\App\CentralLogics\Helpers::format_currency($dt->admin_commission)}}</td>
                    <td>{{\App\CentralLogics\Helpers::format_currency($dt->delivery_charge)}}</td>
                    <td>{{\App\CentralLogics\Helpers::format_currency($dt->tax)}}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
