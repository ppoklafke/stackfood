
<div class="row">
    <div class="col-lg-12 text-center "><h1 > {{translate('Cuisine_List')}}
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
            <th>{{ translate('sl') }}</th>
            <th>{{ translate('Cuisine_Id') }}</th>
            <th>{{ translate('Cuisine_Name') }}</th>
            <th>{{ translate('Total_Restaurants') }}</th>
            <th>{{ translate('Status') }}</th>

        </thead>
        <tbody>
        @foreach($data['data'] as $key => $addon)
            <tr>
        <td>{{ $loop->index+1}}</td>
        <td>{{ $addon->id }}</td>
        <td>{{ $addon->name }}</td>
        <td>{{ $addon->restaurants_count }}</td>
        <td>{{$addon->status == 1 ? translate('messages.Active') : translate('messages.Inactive') }}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
