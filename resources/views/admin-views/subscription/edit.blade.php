@extends('layouts.admin.app')

@section('title',translate('messages.Subscription'))

@section('subscription_index')
active
@endsection

@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-start">
                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription/create-package-icon.png')}}" width="24" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title">{{translate('Subscription Package')}}</h1>
                            <div class="page-header-text">{{ translate('Update_Subscriptions_Packages_for_Subscription_Business_Model') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-20">
            <div class="card-header">
                <div class="w-100 d-flex flex-wrap align-items-start gap-2">
                    <img src="{{dynamicAsset('/public/assets/admin/img/subscription/material-symbols_featured-play-list.png')}}" width="18" alt="img" class="mt-1">
                    <div class="w-0 flex-grow">
                        <h5 class="text--title card-title">{{ translate('Package_Information') }}</h5>
                        <div class="fz-12px">{{ translate('Give_Subscriptions_Package_Information') }}</div>
                    </div>
                </div>
            </div>

    <form action="{{ route('admin.subscription.subscription_update',$subscriptionackage->id) }}" method="post">
        @csrf
        @method('put')

                <div class="card-body">
                        @if ($language)
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a class="nav-link lang_link active"
                                href="#"
                                id="default-link">{{translate('messages.default')}}</a>
                            </li>
                            @foreach ($language as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link"
                                        href="#"
                                        id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                </li>
                            @endforeach
                        </ul>
                        @endif

                    <div class="row g-3">

                        <div class="col-lg-4 col-sm-6 lang_form" id="default-form">
                            <div class="form-group mb-0">
                                <label class="form-label input-label"
                                for="name">{{ translate('Package_Name') }} ({{ translate('Default') }})</label>
                                <input type="text" name="package_name[]" class="form-control" id="name" maxlength="191"  value="{{ $subscriptionackage?->getRawOriginal('package_name') }}"
                                placeholder="{{ translate('Package_Name') }}"
                                >
                            <input type="hidden" name="lang[]" value="default">
                            </div>
                        </div>

                        @if($language)
                                @foreach($language as $key => $lang)

                                <?php
                                if(count($subscriptionackage['translations'])){
                                    $translate = [];
                                    foreach($subscriptionackage['translations'] as $t)
                                    {
                                        if($t->locale == $lang && $t->key=="package_name"){
                                            $translate[$lang]['package_name'] = $t->value;
                                        }
                                    }
                                }
                            ?>


                                <div class="col-lg-4 col-sm-6  d-none lang_form" id="{{$lang}}-form">
                                    <div class="form-group mb-0">
                                        <label class="form-label input-label"
                                        for="{{$lang}}_title">{{ translate('Package_Name') }} ({{strtoupper($lang)}})</label>
                                        <input type="text" name="package_name[]" class="form-control" id="{{$lang}}_title" maxlength="191"  value="{{ $translate[$lang]['package_name']??'' }}"
                                        placeholder="{{ translate('Package_Name') }}"
                                        >
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                    </div>
                                </div>
                                @endforeach
                        @endif


                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Package_Price') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})</label>
                                <input type="number" value="{{ $subscriptionackage->price }}" name="package_price" required  min="0.01" step="0.01" max="999999999" class="form-control" placeholder="{{ translate('Ex: 300') }}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Package_Validity') }} {{ translate('Days') }}</label>
                                <input type="number"   min="1" max="999999999"  value="{{ $subscriptionackage->validity }}"  required name="package_validity"  class="form-control" placeholder="{{ translate('Ex: 365') }}">
                            </div>
                        </div>


                        <div class="col-lg-4 col-sm-6 lang_form default-form" >
                            <div class="form-group m-0">
                                <label class="form-label input-label   text-capitalize"
                                    for="package_info">{{ translate('messages.package_info') }}</label>
                                <textarea class="form-control" placeholder="{{ translate('EX:_Value_for_money') }}"  name="text[]" id="package_info">{{ $subscriptionackage?->getRawOriginal('text')  }}</textarea>
                            </div>
                        </div>

                        @if($language)
                        @foreach($language as $lang)
                        <?php
                        if(count($subscriptionackage['translations'])){
                            $text = [];
                            foreach($subscriptionackage['translations'] as $t)
                            {
                                if($t->locale == $lang && $t->key=="text"){
                                    $text[$lang]['text'] = $t->value;
                                }
                            }
                        }
                    ?>
                        <div class="col-lg-4 col-sm-6 d-none lang_form" id="{{$lang}}-form1">
                            <div class="form-group m-0">
                                <label class="form-label input-label   text-capitalize"
                                    for="package_info">{{ translate('messages.package_info') }} ({{strtoupper($lang)}})</label>
                                <textarea class="form-control" name="text[]" placeholder="{{ translate('EX:_Value_for_money') }}" id="package_info">{{ $text[$lang]['text']??''}}</textarea>
                            </div>
                        </div>
                        @endforeach
                        @endif

                    </div>
                </div>
            </div>
            <div class="card mb-20">
                <div class="card-header">
                    <div class="w-100 d-flex flex-wrap align-items-start gap-2">
                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription/material-symbols_featured-play-list-2.png')}}" alt="img" class="mt-1">
                        <div class="w-0 flex-grow">
                            <h5 class="text--title card-title d-flex gap-3 flex-wrap mb-1">
                                <div>
                                    {{ translate('Package_Available_Features') }}
                                </div>
                                <label class="form-group form-check form--check">
                                    <input type="checkbox" class="form-check-input" id="select-all">
                                    <span class="form-check-label text-dark font-regular text-14">{{ translate('Select_All') }}</span>
                                </label>
                            </h5>
                            <div class="fz-12px">{{ translate('Mark_the_feature_you_want_to_give_in_this_package') }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="check--item-wrapper check--item-wrapper-2 mt-0">
                        <div class="check-item">
                            <label class="form-group form-check form--check">
                                <input type="checkbox" class="form-check-input package-available-feature"  {{ $subscriptionackage->pos == 1 ? 'checked' : '' }} name="pos_system" value="1">
                                <span class="form-check-label text-dark">{{ translate('messages.pos_system') }}</span>
                            </label>
                        </div>
                        <div class="check-item">
                            <label class="form-group form-check form--check">
                                <input type="checkbox" class="form-check-input package-available-feature" {{ $subscriptionackage->self_delivery == 1 ? 'checked' : '' }}  name="self_delivery" value="1">
                                <span class="form-check-label text-dark">{{ translate('messages.self_delivery') }}</span>
                            </label>
                        </div>
                        <div class="check-item">
                            <label class="form-group form-check form--check">
                                <input type="checkbox" class="form-check-input package-available-feature" {{ $subscriptionackage->mobile_app == 1 ? 'checked' : '' }}  name="mobile_app" value="1" >
                                <span class="form-check-label text-dark">{{ translate('messages.Mobile_App') }}</span>
                            </label>
                        </div>
                        <div class="check-item">
                            <label class="form-group form-check form--check">
                                <input type="checkbox" class="form-check-input package-available-feature" {{ $subscriptionackage->review == 1 ? 'checked' : '' }}  name="review" value="1" >
                                <span class="form-check-label text-dark">{{ translate('messages.review') }}</span>
                            </label>
                        </div>
                        <div class="check-item">
                            <label class="form-group form-check form--check">
                                <input type="checkbox" class="form-check-input package-available-feature" {{ $subscriptionackage->chat == 1 ? 'checked' : '' }}  name="chat" value="1" >
                                <span class="form-check-label text-dark">{{ translate('messages.chat') }}</span>
                            </label>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="w-100 d-flex flex-wrap align-items-start gap-2">
                        <img src="{{dynamicAsset('/public/assets/admin/img/subscription/bx_category.png')}}" alt="img" class="mt-1">
                        <div class="w-0 flex-grow">
                            <h5 class="text--title card-title d-flex gap-3 flex-wrap mb-1">
                                <div>
                                    {{ translate('Set_limit') }}
                                </div>
                            </h5>
                            <div class="fz-12px">{{ translate('Set_maximum_order_&_product_limit_for_this_package') }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="__bg-F8F9FC-card p-0">
                            <div class="card-body">
                                <div class="limit-item-card">
                                    <div class="form-group mb-0">
                                        <label class="form-label text-capitalize">{{ translate('Maximum_Order Limit') }}</label>
                                        <div class="d-flex flex-wrap items-center gap-2">
                                            <div class="resturant-type-group p-0">
                                                <label class="form-check form--check mr-2 mr-md-4">
                                                    <input class="form-check-input limit-input" type="radio" {{ $subscriptionackage->max_order == 'unlimited' ? 'checked' : '' }}  name="minimum_order_limit" >
                                                    <span class="form-check-label">
                                                        {{ translate('Unlimited') }} ({{ translate('Default') }})
                                                    </span>
                                                </label>
                                                <label class="form-check form--check mr-2 mr-md-4">
                                                    <input class="form-check-input limit-input"  {{ $subscriptionackage->max_order != 'unlimited' ? 'checked' : '' }}  type="radio" name="minimum_order_limit" value="Use_Limit">
                                                    <span class="form-check-label">
                                                        {{ translate('Use_Limit') }}
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="custom-limit-box">
                                                <input id="max_order" type="number" value="{{ $subscriptionackage->max_order == 'unlimited' ? null : $subscriptionackage->max_order }}" name="max_order" min="1" step="1" max="999999999" class="form-control max_required" placeholder="{{ translate('Ex: 1000') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="__bg-F8F9FC-card p-0">
                            <div class="card-body">
                                <div class="limit-item-card">
                                    <div class="form-group mb-0">
                                        <label class="form-label text-capitalize">{{ translate('Maximum_Item_Limit') }}</label>
                                        <div class="d-flex flex-wrap items-center gap-2">
                                            <div class="resturant-type-group p-0">
                                                <label class="form-check form--check mr-2 mr-md-4">
                                                    <input class="form-check-input limit-input" type="radio" {{ $subscriptionackage->max_product == 'unlimited' ? 'checked' : '' }} name="maximum_item_limit" >
                                                    <span class="form-check-label">
                                                        {{ translate('Unlimited') }} ({{ translate('Default') }})
                                                    </span>
                                                </label>
                                                <label class="form-check form--check mr-2 mr-md-4">
                                                    <input class="form-check-input limit-input" {{ $subscriptionackage->max_product != 'unlimited' ? 'checked' : '' }}  type="radio" name="maximum_item_limit" value="Use_Limit" >
                                                    <span class="form-check-label">
                                                        {{ translate('Use_Limit') }}
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="custom-limit-box">
                                                <input  id="max_product" type="number" value="{{ $subscriptionackage->max_product == 'unlimited' ? null : $subscriptionackage->max_product }}" name="max_product" min="1" step="1" max="999999999" class="form-control max_required" placeholder="{{ translate('Ex: 1000') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn--container justify-content-end mt-3">
                <button type="reset" id="reset_btn" class="btn btn--reset">
                    {{ translate('messages.reset') }}
                </button>
                <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
            </div>

        </form>


    </div>




@endsection

@push('script_2')

<script>
"use strict";
    $('#select-all').on('change', function(){
        if($(this).is(':checked')){
            $('.package-available-feature').prop('checked', true);
        }else{
            $('.package-available-feature').prop('checked', false);
        }
    })
    $('.package-available-feature').on('change', function(){
        if($(this).is(':checked')){
            if($('.package-available-feature').length == $('.package-available-feature:checked').length){
                $('#select-all').prop('checked', true);
            }
        }else{
            $('#select-all').prop('checked', false);
        }
    }).trigger('change');

    $('.limit-input').on('change', function() {

        var closestLimitItemCard = $(this).closest('.limit-item-card');
        var isChecked = $(this).is(':checked');
        if (isChecked) {
            if ($(this).val() == 'Use_Limit') {
                closestLimitItemCard.find('.custom-limit-box').show();
                closestLimitItemCard.find('.max_required').prop('required', true);
            } else {
                closestLimitItemCard.find('.custom-limit-box').hide();
                closestLimitItemCard.find('.max_required').removeAttr('required');
            }
        }
    }).trigger('change');



    $(document).on("click", "#reset_btn", function () {
    setTimeout(reset, 10);
    });

    function reset(){
    $('.limit-input').trigger('change');
    }

</script>

@endpush

