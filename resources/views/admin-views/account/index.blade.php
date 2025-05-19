@extends('layouts.admin.app')

@section('title',translate('messages.Cash_Collection'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

    <!-- Page Heading -->
    <div class="page-header">
        <h1 class="page-header-title mb-2 text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{dynamicAsset('/public/assets/admin/img/collect-cash.png')}}" class="w-20px" alt="public">
            </div>
            <span>
                {{ translate('Cash_Collection') }}
            </span>
        </h1>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{route('admin.account-transaction.store')}}" method='post' id="add_transaction">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                        <label class="input-label" for="type">{{translate('messages.type')}} <span class="form-label-secondary text-danger"
                            data-toggle="tooltip" data-placement="right"
                            data-original-title="{{ translate('messages.Required.')}}"> *
                            </span>
                        <span class="input-label-secondary"></span></label>
                            <select name="type" id="type" class="form-control h--48px">
                                <option value="deliveryman">{{translate('messages.deliveryman')}}</option>
                                <option value="restaurant">{{translate('messages.restaurant')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="restaurant">{{translate('messages.restaurant')}}<span class="input-label-secondary"></span></label>
                            <select id="restaurant" name="restaurant_id" data-placeholder="{{translate('messages.select_restaurant')}}" data-url="{{url('/')}}/admin/restaurant/get-account-data/" data-type="restaurant" class="form-control h--48px get-account-data" title="Select Restaurant" disabled>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="deliveryman">{{translate('messages.deliveryman')}}<span class="input-label-secondary"></span></label>
                            <select id="deliveryman" name="deliveryman_id" data-placeholder="{{translate('messages.select_deliveryman')}}" data-url="{{url('/')}}/admin/delivery-man/get-account-data/" data-type="deliveryman" class="form-control h--48px get-account-data" title="Select deliveryman">

                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="method">{{translate('messages.method')}}<span class="form-label-secondary text-danger"
                                data-toggle="tooltip" data-placement="right"
                                data-original-title="{{ translate('messages.Required.')}}"> *
                                </span>
                            </label>
                            <input class="form-control h--48px" type="text" name="method" id="method" required maxlength="191" placeholder="{{ translate('messages.Ex_:_Cash') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="ref">{{translate('messages.reference')}}<span class="input-label-secondary"></span></label>
                            <input  class="form-control h--48px" type="text" name="ref" id="ref" maxlength="191" placeholder="{{ translate('messages.Ex_:_Collect_Cash') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="amount">{{translate('messages.amount')}} <span class="form-label-secondary text-danger"
                                data-toggle="tooltip" data-placement="right"
                                data-original-title="{{ translate('messages.Required.')}}"> *
                                </span>
                            <span class="input-label-secondary" id="account_info"></span></label>
                            <input class="form-control h--48px" type="number" min=".01" step="0.01" name="amount" id="amount" max="999999999999.99" placeholder="{{ translate('messages.Ex_:_100') }}">
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end">
                    <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                    <button type="submit" class="btn btn--primary">{{translate('messages.collect_cash')}}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header py-2 border-0">
            <div class="search--button-wrapper">
                <h3 class="card-title">
                    <span>{{ translate('messages.transaction_table')}}</span>
                    <span class="badge badge-soft-secondary" id="itemCount" >{{$account_transaction->total()}}</span>
                </h3>
                <!-- Static Search Form -->
                <form class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                        <div class="input--group input-group input-group-merge input-group-flush">
                        <input id="datatableSearch_" type="search" name="search" class="form-control" value="{{ request()?->search ?? null}}"  placeholder="{{ translate('messages.Search_by_Reference') }}" aria-label="Search" required>
                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                    </div>
                    <!-- End Search -->
                </form>
                <!-- Static Search Form -->
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
                        <a id="export-excel" class="dropdown-item" href="{{route('admin.export-account-transaction', ['type'=>'excel',request()->getQueryString()])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{dynamicAsset('public/assets/admin')}}/svg/components/excel.svg"
                                    alt="Image Description">
                            {{translate('messages.excel')}}
                        </a>
                        <a id="export-csv" class="dropdown-item" href="{{route('admin.export-account-transaction', ['type'=>'csv',request()->getQueryString()])}}">
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
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="datatable"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('messages.sl') }}</th>
                            <th>{{ translate('messages.Collected_From') }}</th>
                            <th>{{ translate('messages.User_Type') }}</th>
                            <th>{{translate('messages.Collected_At')}}</th>
                            <th>{{translate('messages.Collected_Amount')}}</th>
                            <th>{{translate('messages.Paymen_Method')}}</th>
                            <th>{{translate('messages.Reference')}}</th>
                            <th class="text-center w-120px">{{translate('messages.action')}}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        @foreach($account_transaction as $k=>$at)
                        @php($data= null)
                        <tr>

                            <td scope="row">{{$k+$account_transaction->firstItem()}}</td>
                            <td>
                                @if($at->restaurant)
                                @php($data=$at->restaurant)
                                <a href="{{route('admin.restaurant.view',[$data->id])}}">{{ Str::limit($data->name, 20, '...') }}</a>
                                @elseif($at->deliveryman)
                                @php($data=$at->deliveryman)
                                <a href="{{route('admin.delivery-man.preview',[$data->id])}}">{{ $data->f_name }} {{ $data->l_name }}</a>
                                @else
                                @php($data=null)
                                    {{translate('messages.not_found')}}
                                @endif
                            </td>
                            <td><label class="">{{ translate($at['from_type'])}}</label></td>
                            <td>
                                {{ \App\CentralLogics\Helpers::time_date_format($at->created_at)  }}
                            </td>
                            <td>{{\App\CentralLogics\Helpers::format_currency($at['amount'])}}</td>
                            <td>{{ translate($at['method']) }}</td>
                            <td class="text-capitalize">{{  $at['ref'] ? translate($at['ref']) : translate('messages.N/A') }} </td>
                            <td>
                                <div class="btn--container justify-content-center"> <a href="#"
                                    data-payment_method="{{ $at->method }}"
                                    data-ref="{{translate($at['ref'])}}"
                                    data-amount="{{\App\CentralLogics\Helpers::format_currency($at['amount'])}}"
                                    data-date="{{\App\CentralLogics\Helpers::time_date_format($at->created_at)}}"
                                    data-type="{{ $at->from_type == 'deliveryman' ?  translate('DeliveryMan_Info') : translate('Restaurant_Info') }}"
                                    data-phone="{{ $data?->phone }}"

                                    data-address="{{ $at->from_type == 'restaurant' ?  $data?->address : $data?->last_location?->location ?? translate('address_not_found') }}"
                                    data-latitude="{{$at->from_type == 'restaurant' ?   $data?->latitude : $data?->last_location?->latitude ?? 0 }}"
                                    data-longitude="{{$at->from_type == 'restaurant' ?   $data?->longitude : $data?->last_location?->longitude ?? 0 }}"
                                    data-name="{{$at->from_type == 'restaurant' ?   $data?->name : $data?->f_name.' '.$data?->l_name }}"

                                    class="btn action-btn btn--warning btn-outline-warning withdraw-info-show" ><i class="tio-visible"></i>
                                    </a>
                                </div>


                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($account_transaction) === 0)
                <div class="empty--data">
                    <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('messages.no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
        </div>
        <div class="card-footer border-0 pt-0">
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">

                    <div>
                        {{$account_transaction->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Account Transaction Information --}}
<div class="sidebar-wrap">
    <div class="withdraw-info-sidebar-overlay"></div>
    <div class="withdraw-info-sidebar">
        <div class="d-flex pb-3">
            <span class="circle bg-light withdraw-info-hide cursor-pointer">
                <i class="tio-clear"></i>
            </span>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 " id="type"></h6>
            </div>
            <div class="card-body">
                <div class="key-val-list d-flex flex-column gap-2" style="--min-width: 60px">
                    <div class="key-val-list-item d-flex gap-3">
                        <span>{{translate('name')}}:</span>
                        <span id="name"></span>
                    </div>
                    <div class="key-val-list-item d-flex gap-3">
                        <span>{{translate('phone')}}:</span>
                        <a href="tel:" id="phone" class="text-dark"></a>
                    </div>
                    <div class="key-val-list-item d-flex gap-3">
                        <span>{{translate('address')}}:</span>
                        <a id="address" target="_blank"></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-2">
            <div class="card-header">
                <h6 class="mb-0 " id="">{{ translate('Transaction_Info') }}</h6>
            </div>
            <div class="card-body">

                <div class="key-val-list d-flex flex-column gap-2" style="--min-width: 60px">

                    <div class="d-flex gap-2 align-items-center ">
                        <span>{{translate('method')}}:</span>
                        <span id="payment_method" class="text-dark font-semibold text-capitalize"></span>
                    </div>
                    <div class="d-flex gap-2 align-items-center ">
                        <span>{{translate('amount')}}:</span>
                        <span class="text-primary font-bold" id="amount"> </span>
                    </div>
                    <div class="d-flex gap-2 align-items-center ">
                        <span>{{translate('request_time')}}:</span>
                        <span id="date"></span>
                    </div>
                    <div class="d-flex gap-2 align-items-center  ">
                        <span>{{translate('reference')}}:</span>
                        <span id="ref" class="text-capitalize fs-12"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('script_2')
<script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/account-index.js"></script>
<script>
    "use strict";
    $('.withdraw-info-hide, .withdraw-info-sidebar-overlay').on('click', function () {
        $('.withdraw-info-sidebar, .withdraw-info-sidebar-overlay').removeClass('show');
    });
    $('.withdraw-info-show').on('click', function () {
        let data = $(this).data();
        // console.log(data)
            $('.sidebar-wrap #payment_method').text(data.payment_method);
            $('.sidebar-wrap #amount').text(data.amount);
            $('.sidebar-wrap #type').text(data.type);
            $('.sidebar-wrap #date').text(data.date);
            $('.sidebar-wrap #ref').text(data.ref);
            $('.sidebar-wrap #name') .text(data.name);
            $('.sidebar-wrap #phone').text(data.phone).attr('href', 'tel:' + data.phone);
            $('.sidebar-wrap #address').text(data.address).attr('href', "https://www.google.com/maps/search/?api=1&query=" + data.latitude + "," + data.longitude);
        $('.withdraw-info-sidebar, .withdraw-info-sidebar-overlay').addClass('show');
    })

    $('#restaurant').select2({
        ajax: {
            url: '{{url('/')}}/admin/restaurant/get-restaurants',
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

    $('.get-account-data').on('change', function() {
        let route = $(this).data('url');
        let type = $(this).data('type');
        let data_id = $(this).val();
        getAccountData(route, data_id, type);
    })

    function getAccountData(route, data_id, type)
    {
        $.get({
                url: route+data_id,
                dataType: 'json',
                success: function (data) {
                    $('#account_info').html('({{translate('messages.cash_in_hand')}}: '+data.cash_in_hand+' {{translate('messages.earning_balance')}}: '+data.earning_balance+')');
                },
            });
    }

    $('#add_transaction').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: '{{route('admin.account-transaction.store')}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.errors) {
                    for (let i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    toastr.success('{{translate('messages.transaction_saved')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    setTimeout(function () {
                        location.href = '{{route('admin.account-transaction.index')}}';
                    }, 2000);
                }
            }
        });
    });
</script>
@endpush
