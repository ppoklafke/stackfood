@extends('layouts.admin.app')

@section('title','Advertisement Requests')
@section('advertisement')
active
@endsection
@section('advertisement_request')
active
@endsection

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Title -->
    <h1 class="page-header-title mb-3 d-flex align-items-center gap-2">
        <img src="{{dynamicAsset('public/assets/admin/img/advertisement.png')}}" alt="">
        {{ translate('messages.Advertisement_Requests') }}
        <span class="badge badge-soft-dark ml-2">{{ $count }}</span>
    </h1>

    <!-- Nav Menus -->
    <ul class="nav nav-tabs border-0 nav--tabs nav--pills mb-4">
        <li class="nav-item">
            <a class="nav-link  {{ !request()?->type  ? 'active' : '' }}" href="{{ route('admin.advertisement.requestList') }}">{{ translate('New_Request') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()?->type == 'update-requests' ? 'active' : '' }} " href="{{ route('admin.advertisement.requestList',['type'=> 'update-requests']) }}">{{ translate('Update_Request') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()?->type == 'denied-requests' ? 'active' : '' }} " href="{{ route('admin.advertisement.requestList',['type'=> 'denied-requests']) }}">{{ translate('Denied_Requests') }}</a>
        </li>
    </ul>



    <div class="card">


        <div class="card-header py-2 border-0">
            <div class="search--button-wrapper">
                <h5 class="card-title"> {{ translate('messages.Advertisement') }} <span class="badge badge-soft-dark ml-2">{{ $adds->total() }}</span></h5>
                <form>
                    <!-- Search -->
                    <div class="input--group input-group input-group-merge input-group-flush">
                        <input id="datatableSearch" type="search" name="search" value="{{ request()?->search ?? null }}" class="form-control" placeholder="{{ translate('Search by ads ID or restaurant name') }}" aria-label="{{translate('messages.search_here')}}">
                        <input type="hidden" value="{{ request()?->type }}" name='type'>
                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                    </div>
                    <!-- End Search -->
                </form>

            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive datatable-custom">
                <table class="font-size-sm table table-borderless table-thead-bordered table-nowrap table-align-middle card-table min-h-225px">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('sl') }}</th>
                            <th>{{translate('Ads ID')}}</th>
                            <th >{{translate('Ads Title')}}</th>
                            <th>{{translate('Restaurant Info')}}</th>
                            <th>{{translate('Ads Type')}}</th>
                            <th>{{translate('Duration')}}</th>
                            <th>{{translate('Action')}}</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($adds as $key=> $add)

                        <tr>

                            <td>{{$key+$adds->firstItem()}}</td>
                            <td> <a href="{{ route('admin.advertisement.show',[$add->id ,'request_page_type'=> request()?->type ?? 'pending-requests']) }}"> {{ $add->id }}</a></td>
                            <td>{{ Str::limit($add->title, 20) }}</td>
                            <td>
                                <a class="media align-items-center text-body" href="{{route('admin.restaurant.view', $add?->restaurant_id)}}">
                                    <img class="avatar avatar-lg mr-3" src="{{ $add->restaurant['logo_full_url'] ?? dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}" alt="">
                                    <div class="media-body">
                                        <h5 class="mb-0">{{ $add?->restaurant?->name }}</h5>
                                        <small class="text-body">{{ $add?->restaurant?->email }}</small>
                                    </div>
                                </a>
                            </td>

                            <td>{{ translate($add?->add_type) }}</td>
                            <td>
                                {{ \App\CentralLogics\Helpers::date_format($add->start_date) }} - <br> {{ \App\CentralLogics\Helpers::date_format($add->end_date) }}
                            </td>


                            <td>
                                <div class="dropdown dropdown-2">
                                    <button type="button" class="bg-transparent border rounded px-2 py-1 title-color" data-toggle="dropdown" aria-expanded="false">
                                        <i class="tio-more-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu "dir="ltr">
                                        <a class="dropdown-item d-flex gap-2 align-items-center" href="{{ route('admin.advertisement.show',[$add->id ,'request_page_type'=> request()?->type ?? 'pending-requests']) }}">
                                            <i class="tio-visible-outlined"></i>
                                            {{ translate('View Ads') }}
                                        </a>

                                        @if ($add->status == 'denied' || $add->active == 0)
                                        <a class="dropdown-item d-flex gap-2 align-items-center" href="{{ route('admin.advertisement.edit',[$add->id ,'request_page_type'=> request()?->type ?? 'pending-requests']) }}">
                                            <i class="tio-edit"></i>
                                            {{ translate('Edit & Resubmit Ads') }}
                                        </a>


                                        @else
                                        <a class="dropdown-item d-flex gap-2 align-items-center" href="{{ route('admin.advertisement.edit',[$add->id ,'request_page_type'=> request()?->type ?? 'pending-requests']) }}">
                                            <i class="tio-edit"></i>
                                            {{ translate('Edit Ads') }}
                                        </a>
                                        @endif

                                        @if ($add->status == 'pending')


                                        <a class="dropdown-item d-flex gap-2 align-items-center new-dynamic-submit-model" id="data-add-{{ $add->id }}" data-id="data-add-{{ $add->id }}" data-title="{{translate('Are you sure you want to Cancel advertisement the request?')}}" data-text="<p>{{translate('You will lost the Restaurant ads request.')}}</p>" data-image="{{dynamicAsset('public/assets/admin/img/modal/deny.png')}}" data-type="deny" data-btn_class="btn-primary" data-2nd_btn_text="{{ translate('messages.Cancel') }}" href="#">
                                            <i class="tio-clear-circle-outlined"></i>
                                            {{ translate('Cancel_Ads') }}
                                        </a>

                                        <form id="data-add-{{ $add->id }}_form" action="{{ route('admin.advertisement.status',['status' => 'paused' ,'id' => $add->id]) }}" method="get">
                                            @csrf
                                            @method('get')
                                            <input type="hidden" name="cancellation_note" id="data-add-{{ $add?->id }}_note">
                                            <input type="hidden" name="status" value="denied">
                                            <input type="hidden" name="id" value="{{ $add->id }}">
                                        </form>






                                        @endif

                                        @if ($add->status != 'pending')
                                        <a class="dropdown-item d-flex gap-2 align-items-center" href="{{ route('admin.advertisement.destroy',$add->id) }}">
                                            <i class="tio-delete"></i>
                                            {{ translate('Delete_Ads') }}
                                        </a>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
                @if(count($adds) === 0)
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
                            {!! $adds->withQueryString()->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')

@endpush
