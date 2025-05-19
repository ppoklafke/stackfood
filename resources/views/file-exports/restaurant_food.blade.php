<div class="row">
    <div class="col-lg-12 text-center "><h1 > {{ translate('Food_List') }}    </h1></div>
    <div class="col-lg-12">

    <table>
        <thead>
            <tr>
                <th>{{ translate('Filter_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Restaurant_Name')  }}: {{ $data['restaurant_name'] }}
                    <br>
                    {{ translate('Zone')  }}: {{ $data['zone'] }}
                    <br>
                    {{ translate('Total_items')  }}: {{ $data['data']->count() }}
                    <br>
                    {{ translate('Active_Items')  }}: {{ $data['data']->where('status' , 1 )->count() }}
                    <br>
                    {{ translate('Inactive_items')  }}: {{ $data['data']->where('status' , 0 )->count() }}
                    <br>
                    {{ translate('Search_Bar_Content')  }}: {{ $data['search'] ??translate('N/A') }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                    <th>{{ translate('sl') }}</th>
                    <th>{{ translate('Image') }}</th>
                    <th>{{ translate('Item_Name') }}</th>
                    <th>{{ translate('Description') }}</th>
                    <th>{{ translate('Category_Name') }}</th>
                    <th>{{ translate('Sub_Category_Name') }}</th>
                    <th>{{ translate('Food_Type') }}</th>

                    <th>{{ translate('Price') }}</th>
                    <th>{{ translate('Available_Variations') }} </th>
                    <th>{{ translate('Available_Addons') }} </th>

                    <th>{{ translate('Discount') }} </th>
                    <th>{{ translate('Discount_Type') }} </th>
                    <th>{{ translate('Available_From') }} </th>
                    <th>{{ translate('Available_Till') }} </th>
                    <th>{{ translate('Tags') }} </th>
                    <th>{{ translate('Status') }} </th>
                </thead>
                <tbody>
                @foreach($data['data'] as $key => $item)
                    <tr>
                <td>{{ $loop->index+1}}</td>
                <td> &nbsp;</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td>
                    {{ Str::limit(($item?->category?->parent ? $item?->category?->parent?->name : $item?->category?->name )  ?? translate('messages.uncategorize')
                    , 20, '...') }}
                </td>
                <td>
                {{ \App\CentralLogics\Helpers::get_sub_category_name($item->category_ids) ?? translate('N/A')  }}
                </td>
                <td> {{ $item->veg == 1? translate('Veg') : translate('Non_Veg')  }}</td>

                <td>
                    {{ \App\CentralLogics\Helpers::format_currency($item->price) }}
                </td>
                <td>
                    {{ \App\CentralLogics\Helpers::get_food_variations($item->variations) == "  "  ? translate('N/A'): \App\CentralLogics\Helpers::get_food_variations($item->variations) }}
                </td>

                <td>
                    {{ \App\CentralLogics\Helpers::get_addon_data($item->add_ons) ==  "  "   ? translate('N/A'): \App\CentralLogics\Helpers::get_addon_data($item->add_ons) }}
                </td>
                <td>{{ $item->discount == 0 ? translate('N/A') : $item->discount}}</td>
                <td>{{ $item->discount_type }}</td>
                <td>{{ \App\CentralLogics\Helpers::time_format($item->available_time_starts) }}</td>
                <td>{{ \App\CentralLogics\Helpers::time_format($item->available_time_ends) }}</td>                <td>
                        @forelse ($item->tags as $c) {{ $c->tag . ',' }} @empty {{  translate('N/A') }} @endforelse
                </td>
                <td> {{ $item->status == 1? translate('Active') : translate('Inactive')  }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
