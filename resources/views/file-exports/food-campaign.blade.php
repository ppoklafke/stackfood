<div class="row">
    <div class="col-lg-12 text-center "><h1 > {{ translate('Food_Campaign_List') }}
    </h1></div>
    <div class="col-lg-12">

    <table>
        <thead>
            <tr>
                <th>{{ translate('Filter_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Search_Bar_Content')  }}: {{ $search ??translate('N/A') }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>


        <tr>
            <th>{{ translate('sl') }}</th>
            <th>{{ translate('Food_Name') }}</th>
            <th>{{ translate('Description') }}</th>
            <th>{{ translate('Category_Name') }}</th>
            <th>{{ translate('Sub_Category_Name') }}</th>
            <th>{{ translate('Price') }}</th>
            <th>{{ translate('Available_Variations') }} </th>
            <th>{{ translate('Addons') }} </th>
            <th>{{ translate('Discount') }} </th>
            <th>{{ translate('Discount_Type') }} </th>
            <th>{{ translate('Type') }} </th>
            <th>{{ translate('Start_Date') }} </th>
            <th>{{ translate('End_Date') }} </th>
            <th>{{ translate('Daily_Start_Time') }} </th>
            <th>{{ translate('Daily_End_Time') }} </th>
            <th>{{ translate('Restaurant_Name') }} </th>
        </thead>
        <tbody>
        @foreach($data as $key => $campaign)
            <tr>
        <td>{{ $loop->index+1}}</td>
        <td>{{ $campaign->title }}</td>
        <td>{{ $campaign->description }}</td>
        <td>
            {{ \App\CentralLogics\Helpers::get_category_name($campaign->category_ids) }}
        </td>
        <td>
        {{ \App\CentralLogics\Helpers::get_sub_category_name($campaign->category_ids) ?? translate('N/A')  }}
        </td>

        <td>
            {{ \App\CentralLogics\Helpers::format_currency($campaign->price) }}
        </td>
        <td>
            {{ \App\CentralLogics\Helpers::get_food_variations($campaign->variations) == "  "  ? translate('N/A'): \App\CentralLogics\Helpers::get_food_variations($campaign->variations) }}
        </td>
        <td>
            {{ \App\CentralLogics\Helpers::get_addon_data($campaign->add_ons) == "  "  ? translate('N/A'): \App\CentralLogics\Helpers::get_addon_data($campaign->add_ons) }}
        </td>


        <td>{{ $campaign->discount == 0 ? translate('N/A') : $campaign->discount }}</td>
        <td>{{ $campaign->discount_type }}</td>
        <td>{{ $campaign->veg == 1 ? translate('Veg') : translate('Non_veg')}}</td>


        <td>{{ \App\CentralLogics\Helpers::date_format($campaign->start_date) }}</td>
        <td>{{ \App\CentralLogics\Helpers::date_format($campaign->end_date) }}</td>
        <td>{{ \App\CentralLogics\Helpers::time_format($campaign->start_time) }}</td>
        <td>{{ \App\CentralLogics\Helpers::time_format($campaign->end_time) }}</td>
        <td>{{ $campaign?->restaurant?->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
