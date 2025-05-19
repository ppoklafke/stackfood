
<div class="row">
    <div class="col-lg-12 text-center "><h1 > {{translate('Subcription_Package_List')}}
    </h1></div>
    <div class="col-lg-12">

    <table>
        <thead>
            <tr>
                <th>{{ translate('Search_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Search_Bar_Content')  }}: {{ $data['search'] ??translate('N/A') }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>


        <tr>
            <th>{{ translate('sl') }}</th>
            <th>{{ translate('Package_Name') }}</th>
            <th>{{ translate('Price') }}</th>
            <th>{{ translate('Duration') }}</th>
            <th>{{ translate('Current_Subscriber') }}</th>
            <th>{{ translate('Status') }}</th>
        </thead>
        <tbody>
        @foreach($data['data'] as $key => $package)
            <tr>
        <td>{{ $key+1}}</td>
        <td> {{ Str::limit($package->package_name, 30, '...')   }}</td>
        <td> {{ \App\CentralLogics\Helpers::format_currency($package->price) }}</td>
        <td> {{$package->validity}}</td>
        {{-- <td>{{$package->transactions_count}}</td> --}}
        <td>{{$package->current_subscribers_count ?? 0}}</td>
        <td>{{$package->status? translate('Active'):translate('Inactive')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
