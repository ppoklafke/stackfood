<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('messages.Deliveryman_Payments') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('filter_criteria') }} -</th>
                <th></th>
                <th></th>
                <th>

                    {{ translate('Search_Bar_Content')  }}- {{ $data['search'] ??translate('N/A') }}

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>{{ translate('messages.sl') }}</th>
                <th>{{ translate('messages.Transaction_Id') }}</th>
                <th>{{ translate('messages.Provided_At') }}</th>
                <th>{{ translate('messages.Payment_Amount') }}</th>
                <th>{{ translate('messages.Deliveryman_Name') }}</th>
                <th>{{ translate('messages.Phone') }}</th>
                <th>{{ translate('messages.Payment_Method') }}</th>
                <th>{{ translate('messages.References') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['dm_earnings'] as $key => $at)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{$at->id}}</td>
                <td>{{ \App\CentralLogics\Helpers::time_date_format($at->created_at) }}</td>

                <td>{{$at['amount']}}</td>
                <td>
                    @if($at->delivery_man)
                    {{$at->delivery_man->f_name.' '.$at->delivery_man->l_name}}
                    @else
                    {{translate('messages.deliveryman_deleted')}}
                    @endif
                </td>
                <td>
                    @if($at->delivery_man)
                    {{$at->delivery_man->phone}}
                    @else
                    {{translate('messages.deliveryman_deleted')}}
                    @endif
                </td>
                <td>{{translate($at->method)}}</td>

                @if(  $at['ref'] == 'delivery_man_wallet_adjustment_full')
                <td>{{ translate('wallet_adjusted') }}</td>
                @elseif( $at['ref'] == 'delivery_man_wallet_adjustment_partial')
                    <td>{{ translate('wallet_adjusted_partially') }}</td>
                @else
                    <td>{{translate($at['ref'])?? translate('N/A') }}</td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
