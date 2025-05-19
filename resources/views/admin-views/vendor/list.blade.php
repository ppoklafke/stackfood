@extends('layouts.admin.app')

@section('title',translate('Restaurant_List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.restaurants')}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{$restaurants->total()}}</span></h1>
        </div>
        <!-- End Page Header -->


        <!-- Filters -->
        <div class="card shadow--card p-0 mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-3">
                        <div class="select-item">
                        <!-- Veg/NonVeg filter -->
                        <select name="type"
                        data-url="{{url()->full()}}" data-filter="type"
                        data-placeholder="{{translate('messages.all')}}" class="form-control js-select2-custom set-filter">
                            <option value="all" {{$type=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                            @if ($toggle_veg_non_veg)
                            <option value="veg" {{$type=='veg'?'selected':''}}>{{translate('messages.veg')}}</option>
                            <option value="non_veg" {{$type=='non_veg'?'selected':''}}>{{translate('messages.non_veg')}}</option>
                            @endif
                            <option value="home_delivery" {{$type=='home_delivery'?'selected':''}}>{{translate('messages.Home_Delivery')}}</option>
                            <option value="take_away" {{$type=='take_away'?'selected':''}}>{{translate('messages.Take_Away')}}</option>
                            <option value="dine_in" {{$type=='dine_in'?'selected':''}}>{{translate('messages.Dine_In')}}</option>
                        </select>
                        <!-- End Veg/NonVeg filter -->
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="select-item">
                        <!-- Veg/NonVeg filter -->
                        <select name="restaurant_model"
                                data-url="{{url()->full()}}" data-filter="restaurant_model"
                        data-placeholder="{{translate('messages.all')}}" class="form-control js-select2-custom set-filter">
                            <option selected disabled>{{translate('messages.Business_model')}}</option>
                            <option value="all" {{$typ=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                            <option value="commission" {{$typ=='commission'?'selected':''}}>{{translate('messages.Commission')}}</option>
                            <option value="subscribed" {{$typ=='subscribed'?'selected':''}}>{{translate('messages.Subscribed')}}</option>
                            <option value="unsubscribed" {{$typ=='unsubscribed'?'selected':''}}>{{translate('messages.Unsubscribed')}}</option>
                            <option value="none" {{$typ=='none'?'selected':''}}>{{translate('messages.Incomplete_Business_Model')}}</option>

                        </select>

                    <!-- End Veg/NonVeg filter -->
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="select-item">
                        <select name="cuisine_id" id="cuisine"
                                data-url="{{url()->full()}}" data-filter="cuisine_id"
                        data-placeholder="{{ translate('messages.select_Cuisine') }}"
                        class="form-control h--45px js-select2-custom set-filter">
                        <option value="all" selected >{{ translate('messages.select_Cuisine') }}</option>
                        @foreach (\App\Models\Cuisine::orderBy('name')->get(['id','name']) as $cu)
                            <option value="{{ $cu['id'] }}"
                                {{ $cuisine_id ==  $cu['id']? 'selected' : '' }}>
                                {{ $cu['name'] }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                @if(!isset(auth('admin')->user()->zone_id))
                    <div class="col-sm-6 col-md-3">
                        <div class="select-item">
                            <select name="zone_id" class="form-control js-select2-custom set-filter"
                                data-url="{{url()->full()}}" data-filter="zone_id">
                                <option selected disabled>{{translate('messages.select_zone')}}</option>
                                <option value="all">{{translate('messages.all_zones')}}</option>
                                @foreach(\App\Models\Zone::orderBy('name')->get(['id','name']) as $z)
                                    <option
                                        value="{{$z['id']}}" {{isset($zone) && $zone->id == $z['id']?'selected':''}}>
                                        {{$z['name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Filters -->


        <!-- Resturent Card Wrapper -->
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card bg--1">
                    <h4 class="title" id="itemCount" >{{$restaurants->total()}}</h4>
                    <span class="subtitle">{{translate('messages.total_restaurants')}}</span>
                    <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/resturant/map-pin.png')}}" alt="resturant">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">

                <div class="resturant-card bg--2">
                    @php($active_restaurants = \App\Models\Restaurant::where(['status'=>1])
                     ->whereHas('vendor', function($q){
                            $q->where('status',1);
                        })
                    ->when( isset($zone) && ($zone->id), function ($query) use ($zone) {
                                    return $query->where('zone_id', $zone->id);
                                    })
                    ->type($type)->RestaurantModel($typ)
                    ->count())
                    @php($active_restaurants = isset($active_restaurants) ? $active_restaurants : 0)
                    <h4 class="title">{{$active_restaurants}}</h4>
                    <span class="subtitle">{{translate('messages.active_restaurants')}}</span>
                    <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/resturant/active-rest.png')}}" alt="resturant">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card bg--3">
                    @php($inactive_restaurants = \App\Models\Restaurant::where(['status'=>0])
                     ->whereHas('vendor', function($q){
                            $q->where('status',1);
                        })
                    ->when( isset($zone) && ($zone->id), function ($query) use ($zone) {
                                    return $query->where('zone_id', $zone->id);
                                    })
                    ->type($type)->RestaurantModel($typ)
                    ->count())
                    @php($inactive_restaurants = isset($inactive_restaurants) ? $inactive_restaurants : 0)
                    <h4 class="title">{{$inactive_restaurants}}</h4>
                    <span class="subtitle">{{translate('messages.inactive_restaurants')}}</span>
                    <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/resturant/inactive-rest.png')}}" alt="resturant">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card bg--4">
                    @php($data = \App\Models\Restaurant::where('created_at', '>=', now()->subDays(30)->toDateTimeString())
                    ->whereHas('vendor', function($q){
                            $q->where('status',1);
                        })
                    ->when( isset($zone) && ($zone->id), function ($query) use ($zone) {
                                    return $query->where('zone_id', $zone->id);
                                    })
                    ->type($type)->RestaurantModel($typ)
                    ->count())
                    <h4 class="title">{{$data}}</h4>
                    <span class="subtitle">{{translate('messages.newly_joined_restaurants')}}</span>
                    <img class="resturant-icon" src="{{dynamicAsset('/public/assets/admin/img/resturant/new-rest.png')}}" alt="resturant">
                </div>
            </div>
        </div>
        <!-- Resturent Card Wrapper -->
        <!-- Transaction Information -->
        <ul class="transaction--information text-uppercase">
            <li class="text--info">
                <i class="tio-document-text-outlined"></i>
                <div>
                    @php($total_transaction = \App\Models\OrderTransaction::count())
                    @php($total_transaction = isset($total_transaction) ? $total_transaction : 0)
                    <span>{{translate('messages.total_transactions')}}</span> <strong>{{$total_transaction}}</strong>
                </div>
            </li>
            <li class="seperator"></li>
            <li class="text--success">
                <i class="tio-checkmark-circle-outlined success--icon"></i>
                <div>
                    @php($comission_earned = \App\Models\AdminWallet::sum('total_commission_earning'))
                    @php($comission_earned = isset($comission_earned) ? $comission_earned : 0)
                    <span>{{translate('messages.commission_earned')}}</span> <strong>{{\App\CentralLogics\Helpers::format_currency($comission_earned)}}</strong>
                </div>
            </li>
            <li class="seperator"></li>
            <li class="text--danger">
                <i class="tio-atm"></i>
                <div>
                    @php($restaurant_withdraws = \App\Models\WithdrawRequest::where(['approved'=>1])->sum('amount'))
                    @php($restaurant_withdraws = isset($restaurant_withdraws) ? $restaurant_withdraws : 0)
                    <span>{{translate('messages.total_restaurant_withdraws')}}</span> <strong>{{\App\CentralLogics\Helpers::format_currency($restaurant_withdraws)}}</strong>
                </div>
            </li>
        </ul>
        <!-- Transaction Information -->
        <!-- Resturent List -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Card Header -->

                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h3 class="card-title">{{translate('messages.restaurants_list')}}</h3>
                            <form class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">

                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                          value="{{ request()?->search  ?? null }}"  placeholder="{{ translate('Ex:_search_by_Restaurant_name_of_Phone_number') }}" aria-label="{{translate('messages.search')}}" required>
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>

                                </div>
                                <!-- End Search -->
                            </form>

                            <!-- Export Button Static -->
                            <div class="hs-unfold ml-3">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn btn-outline-primary btn--primary font--sm" href="javascript:"
                                    data-hs-unfold-options='{
                                        "target": "#usersExportDropdown",
                                        "type": "css-animation"
                                    }'>
                                    <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                                </a>

                                <div id="usersExportDropdown"
                                        class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                                    <span class="dropdown-header">{{translate('messages.download_options')}}</span>
                                    <a target="__blank" id="export-excel" class="dropdown-item" href="{{route('admin.restaurant.restaurants-export', ['type'=>'excel',
                   request()->getQueryString()])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{dynamicAsset('public/assets/admin')}}/svg/components/excel.svg"
                                        alt="Image Description">
                                        {{translate('messages.excel')}}
                                    </a>

                                    <a id="export-excel" class="dropdown-item" href="{{route('admin.restaurant.restaurants-export', ['type'=>'csv',request()->getQueryString()])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                                    src="{{dynamicAsset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                                    alt="Image Description">
                                                    {{translate('messages.csv')}}
                                    </a>
                                </div>
                            </div>
                            <!-- Export Button Static -->
                        </div>
                    </div>
                    <!-- Card Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom resturant-list-table">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false

                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th class="text-uppercase w-90px">{{translate('messages.sl')}}</th>
                                <th class="initial-58">{{translate('messages.restaurant_info')}}</th>
                                <th class="w-230px text-center">{{translate('messages.owner_info')}} </th>
                                <th class="w-130px">{{translate('messages.zone')}}</th>
                                <th class="w-130px">{{translate('messages.cuisine')}}</th>
                                <th class="w-100px">{{translate('messages.status')}}</th>
                                <th class="text-center w-60px">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($restaurants as $key=>$dm)
                                <tr>
                                    <td>{{$key+$restaurants->firstItem()}}</td>
                                    <td>
                                        <a href="{{route('admin.restaurant.view', $dm->id)}}" alt="view restaurant" class="table-rest-info">
                                        <img class="onerror-image" data-onerror-image="{{dynamicAsset('public/assets/admin/img/100x100/food-default-image.png')}}"

                                             src="{{ $dm['logo_full_url'] ?? dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}">
                                            <div class="info">
                                                <span class="d-block text-body">
                                                    {{Str::limit($dm->name,20,'...')}}<br>
                                                    <!-- Rating -->
                                                    <span class="rating">
                                                        @if ($dm->reviews_count)
                                                        @php($reviews_count = $dm->reviews_count)
                                                        @php($reviews = 1)
                                                        @else
                                                        @php($reviews = 0)
                                                        @php($reviews_count = 1)
                                                        @endif
                                                    <i class="tio-star"></i> {{ round($dm->reviews_sum_rating /$reviews_count,1) }}
                                                </span>
                                                    <!-- Rating -->
                                                </span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="d-block owner--name text-center">
                                            {{$dm->vendor->f_name.' '.$dm->vendor->l_name}}
                                        </span>
                                        <span class="d-block font-size-sm text-center">
                                            {{$dm['phone']}}
                                        </span>
                                    </td>
                                    <td>
                                        {{$dm->zone?$dm->zone->name:translate('messages.zone_deleted')}}
                                    </td>
                                    <td>

                                        @if ($dm->cuisine)
                                        <div class="white-space-initial" data-toggle="tooltip" data-placement="bottom" title="@foreach($dm->cuisine as $key  => $c) {{$c->name}}{{ !$loop->last ? ',' : '.'}}@endforeach">
                                            <span  >
                                            @forelse($dm->cuisine as $key  => $c)
                                                {{$c->name}}{{   $key == 3 ? ' ....' :  (!$loop->last ? ',' : '.')}}
                                                @break($key == 3)
                                                @empty
                                                {{ translate('Cuisine_not_found') }}
                                                @endforelse
                                            </span>
                                        </div>
                                        @endif
                                </td>
                                    <td>
                                        @if(isset($dm->vendor->status))
                                            @if($dm->vendor->status)
                                            <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$dm->id}}">
                                                <input type="checkbox" data-url="{{route('admin.restaurant.status',[$dm->id,$dm->status?0:1])}}" data-message="{{translate('messages.you_want_to_change_this_restaurant_status')}}" class="toggle-switch-input status_change_alert" id="stocksCheckbox{{$dm->id}}" {{$dm->status?'checked':''}}>
                                                <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            @else
                                            <span class="badge badge-soft-danger">{{translate('messages.denied')}}</span>
                                            @endif
                                        @else
                                            <span class="badge badge-soft-danger">{{translate('messages.not_approved')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{route('admin.restaurant.edit',[$dm['id']])}}" title="{{translate('messages.edit_restaurant')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                                                href="{{route('admin.restaurant.view',[$dm['id']])}}" title="{{translate('messages.view_restaurant')}}"><i class="tio-invisible"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($restaurants) === 0)
                        <div class="empty--data">
                            <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $restaurants->appends(request()->all())->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- Resturent List -->
    </div>

@endsection

@push('script_2')
    <script>
        "use strict";
        $('.status_change_alert').on('click', function (event) {
            let url = $(this).data('url');
            let message = $(this).data('message');
            status_change_alert(url, message, event)
        })

        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are_you_sure?') }}',
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
                    location.href=url;
                }
            })
        }
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

@endpush
