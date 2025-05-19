<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('messages.Subscription_Transactions') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('filter_criteria') }} -</th>
                <th></th>
                <th></th>
                <th>
                    @if (isset($data['package_name']))
                    {{ translate('Package_name' )}} - {{ $data['package_name'] }}

                    @elseif (isset($data['restaurant']))
                    {{ translate('restaurant_Name' )}} - {{ $data['restaurant'] }}
                    @else
                    {{ translate('All_transactions' )}}

                    @endif

                    @if ($data['start_date'])
                    <br>
                    {{ translate('start_date' )}} - {{ $data['start_date']?Carbon\Carbon::parse($data['start_date'])->format('d M Y'):'' }}
                    @endif
                    @if ($data['end_date'])
                    <br>
                    {{ translate('end_date' )}} - {{ $data['end_date']?Carbon\Carbon::parse($data['end_date'])->format('d M Y'):'' }}
                    @endif
                    <br>
                    {{ translate('filter')  }}- {{  translate($data['filter']) }}
                    <br>
                    {{ translate('plan_type')  }}- {{  translate($data['plan_type']) }}
                    <br>
                    {{ translate('Search_Bar_Content')  }}- {{ $data['search'] ??translate('N/A') }}

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th class="border-top px-4 border-bottom text-center">{{ translate('sl') }}</th>
                <th class="border-top px-4 border-bottom">{{ translate('Transaction_ID') }}</th>
                <th class="border-top px-4 border-bottom"><div class="text-title">{{ translate('Transaction_Date') }}</div></th>
                <th class="border-top px-4 border-bottom">{{ translate('restaurant') }}</th>
                <th class="border-top px-4 border-bottom">{{ translate('Pricing') }}</th>
                <th class="border-top px-4 border-bottom">{{ translate('Payment_Type') }}</th>
                <th class="border-top px-4 border-bottom">{{ translate('Status') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['data'] as $k=> $transaction)
            <tr>
                <td class="px-4 text-center">{{ $k + 1 }}</td>

                <td class="px-4">
                    <div class="text-title">{{ $transaction->id }}</div>
                </td>
                <td class="px-4">
                    <div class="pl-4">{{ \App\CentralLogics\Helpers::date_format($transaction->created_at) }}</div>
                </td>
                <td class="px-4">
                    <div class="text-title">{{ $transaction?->restaurant?->name ?? translate('messages.restaurant deleted!') }}

                    </div>
                </td>
                <td class="px-4">
                    <div class="w--120px text-title text-right pr-5">{{ \App\CentralLogics\Helpers::format_currency($transaction->paid_amount) }}</div>
                </td>
                <td class="px-4">
                    <div>
                        @if ( $transaction->plan_type == 'renew'  )
                        <div class="text-title">{{ translate('Renewal') }}</div>
                        @elseif ($transaction->plan_type == 'new_plan'  )
                        <div class="text-title">{{ translate('Migrate_to_New_Plan') }}</div>
                        @elseif ($transaction->plan_type == 'first_purchased'  )
                        <div class="text-title">{{ translate('Purchased') }}</div>
                        @else
                        <div class="text-title">{{ translate($transaction->plan_type) }}</div>
                        @endif
                        &nbsp;
                        <div class="text-success font-medium">{{ translate('Paid_by') }}  {{ translate($transaction->payment_method) }}</div>
                    </div>
                </td>
                <td class="px-4">
                    @if ( $transaction->payment_status == 'success')
                    <span class="text-success">
                    @elseif($transaction->payment_status ==  'on_hold')
                    <span class="text--info">
                    @else
                        <span class="text--danger">
                    @endif
                        {{ translate($transaction->payment_status)  }}
                    </span>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
