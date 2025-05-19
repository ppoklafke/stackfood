
@extends('layouts.admin.app')

@section('title',translate('messages.Subscription'))

@section('subscription_index')
active
@endsection
@push('css_or_js')


@endpush

@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm">
                    <div class="d-flex align-items-start">
                        <img src="{{dynamicAsset('/public/assets/admin/img/store.png')}}" width="24" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title">{{translate('Subscription Package')}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="js-nav-scroller hs-nav-scroller-horizontal mb-4">
            <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
                <li class="nav-item">
                    <a href="{{ route('admin.subscription.package_details',$id) }}" class="nav-link">{{ translate('Package_Details') }}</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link active">{{ translate('Transactions') }}</a>
                </li>
            </ul>
        </div>
        <div class="card mb-20">
            <div class="card-header border-0">
                <h3 class="text--title card-title">{{ translate('Filter_Option') }}</h3>
            </div>
            <form action="{{ route('admin.subscription.transcation_list',$id) }}" method="get">
                <div class="card-body">
                    <div class="row">
                    <div class="col-lg-4 col-sm-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize">{{translate('Duration')}}</label>
                                <select class="form-control js-select2-custom filter" id="filter" name="filter" >
                                    <option value="all_time" {{ isset($filter) && $filter == 'all_time' ? 'selected' : '' }}>
                                        {{ translate('messages.All Time') }}</option>
                                    <option value="this_year" {{ isset($filter) && $filter == 'this_year' ? 'selected' : '' }}>
                                        {{ translate('messages.This Year') }}</option>

                                    <option value="this_month"
                                        {{ isset($filter) && $filter == 'this_month' ? 'selected' : '' }}>
                                        {{ translate('messages.This Month') }}</option>
                                    <option value="this_week" {{ isset($filter) && $filter == 'this_week' ? 'selected' : '' }}>
                                        {{ translate('messages.This Week') }}</option>
                                    <option value="custom" {{ isset($filter) && $filter == 'custom' ? 'selected' : '' }}>
                                        {{ translate('messages.Custom') }}</option>
                                </select>
                            </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize" for="date_from">{{translate('start_date')}}</label>
                            <input type="date"  value="{{ request()?->start_date }}" {{ isset($filter) && $filter == 'custom' ? " required  name='start_date' " : 'readonly' }}   class="form-control" id="date_from" >
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize" for="date_to">{{translate('end_date')}}</label>
                            <input type="date" value="{{ request()?->end_date }}" {{ isset($filter) && $filter == 'custom' ? " required  name='end_date' " : 'readonly' }}  class="form-control" id="date_to" >
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end">
                    <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('Reset') }}</button>
                    <button type="submit" class="btn btn--primary">{{ translate('Submit') }}</button>
                </div>
            </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header flex-wrap py-2 border-0">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <h4 class="mb-0">{{ translate('Transaction_History') }}</h4>
                    <span class="badge badge-soft-dark rounded-circle">{{ $transactions->total() }}</span>
                </div>
                <div class="search--button-wrapper justify-content-end">
                    <div class="max-sm-flex-1">
                        <select name="plan_type"  data-url="{{ url()->full() }}" data-filter="plan_type" class="custom-select h--40px py-0 status-filter set-filter">
                            <option {{ request()?->plan_type == 'all' ? 'selected' : '' }}  value="all">
                                {{ translate('all') }}
                            </option>
                            <option {{ request()?->plan_type == 'renew' ? 'selected' : '' }}  value="renew">
                                {{ translate('renewal') }}
                            </option>
                            <option {{ request()?->plan_type == 'new_plan' ? 'selected' : '' }}  value="new_plan">
                                {{ translate('Migrate_to_New_Plan') }}
                            </option>
                            <option {{ request()?->plan_type == 'first_purchased' ? 'selected' : '' }}  value="first_purchased">
                                {{ translate('Purchased') }}
                            </option>

                        </select>
                    </div>
                    <form class="search-form">
                        <div class="input-group input--group">
                            <input name="search" type="search" value="{{ request()?->search }}" class="form-control h--40px" placeholder="{{ translate('Ex : Search by ID or restaurant name') }}" aria-label="Search here">
                            <button type="submit" class="btn btn--secondary h--40px"><i class="tio-search"></i></button>
                        </div>
                    </form>
                    <!-- Unfold -->
                    <div class="hs-unfold">
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
                                href="{{ route('admin.subscription.transcation_list_export', ['id'=> $id , 'export_type' => 'excel', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ dynamicAsset('public/assets/admin/svg/components/excel.svg') }}"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item"
                                href="{{  route('admin.subscription.transcation_list_export', ['id' => $id, 'export_type' => 'csv', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ dynamicAsset('public/assets/admin/svg/components/placeholder-csv-format.svg') }}"
                                    alt="Image Description">
                                .{{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
                <!-- End Row -->
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless middle-align __txt-14px">
                        <thead class="thead-light white--space-false">
                            <th class="border-top px-4 border-bottom text-center">{{ translate('sl') }}</th>
                            <th class="border-top px-4 border-bottom">{{ translate('Transaction_ID') }}</th>
                            <th class="border-top px-4 border-bottom"><div class="text-title">{{ translate('Transaction_Date') }}</div></th>
                            <th class="border-top px-4 border-bottom">{{ translate('restaurant') }}</th>
                            <th class="border-top px-4 border-bottom">{{ translate('Pricing') }}</th>
                            <th class="border-top px-4 border-bottom">{{ translate('Payment_Type') }}</th>
                            <th class="border-top px-4 border-bottom">{{ translate('Status') }}</th>
                            <th class="border-top px-4 border-bottom text-center">{{ translate('Action') }}</th>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $k=> $transaction)

                            <tr>
                                <td class="px-4 text-center">{{ $k + $transactions->firstItem() }}</td>

                                <td class="px-4">
                                    <div class="text-title">{{ $transaction->id }}</div>
                                </td>
                                <td class="px-4">
                                    <div class="pl-4">{{ \App\CentralLogics\Helpers::date_format($transaction->created_at) }}</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">

                                        <a class="text--title" href="{{route('admin.restaurant.view', $transaction->restaurant_id)}}">{{ $transaction?->restaurant?->name ?? translate('messages.restaurant deleted!') }}</a>

                                        @if ($transaction?->subscription?->status == 1 && $transaction?->subscription?->expiry_date_parsed && $transaction->subscription->expiry_date_parsed->subDays($subscription_deadline_warning_days)->isBefore(now()))
                                        <span title="<div class='text-left'>Expiring Soon <br /> Expiration Date: {{ \App\CentralLogics\Helpers::date_format($transaction->subscription->expiry_date_parsed)  }}</div>" data-toggle="tooltip" data-html="true">
                                            <img src="{{dynamicAsset('/public/assets/admin/img/invalid.svg')}}" alt="">
                                        </span>
                                        @endif


                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="w--120px text-title text-right pr-5">{{ \App\CentralLogics\Helpers::format_currency($transaction->paid_amount) }}</div>
                                </td>
                                <td class="px-4">
                                    <div>
                                        @if ( $transaction->plan_type == 'renew'  )
                                        <div class="text-title">{{ translate('Renewal') }}</div>
                                        @elseif ($transaction->plan_type == 'new_plan'  )
                                        <div class="text-title">{{ translate('Migrate_to_New_Plan') }}</div>
                                        @elseif ($transaction->plan_type == 'first_purchased'  )
                                        <div class="text-title">{{ translate('Purchased') }}</div>
                                        @else
                                        <div class="text-title">{{ translate($transaction->plan_type) }}</div>
                                        @endif

                                        <div class="text-success font-medium">{{ translate('Paid_by') }}  {{ translate($transaction->payment_method) }}</div>
                                    </div>
                                </td>
                                <td class="px-4">
                                    @if ( $transaction->payment_status == 'success')
                                    <span class="text-success">
                                    @elseif($transaction->payment_status ==  'on_hold')
                                    <span class="text--info">
                                    @else
                                        <span class="text--danger">
                                    @endif
                                        {{ translate($transaction->payment_status)  }}
                                    </span>

                                </td>
                                <td class="px-4">
                                    <div class="btn--container justify-content-center">
                                        <button class="btn action-btn btn--warning btn-outline-warning printButton" data-url={{ route('admin.subscription.invoice',$transaction->id) }} >
                                            <i class="tio-print"></i>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                @if(count($transactions) !== 0)
                <hr>
                @endif
                <div class="page-area">
                    {!! $transactions->withQueryString()->links() !!}
                </div>
                @if(count($transactions) === 0)
                <div class="empty--data">
                    <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
        </div>
    </div>




@endsection

@push('script_2')
<script>
    $("#date_from").on("change", function () {
        $('#date_to').attr('min',$(this).val());
    });

    $("#date_to").on("change", function () {
        $('#date_from').attr('max',$(this).val());
    });


        $(document).on('change','.filter', function () {
            if($(this).val() == 'custom'){
                $('#date_from').removeAttr('readonly').attr('name', 'start_date').attr('required', true);
                $('#date_to').removeAttr('readonly').attr('name', 'end_date').attr('required', true);
            }
            else{
                $('#date_from').attr('readonly',true).removeAttr('name', 'start_date').removeAttr('required');
                $('#date_to').attr('readonly',true).removeAttr('name', 'end_date').removeAttr('required');
            }
        });

        $(document).ready(function() {
            $('.printButton').click(function() {
                window.open($(this).data('url'), '_blank');
            });
        });

    $(document).on("click", "#reset_btn", function () {
        setTimeout(reset, 10);
    });

    function reset(){
        $('.filter').trigger('change');
    }
</script>
@endpush

