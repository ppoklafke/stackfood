@extends('layouts.vendor.app')

@section('title', request()?->type == 'pending' ?  translate('advertisement_pending_list') : translate('Advertisement List'))
@section('advertisement')
active
@endsection

@if (request()?->type == 'pending')

@section('advertisement_pending_list')

@else
@section('advertisement_list')

@endif


active
@endsection

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">



@if ($total_adds == 0)




<h1 class="page-header-title mb-3">{{ translate('Advertisement List') }}</h1>

<div class="card">
    <div class="card-body">
        <div class="text-center max-w-700 mx-auto pt-5">
            <img src="{{dynamicAsset('public/assets/admin/img/advertisement-list.png')}}" class="mw-100 mb-3" alt="">
            <h4 class="mb-2">{{ translate('Advertisement List') }}</h4>
            <p class="mb-4">{{ translate('Uh oh! You didn’t created any advertisement yet') }}!</p>
            <div class="pb-4">
                <a href="{{ route('vendor.advertisement.create') }}" class="btn btn--primary">{{ translate('Create Ads') }}</a>
            </div>
            <hr>
            <div class="max-w-471 mx-auto fs-12 py-4">
                {{ translate('By') }} <strong>{{ translate('Creating Advertisement') }}</strong> {{ translate('you can showcase your items or restaurant to a wider audience through targeted ad campaigns.') }}
            </div>
        </div>
    </div>
</div>



@else



    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-header-title d-flex align-items-center gap-2">
            <img src="{{dynamicAsset('public/assets/admin/img/advertisement.png')}}" alt="">
            {{ translate('messages.Ads_list') }}
            <span class="badge badge-soft-dark ml-2">{{ $adds->total() }}</span>
        </h1>
        <a href="{{ route('vendor.advertisement.create') }}" class="btn btn-primary">  <i class="tio-add"></i> {{ translate('New Advertisement') }}</a>
    </div>
    <!-- Title -->


    <div class="card">

        <div class="card-header py-2 border-0">
            <div class="search--button-wrapper">
            <h5 class="card-title"></h5>
            <form >
                <!-- Search -->
                <div class="input--group input-group input-group-merge input-group-flush">
                    <input id="datatableSearch" type="search" name="search"  value="{{ request()?->search ?? null }}"  class="form-control" placeholder="{{ translate('Search by ads ID or restaurant name') }}" aria-label="{{translate('messages.search_here')}}">
                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                </div>
                <!-- End Search -->
            </form>
            @if (request()?->type != 'pending')
            <div class="select-item min-250">
                <select name="subscription_list" class="form-control js-select2-custom set-filter"
                data-url="{{url()->full()}}" data-filter="ads_type">
                    <option  value="all">{{translate('messages.All Ads')}}</option>
                    <option {{ request()?->ads_type =='running'?'selected':''}} value="running">{{translate('running')}} </option>
                    <option {{request()?->ads_type =='approved'?'selected':''}} value="approved">{{translate('approved')}} </option>
                    <option {{request()?->ads_type =='expired'?'selected':''}} value="expired">{{translate('expired')}} </option>
                    <option {{request()?->ads_type =='denied'?'selected':''}} value="denied">{{translate('denied')}} </option>
                </select>
            </div>
            @endif
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive datatable-custom">
                <table class="font-size-sm table table-borderless table-thead-bordered table-nowrap table-align-middle card-table min-h-225px">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('sl') }}</th>
                            <th >{{translate('Ads ID')}}</th>
                            <th >{{translate('Ads Type')}}</th>
                            <th >{{translate('Ads Title')}}</th>
                            <th >{{translate('Duration')}}</th>
                            <th >{{translate('Status')}}</th>
                            <th >{{translate('Action')}}</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($adds as $key=> $add)

                        <tr>

                            <td>{{$key+$adds->firstItem()}}</td>
                            <td><a href="{{ route('vendor.advertisement.show',$add->id) }}">{{ $add->id }}</a> </td>
                            <td>{{ translate($add?->add_type) }}</td>
                            <td>
                                {{  Str::limit($add?->title, 20, '...') }}
                            </td>

                            <td>
                                {{  \App\CentralLogics\Helpers::date_format($add->start_date) }} - <br> {{  \App\CentralLogics\Helpers::date_format($add->end_date) }}
                            </td>
                            <td>
                                @if ($add->status == 'approved' && $add->active == 1 )
                                <label class="badge badge-soft-primary rounded-pill">{{ translate('messages.running') }}</label>
                                @elseif ($add->status == 'approved' && $add->active == 2 )
                                <label class="badge badge-soft-success rounded-pill">{{ translate('messages.approved') }}</label>
                                @elseif ($add->status == 'paused' && $add->active == 1 )
                                <label class="badge badge-soft-warning rounded-pill">{{ translate('messages.paused') }}</label>
                                @elseif (in_array($add->status ,['denied','expired'] ))
                                <label class="badge badge-soft-danger rounded-pill">{{ translate($add->status) }}</label>
                                @elseif ($add->active == 0)
                                <label class="badge badge-soft-secondary rounded-pill">{{ translate('messages.Expired') }}</label>
                                @else
                                <label class="badge badge-soft-info rounded-pill">{{ translate($add->status) }}</label>
                                @endif

                            </td>

                            <td>
                                <div class="dropdown dropdown-2">
                                    <button type="button" class="bg-transparent border rounded px-2 py-1 title-color" data-toggle="dropdown" aria-expanded="false">
                                        <i class="tio-more-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu" dir="ltr">
                                        <a class="dropdown-item d-flex gap-2 align-items-center" href="{{ route('vendor.advertisement.show',$add->id) }}">
                                            <i class="tio-visible-outlined"></i>
                                            {{ translate('View Ads') }}
                                        </a>

                                        @if ($add->active == 0 || in_array($add->status ,['pending']))
                                        <a class="dropdown-item d-flex gap-2 align-items-center" href="{{ route('vendor.advertisement.edit',$add->id) }}">
                                            <i class="tio-edit"></i>
                                            {{ translate('Edit & Resubmit Ads') }}
                                            </a>

                                            @else
                                            <a class="dropdown-item d-flex gap-2 align-items-center new-dynamic-submit-model" href="#"

                                            id="data-edit-{{ $add->id }}"
                                            data-id="data-edit-{{ $add->id }}"

                                            data-title="{{translate('Do You Want to Edit?')}}"
                                            data-text="<p>{{translate('Your ad is running. If you edit this ad, it will be listed for pending and needs to be approved by the Admin. After the approval, it will be running again.')}}</p>"
                                            data-image="{{dynamicAsset('public/assets/admin/img/modal/package-status-disable.png')}}"
                                            data-type="resume"
                                            data-btn_class = "btn-primary"
                                            data-success_btn_text = "{{ translate('Yes, Edit') }}"


                                            >
                                            <i class="tio-edit"></i>
                                            {{ translate('Edit Ads') }}
                                        </a>
                                        <form  id="data-edit-{{ $add->id }}_form" action="{{ route('vendor.advertisement.edit',$add->id) }}" method="get">
                                        </form>
                                        @endif




                                        @if($add->status == 'paused')
                                            <a class="dropdown-item d-flex gap-2 align-items-center new-dynamic-submit-model"


                                            id="data-add-{{ $add->id }}"
                                            data-id="data-add-{{ $add->id }}"

                                            data-title="{{translate('Are you sure you want to Resume the request?')}}"
                                            data-text="<p>{{translate('This ad will be run again and will show in the user app & websites.')}}</p>"
                                            data-image="{{dynamicAsset('public/assets/admin/img/modal/resume.png')}}"
                                            data-type="resume"
                                            data-btn_class = "btn-primary"


                                            href="#">
                                                <i class="tio-pause-circle"></i>
                                                {{ translate('Resume_Ads') }}
                                            </a>

                                            <form  id="data-add-{{ $add->id }}_form" action="{{ route('vendor.advertisement.status',['status' => 'approved' ,'id' => $add->id]) }}" method="get">
                                                @csrf
                                                @method('get')
                                                <input type="hidden"  name="status" value="approved">
                                                <input type="hidden"  name="id" value="{{ $add->id }}">
                                            </form>




                                        @elseif($add->status == 'approved' && $add->active == 1)
                                        <a class="dropdown-item d-flex gap-2 align-items-center new-dynamic-submit-model"
                                        id="data-add-{{ $add->id }}"
                                        data-id="data-add-{{ $add->id }}"
                                        data-title="{{translate('Are you sure you want to Pause the request?')}}"
                                        data-text="<p>{{translate('This ad will be pause and not show in the user app & websites.')}}</p>"
                                        data-image="{{dynamicAsset('public/assets/admin/img/modal/pause.png')}}"
                                        data-type="pause"

                                        href="#">
                                            <i class="tio-pause-circle"></i>
                                            {{ translate('Pause_Ads') }}
                                            </a>

                                            <form  id="data-add-{{ $add->id }}_form" action="{{ route('vendor.advertisement.status',['status' => 'paused' ,'id' => $add->id]) }}" method="get">
                                                @csrf
                                                @method('get')
                                                <input type="hidden"  name="pause_note" id="data-add-{{ $add?->id }}_note">
                                                <input type="hidden"  name="status" value="paused">
                                                <input type="hidden"  name="id" value="{{ $add->id }}">
                                            </form>
                                            @endif

                                        <a class="dropdown-item d-flex gap-2 align-items-center" href="{{ route('vendor.advertisement.copyAdd', $add->id) }}" >
                                            <i class="tio-copy"></i>
                                            {{ translate('Copy_Ads') }}
                                            </a>


                                        <a class="dropdown-item d-flex gap-2 align-items-center new-dynamic-submit-model"
                                        id="delete-add-{{ $add->id }}"
                                            data-id="delete-add-{{ $add->id }}"
                                            @if ($add->status == 'approved' && $add->active == 1)
                                                data-title="{{translate('You can’t delete the ad')}}"
                                                data-text="<p>{{translate('This Advertisement is currently running, To delete this ad from the list, please  resume the Ad first . Once the status is updated, you can proceed with deletion')}}</p>"
                                                data-image="{{dynamicAsset('public/assets/admin/img/modal/package-status-disable.png')}}"
                                                data-type="warning"
                                            @else
                                                data-type="delete"
                                                data-title="{{translate('Confirm Ad Deletion')}}"
                                                data-text="<p>{{translate('Deleting this ad will remove it permanently. Are you sure you want to proceed?')}}</p>"
                                                data-image="{{dynamicAsset('public/assets/admin/img/modal/delete-icon.png')}}"
                                            @endif
                                            >
                                            <i class="tio-delete"></i>
                                            {{ translate('Delete_Ads') }}
                                            </a>
                                            <form  id="delete-add-{{ $add->id }}_form" action="{{ route('vendor.advertisement.destroy',$add->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                            </form>



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
                            {!! $adds->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="created-sucessful-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="tio-clear fs-24"></i>
                </button>
            </div>
            <div class="modal-body pt-0">
                <div class="text-center max-w-700 mx-auto">
                    <img src="{{dynamicAsset('public/assets/admin/img/created.png')}}" class="mw-100 mb-4" alt="">
                    <h4 class="mb-2">{{ translate('Ad Created Successfully!') }}</h4>
                    <p class="mb-4 fs-12 mx-auto max-w-520">{{ translate('Congratulations on creating your ad! It’s now awaiting approval. To finalize the process & make payment arrangements, please contact our')}} <a class="text--underline" href="mailto:{{\App\CentralLogics\Helpers::get_settings('email_address')  }}">{{ translate('Admin directly.') }}</a>
                   {{   translate(' We look forward to helping you boost your visibility & reach more customers') }}</p>
                    <div class="pb-4">
                        <a href="#" data-dismiss="modal"  class="btn btn--primary">{{ translate('Okay') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif



@endsection

@push('script_2')
<script>
    @if (request()?->has('new_ad'))
    $('#created-sucessful-modal').modal('show')
        var url = new URL(window.location.href);
        var searchParams = new URLSearchParams(url.search);
        searchParams.delete('new_ad');
        var newUrl = url.origin + url.pathname + '?' + searchParams.toString();
        if (!searchParams.toString()) {
            newUrl = url.origin + url.pathname;
        }
        window.history.replaceState(null, '', newUrl);
    @endif

</script>
@endpush
