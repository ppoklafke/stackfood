@extends('layouts.admin.app')

@section('title',translate('messages.update_banner'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i>{{translate('messages.update_banner')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.banner.update', [$banner->id])}}" method="post" id="banner_form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                                        @php($language = $language->value ?? null)
                                        @php($default_lang = str_replace('_', '-', app()->getLocale()))
                                        @if($language)
                                <div class="js-nav-scroller hs-nav-scroller-horizontal">
                                            <ul class="nav nav-tabs mb-4">
                                                <li class="nav-item">
                                                    <a class="nav-link lang_link active"
                                                    href="#"
                                                    id="default-link">{{translate('messages.default')}}</a>
                                                </li>
                                                @foreach (json_decode($language) as $lang)
                                                    <li class="nav-item">
                                                        <a class="nav-link lang_link"
                                                            href="#"
                                                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                </div>
                                            <div class="lang_form" id="default-form">
                                                <div class="form-group">
                                                    <label class="input-label" for="default_title">{{translate('messages.title')}} ({{translate('messages.default')}})</label>
                                                    <input type="text" name="title[]" id="default_title" class="form-control" placeholder="{{translate('messages.new_banner')}}" value="{{$banner->getRawOriginal('title')}}"  >
                                                </div>
                                                <input type="hidden" name="lang[]" value="default">
                                            </div>
                                            @foreach(json_decode($language) as $lang)
                                                <?php
                                                    if(count($banner['translations'])){
                                                        $translate = [];
                                                        foreach($banner['translations'] as $t)
                                                        {
                                                            if($t->locale == $lang && $t->key=="title"){
                                                                $translate[$lang]['title'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                ?>
                                                <div class="d-none lang_form" id="{{$lang}}-form">
                                                    <div class="form-group">
                                                        <label class="input-label" for="{{$lang}}_title">{{translate('messages.title')}} ({{strtoupper($lang)}})</label>
                                                        <input type="text" name="title[]" id="{{$lang}}_title" class="form-control" placeholder="{{translate('messages.new_banner')}}" value="{{$translate[$lang]['title']??''}}"  >
                                                    </div>
                                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                                </div>
                                            @endforeach
                                        @else
                                        <div id="default-form">
                                            <div class="form-group">
                                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.title')}} ({{ translate('messages.default') }})</label>
                                                <input type="text" name="title[]" class="form-control" placeholder="{{translate('messages.new_banner')}}"  value="{{$banner->getRawOriginal('title')}}" maxlength="100" >
                                            </div>
                                            <input type="hidden" name="lang[]" value="default">
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="title">{{translate('messages.zone')}}</label>
                                        <select name="zone_id" id="zone" class="form-control js-select2-custom get-request" data-url="{{url('/')}}/admin/food/get-foods?zone_id=" data-id="choice_item">
                                            <option  disabled selected>---{{translate('messages.select')}}---</option>
                                            @php($zones=\App\Models\Zone::active()->get(['id','name']))
                                            @foreach($zones as $zone)
                                                @if(isset(auth('admin')->user()->zone_id))
                                                    @if(auth('admin')->user()->zone_id == $zone->id)
                                                        <option value="{{$zone['id']}}" {{$zone->id == $banner->zone_id?'selected':''}}>{{$zone['name']}}</option>
                                                    @endif
                                                @else
                                                 <option value="{{$zone['id']}}" {{$zone->id == $banner->zone_id?'selected':''}}>{{$zone['name']}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.banner_type')}}</label>
                                        <select id="banner_type" name="banner_type" class="form-control banner_type_change">
                                            <option value="restaurant_wise" {{$banner->type == 'restaurant_wise'? 'selected':'' }}>{{translate('messages.restaurant_wise')}}</option>
                                            <option value="item_wise" {{$banner->type == 'item_wise'? 'selected':'' }}>{{translate('messages.food_wise')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="restaurant_wise">
                                        <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.restaurant')}}<span
                                                class="input-label-secondary"></span></label>
                                        <select name="restaurant_id" class="js-data-example-ajax" id="resturant_ids"  title="Select Restaurant">
                                            @if($banner->type=='restaurant_wise')
                                             @php($restaurant = \App\Models\Restaurant::where('id', $banner->data)->first())
                                                @if($restaurant)
                                                    <option value="{{$restaurant->id}}" selected>{{$restaurant->name}}</option>
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group" id="item_wise">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.select_food')}}</label>
                                        <select name="item_id" id="choice_item" class="form-control js-select2-custom" placeholder="{{translate('messages.select_food')}}">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex flex-column align-items-center gap-3">
                                        <p class="mb-0">{{ translate('Banner_image') }}</p>

                                        <div class="image-box banner2">
                                            <label for="image-input" class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-2">
                                                <img  class="upload-icon initial-26" src="{{ $banner['image_full_url'] }}"
                                                alt="Upload Icon">
                                                {{-- <span class="upload-text">{{ translate('Upload Image')}}</span> --}}
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
                                <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
<script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/banner-index.js"></script>
<script>
    "use strict";
    $(document).on('ready', function () {
        let zone_id = {{$banner->zone_id}};
        banner_type_change('{{$banner->type}}');

        $('#zone').on('change', function(){
            if($(this).val())
            {
                zone_id = $(this).val();
            }
            else
            {
                zone_id = true;
            }
        });

        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{url('/')}}/admin/restaurant/get-restaurants',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        zone_ids: [zone_id],
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



        $('.js-select2-custom').each(function () {
            let select2 = $.HSCore.components.HSSelect2.init($(this));
        });
    });
    $('.banner_type_change').on('change', function (){
        let order_type = $(this).val();
        banner_type_change(order_type);
    })

        function banner_type_change(order_type) {
           if(order_type=='item_wise')
            {
                $('#restaurant_wise').hide();
                $('#item_wise').show();
                getRequest('{{url('/')}}/admin/food/get-foods?zone_id={{$banner->zone_id}}&data[]={{$banner->data}}','choice_item');
            }
            else if(order_type=='restaurant_wise')
            {
                $('#restaurant_wise').show();
                $('#item_wise').hide();
            }
            else{
                $('#item_wise').hide();
                $('#restaurant_wise').hide();
            }
        }
        @if($banner->type == 'item_wise')
        getRequest('{{url('/')}}/admin/food/get-foods?zone_id={{$banner->zone_id}}&data[]={{$banner->data}}','choice_item');
        @endif
        $('#banner_form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.banner.update', [$banner['id']])}}',
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
                        toastr.success('{{translate('messages.banner_updated_successfully')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.banner.add-new')}}';
                        }, 2000);
                    }
                }
            });
        });

        $('#reset_btn').click(function(){
            $('#banner_title').val("{{$banner->title}}");
            $('#banner_type').val("{{$banner->type}}").trigger('change');
            $('#viewer').attr('src','{{dynamicStorage('storage/app/public/banner')}}/{{$banner['image']}}');
            $('#customFileEg1').val(null);
            $('#zone').val("{{$banner->zone_id}}").trigger('change');
            setTimeout(function () {
                @if($banner->type == 'restaurant_wise')
                    $('#resturant_ids').val("{{$banner->data}}").trigger('change');
                @elseif($banner->type == 'item_wise')
                    $('#choice_item').val("{{$banner->data}}").trigger('change');
                @endif
            }, 1000);
        })
$('.get-request').on('change', function (){
    let route = $(this).data('url')+$(this).val();
    let id = $(this).data('id');
    getRequest(route, id)

})
function getRequest(route, id) {
    $.get({
        url: route,
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty().append(data.options);
        },
    });
}
    </script>
@endpush
