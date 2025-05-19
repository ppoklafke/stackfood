<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Subscription_Report') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('Search_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('package' )}} - {{ $data['package']??translate('all') }}
                    <br>
                    {{ translate('restaurant')}} - {{ $data['restaurant']??translate('all') }}
                    @if ($data['from'])
                    <br>
                    {{ translate('from')}} - {{ $data['from']?Carbon\Carbon::parse($data['from'])->format('d M Y'):'' }}
                    @endif
                    @if ($data['to'])
                    <br>
                    {{ translate('to')}} - {{ $data['to']?Carbon\Carbon::parse($data['to'])->format('d M Y'):'' }}
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
            <th >{{translate('sl')}}</th>
            <th >{{ translate('messages.Transaction_Id') }}</th>
            <th >{{ translate('Transaction Date') }}</th>
            <th >{{ translate('messages.Restaurant_Name') }}</th>
            <th >{{ translate('messages.Package_Name') }}</th>
            <th >{{ translate('messages.Duration') }}</th>
            <th >{{ translate('messages.Pricing') }}</th>
            <th >{{ translate('messages.Payment Status') }}</th>
            <th >{{ translate('messages.Payment_Method') }}</th>
        </thead>
        <tbody>
        @foreach($data['data'] as $key => $transcation)
        <tr>
            <td>
                {{ $key + 1 }}
            </td>
            <td>{{ Str::limit($transcation->id, 40, '...') }}</td>

            <td>{{ \App\CentralLogics\Helpers::time_date_format($transcation->created_at) }}</td>

            <td>
                {{ Str::limit($transcation->restaurant->name, 20, '...') }}
            </td>
            <td>

                {{ Str::limit($transcation->package->package_name, 20, '...') }}

            </td>
            <td>{{ $transcation->validity }} {{ translate('messages.Days') }}</td>
            <td>{{ \App\CentralLogics\Helpers::format_currency($transcation->package->price) }}</td>


            <td>
                <div>
                    {{ \App\CentralLogics\Helpers::format_currency($transcation->paid_amount) }}
                </div>
                <br>
                @if ($transcation->payment_status == 'success')
                    <small class="text-success text-capitalize">
                        {{ translate('messages.paid') }}
                    </small>
                @elseif($transcation->payment_status == 'on_hold')
                    <small class="text-warning text-capitalize">
                        {{ translate('messages.Payment_On_Hold') }}
                    </small>
                @elseif($transcation->payment_status == 'failed')
                    <small class="text-danger text-capitalize">
                        {{ translate('messages.Payment_Failed') }}
                    </small>
                @endif
            </td>
            <td>
                <div class="text-success text-capitalize">
                    @if ($transcation->payment_method == 'wallet')
                        {{ translate('messages.Wallet_payment') }}
                    @elseif($transcation->payment_method == 'manual_payment_admin')
                        {{ translate('messages.Manual_payment') }}
                    @elseif($transcation->payment_method == 'manual_payment_by_restaurant')
                        {{ translate('messages.Manual_payment') }}
                    @elseif($transcation->payment_method == 'free_trial')
                        {{ translate('messages.free_trial') }}
                    @elseif($transcation->payment_method == 'pay_now')
                        {{ translate('messages.Digital_Payment') }}
                    @else
                        {{ translate($transcation->payment_method) }}
                    @endif

                </div>
            </td>

        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
