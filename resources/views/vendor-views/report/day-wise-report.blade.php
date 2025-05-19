@extends('layouts.vendor.app')

@section('title', translate('messages.transaction_report'))

@push('css_or_js')
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{ dynamicAsset('public/assets/admin/img/report.png') }}" class="w--22" alt="">
            </span>
            <span>
                {{ translate('messages.transaction_report') }}
                @if ($from && $to)
                <span class="h6 mb-0 badge badge-soft-success ml-2">
                ( {{$from}} - {{ $to}} )</span>
                @endif
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="card mb-20">
        <div class="card-body">
            <h4 class="">{{ translate('Search_Data') }}</h4>
            <form  method="get">

                <div class="row g-3">

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
                                value={{ $to ? $to  : '' }} required>

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

    <div class="mb-20">
        <div class="row g-2">
                    <div class="col-sm-4">
                        <a class="__card-3 h-100" href="#">
                            <img src="{{ dynamicAsset('/public/assets/admin/img/report/new/trx1.png') }}" class="icon"
                                alt="report/new">
                            <h3 class="title text-008958">{{ \App\CentralLogics\Helpers::number_format_short($delivered) }}
                            </h3>
                            <h6 class="subtitle">{{ translate('Completed_Transaction') }}</h6>
                            <div class="info-icon" data-toggle="tooltip" data-placement="top"
                                data-original-title="{{ translate('When_the_order_is_successfully_delivered_full_order_amount_goes_to_this_section.') }}">
                                <img src="{{ dynamicAsset('/public/assets/admin/img/report/new/info1.png') }}"
                                    alt="report/new">
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="__card-3 h-100" href="#">
                            <img src="{{ dynamicAsset('/public/assets/admin/img/report/new/trx2.png') }}" class="icon"
                                alt="report/new">
                            <h3 class="title text-FF5A54">{{ \App\CentralLogics\Helpers::number_format_short($on_hold) }}
                            </h3>
                            <h6 class="subtitle">{{ translate('On_Hold_Transaction') }}</h6>
                            <div class="info-icon" data-toggle="tooltip" data-placement="top"
                                data-original-title="{{ translate('The_on_going_orders') }}">
                                <img src="{{ dynamicAsset('/public/assets/admin/img/report/new/info3.png') }}"
                                    alt="report/new">
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="__card-3 h-100" href="#">
                            <img src="{{ dynamicAsset('/public/assets/admin/img/report/new/trx3.png') }}" class="icon"
                                alt="report/new">
                            <h3 class="title text-FF5A54">{{ \App\CentralLogics\Helpers::number_format_short($canceled) }}
                            </h3>
                            <h6 class="subtitle">{{ translate('Refunded_Transaction') }}</h6>
                            <div class="info-icon" data-toggle="tooltip" data-placement="top"
                                data-original-title="{{ translate('If_the_order_is_successfully_refunded,_the_full_order_amount_goes_to_this_section_without_the_delivery_fee_and_delivery_tips.') }}">
                                <img src="{{ dynamicAsset('/public/assets/admin/img/report/new/info3.png') }}"
                                    alt="report/new">
                            </div>
                        </a>
                    </div>

        </div>
    </div>

    <!-- End Stats -->
    <!-- Card -->
    <div class="card mt-3">
        <!-- Header -->
        <div class="card-header border-0 py-2">
            <div class="search--button-wrapper">
                <h3 class="card-title">
                    {{ translate('messages.order_transactions') }} <span
                        class="badge badge-soft-secondary" id="countItems">{{ $order_transactions->total() }}</span>
                </h3>
                <form  class="search-form">
                    <!-- Search -->
                    <div class="input--group input-group input-group-merge input-group-flush">
                        <input class="form-control" value="{{ request()->search ?? null }}" placeholder="{{ translate('Search_by_Order_ID') }}" name="search">
                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                    </div>
                    <!-- End Search -->
                </form>
                <!-- Static Export Button -->
                <div class="hs-unfold ml-3">
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
                            href="{{ route('vendor.report.day-wise-report-export', ['type' => 'excel', request()->getQueryString()]) }}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{ dynamicAsset('public/assets/admin/svg/components/excel.svg') }}"
                                alt="Image Description">
                            {{ translate('messages.excel') }}
                        </a>
                        <a id="export-csv" class="dropdown-item"
                            href="{{ route('vendor.report.day-wise-report-export', ['type' => 'csv', request()->getQueryString()]) }}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{ dynamicAsset('public/assets/admin/svg/components/placeholder-csv-format.svg') }}"
                                alt="Image Description">
                            {{ translate('messages.csv') }}
                        </a>

                    </div>
                </div>
                <!-- Static Export Button -->
            </div>
        </div>
        <!-- End Header -->

        <!-- Body -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="datatable" class="table table-thead-bordered table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0">{{ translate('sl') }}</th>
                            <th class="border-0">{{ translate('messages.order_id') }}</th>
                            <th class="border-0">{{ translate('messages.restaurant') }}</th>
                            <th class="border-0">{{ translate('messages.customer_name') }}</th>
                            <th class="border-0 min-w-120">{{ translate('messages.total_item_amount') }}</th>
                            <th class="border-0">{{ translate('messages.item_discount') }}</th>
                            <th class="border-0">{{ translate('messages.coupon_discount') }}</th>
                            <th class="border-0">{{ translate('messages.referral_discount') }}</th>
                            <th class="border-0">{{ translate('messages.discounted_amount') }}</th>
                            <th class="border-0">{{ translate('messages.vat/tax') }}</th>
                            <th class="border-0">{{ translate('messages.delivery_charge') }}</th>
                            <th class="border-0">{{ translate('messages.order_amount') }}</th>
                            <th class="border-0">{{ translate('messages.admin_discount') }}</th>
                            <th class="border-0">{{ translate('messages.restaurant_discount') }}</th>
                            <th class="border-0">{{ translate('messages.admin_commission') }}</th>
                            <th class="border-0">{{ \App\CentralLogics\Helpers::get_business_data('additional_charge_name')??translate('messages.additional_charge') }}</th>
                            <th class="border-0">{{ translate('messages.extra_packaging_amount') }}</th>
                            <th class="min-w-140 text-capitalize">{{ translate('commision_on_delivery_charge') }}</th>
                            <th class="min-w-140 text-capitalize">{{ translate('admin_net_income') }}</th>
                            <th class="min-w-140 text-capitalize">{{ translate('restaurant_net_income') }}</th>
                            <th class="border-0 min-w-120">{{ translate('messages.amount_received_by') }}</th>
                            <th class="border-top border-bottom text-capitalize">{{ translate('messages.payment_method') }}</th>
                            <th class="border-0">{{ translate('messages.payment_status') }}</th>
                            <th class="border-0">{{ translate('messages.action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="set-rows">
                        @foreach ($order_transactions as $k => $ot)
                            <tr scope="row">
                                <td>{{ $k + $order_transactions->firstItem() }}</td>
                                    <td><a
                                            href="{{ route('vendor.order.details', $ot->order_id) }}">{{ $ot->order_id }}</a>
                                    </td>
                                <td  class="text-capitalize">
                                    @if($ot->order->restaurant)
                                        {{Str::limit($ot->order->restaurant->name,25,'...')}}
                                    @endif
                                </td>
                                <td class="white-space-nowrap">
                                    @if ($ot->order->customer)
                                        <a class="text-body text-capitalize"
                                            href="#">
                                            <strong>{{ $ot->order->customer['f_name'] . ' ' . $ot->order->customer['l_name'] }}</strong>
                                        </a>
                                        @elseif($ot->order->is_guest)
                                        @php($customer_details = json_decode($ot->order['delivery_address'],true))
                                        <strong>{{$customer_details['contact_person_name']}}</strong>
                                    @else
                                        <label class="badge badge-danger">{{ translate('messages.invalid_customer_data') }}</label>
                                    @endif
                                </td>
                                <?php
                                $discount_by_admin = 0;
                                    if($ot->order->discount_on_product_by == 'admin'){
                                        $discount_by_admin = $ot->order['restaurant_discount_amount'];
                                    };
                                ?>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order['order_amount'] - $ot->additional_charge  -  $ot->order['dm_tips']-$ot->order['delivery_charge'] - $ot['tax'] - $ot->order['extra_packaging_amount'] + $ot->order['coupon_discount_amount'] + $ot->order['restaurant_discount_amount'] + $ot->order['ref_bonus_amount']) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order->details()->sum(DB::raw('discount_on_food * quantity'))) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order['coupon_discount_amount']) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order['ref_bonus_amount']) }}</td>

                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::number_format_short($ot->order['coupon_discount_amount'] + $ot->order['restaurant_discount_amount'] + $ot->order['ref_bonus_amount']) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->tax) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->delivery_charge + $ot->delivery_fee_comission) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order_amount) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->admin_expense) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->discount_amount_by_restaurant) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->admin_commission   - $ot->additional_charge  + $ot->admin_expens  ) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency(($ot->additional_charge)) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency(($ot->extra_packaging_amount)) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->delivery_fee_comission) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->admin_commission + $ot->delivery_fee_comission ) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->restaurant_amount - $ot->tax) }}</td>
                                @if ($ot->received_by == 'admin')
                                    <td class="text-capitalize white-space-nowrap">{{ translate('messages.admin') }}</td>
                                @elseif ($ot->received_by == 'deliveryman')

                                    <td class="text-capitalize white-space-nowrap">
                                        <div>{{ translate('messages.delivery_man') }} </div>
                                        <div class="text-right mw--85px">
                                            @if (isset($ot->order->delivery_man) && $ot->order->delivery_man->earning == 1)
                                            <span class="badge badge-soft-primary">
                                                {{translate('messages.freelance')}}
                                            </span>
                                            @elseif (isset($ot->order->delivery_man) && $ot->order->delivery_man->earning == 0 && $ot->order->delivery_man->type == 'restaurant_wise')
                                            <span class="badge badge-soft-warning">
                                                {{translate('messages.restaurant')}}
                                            </span>
                                            @elseif (isset($ot->order->delivery_man) && $ot->order->delivery_man->earning == 0 && $ot->order->delivery_man->type == 'zone_wise')
                                            <span class="badge badge-soft-success">
                                                {{translate('messages.admin')}}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                @elseif ($ot->received_by == 'restaurant')
                                    <td class="text-capitalize white-space-nowrap">{{ translate('messages.restaurant') }}</td>
                                @endif
                                <td class="mw--85px text-capitalize min-w-120 ">
                                        {{ translate(str_replace('_', ' ', $ot->order['payment_method'])) }}
                                </td>
                                <td class="text-capitalize white-space-nowrap">
                                    @if ($ot->status)
                                    <span class="badge badge-soft-danger">
                                        {{translate('messages.refunded')}}
                                    </span>
                                    @else
                                    <span class="badge badge-soft-success">
                                        {{translate('messages.completed')}}
                                    </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn btn-outline-success square-btn btn-sm mr-1 action-btn"  href="{{route('vendor.report.generate-statement',[$ot['id']])}}">
                                            <i class="tio-download-to"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Body -->
        @if (count($order_transactions) !== 0)
            <hr>
        @endif
        <div class="page-area">
            {!! $order_transactions->links() !!}
        </div>
        @if (count($order_transactions) === 0)
            <div class="empty--data">
                <img src="{{ dynamicAsset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                <h5>
                    {{ translate('no_data_found') }}
                </h5>
            </div>
        @endif
    </div>
    <!-- End Card -->
</div>

@endsection

@push('script')
@endpush

@push('script_2')
    <script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/vendor/report.js"></script>
@endpush
