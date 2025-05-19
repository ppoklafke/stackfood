@extends('layouts.admin.app')

@section('title', translate('Order_List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mb-2 text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{ dynamicAsset('/public/assets/admin/img/orders.png') }}" alt="public">
                </div>
                <span>
                    {{ translate(str_replace('_', ' ', $status)) }} {{ translate('messages.orders') }}
                </span>
                <span class="badge badge-soft-dark ml-2">{{ $total }}</span>
            </h1>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header flex-wrap py-2">
                <div class="search--button-wrapper justify-content-end">
                    <form class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                        <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                value="{{ request()?->search ?? null }}"
                                placeholder="{{ translate('messages.Ex:_Search your order...') }}"
                                aria-label="{{ translate('messages.search') }}">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                    <!-- Datatable Info -->
                    <!-- End Datatable Info -->

                    <!-- Unfold -->
                    <!-- End Unfold -->
                    @if (Request::is('admin/refund/*'))
                        <div class="select-item">
                            <select name="slist" class="form-control js-select2-custom refund-filter">
                                <option {{ $status == 'requested' ? 'selected' : '' }}
                                    value="{{ route('admin.refund.refund_attr', ['requested']) }}">
                                    {{ translate('messages.Refund_Requests') }}</option>
                                <option {{ $status == 'refunded' ? 'selected' : '' }}
                                    value="{{ route('admin.refund.refund_attr', ['refunded']) }}">
                                    {{ translate('messages.Refund') }}</option>
                                <option {{ $status == 'rejected' ? 'selected' : '' }}
                                    value="{{ route('admin.refund.refund_attr', ['rejected']) }}">
                                    {{ translate('Rejected') }}</option>
                            </select>
                        </div>
                    @endif
                    <!-- Unfold -->
                    <div class="hs-unfold">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn export--btn btn-outline-primary btn--primary font--sm"
                            href="javascript:"
                            data-hs-unfold-options='{
                                "target": "#usersExportDropdown",
                                "type": "css-animation"
                            }'>
                            <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                        </a>

                        <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                            <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                            <a id="export-excel" class="dropdown-item" href="javascript:">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ dynamicAsset('public/assets/admin') }}/svg/components/excel.svg"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item" href="javascript:">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ dynamicAsset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                                {{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <!-- End Unfold -->
                    <!-- Unfold -->

                    @if ( isset($status) && $status !=  'dine_in')
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white filter-show" href="javascript:">
                                <i class="tio-filter-list mr-1"></i>{{ translate('filters') }} <span
                                    class="badge badge-success badge-pill ml-1" id="filter_count"></span>
                            </a>
                        </div>
                    @endif
                    <!-- End Unfold -->
                    <!-- Unfold -->
                    <div class="hs-unfold">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white" href="javascript:"
                            data-hs-unfold-options='{
                                "target": "#showHideDropdown",
                                "type": "css-animation"
                            }'>
                            <i class="tio-table mr-1"></i> {{ translate('messages.columns') }}
                        </a>

                        <div id="showHideDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('messages.order') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_order"
                                                checked>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('messages.date') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_date">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_date"
                                                checked>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('messages.customer') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_customer">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_customer"
                                                checked>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('messages.restaurant') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_restaurant">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_restaurant"
                                                checked>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>



                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('messages.total') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_total">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_total"
                                                checked>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('messages.order_status') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order_status">
                                            <input type="checkbox" class="toggle-switch-input"
                                                id="toggleColumn_order_status" checked>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>



                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="mr-2">{{ translate('messages.actions') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_actions">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_actions"
                                                checked>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom fz--14px">
                <table id="datatable"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0],
                        "orderable": false
                      }],
                     "order": [],
                     "info": {
                       "totalQty": "#datatableWithPaginationInfoTotalQty"
                     },
                     "search": "#datatableSearch",
                     "entries": "#datatableEntries",
                     "isResponsive": false,
                     "isShowPaging": false,
                     "paging": false
                   }'>
                    <thead class="thead-light">
                        <tr>
                            <th class="w-60px">
                                {{ translate('sl') }}
                            </th>
                            <th class="table-column-pl-0 w-90px">{{ translate('messages.Order_ID') }}</th>
                            <th class="w-140px">{{ translate('messages.order_date') }}</th>
                            <th class="w-130px">{{ translate('messages.customer_information') }}</th>
                            <th class="w-140px">{{ translate('messages.restaurant') }}</th>
                            <th class="w-120px">{{ translate('messages.total_amount') }}</th>
                            <th class="text-center w-120px">{{ translate('messages.order_status') }}</th>
                            <th class="text-center w-100px">{{ translate('messages.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        @foreach ($orders as $key => $order)
                            <tr class="status-{{ $order['order_status'] }} class-all">
                                <td class="">
                                    {{ $key + $orders->firstItem() }}
                                </td>
                                <td class="table-column-pl-0">
                                    <a class="text--title"
                                        href="{{ route('admin.order.details', ['id' => $order['id']]) }}">{{ $order['id'] }}
                                        {{-- <span class="text--warning font-bold">(POS)</span></a> --}}
                                </td>
                                <td class="text-uppercase">
                                    <div>
                                        {{ \App\CentralLogics\Helpers::date_format($order->created_at) }}

                                    </div>
                                    <div>
                                        {{ \App\CentralLogics\Helpers::time_format($order->created_at) }}
                                    </div>
                                </td>
                                <td>
                                    @if ($order->is_guest)
                                        @php
                                            $customer_details = json_decode($order['delivery_address'], true);
                                        @endphp

                                        <strong>{{ $customer_details['contact_person_name'] }}</strong>
                                        <div>{{ $customer_details['contact_person_number'] }}</div>
                                    @elseif($order->customer)
                                        <a class="text-body text-capitalize"
                                            href="{{ route('admin.customer.view', [$order['user_id']]) }}">
                                            <div class="customer--name">
                                                {{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}
                                            </div>
                                            <span class="phone">
                                                {{ $order->customer['phone'] }}
                                            </span>
                                        </a>
                                    @else
                                        <label
                                            class="badge badge-danger">{{ translate('messages.invalid_customer_data') }}</label>
                                    @endif
                                </td>

                                <td>
                                    <label class="m-0">
                                        <a href="{{ route('admin.restaurant.view', $order->restaurant_id) }}"
                                            class="text--title" alt="view restaurant">
                                            {{ Str::limit($order->restaurant ? $order->restaurant->name : translate('messages.Restaurant_deleted!'), 20, '...') }}
                                        </a>
                                    </label>
                                </td>
                                <td>
                                    <div class="text-right mw-85px">
                                        <div>
                                            {{ \App\CentralLogics\Helpers::format_currency($order['order_amount']) }}
                                        </div>
                                        @if ($order->payment_status == 'paid')
                                            <strong class="text-success">
                                                {{ translate('messages.paid') }}
                                            </strong>
                                        @elseif($order->payment_status == 'partially_paid')
                                            <strong class="text-success">
                                                {{ translate('messages.partially_paid') }}
                                            </strong>
                                        @else
                                            <strong class="text-danger">
                                                {{ translate('messages.unpaid') }}
                                            </strong>
                                        @endif
                                    </div>
                                </td>

                                @if (isset($order->subscription) && $order->subscription->status != 'canceled')
                                    @php
                                        $order->order_status = $order->subscription_log
                                            ? $order->subscription_log->order_status
                                            : $order->order_status;
                                    @endphp
                                @endif
                                <td class="text-capitalize text-center">
                                    @if ($order['order_status'] == 'pending')
                                        <span class="badge badge-soft-info mb-1">
                                            {{ translate('messages.pending') }}
                                        </span>
                                    @elseif($order['order_status'] == 'confirmed')
                                        <span class="badge badge-soft-info mb-1">
                                            {{ translate('messages.confirmed') }}
                                        </span>
                                    @elseif($order['order_status'] == 'processing')
                                        <span class="badge badge-soft-warning mb-1">
                                            {{ translate('messages.processing') }}
                                        </span>
                                    @elseif($order['order_status'] == 'picked_up')
                                        <span class="badge badge-soft-warning mb-1">
                                            {{ translate('messages.out_for_delivery') }}
                                        </span>
                                    @elseif($order['order_status'] == 'delivered')
                                        <span class="badge badge-soft-success mb-1">
                                            {{$order?->order_type == 'dine_in' ? translate('messages.Completed') : translate('messages.delivered')}}
                                        </span>
                                    @elseif($order['order_status'] == 'failed')
                                        <span class="badge badge-soft-danger mb-1">
                                            {{ translate('messages.payment_failed') }}
                                        </span>
                                    @else
                                        <span class="badge badge-soft-danger mb-1">
                                            {{ translate(str_replace('_', ' ', $order['order_status'])) }}
                                        </span>
                                    @endif
                                    <div class="text-capitalze opacity-7">
                                        @if ($order['order_type'] == 'take_away')
                                            <span>
                                                {{ translate('messages.take_away') }}
                                            </span>
                                        @elseif ($order['order_type'] == 'dine_in')
                                            <span>
                                                {{ translate('Dine_in') }}
                                            </span>
                                        @else
                                            <span>
                                                {{ translate('home_delivery') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="ml-2 btn btn-sm btn--warning btn-outline-warning action-btn"
                                            href="{{ route('admin.order.details', ['id' => $order['id']]) }}"><i
                                                class="tio-invisible"></i></a>

                                        <a class="ml-2 btn btn-sm btn--primary btn-outline-primary download--btn action-btn"
                                            href={{ route('admin.order.generate-invoice', [$order['id']]) }}><i
                                                class="tio-print"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (count($orders) === 0)
                    <div class="empty--data">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/empty.png') }}" alt="public">
                        <h5>
                            {{ translate('no_data_found') }}
                        </h5>
                    </div>
                @endif
            </div>
            <!-- End Table -->

            <div class="card-footer p-0 border-0">
                <!-- Pagination -->
                <div class="page-area px-4 pb-3">
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                            {!! $orders->appends($_GET)->links() !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
        <!-- Order Filter Modal -->
        <div id="datatableFilterSidebar"
            class="hs-unfold-content_ sidebar sidebar-bordered sidebar-box-shadow initial-hidden">
            <div class="card card-lg sidebar-card sidebar-footer-fixed">
                <div class="card-header">
                    <h4 class="card-header-title">{{ translate('messages.order_filter') }}</h4>

                    <!-- Toggle Button -->
                    <a class="js-hs-unfold-invoker_ btn btn-icon btn-xs btn-ghost-dark ml-2 filter-hide"
                        href="javascript:">
                        <i class="tio-clear tio-lg"></i>
                    </a>
                    <!-- End Toggle Button -->
                </div>
                <?php
                $filter_count = 0;
                if (isset($zone_ids) && count($zone_ids) > 0) {
                    $filter_count += 1;
                }
                if (isset($vendor_ids) && count($vendor_ids) > 0) {
                    $filter_count += 1;
                }
                if ($status == 'all') {
                    if (isset($orderstatus) && count($orderstatus) > 0) {
                        $filter_count += 1;
                    }
                    if (isset($scheduled) && $scheduled == 1) {
                        $filter_count += 1;
                    }
                }

                if (isset($from_date) && isset($to_date)) {
                    $filter_count += 1;
                }
                if (isset($order_type)) {
                    $filter_count += 1;
                }

                ?>
                <!-- Body -->
                <form class="card-body sidebar-body sidebar-scrollbar" action="{{ route('admin.order.filter') }}"
                    method="POST" id="order_filter_form">
                    @csrf
                    <small class="text-cap mb-3">{{ translate('messages.zone') }}</small>

                    <div class="mb-2 initial-36">
                        <select name="zone[]" id="zone_ids" class="form-control js-select2-custom"
                            multiple="multiple">
                            @foreach (\App\Models\Zone::orderBy('name')->get(['id', 'name']) as $zone)
                                <option value="{{ $zone->id }}"
                                    {{ isset($zone_ids) ? (in_array($zone->id, $zone_ids) ? 'selected' : '') : '' }}>
                                    {{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr class="my-4">
                    <small class="text-cap mb-3">{{ translate('messages.restaurant') }}</small>
                    <div class="mb-2 initial-36">
                        <select name="vendor[]" id="vendor_ids" class="form-control js-select2-custom"
                            multiple="multiple">
                            @foreach (\App\Models\Restaurant::whereIn('id', $vendor_ids)->get(['id', 'name']) as $restaurant)
                                <option value="{{ $restaurant->id }}" selected>{{ $restaurant->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="my-4">
                    @if ($status == 'all')
                        <small class="text-cap mb-3">{{ translate('messages.order_status') }}</small>

                        <!-- Custom Checkbox -->
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus2" name="orderStatus[]" class="custom-control-input"
                                {{ isset($orderstatus) ? (in_array('pending', $orderstatus) ? 'checked' : '') : '' }}
                                value="pending">
                            <label class="custom-control-label"
                                for="orderStatus2">{{ translate('messages.pending') }}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus1" name="orderStatus[]" class="custom-control-input"
                                value="confirmed"
                                {{ isset($orderstatus) ? (in_array('confirmed', $orderstatus) ? 'checked' : '') : '' }}>
                            <label class="custom-control-label"
                                for="orderStatus1">{{ translate('messages.confirmed') }}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus3" name="orderStatus[]" class="custom-control-input"
                                value="processing"
                                {{ isset($orderstatus) ? (in_array('processing', $orderstatus) ? 'checked' : '') : '' }}>
                            <label class="custom-control-label"
                                for="orderStatus3">{{ translate('messages.processing') }}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus4" name="orderStatus[]" class="custom-control-input"
                                value="picked_up"
                                {{ isset($orderstatus) ? (in_array('picked_up', $orderstatus) ? 'checked' : '') : '' }}>
                            <label class="custom-control-label"
                                for="orderStatus4">{{ translate('messages.out_for_delivery') }}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus5" name="orderStatus[]" class="custom-control-input"
                                value="delivered"
                                {{ isset($orderstatus) ? (in_array('delivered', $orderstatus) ? 'checked' : '') : '' }}>
                            <label class="custom-control-label"
                                for="orderStatus5">{{ translate('messages.delivered') }}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus7" name="orderStatus[]" class="custom-control-input"
                                value="failed"
                                {{ isset($orderstatus) ? (in_array('failed', $orderstatus) ? 'checked' : '') : '' }}>
                            <label class="custom-control-label"
                                for="orderStatus7">{{ translate('messages.failed') }}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus8" name="orderStatus[]" class="custom-control-input"
                                value="canceled"
                                {{ isset($orderstatus) ? (in_array('canceled', $orderstatus) ? 'checked' : '') : '' }}>
                            <label class="custom-control-label"
                                for="orderStatus8">{{ translate('messages.canceled') }}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus9" name="orderStatus[]" class="custom-control-input"
                                value="refund_requested"
                                {{ isset($orderstatus) ? (in_array('refund_requested', $orderstatus) ? 'checked' : '') : '' }}>
                            <label class="custom-control-label"
                                for="orderStatus9">{{ translate('messages.refundRequest') }}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus10" name="orderStatus[]" class="custom-control-input"
                                value="refunded"
                                {{ isset($orderstatus) ? (in_array('refunded', $orderstatus) ? 'checked' : '') : '' }}>
                            <label class="custom-control-label"
                                for="orderStatus10">{{ translate('messages.refunded') }}</label>
                        </div>

                        <hr class="my-4">

                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="scheduled" name="scheduled" class="custom-control-input"
                                value="1" {{ isset($scheduled) ? ($scheduled == 1 ? 'checked' : '') : '' }}>
                            <label class="custom-control-label text-uppercase"
                                for="scheduled">{{ translate('messages.scheduled') }}</label>
                        </div>
                    @endif
                    <hr class="my-4">
                    <small class="text-cap mb-3">{{ translate('messages.order_type') }}</small>
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="take_away" name="order_type" class="custom-control-input"
                            value="take_away"
                            {{ isset($order_type) ? ($order_type == 'take_away' ? 'checked' : '') : '' }}>
                        <label class="custom-control-label text-uppercase"
                            for="take_away">{{ translate('messages.take_away') }}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="dine_in" name="order_type" class="custom-control-input"
                            value="dine_in"
                            {{ isset($order_type) ? ($order_type == 'dine_in' ? 'checked' : '') : '' }}>
                        <label class="custom-control-label text-uppercase"
                            for="dine_in">{{ translate('messages.dine_in') }}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="delivery" name="order_type" class="custom-control-input"
                            value="delivery"
                            {{ isset($order_type) ? ($order_type == 'delivery' ? 'checked' : '') : '' }}>
                        <label class="custom-control-label text-uppercase"
                            for="delivery">{{ translate('messages.delivery') }}</label>
                    </div>
                    <hr class="my-4">

                    <small class="text-cap mb-3">{{ translate('messages.date_between') }}</small>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group m-0">
                                <input type="date" name="from_date" class="form-control" id="date_from"
                                    value="{{ isset($from_date) ? $from_date : '' }}">
                            </div>
                        </div>
                        <div class="col-12 text-center">{{ translate('----to----') }}</div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="date" name="to_date" class="form-control" id="date_to"
                                    value="{{ isset($to_date) ? $to_date : '' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer sidebar-footer">
                        <div class="row gx-2">
                            <div class="col">
                                <button type="reset" class="btn btn-block btn-white" id="reset">
                                    {{ translate('clear_all_filters') }}</button>
                            </div>
                            <div class="col">
                                <button type="submit"
                                    class="btn btn-block btn-primary">{{ translate('save') }}</button>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer -->
                </form>
            </div>
        </div>
        <!-- End Order Filter Modal -->
    @endsection

    @push('script_2')
        <script>
            "use strict";
            $(document).on('ready', function() {
                @if ($filter_count > 0)
                    $('#filter_count').html({{ $filter_count }});
                @endif
                // INITIALIZATION OF SELECT2
                // =======================================================
                $('.js-select2-custom').each(function() {
                    let select2 = $.HSCore.components.HSSelect2.init($(this));
                });

                let zone_id = [];
                $('#zone_ids').on('change', function() {
                    if ($(this).val()) {
                        zone_id = $(this).val();
                    } else {
                        zone_id = [];
                    }
                });


                $('#vendor_ids').select2({
                    ajax: {
                        url: '{{ url('/') }}/admin/restaurant/get-restaurants',
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                zone_ids: zone_id,
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

                // INITIALIZATION OF DATATABLES
                // =======================================================
                let datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excel',
                            className: 'd-none',
                            action: function(e, dt, node, config) {
                                window.location.href =
                                    '{{ route('admin.order.export', ['status' => $status, 'type' => 'excel', request()->getQueryString()]) }}';
                            }
                        },
                        {
                            extend: 'csv',
                            className: 'd-none',
                            action: function(e, dt, node, config) {
                                window.location.href =
                                    '{{ route('admin.order.export', ['status' => $status, 'type' => 'csv', request()->getQueryString()]) }}';
                            }
                        },
                    ],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child input[type="checkbox"]',
                        classMap: {
                            checkAll: '#datatableCheckAll',
                            counter: '#datatableCounter',
                            counterInfo: '#datatableCounterInfo'
                        }
                    },
                    language: {
                        zeroRecords: '<div class="text-center p-4">' +
                            '<img class="mb-3 w-7rem" src="{{ dynamicAsset('public/assets/admin') }}/svg/illustrations/sorry.svg" alt="Image Description">' +
                            '<p class="mb-0">{{ translate('No_data_to_show') }}</p>' +
                            '</div>'
                    }
                });

                $('#export-excel').click(function() {
                    datatable.button('.buttons-excel').trigger()
                });

                $('#export-csv').click(function() {
                    datatable.button('.buttons-csv').trigger()
                });

                $('#datatableSearch').on('mouseup', function(e) {
                    let $input = $(this),
                        oldValue = $input.val();

                    if (oldValue == "") return;

                    setTimeout(function() {
                        let newValue = $input.val();

                        if (newValue == "") {
                            // Gotcha
                            datatable.search('').draw();
                        }
                    }, 1);
                });

                $('#toggleColumn_order').change(function(e) {
                    datatable.columns(1).visible(e.target.checked)
                })

                $('#toggleColumn_date').change(function(e) {
                    datatable.columns(2).visible(e.target.checked)
                })

                $('#toggleColumn_customer').change(function(e) {
                    datatable.columns(3).visible(e.target.checked)
                })
                $('#toggleColumn_restaurant').change(function(e) {
                    datatable.columns(4).visible(e.target.checked)
                })

                $('#toggleColumn_payment_status').change(function(e) {
                    datatable.columns(5).visible(e.target.checked)
                })

                $('#toggleColumn_total').change(function(e) {
                    datatable.columns(5).visible(e.target.checked)
                })
                $('#toggleColumn_order_status').change(function(e) {
                    datatable.columns(6).visible(e.target.checked)
                })

                $('#toggleColumn_actions').change(function(e) {
                    datatable.columns(7).visible(e.target.checked)
                })
                // INITIALIZATION OF TAGIFY
                // =======================================================
                $('.js-tagify').each(function() {
                    let tagify = $.HSCore.components.HSTagify.init($(this));
                });

                $("#date_from").on("change", function() {
                    $('#date_to').attr('min', $(this).val());
                });

                $("#date_to").on("change", function() {
                    $('#date_from').attr('max', $(this).val());
                });
            });

            $('#reset').on('click', function() {
                location.href = '{{ url('/') }}/admin/order/filter/reset';
            });

            $('.refund-filter').on('change', function() {
                window.location.href = this.options[this.selectedIndex].value;
            });

            $('.filter-hide').on('click', function() {
                $('#datatableFilterSidebar,.hs-unfold-overlay').hide(500);
            });

            $('.filter-show').on('click', function() {
                $('#datatableFilterSidebar,.hs-unfold-overlay').show(500);
            });
        </script>
    @endpush
