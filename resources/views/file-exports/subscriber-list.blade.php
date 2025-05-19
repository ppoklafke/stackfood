<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Subscriber_List') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('Filter_Criteria') }}</th>
                <th></th>
                <th>
                    {{ translate('Filter')  }}: {{ $data['filter'] ?? translate('N/A') }}
                    <br>

                    @if (isset($data['subscription_date']))
                    {{ translate('subscription_date')  }}: {{ $data['subscription_date'] ?? translate('N/A') }}
                    <br>
                    @endif
                    @if (isset($data['chose_first']))
                    {{ translate('chose_first')  }}: {{ $data['chose_first'] ?? translate('N/A') }}
                    <br>
                    @endif
                    {{ translate('Search_Bar_Content')  }}: {{ $data['search'] ?? translate('N/A') }}
                    <br>
                </th>
                <th> </th>
                </tr>
        <tr>
            <th>{{ translate('sl') }}</th>
            <th>{{ translate('email') }}</th>
            <th>{{ translate('subscribed_at') }}</th>
        </thead>
        <tbody>
        @foreach($data['customers'] as $key => $customer)
            <tr>
        <td>{{ $key+1}}</td>
        <td>{{ $customer['email'] }}</td>
        <td>{{ \App\CentralLogics\Helpers::time_date_format($customer->created_at) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
