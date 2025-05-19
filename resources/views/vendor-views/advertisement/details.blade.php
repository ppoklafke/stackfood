@extends('layouts.vendor.app')

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
        <div>
            <h1 class="page-header-title mb-1 d-flex align-items-center gap-2">
                {{-- <img src="{{dynamicAsset('public/assets/admin/img/advertisement.png')}}" alt=""> --}}
                {{ translate('Advertisement ID') }} #{{ $advertisement->id }}
            </h1>
            <p class="d-flex gap-2 align-items-center mb-0">
                <span>{{ translate('Ad Placed') }}</span>
                <span class="mx-1">:</span>
                <span class="font-medium text-title">{{\App\CentralLogics\Helpers::time_date_format($advertisement->created_at)  }}</span>
            </p>
        </div>
        {{-- <div class="d-flex gap-1">

            @if ($previousId)

            <a href="{{ route('vendor.advertisement.show', [$previousId]) }}"  data-toggle="tooltip"
                data-placement="top" title="{{ translate('Previous_advertisement') }}" class="arrow-icon">
                <i class="tio-chevron-left"></i>
                </a>
            @endif




                @if ($nextId)
                <a href="{{ route('vendor.advertisement.show', [$nextId] ) }}"  data-toggle="tooltip"
                data-placement="top" title="{{ translate('next_advertisement') }}" class="arrow-icon">
                    <i class="tio-chevron-right"></i>
                </a>

                @endif
        </div> --}}
        <a href="{{  route('vendor.advertisement.edit',[$advertisement->id ,'request_page_type'=> isset($request_page_type) ]) }}" class="btn btn--primary">
            <i class="tio-edit"></i>
            <span>{{ translate('Edit Ads') }}</span>
        </a>
    </div>
    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card mb-3 h-100">
                <div class="card-body p-3 p-sm-4 fs-12">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h4>{{ translate('Ad Details') }}</h4>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-20">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-md-end approve-buttons">


                                </div>
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








                    <div class="col-lg-12">
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
                                    <p class="m-0">{{$translate[$lang]['description']??'------------' }}</p>

                                </div>

                    @endforeach
                        </div>






                        <div class="col-lg-12">
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
                        <h3 class=" mb-4">{{ translate('Ad Status') }}</h3>
<hr>
                        <div class="d-flex flex-column gap-2 gap-lg-3 ">
                            <p class="d-flex gap-2 justify-content-between align-items-center mb-0 ">
                                <span>{{ translate('Request_Verify_Status') }}: </span>
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


                            <p class="d-flex justify-content-between gap-2 align-items-center mb-0">
                                <span>{{ translate('Payment Status') }}
                                <span class="mx-1">:</span></span>
                                @if ($advertisement->is_paid == 1)
                                <span class="font-semibold text-success">{{ translate('Paid') }}</span>
                                @else
                                <span class="font-semibold text-danger">{{ translate('Unpaid') }}</span>

                                @endif
                            </p>
                            <p class="d-flex gap-2 justify-content-between align-items-center mb-0">
                                <span>{{ translate('Ad Type') }}
                                <span class="mx-1">:</span> </span>
                                <span class="font-medium text-title">{{ translate($advertisement->add_type) }}</span>
                            </p>
                            <p class="d-flex gap-2 justify-content-between align-items-center mb-0">
                                <span>{{ translate('Duration') }}<span class="mx-1">:</span></span>
                                <span class="font-medium text-title">{{ \App\CentralLogics\Helpers::date_format($advertisement->start_date).' - ' .\App\CentralLogics\Helpers::date_format($advertisement->end_date) }}</span>
                            </p>
                        </div>

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
