@extends('layouts.admin.app')

@section('title',translate('messages.Update_Campaign'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{translate('messages.Update_Campaign')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.campaign.update-basic',[$campaign['id']])}}" method="post" id=campaign-form
                      enctype="multipart/form-data">
                      @csrf
                      @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = str_replace('_', '-', app()->getLocale()))
                    @if($language)
                                <div class="js-nav-scroller hs-nav-scroller-horizontal">
                        <ul class="nav nav-tabs mb-4">
                            <li class="nav-item">
                                <a class="nav-link lang_link active" href="#" id="default-link">{{ translate('Default') }}</a>
                            </li>
                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link" href="#" id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach
                        </ul>
                                </div>



                        <div class="lang_form" id="default-form">
                            <div class="form-group">
                                <label class="input-label" for="default_title">{{translate('messages.title')}} ({{ translate('Default') }})</label>
                                <input type="text"  name="title[]" id="default_title" class="form-control" placeholder="{{translate('messages.new_campaign')}}" value="{{$campaign->getRawOriginal('title')}}"  >
                            </div>
                            <input type="hidden" name="lang[]" value="default">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.short_description')}} ({{ translate('Default') }})</label>
                                <textarea type="text" name="description[]" class="form-control ckeditor min-height-154px">{!! $campaign->getRawOriginal('description') !!}</textarea>
                            </div>
                        </div>


                        @foreach(json_decode($language) as $lang)
                            <?php
                                if(count($campaign['translations'])){
                                    $translate = [];
                                    foreach($campaign['translations'] as $t)
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
                            <div class="d-none lang_form" id="{{$lang}}-form">
                                <div class="form-group">
                                    <label class="input-label" for="{{$lang}}_title">{{translate('messages.title')}} ({{strtoupper($lang)}})</label>
                                    <input type="text"  name="title[]" id="{{$lang}}_title" class="form-control" placeholder="{{translate('messages.new_campaign')}}" value="{{$translate[$lang]['title']??$campaign['title']}}"  >
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.short_description')}} ({{strtoupper($lang)}})</label>
                                    <textarea type="text" name="description[]" class="form-control ckeditor min-height-154px">{!! $translate[$lang]['description']??$campaign['description'] !!}</textarea>
                                </div>
                            </div>
                        @endforeach
                    @else
                    <div id="default-form">
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.title')}} ({{ translate('Default') }})</label>
                            <input type="text" name="title[]" class="form-control" placeholder="{{translate('messages.new_campaign')}}" value="{{$campaign['title']}}" maxlength="100" >
                        </div>
                        <input type="hidden" name="lang[]" value="default">
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.short_description')}} ({{ translate('Default') }})</label>
                            <textarea type="text" name="description[]" class="form-control ckeditor min-height-154px" maxlength="255">{!! $campaign['description'] !!}</textarea>
                        </div>
                    </div>
                    @endif
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="title">{{translate('messages.start_date')}}</label>
                                        <input type="date" id="date_from" class="form-control" required name="start_date" value="{{$campaign->start_date->format('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="input-label" for="title">{{translate('messages.end_date')}}</label>
                                    <input type="date" id="date_to" class="form-control" required="" name="end_date" value="{{$campaign->end_date->format('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize" for="title">{{translate('messages.daily_start_time')}}</label>
                                        <input type="time" id="start_time" class="form-control" name="start_time" value="{{$campaign->start_time}}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="input-label text-capitalize" for="title">{{translate('messages.daily_end_time')}}</label>
                                    <input type="time" id="end_time" class="form-control" name="end_time" value="{{$campaign->end_time}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex flex-column align-items-center gap-3 mt-4">
                                <p class="mb-0">{{ translate('campaign_image') }}</p>

                                <div class="image-box banner2">
                                    <label for="image-input" class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-2">
                                        <img  class="upload-icon initial-34" src="{{$campaign?->image_full_url }}" alt="Upload Icon">
                                        <img src="#" alt="Preview Image" class="preview-image">
                                    </label>
                                <button type="button" class="delete_image">
                                    <i class="tio-delete"></i>
                                </button>
                                <input type="file" id="image-input" name="image" accept="image/*" hidden>
                            </div>

                            <p class="opacity-75 max-w220 mx-auto text-center">
                                {{ translate('Image format - jpg png jpeg gif Image Size -maximum size 2 MB Image Ratio - 2:1')}}
                            </p>
                        </div>
                    </div>



                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/basic-campaign-edit.js"></script>
    <script>
        "use strict";
        $(document).ready(function(){
            $('#date_from').attr('min',(new Date()).toISOString().split('T')[0]);
            $('#date_to').attr('min','{{$campaign->start_date->format("Y-m-d")}}');
        });

        $('#campaign-form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.campaign.update-basic',[$campaign['id']])}}',
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
                        toastr.success('{{ translate('Campaign_updated_successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.campaign.list', 'basic')}}';
                        }, 2000);
                    }
                }
            });
        });

            $('#reset_btn').click(function(){
                $('#viewer').attr('src','{{dynamicStorage('storage/app/public/campaign')}}/{{$campaign->image}}');
            })

        </script>
@endpush
