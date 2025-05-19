@extends('layouts.admin.app')

@section('title', translate('messages.disbursement_report'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{dynamicAsset('/public/assets/admin/img/report/new/disburstment.png')}}" class="w--22" alt="">
            </span>
                <span>{{translate($tab)}} {{ translate('Disbursement_Report') }}</span>
            </h1>
{{--            <ul class="nav nav-tabs mb-4 border-0 pt-2">--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $tab=='restaurant'?'active':'' }}" href="{{ route('admin.report.disbursement_report',  ['tab' => 'restaurant']) }}" >{{ translate('Restaurant_Disbursements') }}</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $tab=='delivery_man'?'active':'' }}" href="{{ route('admin.report.disbursement_report',  ['tab' => 'delivery_man']) }}">{{ translate('Delivery_Man_Disbursements') }}</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
        </div>
        <!-- Reports -->
        <div class="disbursement-report mb-20">
            <div class="__card-3 rebursement-item">
                <img src="{{dynamicAsset('public/assets/admin/img/report/new/trx1.png')}}" class="icon" alt="report/new">
                <h3 class="title text-008958">{{\App\CentralLogics\Helpers::format_currency($pending)}}
                </h3>
                <h6 class="subtitle">{{ translate('Pending_Disbursements') }}</h6>
                <div class="info-icon" data-toggle="tooltip" data-placement="top" data-original-title="{{ translate('All_the_pending_disbursement_requests_that_require_admin’s_action_(complete/cancel).') }}">
                    <img src="{{dynamicAsset('public/assets/admin/img/report/new/info1.png')}}" alt="report/new">
                </div>
            </div>

            <div class="__card-3 rebursement-item">
                <img src="{{dynamicAsset('public/assets/admin/img/report/new/trx5.png')}}" class="icon" alt="report/new">
                <h3 class="title text-FF7E0D">{{\App\CentralLogics\Helpers::format_currency($completed)}}
                </h3>
                <h6 class="subtitle">{{ translate('Completed_Disbursements') }}</h6>
                <div class="info-icon" data-toggle="tooltip" data-placement="top" data-original-title="{{ translate('The_amount_of_disbursement_is_completed.') }}">
                    <img src="{{dynamicAsset('public/assets/admin/img/report/new/info5.png')}}" alt="report/new">
                </div>
            </div>

            <div class="__card-3 rebursement-item">
                <img src="{{dynamicAsset('public/assets/admin/img/report/new/trx3.png')}}" class="icon" alt="report/new">
                <h3 class="title text-FF5A54">{{\App\CentralLogics\Helpers::format_currency($canceled)}}
                </h3>
                <h6 class="subtitle">{{ translate('Canceled_Transactions') }}</h6>
                <div class="info-icon" data-toggle="tooltip" data-placement="top" data-original-title="{{ translate('See_all_the_canceled_disbursement_amounts_here.') }}">
                    <img src="{{dynamicAsset('public/assets/admin/img/report/new/info3.png')}}" alt="report/new">
                </div>
            </div>
        </div>

        <div class="card mb-20">
            <div class="card-body">
                <h4 class="">{{ translate('Search_Data') }}</h4>
                <form method="get">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-3">
                            <select name="zone_id" class="form-control js-select2-custom set-filter"
                                    data-url="{{ url()->full() }}" data-filter="zone_id" id="zone">
                                <option value="all">{{ translate('messages.All_Zones') }}</option>
                                @foreach (\App\Models\Zone::orderBy('name')->get(['id','name']) as $z)
                                    <option value="{{ $z['id'] }}"
                                        {{ isset($zone) && $zone->id == $z['id'] ? 'selected' : '' }}>
                                        {{ $z['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($tab=='restaurant')
                        <div class="col-sm-6 col-md-3">
                            <select name="restaurant_id" data-url="{{ url()->full() }}" data-filter="restaurant_id"
                                    data-placeholder="{{ translate('messages.select_restaurant') }}"
                                    class="js-data-example-ajax form-control set-filter">
                                @if (isset($restaurant))
                                    <option value="{{ $restaurant->id }}" selected>{{ $restaurant->name }}</option>
                                @else
                                    <option value="all" selected>{{ translate('messages.all_restaurants') }}</option>
                                @endif
                            </select>
                        </div>
                        @else
                        <div class="col-sm-6 col-md-3">
                            <select name="deliveryman_id" id="deliveryman" data-url="{{ url()->full() }}" data-filter="deliveryman_id"
                                    data-placeholder="{{ translate('messages.select_delivery_man') }}"
                                    class="js-data-example-ajax form-control set-filter">
                                @if (isset($delivery_man))
                                    <option value="{{ $delivery_man->id }}" selected>{{ $delivery_man->name }}</option>
                                @else
                                    <option value="all" selected>{{ translate('messages.all_delivery_mans') }}</option>
                                @endif
                            </select>
                        </div>
                        @endif
                        <div class="col-sm-6 col-md-3">
                            <select name="payment_method_id" data-url="{{ url()->full() }}" data-filter="payment_method_id"
                                    data-placeholder="{{ translate('messages.select_payment_method') }}"
                                    class="form-control js-select2-custom set-filter">
                                <option value="all">{{translate('All_Payment_Method')}}</option>
                                @foreach($withdrawal_methods as $item)
                                    <option value="{{$item['id']}}" {{ isset($payment_method_id) && $payment_method_id == $item['id'] ? 'selected' : '' }}>{{$item['method_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select name="status" data-url="{{ url()->full() }}" data-filter="status"
                                    data-placeholder="{{ translate('messages.select_status') }}"
                                    class="form-control js-select2-custom set-filter">
                                <option value="all" {{ isset($status) && $status == 'all' ? 'selected' : '' }}>{{translate('All_status')}}</option>
                                <option value="pending" {{ isset($status) && $status == 'pending' ? 'selected' : '' }}>{{ translate('pending') }}</option>
                                <option value="completed" {{ isset($status) && $status == 'completed' ? 'selected' : '' }}>{{ translate('completed') }}</option>
                                <option value="canceled" {{ isset($status) && $status == 'canceled' ? 'selected' : '' }}>{{ translate('canceled') }}</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select class="form-control set-filter" name="filter"
                                    data-url="{{ url()->full() }}" data-filter="filter">
                                <option value="all_time" {{ isset($filter) && $filter == 'all_time' ? 'selected' : '' }}>
                                    {{ translate('messages.All_Time') }}</option>
                                <option value="this_year" {{ isset($filter) && $filter == 'this_year' ? 'selected' : '' }}>
                                    {{ translate('messages.This_Year') }}</option>
                                <option value="previous_year"
                                    {{ isset($filter) && $filter == 'previous_year' ? 'selected' : '' }}>
                                    {{ translate('messages.Previous_Year') }}</option>
                                <option value="this_month"
                                    {{ isset($filter) && $filter == 'this_month' ? 'selected' : '' }}>
                                    {{ translate('messages.This_Month') }}</option>
                                <option value="this_week" {{ isset($filter) && $filter == 'this_week' ? 'selected' : '' }}>
                                    {{ translate('messages.This_Week') }}</option>
                                <option value="custom" {{ isset($filter) && $filter == 'custom' ? 'selected' : '' }}>
                                    {{ translate('messages.Custom') }}</option>
                            </select>
                        </div>
                        @if (isset($filter) && $filter == 'custom')
                            <div class="col-sm-6 col-md-3">
                                <input type="date" name="from" id="from_date" class="form-control"
                                       placeholder="{{ translate('Start_Date') }}"
                                       value={{ $from ? $from  : '' }} required>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <input type="date" name="to" id="to_date" class="form-control"
                                       placeholder="{{ translate('End_Date') }}"
                                       value={{ $to ? $to  : '' }}  required>
                            </div>
                        @endif
                        <div class="col-sm-6 col-md-3 ml-auto">
                            <button type="submit"
                                    class="btn btn-primary btn-block">{{ translate('Filter') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-header border-0 py-2">
            <div class="search--button-wrapper">
                <h2 class="card-title">
                    {{ translate('Total_Disbursements') }} <span class="badge badge-soft-secondary ml-2" id="countItems">{{ $disbursements->total() }}</span>
                </h2>
                <form class="search-form">
                    <!-- Search -->
                    <div class="input--group input-group input-group-merge input-group-flush">
                        <input class="form-control" value="{{ request()?->search  ?? null }}" placeholder="{{ translate('search_by_id') }}" name="search">
                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                    </div>
                    <!-- End Search -->
                </form>
                <!-- Static Export Button -->
                <div class="hs-unfold ml-3">
                    <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn btn-outline-primary btn--primary font--sm" href="javascript:;"
                       data-hs-unfold-options='{
                            "target": "#usersExportDropdown",
                            "type": "css-animation"
                        }'>
                        <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                    </a>
                    <div id="usersExportDropdown"
                         class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                        <span class="dropdown-header">{{translate('messages.download_options')}}</span>
                        <a id="export-excel" class="dropdown-item" href="{{route('admin.report.disbursement_report_export', ['type'=>'excel','tab'=>$tab,request()->getQueryString()])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{dynamicAsset('public/assets/admin')}}/svg/components/excel.svg" alt="Image Description">
                            {{translate('messages.excel')}}
                        </a>
                        <a id="export-csv" class="dropdown-item" href="{{route('admin.report.disbursement_report_export', ['type'=>'excel','tab'=>$tab,request()->getQueryString()])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{dynamicAsset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg" alt="Image Description">
                            {{translate('messages.csv')}}
                        </a>
                    </div>
                </div>
                <!-- Static Export Button -->
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-thead-bordered table-align-middle card-table">
                    <thead>
                    <tr>
                        <th>{{ translate('sl') }}</th>
                        <th>{{ translate('id') }}</th>
                        @if($tab=='restaurant')
                        <th>{{ translate('Restaurant_Info') }}</th>
                        @else
                        <th>{{ translate('Delivery_Man_Info') }}</th>
                        @endif
                        <th>{{ translate('created_at') }}</th>
                        <th>{{ translate('Disburse_Amount') }}</th>
                        <th>{{ translate('Payment_method') }}</th>
                        <th>{{ translate('status') }}</th>
                        <th>
                            <div class="text-center">
                                {{ translate('action') }}
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($disbursements as $key => $disbursement)
                        <tr>
                            <td>
                                <span class="font-weight-bold">{{ $key+1 }}</span>
                            </td>
                            <td>
                                #{{ $disbursement->disbursement_id }}
                            </td>
                            @if($tab=='restaurant')
                            <td>
                                <a href="{{ route('admin.restaurant.view', $disbursement->restaurant->id) }}" alt="view restaurant"
                                   class="table-rest-info">
                                    <div class="info">
                                            <span class="d-block text-body">
                                                {{ Str::limit($disbursement->restaurant->name, 20, '...') }}<br>
                                            </span>
                                    </div>
                                </a>
                            </td>
                            @else
                                <td>
                                    <a href="{{route('admin.delivery-man.preview', $disbursement->delivery_man_id)}}"
                                       class="table-rest-info">
                                        <div class="info">
                                            <span class="d-block text-body">
                                                {{$disbursement->delivery_man->f_name.' '.$disbursement->delivery_man->l_name}}
                                            </span>
                                        </div>
                                    </a>
                                </td>
                            @endif
                            <td>
                                {{ \App\CentralLogics\Helpers::time_date_format($disbursement->created_at)  }}
                            </td>
                            <td>
                                {{\App\CentralLogics\Helpers::format_currency($disbursement['disbursement_amount'])}}
                            </td>
                            <td>
                                <div>
                                    {{$disbursement?->withdraw_method?->method_name ?? translate('Default_method')}}
                                </div>
                            </td>
                            <td>
                                @if($disbursement->status=='pending')
                                    <label class="badge badge-soft-primary">{{ translate('pending') }}</label>
                                @elseif($disbursement->status=='completed')
                                    <label class="badge badge-soft-success">{{ translate('Completed') }}</label>
                                @else
                                    <label class="badge badge-soft-danger">{{ translate('canceled') }}</label>
                                @endif
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn" data-toggle="modal" data-target="#payment-info-{{$disbursement->id}}" title="View Details">
                                        <i class="tio-visible"></i>
                                    </a>
                                </div>
                            </td>
                            @if($tab=='restaurant')
                            <div class="modal fade" id="payment-info-{{$disbursement->id}}">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header pb-4">
                                            <button type="button" class="payment-modal-close btn-close border-0 outline-0 bg-transparent" data-dismiss="modal">
                                                <i class="tio-clear"></i>
                                            </button>
                                            <div class="w-100 text-center">
                                                <h2 class="mb-2">{{ translate('Payment_Information') }}</h2>
                                                <div>
                                                    <span class="mr-2">{{ translate('Disbursement_ID') }}</span>
                                                    <strong>#{{$disbursement->disbursement_id}}</strong>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="mr-2">{{ translate('status') }}</span>
                                                    @if($disbursement->status=='pending')
                                                        <label class="badge badge-soft-primary">{{ translate('pending') }}</label>
                                                    @elseif($disbursement->status=='completed')
                                                        <label class="badge badge-soft-success">{{ translate('Completed') }}</label>
                                                    @else
                                                        <label class="badge badge-soft-danger">{{ translate('canceled') }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card shadow--card-2">
                                                <div class="card-body">
                                                    <div class="d-flex flex-wrap payment-info-modal-info p-xl-4">
                                                        <div class="item">
                                                            <h5>{{ translate('Restaurant_Information') }}</h5>
                                                            <ul class="item-list">
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('name') }}</span>
                                                                    <span>:</span>
                                                                    <strong>{{$disbursement?->restaurant?->name}}</strong>
                                                                </li>
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('contact') }}</span>
                                                                    <span>:</span>
                                                                    <strong>{{$disbursement?->restaurant?->phone}}</strong>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="item">
                                                            <h5>{{ translate('Owner_Information') }}</h5>
                                                            <ul class="item-list">
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('name') }}</span>
                                                                    <span>:</span>
                                                                    <strong>{{$disbursement->restaurant->vendor->f_name}} {{$disbursement->restaurant->vendor->l_name}}</strong>
                                                                </li>
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('email') }}</span>
                                                                    <span>:</span>
                                                                    <strong>{{$disbursement->restaurant->vendor->email}}</strong>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="item w-100">
                                                            <h5>{{ translate('Account_Information') }}</h5>
                                                            <ul class="item-list">
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('payment_method') }}</span><strong>{{$disbursement?->withdraw_method?->method_name ?? translate('Default_method')}}</strong>
                                                                </li>
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('amount') }}</span>
                                                                    <strong>{{\App\CentralLogics\Helpers::format_currency($disbursement['disbursement_amount'])}}</strong>
                                                                </li>
                                                                @forelse(json_decode($disbursement?->withdraw_method?->method_fields, true) ?? [] as $key=> $item)
                                                                    <li class="d-flex flex-wrap">
                                                                        <span class="name">{{  translate($key) }}</span>
                                                                        <strong>{{$item}}</strong>
                                                                    </li>
                                                                @empty

                                                                @endforelse

                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                                <div class="modal fade" id="payment-info-{{$disbursement->id}}">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header pb-4">
                                                <button type="button" class="payment-modal-close btn-close border-0 outline-0 bg-transparent" data-dismiss="modal">
                                                    <i class="tio-clear"></i>
                                                </button>
                                                <div class="w-100 text-center">
                                                    <h2 class="mb-2">{{ translate('Payment_Information') }}</h2>
                                                    <div>
                                                        <span class="mr-2">{{ translate('Disbursement_ID') }}</span>
                                                        <strong>#{{$disbursement->disbursement_id}}</strong>
                                                    </div>
                                                    <div class="mt-2">
                                                        <span class="mr-2">{{ translate('status') }}</span>
                                                        @if($disbursement->status=='pending')
                                                            <label class="badge badge-soft-primary">{{ translate('pending') }}</label>
                                                        @elseif($disbursement->status=='completed')
                                                            <label class="badge badge-soft-success">{{ translate('Completed') }}</label>
                                                        @else
                                                            <label class="badge badge-soft-danger">{{ translate('canceled') }}</label>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card shadow--card-2">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-wrap payment-info-modal-info p-xl-4">
                                                            <div class="item">
                                                                <h5>{{ translate('Delivery_Man_Information') }}</h5>
                                                                <ul class="item-list">
                                                                    <li class="d-flex flex-wrap">
                                                                        <span class="name">{{ translate('name') }}</span>
                                                                        <span>:</span>
                                                                        <strong>{{$disbursement->delivery_man->f_name.' '.$disbursement->delivery_man->l_name}}</strong>
                                                                    </li>
                                                                    <li class="d-flex flex-wrap">
                                                                        <span class="name">{{ translate('contact') }}</span>
                                                                        <span>:</span>
                                                                        <strong>{{$disbursement?->delivery_man?->phone}}</strong>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="item">
                                                                {{--                                                                <h5>{{ translate('Owner_Information') }}</h5>--}}
                                                                {{--                                                                <ul class="item-list">--}}
                                                                {{--                                                                    <li class="d-flex flex-wrap">--}}
                                                                {{--                                                                        <span class="name">{{ translate('name') }}</span>--}}
                                                                {{--                                                                        <span>:</span>--}}
                                                                {{--                                                                        <strong>{{$disbursement->delivery_man->f_name}} {{$disbursement->delivery_man->l_name}}</strong>--}}
                                                                {{--                                                                    </li>--}}
                                                                {{--                                                                    <li class="d-flex flex-wrap">--}}
                                                                {{--                                                                        <span class="name">{{ translate('email') }}</span>--}}
                                                                {{--                                                                        <span>:</span>--}}
                                                                {{--                                                                        <strong>{{$disbursement->delivery_man->email}}</strong>--}}
                                                                {{--                                                                    </li>--}}
                                                                {{--                                                                </ul>--}}
                                                            </div>
                                                            <div class="item w-100">
                                                                <h5>{{ translate('Account_Information') }}</h5>
                                                                <ul class="item-list">
                                                                    <li class="d-flex flex-wrap">
                                                                        <span class="name">{{ translate('payment_method') }}</span><strong>{{$disbursement?->withdraw_method?->method_name ?? translate('Default_method')}}</strong>
                                                                    </li>
                                                                    <li class="d-flex flex-wrap">
                                                                        <span class="name">{{ translate('amount') }}</span>
                                                                        <strong>{{\App\CentralLogics\Helpers::format_currency($disbursement['disbursement_amount'])}}</strong>
                                                                    </li>
                                                                    @forelse(json_decode($disbursement?->withdraw_method?->method_fields, true) ?? [] as $key=> $item)
                                                                        <li class="d-flex flex-wrap">
                                                                            <span class="name">{{  translate($key) }}</span>
                                                                            <strong>{{$item}}</strong>
                                                                        </li>
                                                                    @empty

                                                                    @endforelse

                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($disbursements) === 0)
                    <div class="empty--data">
                        <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                @endif
            </div>
        </div>
        <div class="page-area px-4 pb-3">
            <div class="d-flex align-items-center justify-content-end">
                <div>
                    {!!$disbursements->links()!!}
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
@endpush

@push('script_2')
    <script>
        "use strict";
        $(document).on('ready', function() {
            $('.js-data-example-ajax').select2({
                ajax: {
                    url: '{{ url('/') }}/admin/restaurant/get-restaurants',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            all:true,
                            @if (isset($zone))
                                zone_ids: [{{ $zone->id }}],
                            @endif

                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    __port: function(params, success, failure) {
                        let $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });

            $('#deliveryman').select2({
                ajax: {
                    url: '{{url('/')}}/admin/delivery-man/get-deliverymen',
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    __port: function (params, success, failure) {
                        let $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });
        });

        $('#from_date,#to_date').change(function() {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })

    </script>
@endpush

