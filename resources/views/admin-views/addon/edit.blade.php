@extends('layouts.admin.app')

@section('title',translate('addon_update'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h2 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{dynamicAsset('/public/assets/admin/img/addon.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('messages.addon_update')}}
                        </span>
                    </h2>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.addon.update',[$addon['id']])}}" method="post">
                    @csrf
                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = str_replace('_', '-', app()->getLocale()))

                    @if($language)
                                <div class="js-nav-scroller hs-nav-scroller-horizontal">
                        <ul class="nav nav-tabs mb-4">
                            <li class="nav-item">
                                <a class="nav-link lang_link active " href="#" id="default-link">{{ translate('Default')}}</a>
                            </li>
                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link " href="#" id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach
                        </ul>
                                </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                        @if ($language)
                        <div class="form-group lang_form" id="default-form">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                            <input type="text" name="name[]" class="form-control" placeholder="{{translate('messages.new_addon')}}" value="{{ $addon?->getRawOriginal('name') }}"  maxlength="191">
                        </div>
                        <input type="hidden" name="lang[]" value="default">
                            @foreach(json_decode($language) as $lang)
                                <?php
                                    if(count($addon['translations'])){
                                        $translate = [];
                                        foreach($addon['translations'] as $t)
                                        {
                                            if($t->locale == $lang && $t->key=="name"){
                                                $translate[$lang]['name'] = $t->value;
                                            }
                                        }
                                    }
                                ?>
                                <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{strtoupper($lang)}})</label>
                                    <input type="text" name="name[]" class="form-control" placeholder="{{translate('messages.new_addon')}}" maxlength="191" value="{{$translate[$lang]['name'] ??''}}"  >
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                            @endforeach
                        @else
                            <div class="form-group lang_form" id="default-form">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                                <input type="text" name="name[]" class="form-control" placeholder="{{translate('messages.new_addon')}}" value="{{ $addon['name'] }}"  maxlength="191">
                            </div>
                            <input type="hidden" name="lang[]" value="default">
                        @endif
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.restaurant')}}<span
                                        class="input-label-secondary"></span></label>
                                <select name="restaurant_id" id="restaurant_id" class="form-control  js-data-example-ajax"  data-placeholder="{{translate('messages.select_restaurant')}}" required oninvalid="this.setCustomValidity('{{translate('messages.please_select_restaurant')}}')">
                                @if($addon->restaurant)
                                <option value="{{$addon->restaurant_id}}" selected="selected">{{$addon->restaurant->name}}</option>
                                @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.price')}}</label>
                                <input type="number" min="0" max="999999999999.99" step="0.01" name="price" value="{{$addon['price']}}" class="form-control" placeholder="200" required>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group mb-0">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ translate('messages.Stock_Type') }}
                                </label>
                                <select name="stock_type" id="stock_type" class="form-control js-select2-custom">
                                    <option  {{$addon['stock_type'] == 'unlimited' ? 'selected':'' }}  value="unlimited">{{ translate('messages.Unlimited_Stock') }}</option>
                                    <option {{$addon['stock_type'] == 'limited' ? 'selected' : '' }} value="limited">{{ translate('messages.Limited_Stock')  }}</option>
                                    <option {{$addon['stock_type'] == 'daily' ? 'selected' : '' }}  value="daily">{{ translate('messages.Daily_Stock')  }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 hide_this">
                            <div class="form-group">
                                <label class="input-label" for="addon_stock">{{translate('messages.Addon_Stock')}}</label>
                                <input type="number" min="0" id="addon_stock" max="999999999999" name="addon_stock"   {{$addon['stock_type'] == 'unlimited' ? 'readonly':'' }} placeholder="{{$addon['stock_type'] == 'unlimited' ? translate('Unlimited') : translate('messages.Ex:_100')  }}"  value="{{$addon['stock_type'] == 'unlimited' ? '':$addon['addon_stock']  }}" class="form-control stock_disable"  >
                            </div>
                        </div>


                    </div>

                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                    </div>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
<script>
    "use strict";
    $('#stock_type').on('change', function () {
        stock_type($(this).val());
        });

        stock_type($('#stock_type').val());
        function stock_type(data){
            if(data == 'unlimited') {
                    $('.stock_disable').prop('readonly', true).prop('required', false).attr('placeholder', '{{ translate('Unlimited') }}').val('');
                     $('.hide_this').addClass('d-none');
                } else {
                    $('.stock_disable').prop('readonly', false).prop('required', true).attr('placeholder', '{{ translate('messages.Ex:_100') }}').val('{{$addon['addon_stock']}}');
                    $('.hide_this').removeClass('d-none');
                }
        }

    $('.js-data-example-ajax').select2({
        ajax: {
            url: '{{url('/')}}/admin/restaurant/get-restaurants',
            data: function (params) {
                return {
                    q: params.term, // search term
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

    $('#reset_btn').click(function(){
            $('#restaurant_id').val("{{$addon->restaurant_id}}").trigger('change');
            $('#stock_type').val("{{$addon->stock_type}}").trigger('change');
            stock_type('{{$addon->stock_type}}');

        })
</script>
@endpush
