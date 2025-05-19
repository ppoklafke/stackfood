@extends('layouts.admin.app')

@section('title', $restaurant->name . "'s" . translate('messages.subscription'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{ dynamicAsset('public/assets/admin/css/croppie.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{dynamicAsset('/public/assets/landing/owl/dist/assets/owl.carousel.css')}}">

   <style>
        p.start {
            text-align: justify;
            display: inline;
        }

        h1.start {
            margin: 0;
            display: inline-block;
        }
    </style>
@endpush

@section('content')



    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="page-header-title text-break">
                    <i class="tio-museum"></i> <span>{{ $restaurant->name }}'s
                        {{ translate('messages.subscription') }}</span>
                </h1>
            </div>

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <span class="hs-nav-scroller-arrow-prev initial-hidden">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next initial-hidden">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>

                <!-- Nav -->
                @include('admin-views.vendor.view.partials._header',['restaurant'=>$restaurant])

                <!-- End Nav -->
            </div>

            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->


        <div class="mb-4 mt-2">
            <form class="row g-4 justify-content-end align-items-end" method="get">
            <input type="hidden" value="{{ $restaurant->id }}" name="id">
                <div class="col-lg-3 col-sm-6">
                    <select class="form-control set-filter"
                    data-url="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'subscriptions-transactions'])}}" data-filter="filter" >
                        <option {{$filter=='all'?'selected':''}} value="all">{{translate('messages.all_time')}}</option>
                        <option {{$filter=='month'?'selected':''}} value="month">{{translate('messages.this_month')}}</option>
                        <option {{$filter=='year'?'selected':''}} value="year">{{translate('messages.this_year')}}</option>
                    </select>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label for="start-date" class="__floating-date-label">
                        <span>{{translate('Start_Date')}}</span>
                    </label>
                    <input type="date" id="start-date" value="{{ $from ?? '' }}" name="start_date" required class="form-control">
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label for="end-date" class="__floating-date-label">
                        <span>{{translate('End_Date')}}</span>
                    </label>
                    <input type="date" id="end-date" value="{{ $to ?? '' }}" name="end_date" required class="form-control">
                </div>
                <div class="col-lg-3 col-sm-6">
                    <button class="btn btn--primary w-100" type="submit">{{translate('show_data')}}</button>
                </div>
            </form>
        </div>



        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper justify-content-end">
                    <h5 class="card-title">
                       {{ translate('messages.Transaction_List') }}
                        <span class="badge badge-soft-secondary badge-pill" id="itemCount">{{ $total }}</span>
                    </h5>
                    <form>
                        <!-- Search -->
                        <input type="hidden" value="{{ $restaurant->id }}" name="id">
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input id="datatableSearch_" type="search" name="search" class="form-control" value="{{request()->get('search')}}"
                                    placeholder="{{ translate('Ex:_Search_by_Transcation_id...') }}" aria-label="Search">
                            <button type="submit" class="btn btn--secondary">
                                <i class="tio-search"></i>
                            </button>
                        </div>
                        <!-- End Search -->
                    </form>
                    <!-- Unfold -->
                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle" href="javascript:;"
                            data-hs-unfold-options='{
                                "target": "#usersExportDropdown",
                                "type": "css-animation"
                            }'>
                            <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                        </a>

                        <div id="usersExportDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-header">{{translate('messages.download_options')}}</span>
                            <a id="export-excel" class="dropdown-item"  href="{{ route('admin.subscription.transcation_list_export', ['type' => 'excel','restaurant_id'=>$restaurant->id ,request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{dynamicAsset('public/assets/admin')}}/svg/components/excel.svg"
                                        alt="Image Description">
                                {{translate('messages.excel')}}
                            </a>
                            <a id="export-csv" class="dropdown-item"  href="{{ route('admin.subscription.transcation_list_export', ['type' => 'excel','restaurant_id'=>$restaurant->id ,request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{dynamicAsset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                        alt="Image Description">
                                {{translate('messages.csv')}}
                            </a>

                        </div>
                    </div>
                    <!-- End Unfold -->
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
                            <th class="w-90px">{{ translate('messages.transaction_id') }}</th>
                                <th class="w-130px">{{ translate('Transaction_Date') }}</th>
                            <th class="w-130px">{{ translate('messages.Package_Name') }}</th>
                            <th class="w-130px">{{ translate('messages.Pricing') }}</th>
                            <th class="w-130px">{{ translate('messages.Duration') }}</th>
                            <th class="w-130px">{{ translate('messages.Payment_Status') }}</th>
                            <th class="w-130px">{{ translate('messages.Payment_Method') }}</th>
                            <th class="text-center w-60px">{{ translate('messages.action') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        @include('admin-views.vendor.view.partials._rest_subs_transcation',['transcations' =>$transcations])
                    </tbody>
                </table>
            </div>
            @if(count($transcations) === 0)
            <div class="empty--data">
                <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
            <!-- End Table -->
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">

                    <div>
                        {!! $transcations->appends(request()->all())->links() !!}
                    </div>
                </div>
            </div>
            <!-- End Footer -->

        </div>
        <!-- End Card -->



    </div>



@endsection

@push('script_2')
<script type="text/javascript" src="{{dynamicAsset('/public/assets/landing/owl/dist/owl.carousel.min.js')}}"></script>
<script>
    "use strict";

    $(document).on('ready', function () {



    // INITIALIZATION OF DATATABLES
    // =======================================================
    let datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
        dom: 'Bfrtip',
        buttons: [
            {
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
                className: 'd-none'
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
                '<img class="mb-3 w-7rem" src="{{dynamicAsset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description">' +
                '<p class="mb-0">{{ translate('No_data_to_show') }}</p>' +
                '</div>'
        }
    });



    $('#datatableSearch').on('mouseup', function (e) {
        let $input = $(this),
            oldValue = $input.val();

        if (oldValue == "") return;

        setTimeout(function () {
            let newValue = $input.val();

            if (newValue == "") {
                // Gotcha
                datatable.search('').draw();
            }
        }, 1);
    });

    $('#toggleColumn_name').change(function (e) {
        datatable.columns(1).visible(e.target.checked)
    })

    $('#toggleColumn_price').change(function (e) {
        datatable.columns(2).visible(e.target.checked)
    })

    $('#toggleColumn_validity').change(function (e) {
        datatable.columns(3).visible(e.target.checked)
    })

    $('#toggleColumn_total_sell').change(function (e) {
        datatable.columns(4).visible(e.target.checked)
    })

    $('#toggleColumn_status').change(function (e) {
        datatable.columns(5).visible(e.target.checked)
    })

    $('#toggleColumn_actions').change(function (e) {
        datatable.columns(6).visible(e.target.checked)
    })
    });
</script>
@endpush









