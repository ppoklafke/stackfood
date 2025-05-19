
@extends('layouts.vendor.app')

@section('title',translate('messages.Restaurant_Transactions'))

@section('subscriberList')
active
@endsection
@push('css_or_js')


@endpush

@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-start">
                        <img src="{{dynamicAsset('/public/assets/admin/img/store.png')}}" width="24" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title">{{ $restaurant->name }} {{translate('Subscription')}} &nbsp; &nbsp;
                                @if($restaurant?->restaurant_sub_update_application?->status == 0)
                                <span class=" badge badge-pill badge-danger">  &nbsp; {{ translate('Expired') }}  &nbsp; </span>
                                @elseif ($restaurant?->restaurant_sub_update_application?->is_canceled == 1)
                                <span class=" badge badge-pill badge-warning">  &nbsp; {{ translate('canceled') }}  &nbsp; </span>
                                @elseif($restaurant?->restaurant_sub_update_application?->status == 1)
                                <span class=" badge badge-pill badge-success">  &nbsp; {{ translate('Active') }}  &nbsp; </span>
                                @endif
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="js-nav-scroller hs-nav-scroller-horizontal mb-4">
            <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
                <li class="nav-item">
                    <a href="{{ route('vendor.subscriptionackage.subscriberDetail',$restaurant->id) }}" class="nav-link ">{{ translate('Subscription_Details') }} </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor.subscriptionackage.subscriberTransactions',$restaurant->id) }}" class="nav-link">{{ translate('Transactions') }}</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link active">{{ translate('Subscription_Refunds') }}</a>
                </li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header flex-wrap py-2 border-0">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <h4 class="mb-0">{{ translate('Transaction_History') }}</h4>
                    <span class="badge badge-soft-dark rounded-circle">{{ $transactions->total() }}</span>
                </div>
                {{-- <div class="search--button-wrapper justify-content-end">
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
                            <input name="search" type="search" value="{{ request()?->search }}" class="form-control h--40px" placeholder="{{ translate('Ex : Search by Transaction ID or restaurant name') }}" aria-label="Search here">
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
                                href="{{ route('vendor.subscriptionackage.subscriberTransactionExport', ['export_type' => 'excel', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ dynamicAsset('public/assets/admin/svg/components/excel.svg') }}"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item"
                                href="{{ route('vendor.subscriptionackage.subscriberTransactionExport', ['export_type' => 'excel', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ dynamicAsset('public/assets/admin/svg/components/placeholder-csv-format.svg') }}"
                                    alt="Image Description">
                                .{{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <!-- End Unfold -->
                </div> --}}
                <!-- End Row -->
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless middle-align __txt-14px">
                        <thead class="thead-light white--space-false">
                            <th class="border-top px-4 border-bottom text-center">{{ translate('sl') }}</th>
                            <th class="border-top px-4 border-bottom"><div class="text-title">{{ translate('Transaction_Date') }}</div></th>
                            <th class="border-top px-4 border-bottom">{{ translate('Package_Name') }}</th>
                            <th class="border-top px-4 border-bottom">{{ translate('Refund_Amount') }}</th>
                            <th class="border-top px-4 border-bottom">{{ translate('Refunded_for') }}</th>
                            <th class="border-top px-4 border-bottom">{{ translate('Status') }}</th>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $k=> $transaction)

                            <tr>
                                <td class="px-4 text-center">{{ $k + $transactions->firstItem() }}</td>
                                <td class="px-4">
                                    <div class="pl-4">{{ \App\CentralLogics\Helpers::date_format($transaction->created_at) }}</div>
                                </td>

                                <td class="px-4">
                                    <div class="text-title">{{ $transaction?->package?->package_name }}</div>
                                </td>

                                <td class="px-4">
                                    <div class="w--120px text-title  pr-5">{{ \App\CentralLogics\Helpers::format_currency($transaction->amount) }}</div>
                                </td>
                                <td class="px-4">
                                    <div class="w--120px text-title  pr-5">{{ str_replace(['validity_left_'], '', $transaction->reference)  }} {{ translate('messages.Days') }}</div>
                                </td>


                                <td class="px-4">
                                    <span class="text-success">
                                        {{ translate('success')  }}
                                    </span>

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
                    <img src="{{dynamicAsset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
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

</script>
@endpush

