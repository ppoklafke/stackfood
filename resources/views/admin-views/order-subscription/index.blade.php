@extends('layouts.admin.app')

@section('title',translate('messages.order_subscriptions'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center __gap-15px">
                <h1 class="page-header-title">
                    <img src="{{dynamicAsset('public/assets/admin/img/orders.png')}}" class="mr-1" alt=""> {{translate('messages.subscription_order_list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$subscriptions->total()}}</span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                    <div class="search--button-wrapper">
                        <h5 class="card-title">
                        </h5>
                        <form  class="search-form">
                            <!-- Search -->
                            <div class="input-group input--group">
                                <input id="datatableSearch" name="search" type="search" class="form-control h--40px" placeholder="{{translate('Search_by_order_Id')}}"  value="{{ request()->search ?? null }}" aria-label="{{translate('messages.search_here')}}">
                                <button type="submit" class="btn btn--secondary h--40px"><i class="tio-search"></i></button>
                            </div>
                            <!-- End Search -->
                        </form>

                    </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom" id="table-div">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                "order": [],
                                "orderCellsTop": true,

                                "entries": "#datatableEntries",
                                "isResponsive": false,
                                "isShowPaging": false,
                                "paging":false
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('messages.sl')}}</th>
                                <th>{{translate('messages.subscription_ID')}}</th>
                                <th>{{translate('messages.order_type')}}</th>
                                <th>{{translate('messages.duration')}}</th>
                                <th>{{translate('messages.restaurant')}}</th>
                                <th>{{translate('messages.customer')}}</th>
                                <th>{{translate('messages.status')}}</th>
                                <th>{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($subscriptions as $key=>$subscription)
                                <tr>
                                    <td>{{$key+$subscriptions->firstItem()}}</td>
                                    <td>
                                        @if (isset($subscription->order))
                                        <a class="text-body" href="{{route('admin.order.subscription.show',[$subscription->id])}}">{{$subscription->order->id}}</a>
                                        @else
                                        <span> {{ translate('Order_not_found')  }}</span>
                                        @endif
                                    </td>
                                    <td>{{translate('messages.'.$subscription->type)}}</td>
                                    <td>
                                        <div>{{\App\CentralLogics\Helpers::date_format($subscription->start_at)}}</div>
                                        <div>{{ \App\CentralLogics\Helpers::date_format($subscription->end_at) }}</div>
                                    </td>
                                    <td>
                                        @if($subscription->restaurant)
                                            <a class="text-body text-capitalize" href="{{route('admin.restaurant.view',[$subscription['restaurant_id']])}}">{{Str::limit($subscription->restaurant['name'], 20, '...')}}</a>
                                        @else
                                            <label class="badge badge-danger">{{translate('messages.Restaurant_deleted!')}}</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscription->customer)
                                            <a class="text-body font-semibold text-capitalize" href="{{route('admin.customer.view',[$subscription['user_id']])}}">{{$subscription->customer['f_name'].' '.$subscription->customer['l_name']}}</a>
                                        @else
                                            <label class="badge badge-danger">{{translate('messages.invalid_customer_data')}}</label>
                                        @endif
                                        <div>
                                            <a class="text-body text-capitalize" href="Tel:{{$subscription->customer['phone']}}">{{$subscription->customer['phone']}}</a>
                                        </div>
                                    </td>

                                    <td>
                                        <div>

                                            @if ($subscription->status == 'active')
                                            <span class="badge badge-soft-success ">
                                                <span class="legend-indicator bg-success"></span>{{translate('messages.'.$subscription->status)}}
                                            </span>
                                            @elseif ($subscription->status == 'paused')
                                            <span class="badge badge-soft-primary">
                                                <span class="legend-indicator bg-danger"></span>{{translate('messages.'.$subscription->status)}}
                                            </span>
                                            @else
                                            <span class="badge badge-soft-primary ">
                                                <span class="legend-indicator bg-info"></span>{{translate('messages.'.$subscription->status)}}
                                            </span>
                                            @endif
                                        </div>
                                        <div class="fs-12">
                                            <span>{{ translate('messages.Total_Order') }} : {{ $subscription->quantity }} </span>
                                        </div>
                                        <div class="fs-12">
                                            <span>{{ translate('messages.Delivered') }} : {{ $subscription->logs()->whereIn('order_status',['delivered'])->count() }} </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn btn-outline-primary dropdown-toggle after-hidden" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="tio-more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="{{route('admin.order.subscription.show',[$subscription->id])}}">{{translate('messages.subscription_order')}}</a>
                                                @if (isset($subscription->order))
                                                    <a class="dropdown-item" href="{{route('admin.order.details',['id'=>$subscription->order->id])}}">{{translate('Ongoing Order')}}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if(count($subscriptions) !== 0)
                <hr>
                @endif
                <div class="page-area px-4 pb-3">
                    {!! $subscriptions->links() !!}
                </div>
                @if(count($subscriptions) === 0)
                <div class="empty--data">
                    <img src="{{dynamicAsset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
            <!-- End Table -->
        </div>
    </div>
            <!-- How it Works -->
            <div class="modal fade" id="how-it-works">
                <div class="modal-dialog status-warning-modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true" class="tio-clear"></span>
                            </button>
                        </div>
                        <div class="modal-body pb-5 pt-0">
                            <div class="single-item-slider owl-carousel">
                                <div class="item">
                                    <div class="max-349 mx-auto mb-20 text-center">
                                        <img src="{{dynamicAsset('/public/assets/admin/img/landing-how.png')}}" alt="" class="mb-20">
                                        <h5 class="modal-title">{{translate('Receive_Order')}}</h5>
                                        <p>
                                            {{translate("Receive_and_see_the_requisitions_of_subscription-based_orders_from_customers.")}}
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="max-349 mx-auto mb-20 text-center">
                                        <img src="{{dynamicAsset('/public/assets/admin/img/page-loader.gif')}}" alt="" class="mb-20">
                                        <h5 class="modal-title">{{translate('Prepare_Food')}}</h5>
                                        <p>
                                            {{translate("As_per_the_order,_prepare_food_for_customers_on_the_requested_date.")}}
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="max-349 mx-auto mb-20 text-center">
                                        <img src="{{dynamicAsset('/public/assets/admin/img/notice-3.png')}}" alt="" class="mb-20">
                                        <h5 class="modal-title">{{translate('Deliver_Food')}}</h5>
                                        <p>
                                            {{translate('On_the_requested_date,_ensure_home_delivery_or_takeaway_delivery_on_time')}}
                                        </p>
                                        <div class="btn-wrap">
                                            <button type="button" data-dismiss="modal" class="btn btn--primary w-100" >{{ translate('Got_it') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="slide-counter"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection

@push('script_2')
<script>
    "use strict";
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'), {
                select: {
                    style: 'multi',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                    '<img class="mb-3" src="{{dynamicAsset('public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description" style="width: 7rem;">' +
                    '<p class="mb-0">No data to show</p>' +
                    '</div>'
                }
            });

            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });

    </script>
@endpush
