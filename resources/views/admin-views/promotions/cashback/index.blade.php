@extends('layouts.admin.app')

@section('title',translate('messages.Cashback_Offer'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap align-items-center justify-content-between">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{dynamicAsset('public/assets/admin/img/Create_Cashback_Offer.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.Create_Cashback_Offer')}}
                </span>
            </h1>
            {{-- <div class="text--primary-2 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                <strong class="mr-2">{{translate('See_how_it_works!')}}</strong>
                <div class="blinkings">
                    <i class="tio-info-outined"></i>
                </div>
            </div> --}}
        </div>

        <!-- End Page Header -->
        <div class="row g-2">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" id="form_data">
                        <form id="cashback-submit" action="{{route('admin.cashback.store')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    @if ($language)
                                    <ul class="nav nav-tabs mb-3 border-0">
                                        <li class="nav-item">
                                            <a class="nav-link lang_link active"
                                            href="#"
                                            id="default-link">{{translate('messages.default')}}</a>
                                        </li>
                                        @foreach ($language as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link lang_link"
                                                    href="#"
                                                    id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                </li>
                                                @endforeach
                                            </ul>
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <div class="lang_form" id="default-form">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="default_title">{{ translate('messages.title') }}
                                                ({{ translate('Default') }})
                                            </label>
                                            <input type="text" value="{{ old('title.0') }}" name="title[]" maxlength="254" id="default_title"
                                                class="form-control" placeholder="{{ translate('messages.Eid_Dhamaka') }}" >
                                        </div>
                                        <input type="hidden" name="lang[]" value="default">
                                    </div>
                                        @foreach ($language as $key => $lang)
                                            <div class="d-none lang_form"
                                                id="{{ $lang }}-form">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                        for="{{ $lang }}_title">{{ translate('messages.title') }}
                                                        ({{ strtoupper($lang) }})
                                                    </label>
                                                    <input type="text" name="title[]" maxlength="254"  value="{{ old('title.'.$key+1) }}" id="{{ $lang }}_title"
                                                        class="form-control" placeholder="{{ translate('messages.Eid_Dhamaka') }}"
                                                         >
                                                </div>
                                                <input type="hidden" name="lang[]" value="{{ $lang }}">
                                            </div>
                                        @endforeach
                                    @else
                                        <div id="default-form">
                                            <div class="form-group">
                                                <label class="input-label"
                                                    for="exampleFormControlInput1">{{ translate('messages.title') }} ({{ translate('messages.default') }})</label>
                                                <input type="text" name="title[]" maxlength="254" class="form-control"
                                                    placeholder="{{ translate('messages.Eid_Dhamaka') }}">
                                            </div>
                                            <input type="hidden" name="lang[]" value="default">
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-6" id="customer_wise">
                                    <div class="form-group">
                                        <label class="input-label" for="select_customer">{{translate('messages.select_customer')}}</label>
                                        <select required name="customer_id[]" id="select_customer"
                                            class="form-control js-select2-custom"
                                            multiple="multiple" data-placeholder="{{translate('messages.select_customer')}}">
                                            <option   value="all">{{translate('messages.all')}} </option>
                                        @foreach(\App\Models\User::get(['id','f_name','l_name']) as $user)
                                            <option class="select_customer_option" value="{{$user->id}}" {{ (isset($customer) && is_numeric($customer) && ($customer == $user->id))?'selected':'' }}>{{$user->f_name.' '.$user->l_name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>



                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.Cashback_Type')}} <span class="form-label-secondary text-danger"
                                            data-toggle="tooltip" data-placement="right"
                                            data-original-title="{{ translate('messages.Required.')}}"> *
                                            </span></label>
                                        <select name="cashback_type" class="form-control" id="cashback_type" required>
                                            <option {{ old('cashback_type')  == 'percentage' ? "selected": '' }} value="percentage">{{translate('messages.percentage')}} (%)</option>
                                            <option {{ old('cashback_type')  == 'amount' ? "selected": '' }}  value="amount">{{translate('messages.amount')}} {{ \App\CentralLogics\Helpers::currency_symbol() }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.Cashback_Amount')}}

                                            <span class="{{ old('cashback_type')  == 'percentage' ||  old('cashback_type') == null  ? '': 'd-none' }} " id="percentage">(%)</span>
                                            <span  class=" {{ old('cashback_type')  == 'amount' && old('cashback_type') !== null ? '': 'd-none' }} " id='cuttency_symbol'>({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                            </span>

                                            <span
                                            class="input-label-secondary text--title" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('Set_the_value_of_Cashback_percentage/_amount_which_will_transfer_to_the_customer_wallet_when_the__order_is_completed.') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <span class="form-label-secondary text-danger"
                                        data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('messages.Required.')}}"> *
                                        </span>

                                        </label>
                                        <input type="number" value="{{  old('cashback_amount') }}" step="0.01" min="1" max="100"  placeholder="{{ translate('messages.Ex:_100') }}"  name="cashback_amount" id="Cash_back_amount" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.Minimum_Purchase')}} ({{ \App\CentralLogics\Helpers::currency_symbol() }})</label>
                                        <input type="number" step="0.01" id="min_purchase" value="{{  old('min_purchase') }}" required name="min_purchase" value="0" min="0" max="999999999999.99" class="form-control"
                                             placeholder="{{ translate('messages.Ex:_100') }}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="max_discount">{{translate('messages.Maximum_Discount')}} ({{ \App\CentralLogics\Helpers::currency_symbol() }})</label>
                                        <input type="number"   placeholder="{{ translate('messages.Ex:_100') }}" step="0.01" min="0" value="{{  old('cashback_type')  == 'percentage' ?  old('max_discount') : null }}" max="999999999999.99" name="max_discount" id="max_discount" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.Start_Date')}}</label>
                                        <input type="date" name="start_date" value="{{  old('start_date') }}" class="form-control" id="date_from" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.End_Date')}}</label>
                                        <input type="date" name="end_date"  value="{{  old('end_date') }}" class="form-control" id="date_to" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.Limit_for_Same_User')}}</label>
                                        <input type="number" step="1" required  value="{{  old('same_user_limit') }}" name="same_user_limit" value="0" min="0" max="9999999" class="form-control"
                                             placeholder="{{ translate('messages.Ex:_5') }}">
                                    </div>
                                </div>

                            </div>
                            <div class="btn--container justify-content-end">
                                <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <button type="submit" class="btn btn--primary cashback-submit">{{translate('messages.submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">{{translate('messages.Cashback_List')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$cashbacks->total()}}</span></h5>


                            <div class="select-item min-250">
                                <select name="subscription_list" class="form-control js-select2-custom set-filter"
                                data-url="{{url()->full()}}" data-filter="cashback_type">
                                    <option  value="all">{{translate('messages.All CashBacks')}}</option>
                                    <option {{ request()?->cashback_type =='amount'?'selected':''}} value="amount">{{translate('Amount')}} {{ \App\CentralLogics\Helpers::currency_symbol() }}</option>
                                    <option {{request()?->cashback_type =='percentage'?'selected':''}} value="percentage">{{translate('Percentage')}} %</option>
                                </select>
                            </div>

                            <form  class="search-form min--270">
                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input id="datatableSearch" type="search" name="search" value="{{ request()?->search }}" class="form-control" placeholder="{{ translate('messages.Ex_:_Search_by_title') }}" aria-label="{{translate('messages.search_here')}}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
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
                                <th class="border-0">{{translate('sl')}}</th>
                                <th class="border-0">{{translate('messages.Name')}}</th>
                                <th class="border-0">{{translate('messages.CashBack_Type')}}</th>
                                <th class="border-0">{{translate('messages.Amount')}}</th>
                                <th class="border-0">{{translate('messages.Duration')}}</th>
                                <th class="border-0 text-center">{{translate('messages.Total_Used')}}</th>
                                <th class="border-0">{{translate('messages.status')}}</th>
                                <th class="border-0 text-center">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($cashbacks as $key=>$bonus)
                                <tr>
                                    <td>{{$key+$cashbacks->firstItem()}}</td>
                                    <td>
                                    <span class="d-block font-size-sm text-body" title="{{ $bonus['title'] }}">
                                    {{Str::limit($bonus['title'],25,'...')}}
                                    </span>
                                    </td>


                                    <td>{{ translate($bonus['cashback_type']) }}</td>
                                    <td> {{  $bonus['cashback_type'] == 'amount' ? \App\CentralLogics\Helpers::format_currency($bonus['cashback_amount']) : $bonus['cashback_amount'] .' %' }}</td>
                                    <td> {{\App\CentralLogics\Helpers::date_format($bonus->start_date)}} -  {{\App\CentralLogics\Helpers::date_format($bonus->end_date)  }}</td>

                                    <td class="text-center">{{ $bonus['total_used']  }}</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="bonusCheckbox{{$bonus->id}}">
                                            <input type="checkbox" data-url="{{route('admin.cashback.status',[$bonus['id'],$bonus->status?0:1])}}" class="toggle-switch-input redirect-url" id="bonusCheckbox{{$bonus->id}}" {{$bonus->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">

                                            <a class="btn action-btn btn--primary btn-outline-primary" href="{{route('admin.cashback.update',[$bonus['id']])}}" title="{{translate('messages.edit_cashback')}}"><i class="tio-edit"></i>
                                            </a>
                                            {{-- <a class="btn action-btn btn--primary btn-outline-primary edit_cashback" data-id="{{$bonus['id']}}"  href="javascript:;" title="{{translate('messages.edit_cashback')}}"><i class="tio-edit"></i>
                                            </a> --}}
                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:" data-id="bonus-{{$bonus['id']}}" data-message="{{ translate('Want_to_delete_this_Cashback_?') }}" title="{{translate('messages.delete_bonus')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.cashback.delete',[$bonus['id']])}}"
                                            method="post" id="bonus-{{$bonus['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        @if(count($cashbacks) !== 0)
                        <hr>
                        @endif
                        <div class="page-area">
                            {!! $cashbacks->links() !!}
                        </div>
                        @if(count($cashbacks) === 0)
                        <div class="empty--data">
                            <img src="{{dynamicAsset('public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>
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
                            <div class="mb-20">
                                <div class="text-center">
                                    <img src="{{dynamicAsset('public/assets/admin/img/image_127.png')}}" alt="" class="mb-20">
                                    <h5 class="modal-title">{{translate('Wallet_bonus_is_only_applicable_when_a_customer_add_fund_to_wallet_via_outside_payment_gateway_!')}}</h5>
                                </div>
                                <ul>
                                    <li>
                                        {{ translate('Customer_will_get_extra_amount_to_his_/_her_wallet_additionally_with_the_amount_he_/_she_added_from_other_payment_gateways._The_bonus_amount_will_be_deduct_from_admin_wallet_&_will_consider_as_admin_expense.') }}
                                    </li>
                                </ul>
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
<script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/cashback-index.js"></script>
<script>
    "use strict";
    $(document).on('ready', function () {
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
                '<img class="w-7rem mb-3" src="{{dynamicAsset('public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">' +

                '</div>'
            }
        });
    });

//     $('.edit_cashback').on('click', function (e) {

//     let url = "{{ route('admin.cashback.update', ['id']) }}";
//         url = url.replace('id', $(this).data("id"));
//     e.preventDefault();
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });
//     $.ajax({
//         type: "GET",
//         url: url,
//         cache: false,
//         beforeSend: function () {
//             $('#loading').show();
//         },
//         success: function (data) {
//             $('#form_data').html(data.view);
//         },
//         complete: function () {
//             $('#loading').hide();
//         },
//         error: function(xhr, textStatus, errorThrown) {
//             console.error("Error:", textStatus, errorThrown);
//         }
//     });
// });

</script>
@endpush
