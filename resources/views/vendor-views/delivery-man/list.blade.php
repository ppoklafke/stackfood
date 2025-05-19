@extends('layouts.vendor.app')

@section('title',translate('messages.deliverymen'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.deliverymen')}}</h1>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title">{{translate('messages.deliveryman_list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$delivery_men->total()}}</span></h5>
                    <form >

                        <div class="input-group input--group">
                            <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}"  name="search" class="form-control" placeholder="{{ translate('messages.Ex :') }} Delivery Men Name or Phone Number" aria-label="{{translate('messages.search')}}">
                            <button type="submit" class="btn btn--secondary">
                                <i class="tio-search"></i>
                            </button>

                        </div>
                        <!-- End Search -->
                    </form>
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                            "order": [],
                            "orderCellsTop": true,
                            "paging":false
                        }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="text-capitalize">{{ translate('messages.sl') }}</th>
                        <th class="text-capitalize">{{translate('messages.name')}}</th>
                        <th class="text-capitalize">{{translate('messages.availability_status')}}</th>
                        <th class="text-capitalize">{{translate('messages.phone')}}</th>
                        <th class="text-capitalize">{{ translate('Active Orders') }}</th>
                        <th class="text-capitalize text-center">{{translate('messages.action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($delivery_men as $key=>$dm)
                        <tr>
                            <td>{{$key+$delivery_men->firstItem()}}</td>
                            <td>
                                <a class="media align-items-center" href="{{route('vendor.delivery-man.preview',[$dm['id']])}}">
                                    <img class="avatar avatar-lg mr-3 onerror-image"
                                         data-onerror-image="{{dynamicAsset('public/assets/admin/img/160x160/img1.jpg')}}"
                                         src="{{ $dm['image_full_url'] }}"
                                         alt="{{$dm['f_name']}} {{$dm['l_name']}}">
                                    <div class="media-body">
                                        <h5 class="text-hover-primary mb-0">{{$dm['f_name'].' '.$dm['l_name']}}</h5>
                                        <span class="rating">
                                            <i class="tio-star"></i> {{count($dm->rating)>0?number_format($dm->rating[0]->average, 1, '.', ' '):0}}
                                        </span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div>
                                    <!-- Status -->
                                    {{translate('messages.Currenty_Assigned_Orders')}}
                                     : {{$dm->current_orders}}
                                    <!-- Status -->
                                </div>
                                @if($dm->application_status == 'approved')
                                    @if($dm->active)
                                    <div>
                                        {{translate('messages.Active_Status')}}   : <strong class="text-primary text-capitalize">{{translate('messages.online')}}</strong>
                                    </div>
                                    @else
                                    <div>
                                        {{translate('messages.Active_Status')}} : <strong class="text-secondary text-capitalize">{{translate('messages.offline')}}</strong>
                                    </div>
                                    @endif
                                @elseif ($dm->application_status == 'denied')
                                    <div>
                                        {{translate('messages.Active_Status')}} : <strong class="text-danger text-capitalize">{{translate('messages.denied')}}</strong>
                                    </div>
                                @else
                                    <div>
                                        {{translate('messages.Active_Status')}} : <strong class="text-info text-capitalize">{{translate('messages.not_approved')}}</strong>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a class="deco-none" href="tel:{{$dm['phone']}}">{{$dm['phone']}}</a>
                            </td>
                            <td>
                                <div class="text-right max-90px">
                                    {{ $dm->orders ? count($dm->orders):0 }}
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn btn--primary btn-outline-primary action-btn" href="{{route('vendor.delivery-man.edit',[$dm['id']])}}" title="{{translate('messages.edit')}}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="delivery-man-{{$dm['id']}}" data-message="{{ translate('Want to remove this deliveryman') }}" title="{{translate('messages.delete')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                    <form action="{{route('vendor.delivery-man.delete',[$dm['id']])}}" method="post" id="delivery-man-{{$dm['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($delivery_men) === 0)
                <div class="empty--data">
                    <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
                <div class="page-area">
                    <table>
                        <tfoot>
                        {!! $delivery_men->links() !!}
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
    <script src="{{dynamicAsset('public/assets/admin/js/view-pages/datatable-search.js')}}"></script>
@endpush
