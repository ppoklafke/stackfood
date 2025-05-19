<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Expense_Reports') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('Search_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>
                    @if(isset($data['zone']))
                    {{ translate('zone' )}} - {{ $data['zone']??translate('all') }}
                    @endif
                    <br>
                    {{ translate('restaurant' )}} - {{ $data['restaurant']??translate('all') }}
                    @if (!isset($data['type'])  && isset($data['customer']))
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
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
        <tr>
            <th>{{ translate('sl') }}</th>
            <th>{{translate('messages.Order_Id')}}</th>
            <th>{{translate('Date_&_Time')}}</th>
            <th>{{ translate('Expense_Type') }}</th>
            <th>{{ translate('Customer_Name') }}</th>
            <th>{{translate('Expense_Amount')}}</th>
        </thead>
        <tbody>
        @foreach($data['expenses'] as $key => $exp)
            <tr>
                <td>{{ $key+1}}</td>
                <td>
                    @if ($exp->order)
                    {{ $exp['order_id'] }}
                    @endif
                </td>

                <td>{{ \App\CentralLogics\Helpers::time_date_format($exp->created_at) }}</td>

                <td>{{translate("messages.{$exp['type']}")}}</td>
                <td class="text-center">
                    @if (isset($exp->order->customer))
                    {{ $exp->order->customer->f_name.' '.$exp->order->customer->l_name }}
                    @elseif ($exp['type'] == 'add_fund_bonus')
                    {{ $exp?->user?->f_name.' '.$exp?->user?->l_name }}
                    @elseif(isset($exp->order->guest))
                    @php($customer_details = json_decode($exp->order['delivery_address'],true))
                    <strong>{{$customer_details['contact_person_name']}}</strong>
                    <div>{{$customer_details['contact_person_number']}}</div>
                @else
                    <label class="badge badge-danger">{{translate('messages.invalid_customer')}}</label>
                    @endif
                </td>
                <td>{{\App\CentralLogics\Helpers::format_currency($exp['amount'])}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
