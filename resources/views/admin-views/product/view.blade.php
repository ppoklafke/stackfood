@extends('layouts.admin.app')

@section('title',translate('Food_Preview'))

@push('css_or_js')

@endpush

@section('content')
<?php
$reviewsInfo = $product->rating()->first();
?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="page-header-title text-break">
                    {{$product['name']}}   @if ($product->stock_type !== 'unlimited' && $product->item_stock <= 0 )
                    <span class="ml-2 badge badge-soft-warning badge-pill font-medium">{{ translate('Out Of Stock') }}</span>
                @endif
                </h1>
                <a href="{{route('admin.food.edit',[$product['id']])}}" class="btn btn--primary">
                    <i class="tio-edit"></i> {{translate('Edit_Info')}}
                </a>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row review--information-wrapper g-2 mb-3">
            <div class="col-lg-9">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="row align-items-md-center">
                            <div class="col-lg-5 col-md-6 mb-3 mb-md-0">
                                <div class="d-flex flex-wrap align-items-center food--media">
                                    <img class="avatar avatar-xxl avatar-4by3 mr-4 initial-53 onerror-image"
                                         src="{{ $product['image_full_url'] }}"
                                         alt="{{ $product->name }} image">
                                    <div class="d-block">
                                            <div class="rating--review">

                                            <h1 class="title">{{ number_format($reviewsInfo?->average, 1)}}<span class="out-of">/5</span></h1>
                                            @if ($reviewsInfo?->average == 5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average < 5 && $reviewsInfo?->average >= 4.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average < 4.5 && $reviewsInfo?->average >= 4)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average < 4 && $reviewsInfo?->average >= 3.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average < 3.5 && $reviewsInfo?->average >= 3)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average < 3 && $reviewsInfo?->average >= 2.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average < 2.5 && $reviewsInfo?->average > 2)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average < 2 && $reviewsInfo?->average >= 1.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average < 1.5 && $reviewsInfo?->average > 1)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average < 1 && $reviewsInfo?->average > 0)
                                            <div class="rating">
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average == 1)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($reviewsInfo?->average == 0)
                                            <div class="rating">
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @endif
                                            <div class="info">
                                                <span>{{translate('messages.of')}} {{$product->reviews->count()}} {{translate('messages.reviews')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-6 mx-auto">
                                <ul class="list-unstyled list-unstyled-py-2 mb-0 rating--review-right py-3">

                                @php($total=$product->rating?array_sum(json_decode($product->rating, true)):0)
                                <!-- Review Ratings -->
                                    <li class="d-flex align-items-center font-size-sm">
                                        @php($five=$product->rating?json_decode($product->rating, true)[5]:0)
                                        <span
                                            class="progress-name mr-3">{{ translate('Excellent') }}</span>
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
                                        @php($four=$product->rating?json_decode($product->rating, true)[4]:0)
                                        <span class="progress-name mr-3">{{ translate('Good') }}</span>
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
                                        @php($three=$product->rating?json_decode($product->rating, true)[3]:0)
                                        <span class="progress-name mr-3">{{ translate('Average') }}</span>
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
                                        @php($two=$product->rating?json_decode($product->rating, true)[2]:0)
                                        <span class="progress-name mr-3">{{ translate('Below_Average') }}</span>
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
                                        @php($one=$product->rating?json_decode($product->rating, true)[1]:0)
                                        <span class="progress-name mr-3">{{ translate('Poor') }}</span>
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
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card h-100">

                    <div class="card-body d-flex flex-column justify-content-center">
                    @if($product->restaurant)
                        <a class="resturant--information-single" href="{{route('admin.restaurant.view', $product->restaurant_id)}}" title="{{$product->restaurant['name']}}">
                            <img class="avatar-img initial-54 onerror-image"
                                 data-onerror-image="{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"

                                 src="{{ $product->restaurant->logo_full_url ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"

                                 alt="Image Description">
                            <div class="text-center">
                                <h5 class="text-capitalize text--title font-semibold text-hover-primary d-block mb-1">
                                    {{$product->restaurant['name']}}
                                </h5>
                                <span class="text--title">
                                    <i class="tio-poi"></i> {{$product->restaurant['address']}}
                                </span>
                            </div>
                        </a>
                    @else
                        <div class="badge badge-soft-danger py-2">{{translate('messages.restaurant_deleted')}}</div>
                    @endif
                    </div>


                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="px-4 w-140px"><h4 class="m-0">{{ translate('Short_Description') }}</h4></th>
                                <th class="px-4 w-120px"><h4 class="m-0">{{translate('messages.price')}}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{translate('messages.Main_Stock')}}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{ translate('Addons') }}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{ translate('Tags') }}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{ translate('Nutrition Details') }}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{ translate('Allergy Details') }}</h4></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4">
                                    <div>
                                        {!!$product['description'] !!}
                                    </div>
                                </td>
                                <td class="px-4">
                                    <span class="d-block mb-1">
                                        <span>{{ translate('Price') }}</span>
                                        <strong>{{\App\CentralLogics\Helpers::format_currency($product['price'])}}</strong>
                                    </span>
                                    <span class="d-block mb-1">{{translate('messages.discount')}} :
                                        <strong>{{\App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::discount_calculate($product,$product['price']))}}</strong>
                                    </span>
                                    <span class="d-block mb-1">
                                        {{translate('messages.available_time_starts')}} : <strong>{{date(config('timeformat'),strtotime($product['available_time_starts']))}}</strong>
                                    </span>
                                    <span class="d-block">
                                        {{translate('messages.available_time_ends')}} : <strong>{{date(config('timeformat'), strtotime($product['available_time_ends']))}}</strong>
                                    </span>
                                </td>
                                <td class="px-4">
                                    @php($tock_out = null)

                                    @if ($product->stock_type == 'unlimited')
                                    <span class="badge badge-soft-info badge-pill font-medium">{{translate('unlimited')}}</span>
                                    @elseif($product->item_stock > 0)
                                    <span class="badge badge-soft-dark ml-2" >  {{ $product->item_stock }} </span>
                                    @else
                                    @php($tock_out = true)
                                    <span class="badge badge-soft-warning badge-pill font-medium">{{ translate('Out Of Stock') }}</span>
                                    @endif
                                </td>
                                <td class="px-4">
                                    @foreach(\App\Models\AddOn::withOutGlobalScope(App\Scopes\RestaurantScope::class)->whereIn('id',json_decode($product['add_ons'],true))->get() as $addon)
                                        <span class="d-block text-capitalize">
                                        {{$addon['name']}} : <strong>{{\App\CentralLogics\Helpers::format_currency($addon['price'])}}</strong>
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-4">
                                    @forelse($product->tags as $c)
                                        {{$c->tag.','}}
                                        @empty
                                        {{ translate('No_tags_found') }}
                                    @endforelse
                                </td>
                                <td class="px-4">
                                    @if ($product->nutritions)
                                        @foreach($product->nutritions as $nutrition)
                                            {{$nutrition->nutrition}}{{ !$loop->last ? ',' : '.'}}
                                        @endforeach
                                    @endif
                                </td>
                                <td class="px-4">
                                    @if ($product->allergies)
                                        @foreach($product->allergies as $allergy)
                                            {{$allergy->allergy}}{{ !$loop->last ? ',' : '.'}}
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->







    @if (count($product->newVariationOptions) > 0)
        <div class="card mb-3">
            <div class="card-header">
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
                            <th>{{translate('messages.sl')}}</th>
                            <th class="text-canter">{{translate('messages.Variation_Name')}}</th>
                            <th  class="text-canter">{{translate('messages.Variation_Wise_Price')}}</th>
                            <th  class="text-canter">{{translate('messages.Stock')}}
                                @if ($tock_out == true)
                                <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.Your main stock is empty.Variations stock won\'t work if the main stock is empty.')}}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="public/img"></span>
                                @endif

                            </th>
                        </tr>
                        </thead>

                        <tbody class="">

                        @foreach($product->newVariationOptions as $key => $variation)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <div>
                                        {{ $variation?->variation?->name }}
                                        <div>
                                        <small>{{ $variation->option_name }} </small>
                                        </div>

                                    </div>

                                </td>
                                <td class="text-canter">
                                    {{ \App\CentralLogics\Helpers::format_currency($variation?->option_price) }}
                                </td>
                                <td class="text-canter {{ $tock_out == true ? 'text-9EADC1' : '' }}   ">
                                    @if ($variation?->stock_type == "unlimited")
                                    <span class="badge badge-soft-info badge-pill font-medium">{{translate('unlimited')}}</span>
                                    @elseif( $variation?->total_stock - $variation?->sell_count > 0 )
                                    {{ $variation?->total_stock - $variation?->sell_count }}
                                    @else
                                    <span class="badge badge-soft-warning badge-pill font-medium">{{ translate('Out Of Stock') }}</span>
                                    @endif
                                </td>


                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endif


        <!-- Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{translate('messages.product_reviews')}}</h5>


                <div class="hs-unfold mr-2">
                    <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"
                        data-hs-unfold-options='{
                                "target": "#usersExportDropdown",
                                "type": "css-animation"
                            }'>
                        <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                    </a>

                    <div id="usersExportDropdown"
                        class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                        <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                        <a id="export-excel" class="dropdown-item" href="{{ route('admin.food.food_wise_reviews_export', ['type' => 'excel', 'restaurant'=> $product->restaurant?->name,'id' => $product['id'],request()->getQueryString()]) }}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{ dynamicAsset('public/assets/admin') }}/svg/components/excel.svg"
                                alt="Image Description">
                            {{ translate('messages.excel') }}
                        </a>
                        <a id="export-csv" class="dropdown-item" href="{{ route('admin.food.food_wise_reviews_export', ['type' => 'csv', 'restaurant'=> $product->restaurant?->name, 'id' => $product['id'], request()->getQueryString()]) }}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{ dynamicAsset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                alt="Image Description">
                            .{{ translate('messages.csv') }}
                        </a>

                    </div>
                </div>

            </div>
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
                        <th>{{translate('messages.review')}}</th>
                        <th>{{translate('messages.date')}}</th>
                        <th class="w-20p text-center">{{translate('messages.restaurant_reply')}}</th>
                        <th>{{translate('messages.status')}}</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($reviews as $review)
                        <tr>
                            <td>
                                @if ($review->customer)
                                    <a class="d-flex align-items-center"
                                    href="{{route('admin.customer.view',[$review['user_id']])}}">
                                        <div class="avatar avatar-circle">
                                            <img class="avatar-img onerror-image"
                                                 data-onerror-image="{{ dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"  width="75" height="75"

                                                 src="{{ $review->customer->image_full_url ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
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
                                <div class="text-wrap mw-400">
                                    <label class="m-0 rating">
                                        {{$review->rating}} <i class="tio-star"></i>
                                    </label>

                                    <p data-toggle="tooltip" data-placement="left"
                                    data-original-title="{{$review['comment']}}"  class="line--limit-1">
                                        {{$review['comment']}}
                                    </p>
                                </div>
                            </td>
                            <td>
                                {{ \App\CentralLogics\Helpers::time_date_format($review->created_at)  }}
                            </td>
                            <td>
                                <p class="text-wrap text-center" data-toggle="tooltip" data-placement="top"
                                   data-original-title="{{ $review?->reply }}">{!! $review->reply?Str::limit($review->reply, 50, '...'): translate('messages.Not_replied_Yet') !!}</p>
                            </td>
                            <td>
                                <label class="toggle-switch toggle-switch-sm" for="reviewCheckbox{{$review->id}}">
                                    <input type="checkbox"
                                           data-id="status-{{ $review['id'] }}" data-message="{{ $review->status ? translate('messages.you_want_to_hide_this_review_for_customer') : translate('messages.you_want_to_show_this_review_for_customer') }}"
                                           class="toggle-switch-input status_form_alert" id="reviewCheckbox{{ $review->id }}"
                                            {{ $review->status ? 'checked' : '' }}>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                                <form action="{{route('admin.food.reviews.status',[$review['id'],$review->status?0:1])}}" method="get" id="status-{{$review['id']}}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($reviews) === 0)
            <div class="empty--data">
                <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
            <!-- End Table -->

            <!-- Pagination -->
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div>
                        {!! $reviews->links() !!}
                    </div>
                </div>
            </div>
            <!-- End Pagination -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
<script>
    "use strict";
    $(".status_form_alert").on("click", function (e) {
        const id = $(this).data('id');
        const message = $(this).data('message');
        e.preventDefault();
        Swal.fire({
            title: '{{translate('messages.Are_you_sure_?')}}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ translate('no') }}',
            confirmButtonText: '{{ translate('yes') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $('#'+id).submit()
            }
        })
    })
</script>
@endpush
