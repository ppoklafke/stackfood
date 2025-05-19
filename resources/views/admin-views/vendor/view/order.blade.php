@extends('layouts.admin.app')

@section('title',$restaurant->name."'s".translate('messages.order'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{dynamicAsset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">

@endpush

@section('content')

<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h1 class="page-header-title text-break">
                <i class="tio-museum"></i> <span>{{$restaurant->name}}</span>
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
    <div class="resturant-card-navbar">

            <div class="order-info-item redirect-url" data-url="{{route('admin.order.list',['all'])}}?vendor[]={{$restaurant->id}}">
                <div class="order-info-icon">
                    <img src="{{dynamicAsset('/public/assets/admin/img/resturant/navbar/all.png')}}" alt="public">
                </div>
                    <h6 class="card-subtitle">{{translate('messages.all_orders')}} <span class="amount text--primary">{{\App\Models\Order::where('restaurant_id', $restaurant->id)->Notpos()->count()}}</span></h6>
            </div>
            <span class="order-info-seperator"></span>
            <div class="order-info-item redirect-url" data-url="{{route('admin.order.list',['scheduled'])}}?vendor[]={{$restaurant->id}}">
                <div class="order-info-icon">
                    <img src="{{dynamicAsset('/public/assets/admin/img/resturant/navbar/schedule.png')}}" alt="public">
                </div>
                <h6 class="card-subtitle">{{translate('messages.scheduled_orders')}}
                <span class="amount text--warning">{{\App\Models\Order::Scheduled()->Notpos()->where('restaurant_id', $restaurant->id)->count()}}</span></h6>
            </div>
            <span class="order-info-seperator"></span>
            <div class="order-info-item redirect-url" data-url="{{route('admin.order.list',['pending'])}}?vendor[]={{$restaurant->id}}">
                <div class="order-info-icon">
                    <img src="{{dynamicAsset('/public/assets/admin/img/resturant/navbar/pending.png')}}" alt="public">
                </div>
                <h6 class="card-subtitle">{{translate('messages.pending_orders')}}
                <span class="amount text--info">
                {{\App\Models\Order::where(['order_status'=>'pending','restaurant_id'=>$restaurant->id])->Notpos()->count()}}</span></h6>
            </div>
            <span class="order-info-seperator"></span>
            <div class="order-info-item redirect-url" data-url="{{route('admin.order.list',['delivered'])}}?vendor[]={{$restaurant->id}}">
                <div class="order-info-icon">
                    <img src="{{dynamicAsset('/public/assets/admin/img/resturant/navbar/delivered.png')}}" alt="public">
                </div>
                <h6 class="card-subtitle">{{translate('messages.delivered_orders')}}
                <span class="amount text--success">{{\App\Models\Order::where(['order_status'=>'delivered', 'restaurant_id'=>$restaurant->id])->Notpos()->count()}}</span></h6>
            </div>
            <span class="order-info-seperator"></span>
            <div class="order-info-item redirect-url" data-url="{{route('admin.order.list',['canceled'])}}?vendor[]={{$restaurant->id}}">
                <div class="order-info-icon">
                    <img src="{{dynamicAsset('/public/assets/admin/img/resturant/navbar/cancel.png')}}" alt="public">
                </div>
                <h6 class="card-subtitle">{{translate('messages.canceled_orders')}}
                <span class="amount text--danger">{{\App\Models\Order::where(['order_status'=>'canceled', 'restaurant_id'=>$restaurant->id])->count()}}</span></h6>
            </div>

    </div>
    <!-- End Page Header -->
    <!-- Page Heading -->
    <div class="card">
        <!-- Card Header -->
        <div class="card-header py-2 border-0">
            <div class="search--button-wrapper">
                <span class="mr-auto">&nbsp;</span>
                <form  class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                    <!-- Search -->

                    <input type="hidden" name="restaurant_id" value="{{$restaurant->id}}">
                    <div class="input--group input-group input-group-merge input-group-flush">
                        <input id="datatableSearch_" type="search" name="search" class="form-control" value="{{ request()?->search ?? null }}"
                                placeholder="{{translate('messages.Search_by_ID')}}" aria-label="{{translate('messages.search')}}" required>
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

                        <a target="__blank" id="export-excel" class="dropdown-item" href="{{route('admin.order.export-orders', ['type'=>'excel', 'restaurant_id'=>$restaurant->id , request()->getQueryString()]) }}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                            src="{{dynamicAsset('public/assets/admin')}}/svg/components/excel.svg"
                            alt="Image Description">
                            {{translate('messages.excel')}}
                        </a>
                        <a target="__blank" id="export-csv" class="dropdown-item" href="{{route('admin.order.export-orders', ['type'=>'csv', 'restaurant_id'=>$restaurant->id , request()->getQueryString()]) }}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                            src="{{dynamicAsset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                            alt="Image Description">
                            {{translate('messages.csv')}}
                        </a>


                    </div>
                </div>
                <!-- Static Export Button -->
            </div>
        </div>
        <!-- Card Header -->
        <div class="card-body p-0">
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
                    "pagination": "datatablePagination"
                }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="text-center pl-4 w-100px">
                            {{translate('sl')}}
                        </th>
                        <th class="table-column-pl-0">{{translate('messages.order_id')}}</th>
                        <th>
                            <div class="pl-2">
                                {{translate('messages.order_date')}}
                            </div>
                        </th>
                        <th>{{translate('messages.customer_info')}}</th>
                        <th>{{translate('messages.total_amount')}}</th>
                        <th>{{translate('messages.order_status')}}</th>
                        <th class="w-100px">{{translate('messages.action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($orders as $key=>$order)

                        <tr class="status-{{$order['order_status']}} class-all">
                            <td class="text-center pl-4">
                                {{$key+ $orders->firstItem()}}
                            </td>
                            <td class="table-column-pl-0">
                                <a class="text--title" href="{{route('admin.order.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                            </td>
                            <td>
                                <div class="d-inline-block text-right text-uppercase">
                                    <span class="d-block">{{date('d-m-Y',strtotime($order['created_at']))}}</span>
                                    <span class="d-block">{{date(config('timeformat'),strtotime($order['created_at']))}}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-center d-inline-block customer-info-table-data">
                                    @if($order->is_guest)
                                         <?php
                                        $customer_details = json_decode($order['delivery_address'],true);
                                    ?>
                                        <strong>{{$customer_details['contact_person_name']}}</strong>
                                        <div>{{$customer_details['contact_person_number']}}</div>
                                    @elseif($order->customer)
                                        <a class="text-capitalize" href="{{route('admin.customer.view',[$order['user_id']])}}">
                                            <span class="d-block">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</span>
                                            <small class="d-block">{{$order->customer['phone']}}</small>
                                        </a>
                                    @else
                                        <label class="badge badge-danger">{{translate('messages.invalid_customer_data')}}</label>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-inline-block text-right total-amount-table-data">
                                    <div class="paid--amount-status">
                                        {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
                                    </div>
                                    @if($order->payment_status=='paid')
                                        <strong class="text--success order--status">
                                            {{translate('messages.paid')}}
                                        </strong>
                                    @else
                                        <strong class="text--danger order--status">
                                            {{translate('messages.unpaid')}}
                                        </strong>
                                    @endif
                                </div>
                            </td>
                            <td class="text-capitalize">
                                @if($order['order_status']=='pending')
                                    <span class="badge badge-soft-info badge--pending">
                                        {{translate('messages.pending')}}
                                    </span>
                                @elseif($order['order_status']=='confirmed')
                                    <span class="badge badge-soft-info ">
                                        {{translate('messages.confirmed')}}
                                    </span>
                                @elseif($order['order_status']=='processing')
                                    <span class="badge badge-soft-warning">
                                        {{translate('messages.processing')}}
                                    </span>
                                @elseif($order['order_status']=='out_for_delivery')
                                    <span class="badge badge-soft-warning badge--on-the-way">
                                        {{translate('messages.out_for_delivery')}}
                                    </span>
                                @elseif($order['order_status']=='delivered')
                                    <span class="badge badge-soft-success ">
                                        {{$order?->order_type == 'dine_in' ? translate('messages.Completed') : translate('messages.delivered')}}
                                    </span>
                                @elseif($order['order_status']=='accepted')
                                    <span class="badge badge-soft-success badge--accepted">
                                        {{translate('messages.accepted')}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-danger badge--cancel">
                                        {{translate(str_replace('_',' ',$order['order_status']))}}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                                href="{{route('admin.order.details',['id'=>$order['id']])}}">
                                    <i class="tio-invisible"></i>
                                </a>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->
        </div>
        <!-- Footer -->
        <div class="page-area px-4 pb-3">
            <div class="d-flex align-items-center justify-content-end">
                <div>
                    {!! $orders->links() !!}
                </div>
            </div>
        </div>
        <!-- End Footer -->
        <!-- End Card -->
    </div>
</div>
@endsection

@push('script_2')
    <!-- Page level plugins -->
    <script>
        "use strict";
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

@endpush
