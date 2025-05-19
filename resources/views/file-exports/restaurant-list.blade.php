<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Restaurant_List') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>




        <tr>

            <th>{{ translate('Total_Restaurant') }} - {{ $data['data']->count() ?? translate('N/A') }} </th>
            <th></th>
            <th></th>
            <th> {{ translate('Active_Restaurant') }} - {{ $data['data']->where('status',1)->count() ?? translate('N/A') }} </th>
            <th></th>
            <th></th>
            <th> {{ translate('Inactive_Restaurant') }} - {{ $data['data']->where('status',0)->count() ?? translate('N/A') }} </th>
            <th></th>
            <th></th>
            <th> {{ translate('Newly_Joined') }} - {{ $data['data']->where('created_at', '>=', now()->subDays(30)->toDateTimeString())->count() ?? translate('N/A') }} </th>
            <th></th>

        </tr>
            <tr>
                <th>{{ translate('Filter_Criteria') }}</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('zone' )}} - {{ $data['zone']??translate('all') }}
                    <br>
                    {{ translate('Type' )}} - {{ $data['type']??translate('all') }}
                    <br>
                    {{ translate('Business_Model' )}} - {{ $data['model']??translate('all') }}
                    <br>
                    {{ translate('Cuisine' )}} - {{ $data['cuisine']??translate('all') }}
                    <br>
                    {{ translate('Search_Bar_Content')  }}- {{ $data['search'] ??translate('N/A') }}
                </th>
                <th> </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        <tr>
            <th>{{ translate('sl') }}</th>
            <th>{{translate('Restaurant_ID')}}</th>
            <th>{{ translate('Restaurant_Logo') }}</th>
            <th>{{ translate('Restaurant_Name') }}</th>
            <th>{{ translate('Ratings') }}</th>
            <th>  {{ translate('Owner_Information') }}</th>
            <th>  {{ translate('Address') }}</th>
            <th> {{ translate('Total_Items') }}</th>
            <th> {{ translate('Business_Model') }}</th>
            <th> {{ translate('Total_Orders') }}</th>
            <th>{{ translate('Cuisines') }}</th>
            <th>{{ translate('Status') }}</th>
        </thead>
        <tbody>
        @foreach($data['data'] as $key => $Restaurant)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{  $Restaurant['id']  }}</td>
            <td>&nbsp;</td>
            <td>{{  $Restaurant['name']  }}</td>
            <td>
                @php($Restaurant_reviews = \App\CentralLogics\RestaurantLogic::calculate_Restaurant_rating($Restaurant['rating']))
                {{ number_format($Restaurant_reviews['rating'], 1)}}
            </td>
            <td> {{ $Restaurant->vendor->f_name .' '  .$Restaurant->vendor->l_name   }}
                        <br>
                    {{ $Restaurant->vendor->phone  }}
            </td>
            <td> {{ $Restaurant->address }} </td>
            <td> {{ $Restaurant->foods_count }} </td>
            <td> {{ translate($Restaurant->restaurant_model) }} </td>
            <td>
                {{ $Restaurant->orders()->RestaurantOrder()->count() }}
            </td>
            <td>
                <div class="white-space-initial">
                    @if ($Restaurant->cuisine)
                        @forelse($Restaurant->cuisine as $c)
                            {{$c->name.','}}
                            @empty
                            {{ translate('Cuisine_not_found') }}
                        @endforelse
                    @endif
                </div>
            </td>
            <td>
                {{ $Restaurant->status == 1 ? translate('Active') : translate('Inactive') }}
            </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
