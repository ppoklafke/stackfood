<div class="row">
    <div class="col-lg-12 text-center "><h1 >{{ translate('messages.Subscriber_list') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('filter_criteria') }} -</th>
                <th></th>
                <th></th>
                <th>

                    {{ translate('zone' )}} - {{ $data['zone'] }}


                    <br>
                    {{ translate('filter')  }}- {{  translate($data['filter']) }}

                    <br>
                    {{ translate('Search_Bar_Content')  }}- {{ $data['search'] ??translate('N/A') }}

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th class="border-top px-4 border-bottom text-center">{{ translate('sl') }}</th>
                <th class="border-top px-4 border-bottom"> {{ translate('restaurant Info') }}  </th>
                <th class="border-top px-4 border-bottom"> {{ translate('Current Package Name') }} </th>
                <th class="border-top px-4 border-bottom"> {{ translate('Package Price') }}  </th>
                <th class="border-top px-4 border-bottom"> {{ translate('Exp Date') }}  </th>
                <th class="border-top px-4 border-bottom text-center"> {{ translate('Total Subscription Used') }}  </th>
                <th class="border-top px-4 border-bottom text-center"> {{ translate('is_trial') }}  </th>
                <th class="border-top px-4 border-bottom text-center"> {{ translate('is_cancel') }}  </th>
                <th class="border-top px-4 border-bottom text-center">{{ translate('Status') }} </th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['data'] as $k=> $subscriber)
            <tr>

                    <td class=" text-center">{{ $k + 1 }}</td>
                    <td >
                        {{ $subscriber->name }}
                    </td>
                    <td >
                        <div>{{ $subscriber?->restaurant_sub_update_application?->package?->package_name }}</div>
                    </td>
                    <td >
                        <div class="text-title">{{  \App\CentralLogics\Helpers::format_currency($subscriber?->restaurant_sub_update_application?->package?->price) }}</div>
                    </td>
                    <td >
                        <div class="text-title">{{  \App\CentralLogics\Helpers::date_format($subscriber?->restaurant_sub_update_application?->expiry_date_parsed) }}</div>
                    </td>
                    <td >
                        <div class="text-title pl-3">{{ $subscriber?->restaurant_all_sub_trans_count }}</div>
                    </td>
                    <td class="px-4">
                        <div class="text-title pl-3">
                            @if ($subscriber?->restaurant_sub_update_application?->is_trial)
                            <span class="badge badge-pill badge-info">{{  translate('Yes') }}</span>

                            @else
                            <span class="badge badge-pill badge-success">{{  translate('No') }}</span>
                            @endif

                    </div>
                    <td class="px-4">
                        <div class="text-title pl-3">
                            @if ($subscriber?->restaurant_sub_update_application?->is_canceled)
                            <span class="badge badge-pill badge-warning">{{  translate('Yes') }}</span>

                            @else
                            <span class="badge badge-pill badge-success">{{  translate('No') }}</span>
                            @endif

                    </div>
                    <td class=" text-center">
                        <div>
                            @if($subscriber?->status == 0 &&  $subscriber?->vendor?->status == 0)
                            <span class="badge badge-soft-info">{{ translate('Approval_Pending') }}</span>
                            {{-- @elseif ($subscriber?->restaurant_sub_update_application?->is_canceled == 1)
                            <span class="badge badge-soft-warning">{{ translate('canceled') }}</span> --}}
                            @elseif($subscriber?->restaurant_sub_update_application?->status == 0)
                            <span class="badge badge-soft-danger">{{ translate('Expired') }}</span>
                            @elseif($subscriber?->restaurant_sub_update_application?->status == 1)
                            <span class="badge badge-soft-success">{{ translate('Active') }}</span>
                            @endif
                        </div>
                    </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
