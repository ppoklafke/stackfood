<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Food_Campaign_Orders_List') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('Message_Analytics') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Total_Orders_in_Campaign')  }}: {{ $data['data']->count() }}
                    <br>
                    {{ translate('Campaign_Name') }}  : {{ $data['campaign']->title }}
                    <br>
                    {{ translate('Restaurant_Name') }}  : {{ $data['campaign']->restaurant?->name }}

                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
            <tr>
                <th>{{ translate('Search_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Search_Bar_Content')  }}: {{  $data['search'] ??translate('N/A') }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
        <tr>
            <th>{{ translate('sl') }}</th>
            <th>{{ translate('Order_id') }}</th>
            <th>{{ translate('Order_Date') }}</th>
            <th>{{ translate('Order_Amount') }}</th>
            <th>{{ translate('Payment_Status') }}</th>
            <th>{{ translate('Order_Status') }}</th>
            <th>{{ translate('Customer_Name') }}</th>
            <th>{{ translate('Start_Date') }}</th>
            <th>{{ translate('End_Date') }}</th>
            <th>{{ translate('Daily_Start_Time') }}</th>
            <th>{{ translate('Daily_End_Time') }}</th>
        </thead>
        <tbody>
        @foreach($data['data'] as $key => $or)
            <tr>
        <td>{{ $loop->index+1}}</td>
        <td>{{ $or->order_id }}</td>
        <td>{{ \App\CentralLogics\Helpers::date_format($or->order->created_at) }}</td>
        <td> {{\App\CentralLogics\Helpers::format_currency($or->order->order_amount)}}  </td>
        <td> {{ $or?->order?->payment_status =='paid' ? translate('messages.Paid') : translate('messages.Unpaid!') }} </td>
        <td> {{ translate($or->order->order_status) }} </td>
        <td>{{ $or->order->customer ?$or->order->customer->f_name.' '.$or->order->customer->l_name : translate('messages.not_found') }}</td>
        <td>{{ \App\CentralLogics\Helpers::date_format($data['campaign']->start_date) }}</td>
        <td>{{ \App\CentralLogics\Helpers::date_format($data['campaign']->end_date) }}</td>
        <td>{{ \App\CentralLogics\Helpers::time_format($data['campaign']->start_time) }}</td>
        <td>{{ \App\CentralLogics\Helpers::time_format($data['campaign']->end_time) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
