@extends('layouts.admin.app')

@section('title',$restaurant->name)

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
    <div class="card">
        <div class="card-header border-0 py-2">
            <div class="search--button-wrapper">
                <h5 class="card-title">
                    {{ translate('Total_Disbursements') }} <span class="badge badge-soft-secondary ml-2" id="countItems">{{ $disbursements->total() }}</span>
                </h5>
                <form class="search-form">
                    <!-- Search -->
                    <div class="input--group input-group input-group-merge input-group-flush">
                        <input class="form-control" value="{{ request()?->search  ?? null }}" placeholder="{{ translate('search_by_restaurant_info') }}" name="search">
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
                        <a id="export-excel" class="dropdown-item" href="{{route('admin.restaurant.disbursement-export', ['id'=>$restaurant->id,'type'=>'excel'])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{dynamicAsset('public/assets/admin')}}/svg/components/excel.svg" alt="Image Description">
                            {{translate('messages.excel')}}
                        </a>
                        <a id="export-csv" class="dropdown-item" href="{{route('admin.restaurant.disbursement-export', ['id'=>$restaurant->id,'type'=>'csv'])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{dynamicAsset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg" alt="Image Description">
                            {{translate('messages.csv')}}
                        </a>
                    </div>
                </div>
                <!-- Static Export Button -->

            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-thead-bordered table-align-middle card-table">
                    <thead>
                    <tr>
                        <th>{{ translate('sl') }}</th>
                        <th>{{ translate('id') }}</th>
                        <th>{{ translate('Disburse_Amount') }}</th>
                        <th>{{ translate('Payment_method') }}</th>
                        <th>{{ translate('status') }}</th>
                        <th>
                            <div class="text-center">
                                {{ translate('action') }}
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($disbursements as $key => $restaurant)
                        <tr>
                            <td>
                                <span class="font-weight-bold">{{$key+$disbursements->firstItem()  }}</span>
                            </td>
                            <td>
                                #{{ $restaurant->disbursement_id }}
                            </td>
                            <td>
                                {{\App\CentralLogics\Helpers::format_currency($restaurant['disbursement_amount'])}}
                            </td>
                            <td>
                                <div>
                                    {{$restaurant?->withdraw_method?->method_name ?? translate('Default_method')}}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-soft-primary">{{$restaurant->status}}</span>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn" data-toggle="modal" data-target="#payment-info-{{$restaurant->id}}" title="View Details">
                                        <i class="tio-visible"></i>
                                    </a>
                                </div>
                            </td>
                            <div class="modal fade" id="payment-info-{{$restaurant->id}}">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header pb-4">
                                            <button type="button" class="payment-modal-close btn-close border-0 outline-0 bg-transparent" data-dismiss="modal">
                                                <i class="tio-clear"></i>
                                            </button>
                                            <div class="w-100 text-center">
                                                <h2 class="mb-2">{{ translate('Payment_Information') }}</h2>
                                                <div>
                                                    <span class="mr-2">{{ translate('Disbursement_ID') }}</span>
                                                    <strong>#{{$restaurant->disbursement_id}}</strong>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="mr-2">{{ translate('status') }}</span>
                                                    <span class="badge badge-soft-primary">{{$restaurant->status}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card shadow--card-2">
                                                <div class="card-body">
                                                    <div class="d-flex flex-wrap payment-info-modal-info p-xl-4">
                                                        <div class="item">
                                                            <h5>{{ translate('Restaurant_Information') }}</h5>
                                                            <ul class="item-list">
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('name') }}</span>
                                                                    <span>:</span>
                                                                    <strong>{{$restaurant?->restaurant?->name}}</strong>
                                                                </li>
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('contact') }}</span>
                                                                    <span>:</span>
                                                                    <strong>{{$restaurant?->restaurant?->phone}}</strong>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="item">
                                                            <h5>{{ translate('Owner_Information') }}</h5>
                                                            <ul class="item-list">
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('name') }}</span>
                                                                    <span>:</span>
                                                                    <strong>{{$restaurant->restaurant->vendor->f_name}} {{$restaurant->restaurant->vendor->l_name}}</strong>
                                                                </li>
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('email') }}</span>
                                                                    <span>:</span>
                                                                    <strong>{{$restaurant->restaurant->vendor->email}}</strong>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="item w-100">
                                                            <h5>{{ translate('Account_Information') }}</h5>
                                                            <ul class="item-list">
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('payment_method') }}</span><strong>{{$restaurant?->withdraw_method?->method_name ?? translate('Default_method')}}</strong>
                                                                </li>
                                                                <li class="d-flex flex-wrap">
                                                                    <span class="name">{{ translate('amount') }}</span>
                                                                    <strong>{{\App\CentralLogics\Helpers::format_currency($restaurant['disbursement_amount'])}}</strong>
                                                                </li>
                                                                @forelse(json_decode($restaurant?->withdraw_method?->method_fields, true) ?? [] as $key=> $item)
                                                                    <li class="d-flex flex-wrap">
                                                                        <span class="name">{{  translate($key) }}</span>
                                                                        <strong>{{$item}}</strong>
                                                                    </li>
                                                                @empty

                                                                @endforelse

                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($disbursements) === 0)
                    <div class="empty--data">
                        <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                @endif
            </div>
        </div>
        <div class="page-area px-4 pb-3">
            <div class="d-flex align-items-center justify-content-end">
                <div>
                    {!!$disbursements->links()!!}
                </div>
            </div>
        </div>
</div>
@endsection

@push('script_2')

@endpush
