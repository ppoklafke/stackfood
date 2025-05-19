@extends('layouts.admin.app')

@section('title',translate('Add_new_Vehicle'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="tio-add-circle-outlined"></i></div>
                        {{translate('messages.add_vehicle_category')}}
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.vehicle.store')}}" method="post" enctype="multipart/form-data" id="vehicle-form">
                    @csrf
                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                    @php($language = $language?->value )
                    @php($default_lang = str_replace('_', '-', app()->getLocale()))
                    @if ($language)
                                <div class="js-nav-scroller hs-nav-scroller-horizontal">
                        <ul class="nav nav-tabs mb-4">
                            <li class="nav-item">
                                <a class="nav-link lang_link active"
                                href="#"
                                id="default-link">{{translate('messages.default')}}</a>
                            </li>
                            @forelse (json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link"
                                        href="#"
                                        id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                </li>
                                @empty
                            @endforelse
                        </ul>
                                </div>
                        @endif
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="row">



                                <div class="col-md-6 lang_form" id="default-form" >
                                    <input type="hidden" name="lang[]" value="default">

                                    <div class="form-group">
                                        <label class="input-label text-capitalize" for="title">{{translate('messages.Vehicle_type')}} ({{ translate('messages.default') }})</label>
                                        <input type="text" id="Vehicle_type" class="form-control h--45px"  name="type[]">
                                    </div>
                                </div>

                                    @if ($language)
                                        @forelse (json_decode($language) as $lang)
                                                <div class="col-md-6 d-none lang_form" id="{{$lang}}-form">
                                                    <div class="form-group">
                                                        <label class="input-label text-capitalize" for="title">{{translate('messages.Vehicle_type')}} ({{strtoupper($lang)}})</label>
                                                        <input type="text" id="Vehicle_type" class="form-control h--45px" name="type[]">
                                                    </div>
                                                </div>

                                                <input type="hidden" name="lang[]" value="{{$lang}}">
                                            @empty
                                        @endforelse
                                    @endif

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize" for="title">{{translate('messages.extra_charges')}} ({{ \App\CentralLogics\Helpers::currency_symbol() }}) <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('This_amount_will_be_added_with_delivery_charge')}}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="public/img"></span></label>
                                        <input type="number" id="extra_charges" class="form-control h--45px" step="0.001" min="0" required name="extra_charges">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize" for="title">{{translate('messages.starting_coverage_area')}} ({{ translate('messages.km') }}) <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.This_value_is_the_minimum_distance_for_a_vehicle_in_this_category_to_serve_an_order.')}}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="public/img"></span></label>
                                        <input type="number" id="starting_coverage_area" class="form-control h--45px" step="0.001" min="0" required name="starting_coverage_area">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize" for="title">{{translate('messages.maximum_coverage_area')}} ({{ translate('messages.km') }}) <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.This_value_is_the_maximum_distance_for_a_vehicle_in_this_category_to_serve_an_order.')}}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="public/img"></span></label>
                                        <input type="number" id="maximum_coverage_area" class="form-control h--45px" step="0.001" min="0"  required name="maximum_coverage_area">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        "use strict";
        $('#vehicle-form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.vehicle.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('messages.Vehicle_created') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.vehicle.list')}}';
                        }, 1000);
                    }
                }
            });
        });

            $('#reset_btn').click(function(){
                $('#choice_item').val(null).trigger('change');
                $('#viewer').attr('src','{{dynamicAsset('public/assets/admin/img/900x400/img1.jpg')}}');
            })


        </script>
@endpush
