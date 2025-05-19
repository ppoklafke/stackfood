<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('Food_Report') }}</h1></div>
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
                    {{ translate('restaurant' )}} - {{ $data['restaurant']??translate('all') }}
                    @if ($data['from'])
                    <br>
                    {{ translate('from' )}} - {{ $data['from']?Carbon\Carbon::parse($data['from'])->format('d M Y'):'' }}
                    @endif
                    @if ($data['to'])
                    <br>
                    {{ translate('to' )}} - {{ $data['to']?Carbon\Carbon::parse($data['to'])->format('d M Y'):'' }}
                    @endif
                    <br>
                    {{ translate('filter')  }}- {{  translate($data['filter']) }}
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
            <th>{{translate('messages.Food_Image')}}</th>
            <th>{{translate('messages.Food_Name')}}</th>
            <th>{{translate('messages.Restaurant_Name')}}</th>
            <th>{{translate('messages.Total_Order_Count')}}</th>
            <th>{{translate('messages.Unit_Price')}}</th>
            <th>{{translate('messages.Total_Amount_Sold')}}</th>
            <th>{{translate('messages.Total_Discount_Given')}}</th>
            <th>{{translate('messages.Average_Sale_Value')}}</th>
            <th>{{translate('messages.Total_Ratings_Given')}}</th>
            <th>{{translate('messages.Average_Ratings')}}</th>
        </thead>
        <tbody>
        @foreach($data['foods'] as $key => $food)
            <tr>
                <td>{{ $key+1}}</td>
                <td></td>
                <td>{{$food['name']}}</td>

                <td>
                    @if($food->restaurant)
                    {{ $food->restaurant->name }}
                    @else
                    {{translate('messages.restaurant_deleted')}}
                    @endif
                </td>
                <td>
                    {{$food->orders_sum_quantity}}
                </td>
                <td>
                    {{ \App\CentralLogics\Helpers::format_currency($food->price) }}
                </td>
                <td>
                    {{ \App\CentralLogics\Helpers::format_currency($food->orders_sum_price) }}
                </td>
                <td>
                    {{ \App\CentralLogics\Helpers::format_currency($food->total_discount) }}
                </td>
                <td>
                    {{ $food->orders_count > 0 ? \App\CentralLogics\Helpers::format_currency(($food->orders_sum_price - $food->total_discount) / ($food->orders_sum_quantity ?? 0)) : 0 }}
                </td>
                <td>{{ $food->rating_count }}</td>
                <td>{{ round($food->avg_rating,1) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
