@extends('layouts.admin.app')
@section('title', translate('add_Offline_Payment_Method'))

@section('content')

    <div class="content container-fluid">
        <div class="mb-0 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset('/public/assets/admin/img/3rd-party.png')}}" alt="">
                {{translate('Add_Offline_Payment_Method')}}
            </h2>
        </div>

                    <form action="{{ route('admin.business-settings.offline.store') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-end mb-3 mt-3">
                            <h4 class="text--primary d-flex flex-wrap align-items-center " id="bkashInfoModalButton">
                                    {{ translate('Section_View') }}
                                <div class="ml-2 blinkings">
                                    <i class="tio-info-outined"></i>
                                </div>
                            </h4>
                        </div>
                        <div class="card">
                            <div class="card-header d-flex  justify-content-between">
                                <div class="d-flex align-items-center gap-2">

                                    <img width="25" src="{{dynamicAsset('/public/assets/admin/img/payment-card.png')}}" alt="">
                                    <h4 class="page-title mt-2">{{translate('payment_information')}}</h4>
                                </div>
                                <button class="btn btn--primary" id="add-more-field-payment">
                                    <i class="tio-add"></i> {{ translate('Add_New_Field') }}
                                </button>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-xl-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="method_name" class="title_color">{{ translate('payment_Method_Name') }}</label>
                                            <input type="text" class="form-control text-break" id="method_name" placeholder="{{ translate('ex:_bkash') }}" name="method_name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-3" id="custom-field-section-payment"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-3 mt-4">
                            <h4 class="d-flex gap-2 justify-content-end text--primary  fw-bold" id="paymentInfoModalButton">
                                {{ translate('Section_View') }}
                                <div class="ml-2 blinkings">
                                    <i class="tio-info-outined"></i>
                                </div>
                            </h4>
                        </div>
                        <div class="card">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div class="d-flex align-items-center gap-2">
                                <img width="25" src="{{dynamicAsset('/public/assets/admin/img/payment-card-fill.png')}}" alt="">
                                <h4 class="page-title mt-2">{{translate('Required Information from Customer')}}</h4>
                                </div>
                                <button class="btn btn--primary" id="add-more-field-customer">
                                    <i class="tio-add"></i> {{ translate('Add_New_Field') }}
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-xl-4 col-sm-6">
                                        <label for="payment_note">
                                            <span>
                                                {{translate('Payment_Note')}}
                                                <span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.This_Section_will_be_field_by_the_customer.') }}" alt="">
                                                    <img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="">
                                                </span>
                                            </span>


                                        </label>
                                        <div class="form-floating">
                                            <textarea class="form-control" name="payment_note" id="payment_note"
                                                placeholder="{{ translate('Ex:ABC_Company') }}"  disabled></textarea>
                                        </div>
                                    </div>
                                </div>

                                    <div class="customer-input-fields-section" id="custom-field-section-customer"></div>
                            </div>
                        </div>


                        <div class="btn--container justify-content-end mt-3">
                            <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                            <button type="submit" class="btn btn--primary demo_check">{{translate('Submit')}}</button>
                        </div>
                    </form>
                </div>


    <div class="modal fade" id="sectionViewModal" tabindex="-1" aria-labelledby="sectionViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-end border-0">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
            <div class="modal-body">
            <div class="d-flex align-items-center flex-column gap-3 text-center">
                <h3>{{translate('Offline Payment')}}
                </h3>
                <img width="100" src="{{dynamicAsset('public/assets/admin/img/offline_payment.png')}}" alt="">
                <p class="text-muted">{{translate('This view is from the user app.')}} <br class="d-none d-sm-block"> {{translate('This_is_how_customer_will_see_in_the_app')}}</p>
            </div>

            <div class="rounded p-4 mt-3" id="offline_payment_top_part">
                <div class="card border-primary">
                    <div class="card-body">
                <div class="d-flex justify-content-between gap-2 mb-3">
                    <h4 id="payment_modal_method_name"><span></span></h4>
                    <div class="text-primary d-flex align-items-center gap-2">
                        {{translate('Pay_on_this_account')}}
                        <img width="25" src="{{dynamicAsset('public/assets/admin/img/tick.png')}}" alt="">
                    </div>
                </div>

                <div class="d-flex text-wrap flex-column gap-2" id="methodNameDisplay"> </div>
                <div class="d-flex text-wrap flex-column gap-2" id="displayDataDiv"> </div>
            </div>
            </div>
        </div>

            <div class="rounded p-4 mt-3 mt-4" id="offline_payment_bottom_part">
                <h2 class="text-center mb-4">{{translate('Amount')}} : xxx</h2>

                <h4 class="mb-3">{{translate('Payment_Info')}}</h4>
                <div class="d-flex flex-column gap-3 mb-3" id="customer-info-display-div">

                </div>
                <div class="d-flex flex-column gap-3">
                    <textarea name="payment_note" id="payment_note" class="form-control"
                        readonly rows="10" placeholder="Note"></textarea>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>


@endsection


@push('script_2')

    <script src="{{dynamicAsset('public/assets/admin/js/view-pages/offline-payment.js')}}"></script>

    <script>
        "use strict";
        jQuery(document).ready(function ($) {
            let counter = 0;
            let counterPayment = 0;

            $('#add-more-field-customer').on('click', function (event) {
                if(counter < 14) {
                    event.preventDefault();

                    $('#custom-field-section-customer').append(
                        `<div id="field-row-customer--${counter}" class="field-row-customer">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{translate('input_field_name')}} *</label>
                                        <input type="text" class="form-control" name="customer_input[${counter}]"
                                        placeholder="{{ translate('ex') }}: {{ translate('payment_By') }}" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{translate('placeholder')}} *</label>
                                        <input type="text" class="form-control" name="customer_placeholder[${counter}]"
                                        placeholder="{{ translate('ex') }}: {{ translate('Enter Name') }}" value="" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between gap-2">
                                            <div class="form-check text-start mb-3">
                                            <input class="form-check-input" type="checkbox" value="1" name="is_required[${counter}]" id="flexCheckDefault__${counter}" checked>
                                            <label class="form-check-label" for="flexCheckDefault__${counter}">
                                                {{translate('is_required_?')}}
                                            </label>
                                        </div>
                                        <span class="btn action-btn btn--danger btn-outline-danger remove-field"  data-id="${counter}" style="cursor: pointer;">
                                            <i class="tio-delete-outlined"></i>
                                        </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>`
                    );

                    $(".js-select").select2();

                    counter++;
                } else {
                    Swal.fire({
                        title: '{{translate('Reached maximum')}}',
                        confirmButtonText: '{{translate('ok')}}',
                    });
                }
            })

            $('#add-more-field-payment').on('click', function (event) {
                if(counterPayment < 14) {
                    event.preventDefault();

                    $('#custom-field-section-payment').append(
                        `<div id="field-row-payment--${counterPayment}" class="field-row-payment">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title_color">{{ translate('Title') }}</label>
                                    <input type="text" name="input_name[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('Bank_Name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input_data" class="title_color">{{ translate('Data') }}</label>
                                    <input type="text" name="input_data[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('ABC_bank') }}" required>
                                </div>
                            </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <div class="d-flex justify-content-end">
                                        <span class="btn action-btn btn--danger btn-outline-danger remove-field-payment" data-id="${counterPayment}"  style="cursor: pointer;">
                                            <i class="tio-delete-outlined"></i>
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>`
                    );

                    $(".js-select").select2();

                    counterPayment++;
                } else {
                    Swal.fire({
                        title: '{{translate('Reached maximum')}}',
                        confirmButtonText: '{{translate('ok')}}',
                    });
                }
            })

            $('form').on('reset', function () {
                if(counter > 1) {
                    $('#custom-field-section-payment').html("");
                    $('#custom-field-section-customer').html("");
                    $('#method_name').val("");
                    $('#payment_note').val("");
                }

                counter = 1;
            })

            $(document).on('click', '.remove-field-payment', function () {
                let fieldRowId=  $(this).data('id');
                $( `#field-row-payment--${fieldRowId}` ).remove();
                counterPayment--;

            });
            $(document).on('click', '.remove-field', function () {
                let fieldRowId=  $(this).data('id');
                $( `#field-row-customer--${fieldRowId}` ).remove();
                counter--;

            });
        });

    </script>


@endpush
