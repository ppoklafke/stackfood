@extends('layouts.admin.app')

@section('title', translate('messages.food_report'))

@push('css_or_js')
@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <i class="tio-filter-list"></i>
                <span>
                    {{ translate('earning_report') }}
                    @if ($from && $to)
                        <span class="h6 mb-0 badge badge-soft-success ml-2">
                            ( {{ $from }} - {{ $to }} )</span>
                    @endif
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

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
                        <div class="col-sm-6 col-md-3">
                            <select class="js-select2-custom form-control">
                                <option value="">All Customer</option>
                                <option value="">All Customer</option>
                                <option value="">All Customer</option>
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <input type="text" class="date-range-picker form-control">
                        </div>

                        <div class="col-sm-6 col-md-3 ml-auto">
                            <button type="submit" class="btn btn-primary btn-block">{{ translate('Filter') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="">
            <div class="d-flex flex-wrap gap-3 mb-3">
                <!-- Card -->
                <div class="earning-statistics-chart-area">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-evenly justify-content-lg-between border-0">
                                <h4 class="card-title m-0">
                                    <i class="tio-chart-bar-4"></i>
                                    {{ translate('Earning Statistics (Total Earing $37932.00)') }}
                                </h4>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="chart--extension">
                                    {{ \App\CentralLogics\Helpers::currency_symbol() }}({{ translate('messages.currency') }})
                                </div>
                                <div class="chartjs-custom w-75 flex-grow-1 h-20rem mr-xl-4">
                                    <canvas id="chart1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
                <div class="pie-chart-area">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="position-relative" >
                                <div class="chartjs-custom mx-auto">
                                    <div id="order-report"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="card h-100">
                    <!-- Header -->
                    <div class="card-header border-0 py-2">
                        <div class="search--button-wrapper">
                            <h3 class="card-title">
                                {{ translate('Order List') }}
                            </h3>
                            <form class="search-form">
                                <!-- Search -->
                                <div class="input--group input-group">
                                    <input id="datatableSearch" name="search" type="search" class="form-control" value="{{ request()->search ?? null }}"
                                        placeholder="{{ translate('Search_by_food_name') }}"
                                        aria-label="{{ translate('messages.search_here') }}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                            <div class="w-160px">
                                <select class="js-select2-custom form-control">
                                    <option value="">All Order List</option>
                                    <option value="">Camping Order</option>
                                    <option value="">Regular Order</option>
                                </select>
                            </div>
                            <div class="hs-unfold mr-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40"
                                    href="javascript:;"
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
                                        href="{{ route('admin.report.food-wise-report-export', ['export_type' => 'excel', request()->getQueryString()]) }}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ dynamicAsset('public/assets/admin') }}/svg/components/excel.svg"
                                            alt="Image Description">
                                        {{ translate('messages.excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item"
                                        href="{{ route('admin.report.food-wise-report-export', ['export_type' => 'csv', request()->getQueryString()]) }}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ dynamicAsset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                        {{ translate('messages.csv') }}
                                    </a>
                                </div>
                            </div>


                            <!-- End Unfold -->
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Header -->
                    <div class="card-body">
                        <!-- Table -->
                        <div class="table-responsive datatable-custom" id="table-div">
                            <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table">
                                <thead class="thead-light white-space-initial">
                                    <tr>
                                        <th>{{ translate('sl') }}</th>
                                        <th>{{ translate('Order ID') }}</th>
                                        <th class="text-right">{{ translate('Order Amount') }}</th>
                                        <th class="text-right">{{ translate('VAT/Tax') }}</th>
                                        <th class="text-right">{{ translate('Extra Packaging Charge') }}</th>
                                        <th class="text-right">{{ translate('Additional Charge') }}</th>
                                        <th class="text-right">{{ translate('Commission on Order') }}</th>
                                        <th class="text-right">{{ translate('Commission on Delivery Charge') }}</th>
                                        <th class="text-right">{{ translate('Total Earning') }} <i class="tio-info-outined" data-toggle="tooltip" title="Info Upgraded"></i></th>
                                    </tr>
                                </thead>

                                <tbody id="set-rows">
                                    @foreach ($foods as $key => $food)
                                        <tr>
                                            <td>{{ $key + $foods->firstItem() }}</td>
                                            <td><div class="font-medium">100234</div></td>
                                            <td class="text-right">$ 687.93</td>
                                            <td class="text-right">$ 687.93</td>
                                            <td class="text-right">$ 687.93</td>
                                            <td class="text-right">$ 687.93</td>
                                            <td class="text-right">$ 687.93</td>
                                            <td class="text-right">$ 687.93</td>
                                            <td class="text-right">$ 687.93</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if (count($foods) !== 0)
                                <hr>
                            @endif
                            <div class="page-area px-4 pb-3">
                                {!! $foods->links() !!}
                            </div>
                            @if (count($foods) === 0)
                                <div class="empty--data">
                                    <img src="{{ dynamicAsset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                                    <h5>
                                        {{ translate('no_data_found') }}
                                    </h5>
                                </div>
                            @endif
                        </div>
                        <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@push('script')
    <script src="{{ dynamicAsset('public/assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ dynamicAsset('public/assets/admin') }}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{ dynamicAsset('public/assets/admin') }}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
    <script src="{{dynamicAsset('public/assets/admin/apexcharts/apexcharts.min.js')}}"></script>
@endpush


@push('script_2')
    <script src="{{dynamicAsset('/public/assets/admin/js/view-pages/apex-charts.js')}}"></script>
    <script>
        "use strict";
        loadchart();
        function loadchart(){
            const id = "#order-report"
            const value = [44, 55]
            const label = ['Camping', 'Regular']
            const legendPosition = "top"

            newdonutChart(id, value, label, legendPosition)
        }

    </script>



    <script>
        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function() {
            $.HSCore.components.HSChartJS.init($(this));
        });

        let updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

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

        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

        let ctx = document.getElementById('chart1').getContext("2d");
        let data = {

            labels:[{!! implode(',',$label) !!}, "Regular Earning"],

            datasets: [
                {
                    label: "{{  translate('Total_Amount_Sold') }}",
                    fill: false,
                    lineTension: 0.1,
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [6510, 5910, 8010, 8110, 5601, 55, 4000],
                    spanGaps: false,
                    backgroundColor: "#7ECAFF",
                    hoverBackgroundColor: "#7ECAFF",
                    borderColor: "#7ECAFF",
                },
                {
                    label: "{{  translate('Regular Earning') }}",
                    fill: false,
                    lineTension: 0.1,
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [9510, 9910, 12100, 11041, 01106, 135, 9900],
                    spanGaps: false,
                    backgroundColor: "#93f0cf",
                    hoverBackgroundColor: "#93f0cf",
                    borderColor: "#93f0cf",
                }
            ]
        };

        let options = {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: false,
                position: "top",
                fontSize: 18,
                fontColor: "#111"
            },
            legend: {
                show: true,
                position: "top",
                horizontalAlign: 'right',
                labels: {
                    fontColor: "#333",
                    fontSize: 12,
                },
            },
            cornerRadius: 5,

            tooltips: {
                enabled: true,
                hasIndicator: true,
                // mode: 'single',
                mode: "index",
                intersect: false,

            },

            scales: {
                yAxes: [{
                    gridLines: {
                        color: "#e7eaf3",
                        drawBorder: false,
                        zeroLineColor: "#e7eaf3"
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: {{ceil((array_sum($data)/10000))*2000}},
                        fontSize: 12,
                        fontColor: "#97a4af",
                        fontFamily: "Open Sans, sans-serif",
                        padding: 10
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        fontSize: 12,
                        fontColor: "#97a4af",
                        fontFamily: "Open Sans, sans-serif",
                        padding: 5
                    },
                    categoryPercentage: 0.3,
                    maxBarThickness: "10"
                }]
            },

            hover: {
                mode: "nearest",
                intersect: true,
            },
        };

        let myLineChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });

    </script>
@endpush
