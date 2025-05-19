@extends('layouts.admin.app')

@section('title',translate('Delivery_Man_Preview'))

@push('css_or_js')
<style>

    .padding{
    padding: 5px !important;
    }
    .font{
        font-weight: bold;
    }
</style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="card mb-3">
            <div class="card-header border-0 pb-0">
                <h1 class="page-header-title">
                    <span class="page-header-icon">
                        <i class="tio-account-square-outlined"></i>
                    </span>
                    <span>{{translate('messages.deliveryman_details')}}</span>
                </h1>
            </div>
        <!-- End Page Header -->
            <div class="card-body pt-2">
                <div>
                    <div class="mb-4">
                        @if($dm->application_status == 'approved')
                        <div class="js-nav-scroller hs-nav-scroller-horizontal">
                            <!-- Nav -->
                            <ul class="nav nav-tabs page-header-tabs mt-0">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'info'])}}"  aria-disabled="true">{{translate('messages.info')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{translate('messages.transaction')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'timelog'])}}"  aria-disabled="true">{{translate('messages.timelog')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'conversation'])}}"  aria-disabled="true">{{translate('messages.conversations')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'disbursement'])}}"  aria-disabled="true">{{translate('messages.disbursements')}}</a>
                                </li>
                            </ul>
                            <!-- End Nav -->
                        </div>
                        @else
                        <div class="btn--container justify-content-end">
                            <a class="btn btn--primary text-capitalize my-2 request-alert"
                            data-url="{{route('admin.delivery-man.application',[$dm['id'],'approved'])}}" data-message="{{translate('messages.you_want_to_approve_this_application_?')}}"
                                href="javascript:">{{translate('messages.approve')}}</a>
                            @if($dm->application_status !='denied')
                            <a class="btn btn--danger text-capitalize my-2 request-alert"
                            data-url="{{route('admin.delivery-man.application',[$dm['id'],'denied'])}}" data-message="{{translate('messages.you_want_to_deny_this_application_?')}}"
                                href="javascript:">{{translate('messages.deny')}}</a>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="row g-3 justify-content-center">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-sm-6 col-md-4">
                            <div class="resturant-card bg--2">
                                <h2 class="title">
                                    {{$dm->orders->count()}}
                                </h2>
                                <h5 class="subtitle">
                                    {{translate('messages.total_orders')}}
                                </h5>
                                <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/tick.png')}}" alt="img">
                            </div>
                        </div>

                        <!-- Collected Cash Card Example -->
                        <div class="col-sm-6 col-md-4">
                            <div class="resturant-card bg--3">
                                <h2 class="title">
                                    {{\App\CentralLogics\Helpers::format_currency($dm->wallet?($dm->wallet->collected_cash > 0 ? $dm->wallet->collected_cash: 0):0.0)}}
                                </h2>
                                <h5 class="subtitle">
                                    {{translate('messages.cash_in_hand')}}
                                </h5>
                                <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/transactions/withdraw-amount.png')}}" alt="transactions">
                            </div>
                        </div>

                        <!-- Total Earning Card Example -->
                        <div class="col-sm-6 col-md-4">
                            <div class="resturant-card bg--6">
                                <h2 class="title">
                                    {{\App\CentralLogics\Helpers::format_currency($dm->wallet?($dm->wallet->total_earning > 0 ? $dm->wallet->total_earning : 0):0.00)}}
                                </h2>
                                <h5 class="subtitle">
                                    {{translate('messages.total_earning')}}
                                </h5>
                                <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/transactions/earning.png')}}" alt="transactions">
                            </div>
                        </div>

                        <!-- Total Earning Card Example -->

                        <?php
                        $balance = 0;
                        if($dm->wallet){
                            $balance = $dm->wallet->total_earning - ($dm->wallet->total_withdrawn + $dm->wallet->pending_withdraw + $dm->wallet->collected_cash );
                        }

                        ?>
                            @if($dm->earning)

                            @if ($balance > 0)
                                <div class="col-sm-6 col-md-4">
                                    <div class="resturant-card bg--4">
                                        <h2 class="title">
                                            {{\App\CentralLogics\Helpers::format_currency(abs($balance))}}
                                        </h2>
                                        <h5 class="subtitle">
                                            {{translate('messages.Withdraw_Able_Balance')}}
                                        </h5>
                                        <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/transactions/cash.png')}}" alt="transactions">
                                    </div>
                                </div>
                            @elseif($balance < 0)
                                <div class="col-sm-6 col-md-4">
                                    <div class="resturant-card bg--4">
                                        <h2 class="title">
                                            {{\App\CentralLogics\Helpers::format_currency(abs($dm->wallet->collected_cash))}}
                                        </h2>
                                        <h5 class="subtitle">
                                            {{translate('messages.Payable_Balance')}}
                                        </h5>
                                        <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/transactions/cash.png')}}" alt="transactions">
                                    </div>
                                </div>

                                @else
                                <div class="col-sm-6 col-md-4">
                                    <div class="resturant-card bg--4">
                                        <h2 class="title">
                                            {{\App\CentralLogics\Helpers::format_currency(0)}}
                                        </h2>
                                        <h5 class="subtitle">
                                            {{translate('messages.Balance')}}
                                        </h5>
                                        <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/transactions/cash.png')}}" alt="transactions">
                                    </div>
                                </div>

                            @endif


                        <div class="col-sm-6 col-md-4">
                            <div class="resturant-card bg--5">
                                <h2 class="title">
                                    {{\App\CentralLogics\Helpers::format_currency($dm->wallet?($dm->wallet->total_withdrawn > 0 ? $dm->wallet->total_withdrawn : 0):0.00)}}
                                </h2>
                                <h5 class="subtitle">
                                    {{translate('messages.Total_withdrawn')}}
                                </h5>
                                <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/transactions/pending.png')}}" alt="transactions">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="resturant-card bg--1">
                                <h2 class="title">
                                    {{\App\CentralLogics\Helpers::format_currency($dm->wallet?($dm->wallet->pending_withdraw > 0 ? $dm->wallet->pending_withdraw : 0 ):0.00)}}
                                </h2>
                                <h5 class="subtitle">
                                    {{translate('messages.Pending_withdraw')}}
                                </h5>
                                <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/transactions/image_withdaw.png')}}" alt="transactions">
                            </div>
                        </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Card -->
        <div class="card mb-3">
            <div class="card-header">
                <div class="search--button-wrapper">
                    <h5 class="page-header-title delivery--man-single-name align-items-center mr-auto">
                        {{$dm['f_name'].' '.$dm['l_name']}}

                        (@if($dm->zone)
                            {{$dm->zone->name}}
                        @else {{translate('messages.zone_deleted')}}
                        @endif )
                        @if($dm->application_status=='approved')
                            @if($dm['status'])
                                @if($dm['active'])
                                    <label class="badge badge-soft-primary mb-0 ml-1">{{translate('messages.online')}}</label>
                                @else
                                    <label class="badge badge-soft-danger mb-0 ml-1">{{translate('messages.offline')}}</label>
                                @endif
                            @else
                            <span class="badge badge-danger">{{translate('messages.suspended')}}</span>
                            @endif

                        @else
                        <label class="m-0 badge badge-soft-{{$dm->application_status=='pending'?'info':'danger'}}">

                            {{   $dm->application_status=='pending'? translate('messages.not_approved'): translate('messages.'.$dm->application_status) }}
                        </label>
                        @endif
                    </h5>
                    @if($dm->application_status=='approved')
                    <div class="hs-unfold">
                        <a  href="javascript:"  data-url="{{route('admin.delivery-man.status',[$dm['id'],$dm->status?0:1])}}" data-message="{{$dm->status?translate('messages.you_want_to_suspend_this_deliveryman_?'):translate('messages.you_want_to_unsuspend_this_deliveryman_?')}}" class="btn {{$dm->status?'btn--danger':'btn--primary'}} mr-2 request-alert">
                                {{$dm->status?translate('messages.suspend_this_delivery_man'):translate('messages.unsuspend_this_delivery_man')}}
                        </a>
                    </div>
                    @endif
                    <div class="hs-unfold">
                        <div class="dropdown">
                            <button class="btn btn--reset initial-21 dropdown-toggle w-100" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                {{translate('messages.type')}} ({{$dm->earning?translate('messages.freelancer'):translate('messages.salary_based')}})
                            </button>
                            <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item {{$dm->earning?'active':''}} request-alert"
                                data-url="{{route('admin.delivery-man.earning',[$dm['id'],1])}}" data-message="{{translate('messages.want_to_enable_earnings_?')}}"
                                    href="javascript:">{{translate('messages.freelancer')}}</a>
                                <a class="dropdown-item {{$dm->earning?'':'active'}} request-alert"
                                data-url="{{route('admin.delivery-man.earning',[$dm['id'],0])}}" data-message="{{translate('messages.want_to_disable_earnings_?')}}"
                                    href="javascript:">{{translate('messages.salary_based')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Body -->
            <div class="card-body">
                <div class="row gy-3 align-items-center">
                    <div class="col-md-4">

                        <h2 class="title">{{ translate('Vehicle_Information') }}</h2>
                            @if (isset($dm->vehicle))
                            <div>{{ translate('Vehicle_Type') }} : {{ $dm->vehicle->type}}</div>
                            <div>{{ translate('Vehicle_Extra_Charges') }} : {{ $dm->vehicle->extra_charges}}</div>
                            <div>{{ translate('Vehicle_minimum_coverage_area') }} : {{ $dm->vehicle->starting_coverage_area}}</div>
                            <div>{{ translate('Vehicle_maximum_coverage_area') }} : {{ $dm->vehicle->maximum_coverage_area}}</div>
                            @else
                            <div>{{ translate('No_vehicle_data_found') }}</div>
                            @endif

                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <img class="avatar avatar-xxl avatar-4by3 mr-4 mw-120px initial-22 onerror-image"
                                 data-onerror-image="{{dynamicAsset('public/assets/admin/img/160x160/img1.jpg')}}"
                                 src="{{ $dm['image_full_url'] }}"
                                 alt="Image Description">
                            <div class="d-block">
                                <div class="rating--review">
                                    <h1 class="title">{{count($dm->rating)>0?number_format($dm->rating[0]->average, 1):0}}<span class="out-of">/5</span></h1>
                                    @if (count($dm->rating)>0)
                                    @if ($dm->rating[0]->average == 5)
                                    <div class="rating">
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                    </div>
                                    @elseif ($dm->rating[0]->average < 5 && $dm->rating[0]->average > 4.5)
                                    <div class="rating">
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star-half"></i></span>
                                    </div>
                                    @elseif ($dm->rating[0]->average < 4.5 && $dm->rating[0]->average > 4)
                                    <div class="rating">
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                    </div>
                                    @elseif ($dm->rating[0]->average < 4 && $dm->rating[0]->average > 3)
                                    <div class="rating">
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                    </div>
                                    @elseif ($dm->rating[0]->average < 3 && $dm->rating[0]->average > 2)
                                    <div class="rating">
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                    </div>
                                    @elseif ($dm->rating[0]->average < 2 && $dm->rating[0]->average > 1)
                                    <div class="rating">
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                    </div>
                                    @elseif ($dm->rating[0]->average < 1 && $dm->rating[0]->average > 0)
                                    <div class="rating">
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                    </div>
                                    @elseif ($dm->rating[0]->average == 1)
                                    <div class="rating">
                                        <span><i class="tio-star"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                    </div>
                                    @elseif ($dm->rating[0]->average == 0)
                                    <div class="rating">
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                        <span><i class="tio-star-outlined"></i></span>
                                    </div>
                                    @endif
                                    @endif
                                    <div class="info">
                                        <span>{{$dm->reviews->count()}} {{translate('messages.reviews')}}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <ul class="list-unstyled list-unstyled-py-2 mb-0 rating--review-right py-3">

                        @php($total=$dm->reviews->count())
                        <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($five=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],5))
                                <span class="progress-name mr-3">{{ translate('messages.excellent') }}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($five/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$five}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($four=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],4))
                                <span class="progress-name mr-3">{{ translate('messages.good') }}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($four/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$four}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($three=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],3))
                                <span class="progress-name mr-3">{{ translate('messages.average') }}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($three/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$three}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($two=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],2))
                                <span class="progress-name mr-3">{{ translate('messages.below_average') }}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($two/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$two}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($one=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],1))
                                <span class="progress-name mr-3">{{ translate('messages.poor') }}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($one/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$one}}</span>
                            </li>
                            <!-- End Review Ratings -->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->
        <!-- Card -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-header-title">{{ translate('Identity_Information') }}</h5>
            </div>
            <!-- Body -->
            <div class="card-body">
                <div class="row gy-3 align-items-center">
                    <div class="col-md-4">
                        <h5>{{ translate('identity_type') }} :
                                <span class="font-light"> {{ translate($dm->identity_type) }} </span>
                        </h5>
                        <h5>{{ translate('identity_number') }} :
                                <span class="font-light"> {{ $dm->identity_number }} </span>
                        </h5>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex flex-wrap justify-content-center ">
                        @foreach ($dm->identity_image_full_url as $key => $img)
                                    <button class="btn padding p-1" data-toggle="modal"
                                        data-target="#image-{{ $key }}">
                                        <div class="gallary-card">
                                            <img data-onerror-image="{{ dynamicAsset('/public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $img }}" class="avatar avatar-xxl avatar-4by3 mw-120px initial-22 onerror-image">
                                        </div>
                                    </button>
                                    <div class="modal fade" id="image-{{ $key }}" tabindex="-1" role="dialog"
                                    aria-labelledby="myModlabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModlabel">
                                                    {{ translate('messages.Identity_Image') }}</h4>
                                                <button type="button" class="close" data-dismiss="modal"><span
                                                        aria-hidden="true">&times;</span><span
                                                        class="sr-only">{{ translate('messages.Close') }}</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <img data-onerror-image="{{ dynamicAsset('/public/assets/admin/img/900x400/img1.jpg') }}"
                                                     src="{{ $img }}"
                                                    class="w-100 onerror-image">
                                            </div>
                                            <div class="modal-footer">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </div>
                    </div>
                </div>
            </div>

            <!-- End Body -->
        </div>
        <!-- End Card -->

        @if($dm->additional_data && count(json_decode($dm->additional_data, true)) > 0 )
            <div class="row mb-2">

            <div class="col-lg-12  mt-2">
                <div class="card ">

                    <div class="card-header justify-content-between align-items-center">
                        <label class="input-label text-capitalize d-inline-flex align-items-center m-0">
                            <span class="line--limit-1"><img src="{{ dynamicAsset('/public/assets/admin/img/company.png') }}"
                                    alt=""> {{ translate('Additional_Information') }} </span>
                            <span data-toggle="tooltip" data-placement="right"
                                data-original-title="{{ translate('Additional_Information') }}"
                                class="input-label-secondary">
                                <img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="info"></span>
                        </label>
                    </div>
                    <div class="card-body">
                        <div class="__registration-information">
                            <div class="item">
                                <ul>
                                    @foreach (json_decode($dm->additional_data, true) as $key => $item)
                                        @if (is_array($item))


                                        @foreach ($item as $k => $data)
                                        <li>
                                            <span class="left"> {{ $k == 0 ? translate($key) : ''}} </span>
                                            <span class="right">{{ $data }} </span>
                                        </li>
                                        @endforeach
                                        @else
                                        <li>
                                            <span class="left"> {{ translate($key) }} </span>
                                            <span class="right">{{ $item ?? translate('messages.N/A')}} </span>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            @endif

            @if($dm->additional_documents && count(json_decode($dm->additional_documents, true)) > 0 )
            <div class="row mb-2">
            <div class="col-lg-12 mb-2 mt-2">
                <div class="card ">
                    <div class="card-header justify-content-between align-items-center">
                        <label class="input-label text-capitalize d-inline-flex align-items-center m-0">
                            <span class="line--limit-1"><i class="tio-file-text-outlined"></i>
                                {{ translate('Documents') }} </span>
                            <span data-toggle="tooltip" data-placement="right"
                                data-original-title="{{ translate('Additional_Documents') }}"
                                class="input-label-secondary">
                                <img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="info"></span>
                        </label>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3"> {{ translate('Attachments') }} </h5>
                        <div class="d-flex flex-wrap gap-4 align-items-start">
                                @foreach (json_decode($dm->additional_documents, true) as $key => $item)

                                @php($item  = is_string($item) ? json_deocde($item,true) : $item  )
                                @foreach ($item as $file)

                                @php($file =  is_string($file) ? ['file' => $file, 'storage' => 'public'] :  $file )
                                            <?php
                                                $path_info = pathinfo(\App\CentralLogics\Helpers::get_full_url('additional_documents/dm',$file['file'],$file['storage']));
                                                $f_date = $path_info['extension'];
                                                ?>

                                        @if (in_array($f_date, ['pdf', 'doc', 'docs', 'docx' ]))
                                                @if ($f_date == 'pdf')
                                                    <div class="attachment-card min-w-260">
                                                        <label for="">{{ translate($key) }}</label>
                                                        <a href="{{ \App\CentralLogics\Helpers::get_full_url('additional_documents/dm',$file['file'],$file['storage']) }}" target="_blank" rel="noopener noreferrer">
                                                            <div class="img ">


                                                                <iframe src="https://docs.google.com/gview?url={{ \App\CentralLogics\Helpers::get_full_url('additional_documents/dm',$file['file'],$file['storage']) }}&embedded=true"></iframe>

                                                            </div>
                                                        </a>

                                                        <a href="{{ \App\CentralLogics\Helpers::get_full_url('additional_documents/dm',$file['file'],$file['storage']) }}" download
                                                            class="download-icon mt-3">
                                                            <img src="{{ dynamicAsset('/public/assets/admin/new-img/download-icon.svg') }}" alt="">
                                                        </a>
                                                        <a href="#" class="pdf-info">
                                                            <img src="{{ dynamicAsset('/public/assets/admin/new-img/pdf.png') }}" alt="">
                                                            <div class="w-0 flex-grow-1">
                                                                <h6 class="title">{{ translate('Click_To_View_The_file.pdf') }}
                                                                </h6>

                                                            </div>
                                                        </a>
                                                    </div>
                                                    @else
                                                    <div class="attachment-card  min-w-260">
                                                        <label for="">{{ translate($key) }}</label>
                                                        <a href="{{ \App\CentralLogics\Helpers::get_full_url('additional_documents/dm',$file['file'],$file['storage']) }}" target="_blank" rel="noopener noreferrer">
                                                            <div class="img ">

                                                                <iframe src="https://docs.google.com/gview?url={{ \App\CentralLogics\Helpers::get_full_url('additional_documents/dm',$file['file'],$file['storage']) }}&embedded=true"></iframe>


                                                            </div>
                                                        </a>
                                                        <a href="{{ \App\CentralLogics\Helpers::get_full_url('additional_documents/dm',$file['file'],$file['storage']) }}" download
                                                            class="download-icon mt-3">
                                                            <img src="{{ dynamicAsset('/public/assets/admin/new-img/download-icon.svg') }}" alt="">
                                                        </a>
                                                        <a href="#" class="pdf-info">
                                                            <img src="{{ dynamicAsset('/public/assets/admin/new-img/doc.png') }}" alt="">
                                                            <div class="w-0 flex-grow-1">
                                                                <h6 class="title">{{ translate('Click_To_View_The_file.doc') }}
                                                                </h6>

                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                        @endif

                                    @endforeach
                                @endforeach
                            </div>
                        <br>
                        <br>

                        <h5 class="mb-3"> {{ translate('Images') }} </h5>
                        <div class="d-flex flex-wrap gap-4 align-items-start">
                            @foreach (json_decode($dm->additional_documents, true) as $key => $item)

                            @php($item  = is_string($item) ? json_deocde($item,true) : $item  )

                            @foreach ($item as $file)
                            @php($file =  is_string($file) ? ['file' => $file, 'storage' => 'public'] :  $file )
                                    <?php
                                        $path_info = pathinfo(\App\CentralLogics\Helpers::get_full_url('additional_documents/dm',$file['file'],$file['storage']) );
                                        $f_date = $path_info['extension'];
                                        ?>
                                    @if (in_array($f_date, ['jpg', 'jpeg', 'png']))
                                    <div class="attachment-card max-w-360">
                                        <label for="">{{ translate($key) }}</label>
                                        <a href="{{ \App\CentralLogics\Helpers::get_full_url('additional_documents/dm',$file['file'],$file['storage']) }}" download
                                            class="download-icon mt-3">
                                            <img src="{{ dynamicAsset('/public/assets/admin/new-img/download-icon.svg') }}" alt="">
                                        </a>
                                        <img src="{{ \App\CentralLogics\Helpers::get_full_url('additional_documents/dm',$file['file'],$file['storage'])  }}"
                                            class="aspect-615-350 cursor-pointer mw-100 object--cover" alt="">
                                    </div>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            </div>
            @endif
        <!-- Card -->
        <div class=" card">

                     <!-- Header -->
                     <div class="card-header py-2 border-0">
                        <h5 class="card-header-title">
                                {{translate('messages.review_list')}}
                        </h5>
                        <div class="search--button-wrapper justify-content-end">
                            <!-- Unfold -->
                            <div class="hs-unfold mr-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:"
                                    data-hs-unfold-options='{
                                            "target": "#usersExportDropdown",
                                            "type": "css-animation"
                                        }'>
                                    <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                                </a>

                                <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                    <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                                    <a id="export-excel" class="dropdown-item" href="{{route('admin.delivery-man.review-export', ['type'=>'excel','id'=>$dm->id,request()->getQueryString()])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ dynamicAsset('public/assets/admin') }}/svg/components/excel.svg"
                                            alt="Image Description">
                                        {{ translate('messages.excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item" href="{{route('admin.delivery-man.review-export', ['type'=>'csv','id'=>$dm->id,request()->getQueryString()])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ dynamicAsset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                        .{{ translate('messages.csv') }}
                                    </a>
                                </div>
                            </div>
                            <!-- End Unfold -->
                        </div>
                    </div>
                    <!-- End Header -->
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0, 3, 6],
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
                        <th>{{translate('messages.reviewer')}}</th>
                        <th>{{ translate('messages.Order_ID') }}</th>
                        <th>{{translate('messages.review')}}</th>
                        <th>{{translate('messages.date')}}</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($reviews as $review)
                        <tr>
                            <td>
                                @if($review->customer)
                                <a class="d-flex align-items-center"
                                   href="{{route('admin.customer.view',[$review['user_id']])}}">
                                    <div class="avatar rounded">
                                        <img class="avatar-img" width="75" height="75"
                                             src="{{ $review?->customer?->image ?? dynamicAsset('public/assets/admin/img/160x160/img1.png') }}"
                                             alt="Image Description">
                                    </div>
                                    <div class="ml-3">
                                    <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                                            class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                            title="Verified Customer"></i></span>
                                        <span class="d-block font-size-sm text-body">{{$review->customer->email}}</span>
                                    </div>
                                </a>
                                @else
                                {{translate('messages.customer_not_found')}}
                                @endif
                            </td>
                            <td>
                                <a href="{{route('admin.order.details',['id'=>$review->order_id])}}">{{$review->order_id}}</a>
                            </td>
                            <td>
                                <div class="text-wrap initial-23">
                                    <label class="rating m-0">
                                        {{$review->rating}} <i class="tio-star"></i>
                                    </label>
                                    <p class="mb-0">
                                        {{$review['comment']}}
                                    </p>
                                </div>
                            </td>
                            <td>
                                {{date('d M Y '. config('timeformat'),strtotime($review['created_at']))}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($reviews) === 0)
                <div class="empty--data">
                    <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
            <!-- End Table -->

            <!-- Footer -->
                <div class="page-area px-4 pb-3">
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                            {!! $reviews->links() !!}
                        </div>
                    </div>
                </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
<script>
    "use strict";
    $('.request-alert').on('click',function (){
        let url = $(this).data('url');
        let message = $(this).data('message');
        request_alert(url, message);
    })
    function request_alert(url, message) {
        Swal.fire({
            title: '{{translate('messages.are_you_sure_?')}}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{translate('messages.no')}}',
            confirmButtonText: '{{translate('messages.yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = url;
            }
        })
    }
</script>
@endpush
