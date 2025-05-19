<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Basic_Campaign_List') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('Message_Analytics') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Total_Campaign')  }}: {{ $data->count() }}
                    <br>
                    {{ translate('Currently_Running')  }}: {{ $data->where('status',1)->count() }}

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
                    {{ translate('Search_Bar_Content')  }}: : {{ $search ??translate('N/A') }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
        <tr>
            <th>{{ translate('sl') }}</th>
            <th>{{ translate('Campaign_Name') }}</th>
            <th>{{ translate('Description') }}</th>
            <th>{{ translate('Start_Date') }}</th>
            <th>{{ translate('End_Date') }}</th>
            <th>{{ translate('Daily_Start_Time') }}</th>
            <th>{{ translate('Daily_End_Time') }}</th>
            <th>{{ translate('Total_Restaurant_Joined') }} </th>
        </thead>
        <tbody>
        @foreach($data as $key => $campaign)
            <tr>
        <td>{{ $loop->index+1}}</td>
        <td>{{ $campaign->title }}</td>
        <td>{{ $campaign->description }}</td>
        <td>{{ \App\CentralLogics\Helpers::date_format($campaign->start_date) }}</td>
        <td>{{ \App\CentralLogics\Helpers::date_format($campaign->end_date) }}</td>
        <td>{{ \App\CentralLogics\Helpers::time_format($campaign->start_time) }}</td>
        <td>{{ \App\CentralLogics\Helpers::time_format($campaign->end_time) }}</td>
        <td>{{ $campaign->restaurants->count() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
