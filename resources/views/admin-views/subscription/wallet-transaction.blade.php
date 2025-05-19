
@extends('layouts.admin.app')

@section('title',translate('messages.Store_Transactions'))

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
                                @if($restaurant?->status == 0 &&  $restaurant?->vendor?->status == 0)
                                <span class=" badge badge-pill badge-info">  &nbsp; {{ translate('Approval_Pending') }}  &nbsp; </span>
                                @elseif($restaurant?->restaurant_sub_update_application?->status == 0)
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
                    <a href="{{ route('admin.subscription.subscriberDetail',$restaurant->id) }}" class="nav-link ">{{ translate('Subscription_Details') }} </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subscription.subscriberTransactions',$restaurant->id) }}" class="nav-link">{{ translate('Transactions') }}</a>
                </li>
                <li class="nav-item ">
                    <a href="3" class="nav-link active">{{ translate('Subscription_Refunds') }}</a>
                </li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header flex-wrap py-2 border-0">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <h4 class="mb-0">{{ translate('Refund_History') }}</h4>
                    <span class="badge badge-soft-dark rounded-circle">{{ $transactions->total() }}</span>
                </div>

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
    $(document).on("click", "#reset_btn", function () {
        setTimeout(reset, 10);
    });

    function reset(){
        $('.filter').trigger('change');
    }
</script>
@endpush

