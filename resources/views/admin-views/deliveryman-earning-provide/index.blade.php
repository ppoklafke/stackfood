@extends('layouts.admin.app')

@section('title',translate('messages.Provide_Delivery_Man_Earning'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <span class="card-header-icon">
                    <i class="tio-money"></i>
                </span>
                <span>
                    {{translate('Provide_Delivery_Man_Earning')}}
                </span>
            </h4>
        </div>
        <div class="card-body">
            <form action="{{route('admin.provide-deliveryman-earnings.store')}}" method='post' id="add_transaction">
                @csrf
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            <label class="input-label" for="deliveryman">{{translate('messages.deliveryman')}}<span class="input-label-secondary"></span></label>
                            <select id="deliveryman" name="deliveryman_id" data-placeholder="{{translate('messages.select_deliveryman')}}" data-url="{{url('/')}}/admin/delivery-man/get-account-data/" data-type="deliveryman" class="form-control account-data" title="Select deliveryman">

                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            <label class="input-label" for="amount">{{translate('messages.amount')}}<span class="input-label-secondary" id="account_info"></span></label>
                            <input class="form-control h--45px" type="number" min="1" step="0.01" name="amount" id="amount" max="999999999999.99" placeholder="{{ translate('Ex:_100') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            <label class="input-label" for="method">{{translate('messages.method')}}<span class="input-label-secondary"></span></label>
                            <input class="form-control h--45px" type="text" name="method" id="method" required maxlength="191" placeholder="{{ translate('Ex:_Cash') }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            <label class="input-label" for="ref">{{translate('messages.reference')}}<span class="input-label-secondary"></span></label>
                            <input  class="form-control h--45px" type="text" name="ref" id="ref" maxlength="191" placeholder="{{ translate('Ex:_Collect_Cash') }}">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <div class="btn--container justify-content-end">
                        <button class="btn btn--reset" type="reset">{{translate('messages.reset')}}</button>
                        <button class="btn btn--primary" type="submit">{{translate('messages.save')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0 py-2">
                    <div class="search--button-wrapper">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-file-text-outlined"></i>
                            </span>
                            <span>{{ translate('messages.Distribute_DM_Earning_table')}}</span>
                        </h5>
                        <!-- Static Search Form -->
                        <form>
                            <div class="input--group input-group">
                                <input name="search" type="search"  value="{{ request()?->search ?? null}}" class="form-control" placeholder="{{ translate('Ex:_Search_here_by_Name') }}">
                                <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                            </div>
                        </form>

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
                                <a id="export-excel" class="dropdown-item" href="{{route('admin.export-deliveryman-earning',  ['type'=>'excel',request()->getQueryString()])}}">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{dynamicAsset('public/assets/admin')}}/svg/components/excel.svg"
                                            alt="Image Description">
                                    {{translate('messages.excel')}}
                                </a>
                                <a id="export-csv" class="dropdown-item" href="{{route('admin.export-deliveryman-earning', ['type'=>'csv',request()->getQueryString()])}}">
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
                                    <th>{{translate('messages.name')}}</th>
                                    <th>{{translate('messages.received_at')}}</th>
                                    <th>{{translate('messages.amount')}}</th>
                                    <th>{{translate('messages.method')}}</th>
                                    <th>{{translate('messages.reference')}}</th>
                                </tr>
                            </thead>
                            <tbody id="set-rows">
                            @foreach($provide_dm_earning as $k=>$at)
                                <tr>
                                    <td scope="row">{{$k+$provide_dm_earning->firstItem()}}</td>
                                    <td>@if($at->delivery_man)<a href="{{route('admin.delivery-man.preview', $at->delivery_man_id)}}">{{$at->delivery_man->f_name.' '.$at->delivery_man->l_name}}</a> @else <label class="text-capitalize text-danger">{{translate('messages.deliveryman_deleted')}}</label> @endif </td>
                                    <td>
                                        {{  \App\CentralLogics\Helpers::time_date_format($at->created_at) }}
                                    </td>
                                    <td>{{\App\CentralLogics\Helpers::format_currency($at['amount'])}}</td>
                                    <td>{{$at['method']}}</td>
                                    @if(  $at['ref'] == 'delivery_man_wallet_adjustment_full')
                                    <td>{{ translate('wallet_adjusted') }}</td>
                                @elseif( $at['ref'] == 'delivery_man_wallet_adjustment_partial')
                                    <td>{{ translate('wallet_adjusted_partially') }}</td>
                                @else
                                <td>{{translate($at['ref'])?? translate('N/A') }}</td>

                                @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($provide_dm_earning) === 0)
                        <div class="empty--data">
                            <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    {{$provide_dm_earning->links()}}
                </div>
            </div>
        </div>
     </div>
</div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/deliveryman-earning-provide.js"></script>
    <script>
        "use strict";
    $('#deliveryman').select2({
        ajax: {
            url: '{{url('/')}}/admin/delivery-man/get-deliverymen',
            data: function (params) {
                return {
                    q: params.term, // search term
                    earning: true,
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

    function getAccountData(route, data_id, type)
    {
        $.get({
                url: route+data_id,
                dataType: 'json',
                success: function (data) {
                    $('#account_info').html('({{translate('messages.payable_amount')}}: '+data.payable_amount+')');
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
            url: '{{route('admin.provide-deliveryman-earnings.store')}}',
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
                        location.href = '{{route('admin.provide-deliveryman-earnings.index')}}';
                    }, 2000);
                }
            }
        });
    });
</script>
@endpush
