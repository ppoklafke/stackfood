<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('messages.Collect_Cash_Transactions') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('filter_criteria') }} -</th>
                <th></th>
                <th></th>
                <th>

                    {{ translate('Search_Bar_Content')  }} - {{ $data['search'] ??translate('N/A') }}

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>{{ translate('messages.sl') }}</th>
                <th>{{ translate('messages.Transaction_Id') }}</th>
                <th>{{ translate('messages.Transaction_Time') }}</th>
                <th>{{ translate('messages.Collected_Amount') }}</th>
                <th>{{ translate('messages.Collected_From') }}</th>
                <th>{{ translate('messages.User_Type') }}</th>
                <th>{{ translate('messages.Phone') }}</th>
                <th>{{ translate('messages.Email') }}</th>
                <th>{{ translate('messages.Payment_Method') }}</th>
                <th>{{ translate('messages.References') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['account_transactions'] as $key => $at)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{$at->id}}</td>
                <td>{{ \App\CentralLogics\Helpers::time_date_format($at->created_at) }}</td>

                <td>{{$at['amount']}}</td>
                <td>
                    @if($at->restaurant)
                    {{ $at->restaurant->name}}
                    @elseif($at->deliveryman)
                    {{ $at->deliveryman->f_name }} {{ $at->deliveryman->l_name }}
                    @else
                        {{translate('messages.not_found')}}
                    @endif
                </td>
                <td>{{translate($at['from_type'])}}</td>
                <td>
                    @if($at->restaurant)
                    {{ $at->restaurant->phone}}
                    @elseif($at->deliveryman)
                    {{ $at->deliveryman->phone }}
                    @else
                        {{translate('messages.not_found')}}
                    @endif
                </td>
                <td>
                    @if($at->restaurant)
                    {{ $at->restaurant->email}}
                    @elseif($at->deliveryman)
                    {{ $at->deliveryman->email }}
                    @else
                        {{translate('messages.not_found')}}
                    @endif
                </td>
                <td>{{translate($at->method)}}</td>
                <td>{{  $at['ref'] ? translate($at['ref']) : translate('messages.N/A') }} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
