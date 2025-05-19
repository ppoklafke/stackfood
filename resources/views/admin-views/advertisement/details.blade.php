@extends('layouts.admin.app')

@section('title','Advertisement Details')

@section('advertisement')
active
@endsection
@if (isset($request_page_type))
@section('advertisement_request')
@else
@section('advertisement_list')
@endif
active
@endsection
@push('css_or_js')
    <link rel="stylesheet" type="text/css" href="{{dynamicAsset('public/assets/admin/css/daterangepicker.css')}}"/>
@endpush

@section('content')
<div class="content container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h1 class="page-header-title m-0 d-flex align-items-center gap-2">
            <img src="{{dynamicAsset('public/assets/admin/img/advertisement.png')}}" alt="">
            {{ translate('Ads Details') }}
        </h1>
        <div class="d-flex gap-1">

            @if ($previousId)

            <a href="{{ route('admin.advertisement.show', [$previousId]) }}"  data-toggle="tooltip"
                data-placement="top" title="{{ translate('Previous_advertisement') }}" class="arrow-icon">
                <i class="tio-chevron-left"></i>
                </a>
            @endif




                @if ($nextId)
                <a href="{{ route('admin.advertisement.show', [$nextId] ) }}"  data-toggle="tooltip"
                data-placement="top" title="{{ translate('next_advertisement') }}" class="arrow-icon">
                    <i class="tio-chevron-right"></i>
                </a>

                @endif
        </div>
    </div>
    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card mb-3 h-100">
                <div class="card-body p-3 p-sm-4 fs-12">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h4>{{ translate('Ads_ID_#') }}{{ $advertisement->id }}</h4>
                            <p class="d-flex gap-2 align-items-center mb-0">
                                <span class="w-80px">{{ translate('Ad Placed') }}</span>
                                <span class="mx-1">:</span>
                                <span class="font-medium text-title">{{\App\CentralLogics\Helpers::time_date_format($advertisement->created_at)  }}</span>
                            </p>
                            <p class="d-flex gap-2 align-items-center mb-0">
                                <span class="w-80px">{{ translate('Ad Type') }}  </span>
                                <span class="mx-1">:</span>
                                <span class="font-medium text-title">{{ translate($advertisement->add_type) }}</span>
                            </p>
                            <p class="d-flex gap-2 align-items-center mb-0">
                                <span class="w-80px">{{ translate('Duration') }} </span>
                                <span class="mx-1">:</span>
                                <span class="font-medium text-title">{{ \App\CentralLogics\Helpers::date_format($advertisement->start_date).' - ' .\App\CentralLogics\Helpers::date_format($advertisement->end_date) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-20">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-md-end approve-buttons">
                                    @if ( $advertisement->status === 'pending')


                                    <a type="button"  class="btn btn-outline-danger new-dynamic-submit-model"
                                    id="data-add-{{ $advertisement->id }}"
                                    data-id="data-add-{{ $advertisement->id }}"
                                    data-title="{{translate('Are you sure you want to deny the request?')}}"
                                    data-text="<p>{{translate('You will lost the Restaurant ads request.')}}</p>"
                                    data-image="{{dynamicAsset('public/assets/admin/img/modal/deny.png')}}"
                                    data-type="deny"
                                    data-btn_class = "btn-primary"
                                    data-2nd_btn_text = "{{ translate('messages.Cancel') }}"

                                    href="#">
                                    <i class="tio-clear"></i>
                                    <span>{{ translate('Deny') }}</span>
                                        </a>

                                        <form  id="data-add-{{ $advertisement->id }}_form" action="{{ route('admin.advertisement.status',['status' => 'paused' ,'id' => $advertisement->id]) }}" method="get">
                                            @csrf
                                            @method('get')
                                            <input type="hidden"  name="cancellation_note" id="data-add-{{ $advertisement?->id }}_note">
                                            <input type="hidden"  name="status" value="denied">
                                            <input type="hidden"  name="id" value="{{ $advertisement->id }}">
                                        </form>

                                    <a type="button" class="btn btn-outline-success" href="#"
                                        @if ($advertisement->active == 0)
                                        data-toggle="modal" data-target="#exp-approve-model"

                                        @else

                                        data-toggle="modal" data-target="#confirm-approve-model"
                                        @endif
                                         >

                                        <i class="tio-done"></i>
                                        <span>{{ translate('Approve') }}</span>
                                        </a>
                                    @elseif($advertisement->status === 'denied')
                                        <a type="button" class="btn btn-outline-success" href="#"


                                            @if ($advertisement->active == 0)
                                            data-toggle="modal" data-target="#exp-approve-model"

                                            @else

                                            data-toggle="modal" data-target="#confirm-approve-model"
                                            @endif

                                            >
                                            <i class="tio-done"></i>
                                            <span>{{ translate('Approve') }}</span>
                                        </a>
                                    @endif

                                    <a href="{{  route('admin.advertisement.edit',[$advertisement->id ,'request_page_type'=> isset($request_page_type) ]) }}" class="btn btn--primary">
                                        <i class="tio-edit"></i>
                                        <span>{{ translate('Edit Ads') }}</span>
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-2 gap-lg-3 align-items-lg-end">
                                <p class="d-flex gap-2 align-items-center mb-0 justify-content-md-end">
                                    <span>{{ translate('Status') }}: </span>
                                    @if ($advertisement->status == 'approved' && $advertisement->active == 1 )
                                    <span class="px-2  badge badge-soft-primary rounded-pill">{{ translate('messages.running') }}</span>
                                    @elseif ($advertisement->status == 'approved' && $advertisement->active == 2 )
                                    <span class="px-2  badge badge-soft-success rounded-pill">{{ translate('messages.approved') }}</span>
                                    @elseif ($advertisement->status == 'paused' && $advertisement->active == 1 )
                                    <span class="px-2  badge badge-soft-warning rounded-pill">{{ translate('messages.paused') }}</span>
                                    @elseif (in_array($advertisement->status ,['denied','expired'] ))
                                    <span class="px-2  badge badge-soft-danger rounded-pill">{{ translate($advertisement->status) }}</span>
                                    @elseif ($advertisement->active == 0)
                                    <span class="px-2  badge badge-soft-secondary rounded-pill">{{ translate('messages.Expired') }}</span>
                                    @else
                                    <span class="px-2  badge badge-soft-info rounded-pill">{{ translate($advertisement->status) }}</span>
                                    @endif


                                </p>
                                <p class="d-flex gap-2 align-items-center mb-0 justify-content-md-end">
                                    <span>{{ translate('Payment Status') }}: </span>
                                    @if ($advertisement->is_paid == 1)
                                    <span class="font-semibold text-success">{{ translate('Paid') }}</span>
                                    @else
                                    <span class="font-semibold text-danger">{{ translate('Unpaid') }}</span>

                                    @endif
                                </p>
                            </div>
                            </div>
                            <div class="col-lg-12">
                        @if ( ($advertisement->status == 'denied' && $advertisement->cancellation_note  != null) || ($advertisement->status == 'paused' && $advertisement->pause_note  != null) )
                            <div class="border rounded d-flex flex-wrap p-2 mb-4 gap-1 bg--3">
                                <div class="text-danger font-bold">
                                    {{ $advertisement->status == 'denied' ? translate('#_Cancellation Note') : translate('#_Pause Note')  }} :
                                </div>
                                <div class="flex-grow">{{  $advertisement->status == 'denied' ? $advertisement->cancellation_note : $advertisement->pause_note }}</div>
                            </div>

                            @endif
                        <hr class="m-0">
                    </div>








                    <div class="col-lg-5">
                        <div class="js-nav-scroller hs-nav-scroller-horizontal">

                            <ul class="nav nav-tabs mb-3 border-0">
                                <li class="nav-item">
                                    <a class="nav-link text--black lang_link active"
                                    href="#"
                                    id="default-link">{{translate('messages.default')}}</a>
                                </li>
                                @foreach ($language as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link text--black lang_link"
                                            href="#"
                                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                        </li>
                                @endforeach
                            </ul>
                        </div>
                            <div class="lang_form" id="default-form">
                                <h4 class="mb-2">{{ translate('Title') }}:</h4>
                                <p class="">{{ $advertisement?->getRawOriginal('title') }}</p>
                                <h4 class="mb-2">{{ translate('Description') }}:</h4>
                                <p class="m-0">{{ $advertisement?->getRawOriginal('description') }}</p>

                            </div>





                            @foreach ($language as $lang)
                            <?php
                                if(count($advertisement['translations'])){
                                    $translate = [];
                                    foreach($advertisement['translations'] as $t)
                                    {
                                        if($t->locale == $lang && $t->key=="title"){
                                            $translate[$lang]['title'] = $t->value;
                                        }
                                        if($t->locale == $lang && $t->key=="description"){
                                            $translate[$lang]['description'] = $t->value;
                                        }
                                    }
                                }
                            ?>


                                <div class="d-none lang_form" id="{{ $lang }}-form">

                                    <h4 class="mb-2">{{ translate('Title') }}:</h4>
                                    <p class="">{{$translate[$lang]['title']??'------------'}}</p>
                                    <h4 class="mb-2">{{ translate('Description') }}:</h4>
                                    <p class="m-0">{{$advertisement?->getRawOriginal('description') }}</p>

                                </div>

                    @endforeach
                        </div>






                        <div class="col-lg-7">
                            @if($advertisement?->add_type == 'video_promotion')
                            <div class="d-flex gap-3 flex-wrap flex-sm-nowrap">
                                <div class="w-100">
                                    <h4 class="mb-2">{{ translate('Video') }}</h4>
                                    <div class="img-wrap max-w-260px position-relative rounded overflow-hidden before-content" data-toggle="modal" data-target="#video-modal">
                                        <video src="{{ $advertisement?->video_attachment_full_url }}" controls class="w-100 rounded"></video>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="d-flex gap-3 flex-wrap flex-sm-nowrap">
                                <div class="w-100 add-profile-image">
                                    <h4 class="mb-2">{{ translate('Profile Image') }}</h4>
                                    <div class="cursor-pointer profile_image_view img-wrap max-w-130px">
                                        <img src="{{ $advertisement?->profile_image_full_url }}" class="w-100 rounded object-cover aspect-1-1">
                                    </div>
                                </div>
                                <div class="w-100 add-profile-banner">
                                    <h4 class="mb-2">{{ translate('Cover Image') }}</h4>
                                    <div class="cursor-pointer cover_image_view img-wrap max-w-260px">
                                        <img src="{{ $advertisement?->cover_image_full_url }}" class="w-100 rounded object-cover aspect-2-1">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="h-100 d-flex flex-column gap-3">
                <div class="card flex-grow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">{{ translate('Advertisement Setup') }}</h3>
                        <div class="form-group">
                            <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                <span class="line--limit-1">{{ translate('Paid Status') }}</span>
                                <input type="checkbox" id="is_paid" value="1" name="is_paid" data-id="is_paid" data-type="toggle" data-image-on="{{dynamicAsset('public/assets/admin/img/modal/dm-tips-on.png')}}" data-image-off="{{dynamicAsset('public/assets/admin/img/modal/dm-tips-off.png')}}" data-title-on="{{ translate('messages.Are_you_sure?') }}" data-title-off="{{ translate('messages.Are_you_sure?') }}" data-text-on="<p>{{ translate('You_want_to_marked_this_advertisment_as_Paid.') }}</p>" data-text-off="<p>{{ translate('You_want_to_marked_this_advertisment_as_Unpaid.') }}</p>" class="status toggle-switch-input dynamic-checkbox" {{ $advertisement?->is_paid == 1 ? 'checked'  : '' }} >
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>

                        <form action="{{route('admin.advertisement.paidStatus')}}" id="is_paid_form" method="get">
                            <input type="hidden" name="add_id" value="{{  $advertisement?->id }}">
                        </form>
                            @if (!in_array($advertisement->status ,['denied','pending']))

                            <div class="mb-20">
                                <label class="form-label">{{ translate('Ads Status') }}</label>
                                {{-- <select class="form-control js-select" name="ads_status">
                                    <option disabled  >{{ translate('Change_sta') }}</option>
                                    <option value="Running">{{ translate('Running') }}</option>
                                    <option value="approved">{{ translate('Approved') }}</option>
                                </select> --}}


                                @if($advertisement->status == 'paused')
                                <a class="btn btn-soft-primary justify-content-center d-flex gap-2 align-items-center new-dynamic-submit-model"


                                id="data-add-{{ $advertisement->id }}"
                                data-id="data-add-{{ $advertisement->id }}"

                                data-title="{{translate('Are you sure you want to Resume the request?')}}"
                                data-text="<p>{{translate('This ad will be run again and will show in the user app & websites.')}}</p>"
                                data-image="{{dynamicAsset('public/assets/admin/img/modal/resume.png')}}"
                                data-type="resume"
                                data-btn_class = "btn-primary"


                                href="#">
                                    <i class="tio-pause-circle"></i>
                                    {{ translate('Resume_Ads') }}
                                </a>

                                <form  id="data-add-{{ $advertisement->id }}_form" action="{{ route('admin.advertisement.status',['status' => 'approved' ,'id' => $advertisement->id]) }}" method="get">
                                    @csrf
                                    @method('get')
                                    <input type="hidden"  name="status" value="approved">
                                    <input type="hidden"  name="id" value="{{ $advertisement->id }}">
                                </form>

                            @elseif($advertisement->status == 'approved' && ($advertisement->active == 1 || $advertisement->active == 2 ))
                            <a class="btn btn-soft-danger justify-content-center d-flex gap-2 align-items-center new-dynamic-submit-model"
                            id="data-add-{{ $advertisement->id }}"
                            data-id="data-add-{{ $advertisement->id }}"
                            data-title="{{translate('Are you sure you want to Pause the request?')}}"
                            data-text="<p>{{translate('This ad will be pause and not show in the user app & websites.')}}</p>"
                            data-image="{{dynamicAsset('public/assets/admin/img/modal/pause.png')}}"
                            data-type="pause"

                            href="#">
                                <i class="tio-pause-circle"></i>
                                {{ translate('Pause_Ads') }}
                                </a>

                                <form  id="data-add-{{ $advertisement->id }}_form" action="{{ route('admin.advertisement.status',['status' => 'paused' ,'id' => $advertisement->id]) }}" method="get">
                                    @csrf
                                    @method('get')
                                    <input type="hidden"  name="pause_note" id="data-add-{{ $advertisement?->id }}_note">
                                    <input type="hidden"  name="status" value="paused">
                                    <input type="hidden"  name="id" value="{{ $advertisement->id }}">
                                </form>
                            @endif



                            </div>
                            @endif

                        <div>
                            <label class="form-label">{{ translate('Validity') }}</label>
                            <div class="position-relative">
                                <i class="tio-calendar-month icon-absolute-on-right"></i>
                                <input type="text" class="form-control h-45 position-relative bg-transparent" value="{{ Carbon\Carbon::parse($advertisement?->start_date)->format('m/d/Y')  . ' - '.  Carbon\Carbon::parse($advertisement?->end_date)->format('m/d/Y')  }}" name="dates" placeholder="Select Validation Date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card flex-grow">
                    <div class="card-body">
                        <h5 class="card-title mb-3 align-items-center gap-2 text-title">
                            <span class="card-header-icon">
                                <i class="tio-shop"></i>
                            </span>
                            <span>{{ translate('Restaurant info') }}</span>
                        </h5>
                        <a href="{{route('admin.restaurant.view', $advertisement->restaurant_id)}}" class="media align-items-start deco-none resturant--information-single">
                            <div class="avatar avatar-circle">
                                <img class="avatar-img w-75px" src="{{ $advertisement->restaurant['logo_full_url'] ?? dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}" alt="image">

                            </div>
                            <div class="media-body pl-3">
                                <span class="fz--14px text-title font-semibold text-hover-primary d-block">
                                    {{ $advertisement?->restaurant?->name }}
                                    </span>
                                    <span class="text-body">
                                        <strong class="text-title font-semibold">
                                            {{ $advertisement?->restaurant?->total_order }}

                                            </strong>
                                            {{ translate('Orders served') }}
                                            </span>
                                            <span class="text-title font-semibold d-block">
                                                <i class="tio-call-talking-quiet"></i> {{ $advertisement?->restaurant?->phone }}
                                </span>
                                <span class="text-title">
                                    <i class="tio-poi"></i> {{ $advertisement?->restaurant?->address }}
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="video-modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header px-4 pt-4">
                    <h4 class="modal-title">{{ translate('Video Preview') }}</h4>
                    <button type="button" data-dismiss="modal" class="btn p-0">
                        <i class="tio-clear fs-24"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <video src="{{ $advertisement?->video_attachment_full_url }}" controls class="w-100 rounded d-flex"></video>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="imagemodal_profile" tabindex="-1"
    role="dialog" aria-labelledby="order_proof"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"
                    id="order_proof">
                    {{ translate('Profile Image') }}</h4>
                <button type="button" class="close"
                    data-dismiss="modal"><span
                        aria-hidden="true">&times;</span><span
                        class="sr-only">{{ translate('messages.cancel') }}</span></button>
            </div>
            <div class="modal-body">
                <img src="{{ $advertisement?->profile_image_full_url }}"
                    class="initial--22 w-100">
            </div>

            <div class="modal-footer">
                <a class="btn btn-primary" data-dismiss="modal"
                   href="#">
                    {{ translate('messages.Close') }}
                </a>
            </div>
        </div>
    </div>
</div>
    <div class="modal fade" id="imagemodal_cover" tabindex="-1"
    role="dialog" aria-labelledby="order_proof"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"
                    id="order_proof">
                    {{ translate('Cover Image') }}</h4>
                <button type="button" class="close"
                    data-dismiss="modal"><span
                        aria-hidden="true">&times;</span><span
                        class="sr-only">{{ translate('messages.cancel') }}</span></button>
            </div>
            <div class="modal-body">
                <img src="{{ $advertisement?->cover_image_full_url }}"
                    class="initial--22 w-100">
            </div>

            <div class="modal-footer">
                <a class="btn btn-primary" data-dismiss="modal"
                   href="#">
                    {{ translate('messages.Close') }}
                </a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exp-approve-model">
    <div class="modal-dialog modal-dialog-centered status-warning-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true" class="tio-clear"></span>
                </button>
            </div>
            <div class="modal-body pb-5 pt-0">
                <div class="max-349 mx-auto mb-20">
                    <div>
                        <div class="text-center">
                            <img src="{{  dynamicAsset('public/assets/admin/img/modal/timeout.png') }}" class="mb-20">
                            <h5 class="modal-title"></h5>
                        </div>
                        <div class="text-center" >
                            <h3 > {{ translate('This advertisement is already expired.') }}</h3>
                            <div > <p>{{ translate('After approval this Advertisement will automatically show in the expired list as the duration is already over.') }}</h3></p></div>
                        </div>

                        </div>

                    <div class="btn--container justify-content-center">
                            <a href="{{  route('admin.advertisement.edit',[$advertisement->id ,'request_page_type'=> isset($request_page_type) ]) }}"  class="btn btn-success min-w-120" >{{translate("Edit & Approve")}}</a>
                            <a href="{{ route('admin.advertisement.status',['status' => 'approved' ,'id' => $advertisement->id ,'approved' => 1]) }}" type="button"  class="btn btn--secondary  min-w-120">{{translate('Only Approve')}}</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-approve-model">
    <div class="modal-dialog modal-dialog-centered status-warning-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true" class="tio-clear"></span>
                </button>
            </div>
            <div class="modal-body pb-5 pt-0">
                <div class="max-349 mx-auto mb-20">
                    <div>
                        <div class="text-center">
                            <img width="80" src="{{  dynamicAsset('public/assets/admin/img/modal/tick.png') }}" class="mb-20">
                            <h5 class="modal-title"></h5>
                        </div>
                        <div class="text-center" >
                            <h3 > {{ translate('Are_you_sure_?') }}</h3>
                            <div > <p>{{ translate('After approval this Advertisement will show in The User App & Websites.') }}</h3></p></div>
                        </div>

                        </div>

                    <div class="btn--container justify-content-center">
                        <button data-dismiss="modal" class="btn btn--secondary min-w-120" >{{translate("Not_Now")}}</button>
                        <a href="{{ route('admin.advertisement.status',['status' => 'approved' ,'id' => $advertisement->id ,'approved' => 1]) }}" type="button"  class="btn btn-outline-success min-w-120">{{translate('Approve')}}</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



</div>
@endsection

@push('script_2')

    <script type="text/javascript" src="{{dynamicAsset('public/assets/admin/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{dynamicAsset('public/assets/admin/js/daterangepicker.min.js')}}"></script>

    <script>
        $(function() {
            $('input[name="dates"]').daterangepicker({
                startDate: moment('{{ $advertisement?->start_date }}').startOf('hour'),
                endDate: moment('{{ $advertisement?->end_date }}').startOf('hour'),
                minDate: new Date(),
                autoUpdateInput: false,

            });
            $('.js-select').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('M/D/Y') + ' - ' + picker.endDate.format('M/D/Y'));
                location.href = '{{ route('admin.advertisement.updateDate',$advertisement->id) }}' + '?start_date=' + picker.startDate.format('M/D/Y') + '&end_date=' + picker.endDate.format('M/D/Y');
            });

        });

        $('.modal').on('hidden.bs.modal', function (e) {
            $(this).find('video')[0].pause();
        });

        $('.profile_image_view').on('click', function () {

            $('#imagemodal_profile').modal('show');
        })
        $('.cover_image_view').on('click', function () {

            $('#imagemodal_cover').modal('show');
        })
    </script>

@endpush
