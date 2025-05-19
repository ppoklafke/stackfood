@extends('layouts.admin.app')

@section('title',translate('messages.Subscriber_List'))

@section('subscriberList')
active
@endsection

@push('css_or_js')

@endpush

@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-start">
                        <img src="{{dynamicAsset('/public/assets/admin/img/store.png')}}" width="24" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title">{{translate('Subscribed Restaurant List')}}</h1>
                        </div>
                    </div>
                </div>
                <div class="min--200">
                    <select name="zone_id" class="form-control js-select2-custom set-filter" data-url="{{ url()->full() }}" data-filter="zone_id" id="zone">
                        <option value="all">{{translate('All Zones')}}</option>
                        @foreach(\App\Models\Zone::orderBy('name')->get(['id','name']) as $z)
                            <option value="{{$z['id']}}" {{ request()?->zone_id == $z['id']?'selected':''}}>
                                {{($z['name'])}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-20">
            <div class="row g-3">
                <div class="col-sm-6 col-lg-3">
                    <a class="__card-2 __bg-1" href="#">
                        <h4 class="title text--title">{{ $data['total_subscribed_user'] }}</h4>
                        <span class="subtitle">{{ translate('Total_Subscribed_User') }}</span>
                        <img src="{{dynamicAsset('public/assets/admin/img/subscription-plan/subscribed-user.png')}}" alt="report/new" class="card-icon" width="35px">
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a class="__card-2 __bg-3" href="#">
                        <h4 class="title text--title">{{ $data['active_subscription'] }}</h4>
                        <span class="subtitle">{{ translate('Active_Subscriptions') }}</span>
                        <img src="{{dynamicAsset('public/assets/admin/img/subscription-plan/active-user.png')}}" alt="report/new" class="card-icon" width="35px">
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a class="__card-2 __bg-6" href="#">
                        <h4 class="title text--title">{{ $data['expired_subscription'] }}</h4>
                        <span class="subtitle">{{ translate('Expired_Subscription') }}</span>
                        <img src="{{dynamicAsset('public/assets/admin/img/subscription-plan/expired-user.png')}}" alt="report/new" class="card-icon" width="35px">
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a class="__card-2 __bg-4" href="#">
                        <h4 class="title text--title">{{ $data['expired_soon'] }}</h4>
                        <span class="subtitle">{{ translate('Expiring_Soon') }} </span>
                        <img src="{{dynamicAsset('public/assets/admin/img/subscription-plan/expired-soon.png')}}" alt="report/new" class="card-icon" width="35px">
                    </a>
                </div>
            </div>
        </div>
        <ul class="transaction--information text-uppercase">
            <li class="text--info">
                <i class="tio-document-text-outlined"></i>
                <div>
                    <span> {{ translate('Total_transactions') }} </span> <strong> {{ $data['total_transactions']  }}</strong>
                </div>
            </li>
            <li class="seperator"></li>
            <li class="text--success">
                <i class="tio-checkmark-circle-outlined success--icon"></i>
                <div>
                    <span> {{ translate('Total_earning') }} </span> <strong> {{ \App\CentralLogics\Helpers::format_currency($data['total_paid_amount'])  }}</strong>
                </div>
            </li>
            <li class="seperator"></li>
            <li class="text--warning">
                <i class="tio-atm"></i>
                <div>
                    <span> {{ translate('EARNED_THIS_MONTH') }} </span> <strong> {{ \App\CentralLogics\Helpers::format_currency($data['current_month_paid_amount'])  }}</strong>
                </div>
            </li>
        </ul>
        <div class="card">
            <div class="card-header flex-wrap py-2 border-0">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <h4 class="mb-0">{{ translate('Restaurant_List') }}</h4>
                    <span class="badge badge-soft-dark rounded-circle">{{ $subscribers->total() }}</span>
                </div>
                <div class="search--button-wrapper justify-content-end">
                    <div class="max-sm-flex-1">
                        <select   name="subscription_type"  data-url="{{ url()->full() }}" data-filter="subscription_type" class="custom-select h--40px py-0 status-filter set-filter" >
                            <option {{ request()?->subscription_type == 'all' ? 'selected' : '' }}  value="all">
                                {{ translate('all') }}
                            </option>
                            <option {{ request()?->subscription_type == 'active' ? 'selected' : '' }}  value="active">
                                {{ translate('active') }}
                            </option>
                            <option {{ request()?->subscription_type == 'expired' ? 'selected' : '' }}  value="expired">
                                {{ translate('expired') }}
                            </option>
                            <option {{ request()?->subscription_type == 'cancaled' ? 'selected' : '' }}  value="cancaled">
                                {{ translate('cancaled') }}
                            </option>
                            <option {{ request()?->subscription_type == 'free_trial' ? 'selected' : '' }}  value="free_trial">
                                {{ translate('Free_trial') }}
                            </option>

                        </select>
                    </div>
                    <form class="search-form">
                        <div class="input-group input--group">
                            <input name="search" type="search" value="{{ request()?->search }}" class="form-control h--40px" placeholder="{{ translate('Ex :Search by name & package name') }}" aria-label="Search here">
                            <button type="submit" class="btn btn--secondary h--40px"><i class="tio-search"></i></button>
                        </div>
                    </form>
                    <!-- Unfold -->
                    <div class="hs-unfold">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn font--sm"
                            href="javascript:;"
                            data-hs-unfold-options="{
                                &quot;target&quot;: &quot;#usersExportDropdown&quot;,
                                &quot;type&quot;: &quot;css-animation&quot;
                            }"
                            data-hs-unfold-target="#usersExportDropdown" data-hs-unfold-invoker="">
                            <i class="tio-download-to mr-1"></i> {{ translate('export') }}
                        </a>

                        <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right hs-unfold-content-initialized hs-unfold-css-animation animated hs-unfold-reverse-y hs-unfold-hidden">

                            <span class="dropdown-header">{{ translate('download_options') }}</span>
                            <a id="export-excel" class="dropdown-item"
                                href="{{ route('admin.subscription.subscriberListExport', ['export_type' => 'excel', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ dynamicAsset('public/assets/admin/svg/components/excel.svg') }}"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item"
                                href="{{ route('admin.subscription.subscriberListExport', ['export_type' => 'csv', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ dynamicAsset('public/assets/admin/svg/components/placeholder-csv-format.svg') }}"
                                    alt="Image Description">
                                .{{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
                <!-- End Row -->
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless middle-align __txt-14px">
                        <thead class="thead-light white--space-false">
                            <th class="border-top px-4 border-bottom text-center">{{ translate('sl') }}</th>
                            <th class="border-top px-4 border-bottom"> {{ translate('Restaurant Info') }}  </th>
                            <th class="border-top px-4 border-bottom"> {{ translate('Current Package Name') }} </th>
                            <th class="border-top px-4 border-bottom"> {{ translate('Package Price') }}  </th>
                            <th class="border-top px-4 border-bottom"> {{ translate('Exp Date') }}  </th>
                            <th class="border-top px-4 border-bottom text-center"> {{ translate('Total Subscription Used') }}  </th>
                            <th class="border-top px-4 border-bottom text-center"> {{ translate('is_trial') }}  </th>
                            <th class="border-top px-4 border-bottom text-center"> {{ translate('is_cancel') }}

                                <span
                                data-toggle="tooltip" data-placement="right"
                                data-original-title="{{ translate('The subscription is canceled by admin/restaurant but subscribers will continue to get full subscription privileges until the expiration date') }}"><img
                                    src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}">
                            </span>

                            </th>
                            <th class="border-top px-4 border-bottom text-center">{{ translate('Status') }} </th>
                            <th class="border-top px-4 border-bottom text-center">{{ translate('Action') }} </th>
                        </thead>
                        <tbody>
                            @foreach ($subscribers as $k=> $subscriber)
                            <tr>
                                <td class="px-4 text-center">{{ $k + $subscribers->firstItem() }}</td>
                                <td class="px-4">
                                    <a href="{{route('admin.restaurant.view', $subscriber->id)}}" alt="view restaurant" class="table-rest-info">
                                        <img src="{{ $subscriber->logo_full_url ?? dynamicAsset('public/assets/admin/img/100x100/1.png') }}" >
                                        <div class="info">

                                            <span class="d-block text-body">
                                                {{Str::limit($subscriber->name,20,'...')}}<br>

                                            <span class="rating">
                                                @php($restaurant_rating = $subscriber['rating'] == null ? 0 : array_sum($subscriber['rating']) / 5)
                                                <i class="tio-star"></i> {{ $restaurant_rating }}
                                            </span>
                                            </span>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-4">
                                    <div>{{ $subscriber?->restaurant_sub_update_application?->package?->package_name  ?? translate('Package_Not_Found!')}}</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">{{  \App\CentralLogics\Helpers::format_currency($subscriber?->restaurant_sub_update_application?->package?->price) }}</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">{{  \App\CentralLogics\Helpers::date_format($subscriber?->restaurant_sub_update_application?->expiry_date_parsed) }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="text-title pl-3">{{ $subscriber?->restaurant_all_sub_trans_count }}</div>
                                </td>

                                <td class="px-4">
                                    <div class="text-title pl-3">
                                        @if ($subscriber?->restaurant_sub_update_application?->is_trial)
                                        <span class="badge badge-soft-info">{{  translate('Yes') }}</span>

                                        @else
                                        <span class="badge badge-soft-success">{{  translate('No') }}</span>
                                        @endif

                                </div>
                                <td class="px-4">
                                    <div class="text-title pl-3">
                                        @if ($subscriber?->restaurant_sub_update_application?->is_canceled)
                                        <span class="badge badge-soft-warning">{{  translate('Yes') }}</span>

                                        @else
                                        <span class="badge badge-soft-success">{{  translate('No') }}</span>
                                        @endif

                                </div>
                                </td>
                                <td class="px-4 text-center">
                                    <div>
                                        @if($subscriber?->status == 0 &&  $subscriber?->vendor?->status == 0)
                                        <span class="badge badge-soft-info">{{ translate('Approval_Pending') }}</span>
                                        @elseif($subscriber?->restaurant_sub_update_application?->status == 0)
                                        <span class="badge badge-soft-danger">{{ translate('Expired') }}</span>
                                        @elseif($subscriber?->restaurant_sub_update_application?->status == 1)
                                        <span class="badge badge-soft-success">{{ translate('Active') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--warning btn-outline-warning" href="{{ route('admin.subscription.subscriberDetail',$subscriber->id) }}">
                                            <i class="tio-invisible"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(count($subscribers) !== 0)
                <hr>
                @endif
                <div class="page-area">
                    {!! $subscribers->withQueryString()->links() !!}
                </div>
                @if(count($subscribers) === 0)
                <div class="empty--data">
                    <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
        </div>
    </div>




@endsection

@push('script_2')

@endpush

