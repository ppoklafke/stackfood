<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Deliveryman_List') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('Search_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('zone' )}} - {{ $data['zone']??translate('all') }}
                    <br>
                    {{ translate('Search_Bar_Content')  }}- {{ $data['search'] ??translate('N/A') }}

                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
            <tr>
                <th>{{ translate('Analytics') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('total_delivery_man')  }}- {{ $data['delivery_men']->count() }}
                    <br>
                    {{ translate('active_delivery_man')  }}- {{ $data['delivery_men']->where('status',1)->count()}}
                    <br>
                    {{ translate('inactive_delivery_man')  }}- {{ $data['delivery_men']->where('status',0)->count() }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        <tr>
            <th>{{ translate('sl') }}</th>
            <th>{{translate('Image')}}</th>
            <th>{{ translate('First_Name') }}</th>
            <th>{{ translate('Fast_Name') }}</th>
            <th>{{ translate('Phone') }}</th>
            <th>{{ translate('Email') }}</th>
            <th>{{ translate('Deliveryman_Type') }}</th>
            <th>{{ translate('Total_Completed') }}</th>
            <th>{{ translate('Total_Running_Orders') }}</th>
            <th>{{ translate('Status') }}</th>
            <th>{{ translate('Zone') }}</th>
            <th>{{ translate('Vehicle_Type') }}</th>
            <th>{{ translate('Identity_Type') }}</th>
            <th>{{ translate('Identity_Number') }}</th>
        </thead>
        <tbody>
        @foreach($data['delivery_men'] as $key => $item)
        <tr>
            <td>{{$key+1}}</td>
            <td></td>
            <td>{{  $item['f_name']  }}</td>
            <td>{{  $item['l_name']  }}</td>
            <td>{{  $item['phone']  }}</td>
            <td>{{  $item['email']  }}</td>
            <td>{{ $item->earning?translate('messages.freelancer'):translate('messages.salary_based') }}</td>
            <td>{{ $item['order_count'] }}</td>
            <td>{{ $item['current_orders'] }}</td>
            <td>{{ $item->active?translate('messages.online'):translate('messages.offline') }}</td>
            <td>{{ $item->zone?$item->zone->name: translate('N/A') }}</td>
            <td>{{ $item->vehicle?$item->vehicle->type: translate('N/A') }}</td>
            <td>{{ translate($item->identity_type) }}</td>
            <td>{{ $item->identity_number }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
