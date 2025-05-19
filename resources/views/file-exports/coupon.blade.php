
<div class="row">
    <div class="col-lg-12 text-center "><h1 > {{translate('Coupon_List')}}
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
            <th>{{ translate('Coupon_Title') }}</th>
            <th>{{ translate('Coupon_Code') }}</th>
            <th>{{ translate('Coupon_Type') }}</th>
            <th>{{ translate('Number_of_Uses') }}</th>
            <th>{{ translate('Min_Purchase_Amount') }}</th>
            <th>{{ translate('Max_Discount_Amount') }} </th>
            <th>{{ translate('Discount_Type') }} </th>
            <th>{{ translate('Discount') }} </th>
            <th>{{ translate('Start_Date') }} </th>
            <th>{{ translate('End_Date') }} </th>
        </thead>
        <tbody>
        @foreach($data['data'] as $key => $coupon)
            <tr>
        <td>{{ $loop->index+1}}</td>
        <td>{{ $coupon->title }}</td>
        <td>{{ $coupon->code }}</td>
        <td>{{ translate($coupon->coupon_type) }}</td>
        <td>{{ $coupon->total_uses }}</td>
        <td>{{ $coupon->min_purchase }}</td>
        <td>{{ $coupon->max_discount }}</td>
        <td>{{ $coupon->discount == 0 ? translate('N/A') :$coupon->discount }}</td>
        <td>{{ translate($coupon->discount_type) }}</td>
        <td>{{ \App\CentralLogics\Helpers::date_format($coupon->start_date) }}</td>
        <td>{{ \App\CentralLogics\Helpers::date_format($coupon->expire_date) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
