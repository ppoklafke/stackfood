<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('messages.Restaurant_Withdraw_Transactions') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('filter_criteria') }} -</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('request_status')  }}- {{  $data['request_status']?translate($data['request_status']):translate('all') }}
                    <br>
                    {{ translate('Search_Bar_Content')  }}- {{ $data['search'] ??translate('N/A') }}

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>{{ translate('messages.sl') }}</th>
                <th>{{ translate('messages.Request_Time') }}</th>
                <th>{{ translate('messages.Requested_Amount') }}</th>
                <th>{{ translate('messages.Restaurant_Name') }}</th>
                <th>{{ translate('messages.Owner_Name') }}</th>
                <th>{{ translate('messages.Phone') }}</th>
                <th>{{ translate('messages.Email') }}</th>
                <th>{{ translate('messages.Bank_Account_No.') }}</th>
                <th>{{ translate('messages.Request_Status') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['withdraw_requests'] as $key => $wr)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ \App\CentralLogics\Helpers::time_date_format($wr->created_at) }}</td>

                <td>{{$wr['amount']}}</td>
                <td>
                    @if($wr->vendor)
                    {{ $wr->vendor->restaurants[0]->name }}
                    @else
                    {{translate('messages.restaurant deleted!') }}
                    @endif
                </td>
                <td>{{$wr->vendor->f_name}} {{$wr->vendor->l_name}}</td>
                <td>{{$wr->vendor->phone}}</td>
                <td>{{$wr->vendor->email}}</td>
                <td>{{$wr->vendor && $wr->vendor->account_no ? $wr->vendor->account_no : 'No Data found'}}</td>
                <td>
                    @if($wr->approved==0)
                        {{ translate('messages.pending') }}
                    @elseif($wr->approved==1)
                        {{ translate('messages.approved') }}
                    @else
                        {{ translate('messages.denied') }}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
