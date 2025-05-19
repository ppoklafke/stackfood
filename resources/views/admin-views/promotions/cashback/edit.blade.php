@extends('layouts.admin.app')

@section('title',translate('Edit_Cashback_Offer'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{dynamicAsset('public/assets/admin/img/Create_Cashback_Offer.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.Edit_Cashback_Offer')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body" id="form_data">
                <form action="{{route('admin.cashback.update',['id'=>$cashback?->id ])}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            @if ($language)
                            <ul class="nav nav-tabs mb-3 border-0">
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
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <div class="lang_form" id="default-form">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="default_title">{{ translate('messages.title') }}
                                        ({{ translate('Default') }})
                                    </label>
                                    <input type="text" name="title[]" maxlength="254" value="{{$cashback?->getRawOriginal('title')}}" id="default_title"
                                        class="form-control" placeholder="{{ translate('messages.Eid_Dhamaka') }}" >
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                            </div>
                                @foreach ($language as $lang)
                                <?php
                                if(count($cashback['translations'])){
                                    $translate = [];
                                    foreach($cashback['translations'] as $t)
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
                                    <div class="d-none lang_form"
                                        id="{{ $lang }}-form">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="{{ $lang }}_title">{{ translate('messages.title') }}
                                                ({{ strtoupper($lang) }})
                                            </label>
                                            <input type="text" name="title[]" maxlength="254" id="{{ $lang }}_title" value="{{$translate[$lang]['title']??''}}"
                                                class="form-control" placeholder="{{ translate('messages.Eid_Dhamaka') }}"
                                                 >
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang }}">
                                    </div>
                                @endforeach
                            @else
                                <div id="default-form">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.title') }} ({{ translate('messages.default') }})</label>
                                        <input type="text" name="title[]" maxlength="254" class="form-control"
                                            placeholder="{{ translate('messages.Eid_Dhamaka') }}">
                                    </div>
                                    <input type="hidden" name="lang[]" value="default">
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-6" id="customer_wise">
                            <div class="form-group">
                                <label class="input-label" for="select_customer">{{translate('messages.select_customer')}}</label>
                                <select required name="customer_id[]" id="select_customer"
                                class="form-control js-select2-custom"
                                multiple="multiple" placeholder="{{translate('messages.select_customer')}}">
                                <option value="all" {{in_array('all', json_decode($cashback->customer_id))?'selected':''}}>{{translate('messages.all')}} </option>
                                @foreach(\App\Models\User::get(['id','f_name','l_name']) as $user)
                                <option value="{{$user->id}}" {{in_array($user->id, json_decode($cashback->customer_id))?'selected':''}}>{{$user->f_name.' '.$user->l_name}}</option>
                            @endforeach
                            </select>

                            </div>
                        </div>



                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.Cashback_Type')}} <span class="form-label-secondary text-danger"
                                    data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Required.')}}"> *
                                    </span></label>
                                <select name="cashback_type" class="form-control"  data-mas_discount="{{ $cashback?->max_discount ?? null }}" id="cashback_type" required>
                                    <option {{ $cashback->cashback_type ==  'percentage' ? 'selected'  : '' }} value="percentage">{{translate('messages.percentage')}} (%)</option>
                                    <option {{ $cashback->cashback_type ==  'amount' ? 'selected'  : '' }} value="amount">{{translate('messages.amount')}} {{ \App\CentralLogics\Helpers::currency_symbol() }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.Cashback_Amount')}}

                                    <span  class=" {{ $cashback->cashback_type ==  'percentage' ? 'd-none'  : '' }}   " id='cuttency_symbol'>({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                    </span>
                                    <span  class=" {{ $cashback->cashback_type ==  'percentage' ? ''  : 'd-none' }}"  id="percentage">(%)</span>

                                    <span
                                    class="input-label-secondary text--title" data-toggle="tooltip"
                                    data-placement="right"
                                    data-original-title="{{ translate('Set_the_Cash_back_amount/percentage_a_customer_will_receive_after_a_successfull_order.') }}">
                                    <i class="tio-info-outined"></i>
                                </span>
                                <span class="form-label-secondary text-danger"
                                data-toggle="tooltip" data-placement="right"
                                data-original-title="{{ translate('messages.Required.')}}"> *
                                </span>

                                </label>
                                <input type="number"   step="0.01" min="1" value="{{  $cashback->cashback_amount }}" max="{{ $cashback->cashback_type ==  'percentage' ? '100'  : '999999999.99' }}"  placeholder="{{ translate('messages.Ex:_100') }}"  name="cashback_amount" id="Cash_back_amount" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.Minimum_Purchase')}} ({{ \App\CentralLogics\Helpers::currency_symbol() }})</label>
                                <input type="number" step="0.01" id="min_purchase" required name="min_purchase" value="{{ $cashback->min_purchase }}" min="0" max="999999999999.99" class="form-control"
                                placeholder="{{ translate('messages.Ex:_100') }}">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="max_discount">{{translate('messages.Maximum_Discount')}} ({{ \App\CentralLogics\Helpers::currency_symbol() }})</label>
                                <input type="number" step="0.01" min="0" placeholder="{{ translate('messages.Ex:_100') }}"  max="999999999999.99"  {{ $cashback->cashback_type ==  'percentage' ? 'required'  : 'readonly' }}   value="{{ $cashback->max_discount }}" name="max_discount" id="max_discount" class="form-control" >
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.Start_Date')}}</label>
                                <input type="date" name="start_date" value="{{ $cashback->start_date }}" class="form-control" id="date_from" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.End_Date')}}</label>
                                <input type="date" name="end_date" value="{{ $cashback->end_date }}"  class="form-control" id="date_to" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.Limit_for_Same_User')}}</label>
                                <input type="number" step="1" name="same_user_limit" value="{{ $cashback->same_user_limit }}"  value="0" min="0" max="9999999" class="form-control" required
                                placeholder="{{ translate('messages.Ex:_5') }}">
                            </div>
                        </div>

                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.Update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>


        "use strict";
        $('#reset_btn').click(function(){
            setTimeout(reset_select, 100);
        })
        function reset_select(){
            $('#select_customer').trigger('change');
            if($('#cashback_type').val() == 'amount')
                    {
                        $('#max_discount').attr("readonly","true");
                        $('#max_discount').removeAttr("required");
                        $('#percentage').addClass('d-none');
                        $('#cuttency_symbol').removeClass('d-none');
                        $('#Cash_back_amount').attr('max',99999999999);
                    }else{
                        $('#max_discount').removeAttr("readonly");
                        $('#max_discount').attr("required","true");
                        $('#percentage').removeClass('d-none');
                        $('#cuttency_symbol').addClass('d-none');
                        $('#Cash_back_amount').attr('max',100);
                    }
        }
        $(document).on('ready', function () {
            $('#date_from').attr('min',(new Date()).toISOString().split('T')[0]);
            $('#date_from').attr('max','{{date("Y-m-d",strtotime($cashback["end_date"]))}}');
            $('#date_to').attr('min','{{date("Y-m-d",strtotime($cashback["start_date"]))}}');
        });



        $(document).ready(function() {

                $('#cashback_type').on('change', function() {


                    if($('#cashback_type').val() == 'amount')
                    {
                        $('#max_discount').attr("readonly","true");
                        $('#max_discount').removeAttr("required");
                        $('#max_discount').val( $(this).data("max_discount"));
                        $('#percentage').addClass('d-none');
                        $('#cuttency_symbol').removeClass('d-none');
                        $('#Cash_back_amount').attr('max',99999999999);
                    }
                    else
                    {
                        $('#max_discount').removeAttr("readonly");
                        $('#max_discount').attr("required","true");
                        $('#percentage').removeClass('d-none');
                        $('#cuttency_symbol').addClass('d-none');
                        $('#Cash_back_amount').attr('max',100);

                    }
                });

                $('#date_from').attr('min',(new Date()).toISOString().split('T')[0]);
                $('#date_to').attr('min',(new Date()).toISOString().split('T')[0]);

                // INITIALIZATION OF SELECT2
                // =======================================================
                $('.js-select2-custom').each(function () {
                    let select2 = $.HSCore.components.HSSelect2.init($(this));
                });
            });

            $("#date_from").on("change", function () {
                $('#date_to').attr('min',$(this).val());
            });

            $("#date_to").on("change", function () {
                $('#date_from').attr('max',$(this).val());
            });




    </script>
@endpush
