
<div class="row">
    <div class="col-lg-12 text-center "><h1 > {{translate('Restaurant_Cash_Transactions')}}
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
            <th>{{translate('messages.Transaction_ID')}}</th>
            <th>{{translate('messages.Received_At')}}</th>
            <th>{{translate('messages.Balance_Before_Transaction')}}</th>
            <th>{{translate('messages.Amount')}}</th>
            <th>{{translate('messages.Reference')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data['data'] as $key => $at)
            <tr>
                <td>{{ $loop->index+1}}</td>
                    <td>{{  $at->id }}</td>
                    <td>{{ \App\CentralLogics\Helpers::time_date_format($at->created_at)  }}</td>
                    <td>{{\App\CentralLogics\Helpers::format_currency($at['current_balance'])}}</td>
                    <td>{{\App\CentralLogics\Helpers::format_currency($at['amount'])}}</td>
                    <td>{{$at['ref'] ??  translate('N/A')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
