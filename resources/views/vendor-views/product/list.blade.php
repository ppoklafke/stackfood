@extends('layouts.vendor.app')

@section('title',translate('messages.food_list'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="mb-2 mb-sm-0">
                <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.food_list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$foods->total()}}</span></h1>
            </div>
            <div class="my-2">
                <div class="row g-2 align-items-center justify-content-end">

                    <div class="col-auto">
                        <a href="{{route('vendor.food.stockOutList')}}" class="btn max-sm-12 btn--danger w-100"> {{translate('messages.Out of Stock Foods')}}</a>
                    </div>
                    <div class="col-auto">
                        <a href="{{route('vendor.food.add-new')}}" class="btn max-sm-12 btn--primary w-100"><i
                                    class="tio-add-circle"></i> {{translate('messages.add_new_food')}}</a>
                    </div>

                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header border-0">
                <div class="search--button-wrapper search--button-area justify-content-end">
                    <form id="search-form">
                        @csrf
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{ translate('messages.Ex : Search Food Name') }}">
                            <button type="submit" class="btn btn--secondary">
                                <i class="tio-search"></i>
                            </button>
                        </div>
                    <!-- End Search -->
                    </form>
                    <!-- Unfold -->
                    <div class="hs-unfold initial-89">
                        <select name="category_id" id="category" data-placeholder="{{ translate('messages.select_category') }}"
                                class="js-data-example-ajax form-control set-filter" id="category_id"
                                data-url="{{url()->full()}}" data-filter="category_id">
                            @if($category)
                                <option value="{{$category->id}}" selected>{{$category->name}} ({{$category->position == 0?translate('messages.main'):translate('messages.sub')}})</option>
                            @else
                                <option value="all" selected>{{translate('messages.all_categories')}}</option>
                            @endif
                        </select>
                    </div>
                    <!-- End Unfold -->
                    @if ($toggle_veg_non_veg)
                    <!-- Veg/NonVeg filter -->
                    <div class="col-auto">
                        <select name="category_id" data-url="{{url()->full()}}" data-filter="type" data-placeholder="{{translate('messages.all')}}" class="form-control w-100 max-sm-12 set-filter">
                            <option value="all" {{$type=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                            <option value="veg" {{$type=='veg'?'selected':''}}>{{translate('messages.veg')}}</option>
                            <option value="non_veg" {{$type=='non_veg'?'selected':''}}>{{translate('messages.non_veg')}}</option>
                        </select>
                    </div>
                    <!-- End Veg/NonVeg filter -->
                    @endif
                    <!-- Unfold -->
                    <div class="hs-unfold">
                        <a class="js-hs-unfold-invoker btn btn-white" href="javascript:;"
                            data-hs-unfold-options='{
                            "target": "#showHideDropdown",
                            "type": "css-animation"
                            }'>
                            <i class="tio-table mr-1"></i> {{ translate('Columns') }}
                        </a>

                        <div id="showHideDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('Name') }}</span>
                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_name">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_name" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    <!-- End Checkbox Switch -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('Type') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_type">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_type" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    <!-- End Checkbox Switch -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('Status') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_status">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_status" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('Price') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_price">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_price" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('Action') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_action">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_action" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    data-hs-datatables-options='{
                        "columnDefs": [{
                            "targets": [],
                            "width": "5%",
                            "orderable": false
                        }],
                        "order": [],
                        "info": {
                        "totalQty": "#datatableWithPaginationInfoTotalQty"
                        },

                        "entries": "#datatableEntries",
                        "isResponsive": false,
                        "isShowPaging": false,
                            "paging":false
                    }'>
                    <thead class="thead-light">
                    <tr>
                        <th>{{ translate('messages.sl') }}</th>
                        <th class="w-20p">{{translate('messages.name')}}</th>
                        <th class="w-20p">{{translate('messages.category')}}</th>
                        <th class="text-center">{{translate('messages.price')}}</th>
                        <th class="text-center">{{translate('messages.Recommended')}}</th>
                        <th class="text-center">{{translate('messages.status')}}</th>
                        <th class="text-center">{{translate('messages.action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($foods as $key=>$food)
                    @php( $stock_out = null)

                        <tr>
                            <td>{{$key+$foods->firstItem()}}</td>
                            <td>
                                <a class="media align-items-center" href="{{route('vendor.food.view',[$food['id']])}}">
                                    <img class="avatar avatar-lg mr-3 onerror-image" src="{{ $food['image_full_url'] }}"
                                         data-onerror-image="{{dynamicAsset('public/assets/admin/img/100x100/food-default-image.png')}}" alt="{{$food->name}} image">
                                    <div class="media-body">
                                        <h5 class="text-hover-primary mb-0">{{Str::limit($food['name'],20,'...')}}


                                            @if ($food->stock_type != 'unlimited' &&  $food->item_stock <= 0 )
                                            @php( $stock_out = true)

                                            <span class="badge badge-soft-warning badge-pill font-medium">{{ translate('Out Of Stock') }}</span><span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.Your_main_stock_is_out_of_stock.')}}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="public/img"></span>
                                            @else

                                            <?php

                                                if(isset($food->variations)){
                                                    foreach (json_decode($food->variations,true) as $item) {
                                                        if (isset($item['values']) && is_array($item['values'])) {
                                                            foreach ($item['values'] as $value) {
                                                                if(isset($value['stock_type']) && $value['stock_type'] != 'unlimited' &&   $value['current_stock'] <= 0){
                                                                    $stock_out = true;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                                @if($stock_out)
                                                {{-- <span class="badge badge-soft-warning badge-pill font-medium">{{ translate('Out Of Stock') }}</span> --}}
                                                <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.One_or_more_variations_are_out_of_stock.')}}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="public/img"></span>
                                                @endif
                                            @endif

                                        </h5>
                                    </div>
                                </a>
                            </td>
                            <td>
                                {{ Str::limit(($food?->category?->parent ? $food?->category?->parent?->name : $food?->category?->name )  ?? translate('messages.uncategorize')
                                , 20, '...') }}
                            </td>
                            <td>
                                <div class="text-right mx-auto mw-36px">
                                <!-- Static Symbol -->

                                <!-- Static Symbol -->
                                    {{($food['price'])}}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <div class="mx-auto">
                                        <label class="toggle-switch toggle-switch-sm mr-2"  data-toggle="tooltip" data-placement="top" title="{{ translate('messages.Recommend_to_customers') }}" for="stocksCheckbox{{$food->id}}">
                                            <input type="checkbox" data-url="{{route('vendor.food.recommended',[$food['id'],$food->recommended?0:1])}}" class="toggle-switch-input redirect-url" id="stocksCheckbox{{$food->id}}" {{$food->recommended?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <div class="mx-auto">
                                        <label class="toggle-switch toggle-switch-sm mr-2" data-toggle="tooltip" data-placement="top" title="{{ translate('messages.Change_food_visibility_to_customers') }}" for="statusCheckbox{{$food->id}}">
                                            <input type="checkbox" data-url="{{route('vendor.food.status',[$food['id'],$food->status?0:1])}}" class="toggle-switch-input redirect-url" id="statusCheckbox{{$food->id}}" {{$food->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    @if($stock_out)
                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn " href="#update-stock{{ $food['id'] }}" title="{{ translate('update_stock') }}" data-toggle="modal">
                                        <i class="tio-autorenew"></i>
                                    </a>
                                    @endif
                                    <a class="btn action-btn btn--primary btn-outline-primary"
                                        href="{{route('vendor.food.edit',[$food['id']])}}" title="{{translate('messages.edit_food')}}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                        data-id="food-{{$food['id']}}" data-message="{{ translate('Want to delete this item ?') }}" title="{{translate('messages.delete_food')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                    <form action="{{route('vendor.food.delete',[$food['id']])}}"
                                            method="post" id="food-{{$food['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>



                             {{-- Stock Update Modal --}}
                             <div class="modal fade" id="update-stock{{ $food['id'] }}">
                                <div class="modal-dialog max-w-450px">
                                    <div class="modal-content">
                                        <div class="modal-header px-2 pt-2">
                                            <div></div>
                                            <button type="button" data-dismiss="modal" class="btn p-0">
                                                <i class="tio-clear fs-24"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body pt-2">
                                            <div class="table-rest-info mb-30 align-items-start">
                                                <img src="{{ $food['image_full_url'] }}" class="w-80px">
                                                <div class="info fs-12 text-body">
                                                    <span class="d-block text-title fs-15 mb-2">
                                                    {{ $food['name'] }}
                                                        <span class="rating">
                                                            ({{ round($food->avg_rating,2) }}/5)
                                                        </span>
                                                        @if ($food->veg == 1)

                                                        <span class="badge badge-soft-success rounded-pill">{{ translate('Veg') }}</span>
                                                        @else
                                                        <span class="badge badge-soft-danger rounded-pill">{{ translate('Non_Veg') }}</span>
                                                        @endif
                                                    </span>
                                                    <div>
                                                        {{ translate('Price') }} : <span class="font-medium">{{ \App\CentralLogics\Helpers::format_currency($food['price'])  }}</span> | {{ translate('Discount') }} : <span class="font-medium"> {{ $food->discount_type == 'percent' ?  $food->discount . ' %' :  \App\CentralLogics\Helpers::format_currency($food['discount'])   }}</span>
                                                    </div>
                                                    <div>
                                                        {{ translate('Addons') }}: <span class="font-medium">
                                                            @forelse(\App\Models\AddOn::whereIn('id',json_decode($food['add_ons'],true))->get('name') as $addon)
                                                            {{$addon['name']  }}{{ !$loop->last ? ',' : '.' }}
                                                            @empty
                                                            {{ translate('No_addons_found.') }}
                                                            @endforelse
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="{{ route('vendor.food.updateStock') }}" method="POST" >
                                                @method("post")
                                                @csrf
                                                <input type="hidden" value="{{ $food->id }}"  name="food_id">
                                                <div class="__bg-F8F9FC-card text-left">
                                                    <label class="input-label">
                                                        {{ translate('Main_Stock') }}
                                                    </label>
                                                    <input type="number" step="1" name="item_stock" value="{{ $food->item_stock }}" required min="1" max="99999999999" class="form-control" placeholder="Ex : 50">
                                                </div>

                                                <div class="__bg-F8F9FC-card text-left">
                                            @if (isset($food->variations) && count(json_decode($food->variations,true)) >0 )

                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <h5>{{ translate('Variation') }}</h5>
                                                        </div>
                                                        <div class="col-6">
                                                            <h5>{{ translate('Stock') }}</h5>
                                                        </div>
                                                    </div>
                                            @foreach (json_decode($food->variations,true) as $item)
                                                <div class="row g-1 mb-3">

                                                    <div class="col-12">
                                                        <h6 class="m-0">
                                                            {{ $item['name'] }}
                                                        </h6>
                                                    </div>

                                                    @if (isset($item['values']) && is_array($item['values']))
                                                        @foreach ($item['values'] as $value)
                                                            @if (isset($value['option_id']))
                                                            <div class="col-12">
                                                                <div class="row g-1 align-items-center">
                                                                    <span class="col-6">{{  $value['label']  }} :</span>
                                                                    <div class="col-6">
                                                                        <input class="form-control" required value="{{ $value['current_stock'] }}" type="number" min="1" step="1" max="999999999" name="option[{{ $value['option_id'] }}]"  placeholder="Ex : 50">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @endif
                                                        @endforeach

                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif





                                                </div>

                                                <div class="d-flex justify-content-end gap-3 mt-3">
                                                    <button type="submit" class="btn btn--primary">{{ translate('Update') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>



                    @endforeach
                    </tbody>
                </table>
                @if(count($foods) === 0)
                <div class="empty--data">
                    <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
                <div class="page-area">
                    <table>
                        <tfoot class="border-top">
                        {!! $foods->links() !!}
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- End Table -->
        </div>
        <!-- End Card -->
    </div>

@endsection

@push('script_2')
    <script>
        "use script";
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
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
                '<img class="mb-3 w-7rem" src="{{dynamicAsset('public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">' +
                '<p class="mb-0">{{ translate('No_data_to_show') }}</p>' +
                '</div>'
          }
        });

        $('#datatableSearch').on('mouseup', function (e) {
          let $input = $(this),
            oldValue = $input.val();

          if (oldValue == "") return;

          setTimeout(function(){
            let newValue = $input.val();

            if (newValue == ""){
              // Gotcha
              datatable.search('').draw();
            }
          }, 1);
        });

        $('#toggleColumn_index').change(function (e) {
          datatable.columns(0).visible(e.target.checked)
        })
        $('#toggleColumn_name').change(function (e) {
          datatable.columns(1).visible(e.target.checked)
        })

        $('#toggleColumn_type').change(function (e) {
          datatable.columns(2).visible(e.target.checked)
        })

        $('#toggleColumn_status').change(function (e) {
          datatable.columns(4).visible(e.target.checked)
        })
        $('#toggleColumn_price').change(function (e) {
          datatable.columns(3).visible(e.target.checked)
        })
        $('#toggleColumn_action').change(function (e) {
          datatable.columns(5).visible(e.target.checked)
        })
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $('#category').select2({
            ajax: {
                url: '{{route("vendor.category.get-all")}}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        all:true,
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

        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('vendor.food.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
