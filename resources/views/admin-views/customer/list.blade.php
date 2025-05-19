@extends('layouts.admin.app')

@section('title', translate('Customer_list'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm">
                    <h1 class="page-header-title">
                        <span class="page-header-icon"><i class="tio-group-equal"></i></span>
                        {{ translate('messages.customers') }}
                    </h1>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Page Header -->


        <!-- End Page Header -->
        <div class="card mb-3">
            <div class="card-body">
                <form>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ translate('Order Date') }}</label>
                            <div class="position-relative">
                                <span class="tio-calendar icon-absolute-on-right"></span>
                                <input type="text" data-startDate="09/04/2024" data-endDate="09/24/2024" readonly
                                    name="order_date" value="{{ request()->get('order_date') ?? null }}"
                                    class="date-range-picker form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ translate('Customer Joining Date') }}</label>
                            <div class="position-relative">
                                <span class="tio-calendar icon-absolute-on-right"></span>
                                <input type="text" readonly name="join_date"
                                    value="{{ request()->get('join_date') ?? null }}"
                                    class="date-range-picker form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ translate('Customer status') }}</label>
                            <select name="filter" data-placeholder="{{ translate('messages.Select_Status') }}"
                                class="form-control js-select2-custom ">
                                <option value="" selected disabled> {{ translate('messages.Select_Status') }}
                                </option>
                                <option {{ request()->get('filter') == 'all' ? 'selected' : '' }} value="all">
                                    {{ translate('messages.All_Customers') }}</option>
                                <option {{ request()->get('filter') == 'active' ? 'selected' : '' }} value="active">
                                    {{ translate('messages.Active_Customers') }}</option>
                                <option {{ request()->get('filter') == 'blocked' ? 'selected' : '' }} value="blocked">
                                    {{ translate('messages.Inactive_Customers') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ translate('Sort By') }}</label>
                            <select name="order_wise"
                                data-placeholder="{{ translate('messages.Select Customer Sorting Order') }}"
                                class="form-control js-select2-custom">
                                <option value="" selected disabled>
                                    {{ translate('messages.Select Customer Sorting Order') }} </option>
                                <option {{ request()->get('order_wise') == 'top' ? 'selected' : '' }} value="top">
                                    {{ translate('messages.Sort by order count') }}</option>
                                <option {{ request()->get('order_wise') == 'order_amount' ? 'selected' : '' }}
                                    value="order_amount">{{ translate('messages.Sort by order amount') }}</option>
                                <option {{ request()->get('order_wise') == 'oldest' ? 'selected' : '' }} value="oldest">
                                    {{ translate('messages.Sort by oldest') }}</option>
                                <option {{ request()->get('order_wise') == 'latest' ? 'selected' : '' }} value="latest">
                                    {{ translate('messages.Sort by newest') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ translate('Choose First') }}</label>
                            <input type="number" min="1" name="show_limit" class="form-control"
                                value="{{ request()->get('show_limit') }}" placeholder="{{ translate('Ex : 100') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="d-md-block">&nbsp;</label>
                            <div class="btn--container justify-content-end">
                                <button type="submit" class="btn btn--primary">{{ translate('Filter') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Card -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header gap-2 flex-wrap pt-3 border-0">
                <h3 class="m-0">
                    {{ translate('messages.customer_list') }} <span class="badge badge-soft-dark ml-2"
                        id="count">{{ $customers->total() }}</span>
                </h3>
                <div class="search--button-wrapper justify-content-end">
                    <form>
                        <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                value="{{ request()?->search ?? null }}"
                                placeholder="{{ translate('Ex:_Search_by_name') }}" aria-label="Search" required>
                            <button type="submit" class="btn btn--secondary">
                                <i class="tio-search"></i>
                            </button>
                        </div>
                        <!-- End Search -->
                    </form>
                    <div class="d-flex flex-wrap justify-content-sm-end align-items-sm-center ml-0 mr-0">


                        <!-- Unfold -->
                        <div class="hs-unfold mr-2">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle" href="javascript:;"
                                data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                                <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                            </a>

                            <div id="usersExportDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                                <a id="export-excel" class="dropdown-item"
                                    href="{{ route('admin.customer.export', ['type' => 'excel', request()->getQueryString()]) }}">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{ dynamicAsset('public/assets/admin') }}/svg/components/excel.svg"
                                        alt="Image Description">
                                    {{ translate('messages.excel') }}
                                </a>
                                <a id="export-csv" class="dropdown-item"
                                    href="{{ route('admin.customer.export', ['type' => 'csv', request()->getQueryString()]) }}">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{ dynamicAsset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                        alt="Image Description">
                                    .{{ translate('messages.csv') }}
                                </a>
                            </div>
                        </div>
                        <!-- End Unfold -->
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
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
                     "pageLength": 25,
                     "isResponsive": false,
                     "isShowPaging": false,
                     "paging":false
                   }'>
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0">
                                {{ translate('sl') }}
                            </th>
                            <th class="table-column-pl-0 border-0">{{ translate('messages.name') }}</th>
                            <th class="border-0">{{ translate('messages.contact_information') }}</th>
                            <th class="border-0">{{ translate('messages.total_order') }}</th>
                            <th class="border-0">{{ translate('messages.total_order_amount') }}</th>
                            <th class="border-0">{{ translate('messages.Joining_date') }}</th>
                            <th class="border-0">
                                {{ translate('messages.active') }}/{{ translate('messages.inactive') }}</th>
                            <th class="border-0">{{ translate('messages.actions') }}</th>
                        </tr>
                    </thead>
                    @php
                        $count = 0;
                    @endphp
                    <tbody id="set-rows">
                        @foreach ($customers as $key => $customer)
                            <tr class="">
                                <td class="">
                                    {{ (request()->get('show_limit') ? $count++ : $key) + $customers->firstItem() }}
                                </td>
                                <td class="table-column-pl-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <img class="rounded aspect-1-1 object-cover" width="40"
                                            data-onerror-image="{{ asset('public/assets/admin/img/160x160/img1.jpg') }}"
                                            src="{{ $customer->image_full_url }}" alt="Image Description">
                                        <a href="{{ route('admin.customer.view', [$customer['id']]) }}"
                                            class="text-body text-hover-primary">
                                            {{  $customer['f_name']?  $customer['f_name'] . ' ' . $customer['l_name']  : translate('Incomplete_profile') }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <a href="mailto:{{ $customer['email'] }}" class="text-body text-hover-primary">
                                            {{ $customer['email'] }}
                                        </a>
                                    </div>
                                    <div>
                                        <a href="tel:{{ $customer['phone'] }}" class="text-body text-hover-primary">
                                            {{ $customer['phone'] }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <label class="badge">
                                        {{ $customer->orders_count }}
                                    </label>
                                </td>
                                <td>
                                    <label class="badge">
                                        {{ \App\CentralLogics\Helpers::format_currency($customer->orders()->sum('order_amount')) }}
                                    </label>
                                </td>
                                <td>
                                    <label class="badge">
                                        {{ \App\CentralLogics\Helpers::date_format($customer->created_at) }}
                                    </label>
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm ml-xl-4"
                                        for="stocksCheckbox{{ $customer->id }}">
                                        <input type="checkbox"
                                            data-url="{{ route('admin.customer.status', [$customer->id, $customer->status ? 0 : 1]) }}"
                                            data-message="{{ $customer->status ? translate('messages.you_want_to_block_this_customer') : translate('messages.you_want_to_unblock_this_customer') }}"
                                            class="toggle-switch-input status_change_alert"
                                            id="stocksCheckbox{{ $customer->id }}"
                                            {{ $customer->status ? 'checked' : '' }}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <a class="btn action-btn btn--warning btn-outline-warning"
                                        href="{{ route('admin.customer.view', [$customer['id']]) }}"
                                        title="{{ translate('messages.view_customer') }}"><i
                                            class="tio-visible-outlined"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if (count($customers) === 0)
                <div class="empty--data">
                    <img src="{{ dynamicAsset('/public/assets/admin/img/empty.png') }}" alt="public">
                    <h5>
                        {{ translate('no_data_found') }}
                    </h5>
                </div>
            @endif
            <!-- End Table -->
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div>
                        {!! $customers->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
            <!-- End Footer -->

        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
        "use strict";
        $('.status_change_alert').on('click', function(event) {
            let url = $(this).data('url');
            let message = $(this).data('message');
            status_change_alert(url, message, event)
        })

        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are_you_sure_?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
        $(document).on('ready', function() {
            // INITIALIZATION OF NAV SCROLLER
            // =======================================================
            $('.js-nav-scroller').each(function() {
                new HsNavScroller($(this)).init()
            });

            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });


            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        className: 'd-none'
                    },
                    {
                        extend: 'excel',
                        className: 'd-none'
                    },
                    {
                        extend: 'csv',
                        className: 'd-none'
                    },
                    {
                        extend: 'pdf',
                        className: 'd-none',
                        customize: function(doc) {
                            doc.content[1].table.body.forEach(row => {
                                row.splice(4, 3);
                            });
                        }
                    },
                    {
                        extend: 'print',
                        className: 'd-none'
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

            $('#toggleColumn_name').change(function(e) {
                datatable.columns(1).visible(e.target.checked)
            })

            $('#toggleColumn_email').change(function(e) {
                datatable.columns(2).visible(e.target.checked)
            })

            $('#toggleColumn_total_order').change(function(e) {
                datatable.columns(3).visible(e.target.checked)
            })

            $('#toggleColumn_status').change(function(e) {
                datatable.columns(4).visible(e.target.checked)
            })

            $('#toggleColumn_actions').change(function(e) {
                datatable.columns(5).visible(e.target.checked)
            })

            // INITIALIZATION OF TAGIFY
            // =======================================================
            $('.js-tagify').each(function() {
                let tagify = $.HSCore.components.HSTagify.init($(this));
            });
        });
    </script>
@endpush
