@extends('layouts.admin.app')

@section('title',translate('AddOn_Bulk_Import'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <div class="page-header">
            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{dynamicAsset('/public/assets/admin/img/export.png')}}" alt="">
                </div>
                {{translate('messages.addons_bulk_import')}}
            </h1>
        </div>
        <!-- Content Row -->
        <div class="card">
            <div class="card-body p-2 p-xl-3">

                <div class="row gy-2">
                    <div class="col-lg-4">
                        <div class="border rounded p-3 p-xl-4">
                            <div class="d-flex justify-content-between gap-2 mb-4">
                                <div class="d-flex flex-column gap-1">
                                    <h2 class="mb-0 font-weight-normal">{{ translate('messages.step_1') }}</h2>
                                    <div class="text-capitalize">{{ translate('messages.download_the_excel_file') }}</div>
                                </div>
                                <img width="60" src="{{dynamicAsset('/public/assets/admin/img/bulk1.png')}}" alt="">
                            </div>

                            <h5 class="mb-3">{{ translate('messages.instruction') }}</h5>
                            <ul class="pl-4">
                                <li>
                                    {{ translate('Download_the_format_file_and_fill_it_with_proper_data.') }}
                                </li>
                                <li>
                                    {{ translate('You_can_download_the_example_file_to_understand_how_the_data_must_be_filled.') }}
                                </li>
                                <li>
                                    {{ translate('Have_to_upload_excel_file.') }}
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="border rounded p-3 p-xl-4">
                            <div class="d-flex justify-content-between gap-2 mb-4">
                                <div class="d-flex flex-column gap-1">
                                    <h2 class="mb-0 font-weight-normal">{{ translate('messages.step_2') }}</h2>
                                    <div class="text-capitalize">{{ translate('messages.Match Spread sheet data according to instruction') }}</div>
                                </div>
                                <img width="60" src="{{dynamicAsset('/public/assets/admin/img/bulk2.png')}}" alt="">
                            </div>

                            <h5 class="mb-3">{{ translate('messages.instruction') }}</h5>
                            <ul class="pl-4">
                                <li>
                                    {{ translate('Fill_up_the_data_according_to_the_format') }}
                                </li>
                                <li>
                                    {{ translate('You_can_get_restaurant_id_from_their_list_please_input_the_right_ids')}}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="border rounded p-3 p-xl-4">
                            <div class="d-flex justify-content-between gap-2 mb-4">
                                <div class="d-flex flex-column gap-1">
                                    <h2 class="mb-0 font-weight-normal">{{ translate('messages.step_3 ') }}</h2>
                                    <div class="text-capitalize">{{ translate('messages.Validate data and complete import') }}</div>
                                </div>
                                <img width="60" src="{{dynamicAsset('/public/assets/admin/img/bulk3.png')}}" alt="">
                            </div>

                            <h5 class="mb-3">{{ translate('messages.instruction') }}</h5>
                            <ul class="pl-4">
                                <li>
                                    {{ translate('In_the_Excel_file_upload_section,_first_select_the_upload_option.') }}
                                 </li>
                                 <li>
                                    {{ translate('Upload_your_file_in_.xls,_.xlsx_format.') }}
                                 </li>
                                 <li>
                                    {{ translate('Finally_click_the_upload_button.') }}
                                 </li>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="text-center pb-4 mt-4">
                    <h3 class="mb-3 export--template-title">{{ translate('Download_Spreadsheet_Template')}}</h3>
                    <div class="btn--container justify-content-center export--template-btns">
                        <a href="{{dynamicAsset('public/assets/addons_bulk_format.xlsx')}}" download=""
                            class="btn btn-outline-primary">{{ translate('with_Current_Data') }}</a>
                        <a href="{{dynamicAsset('public/assets/addons_bulk_format_nodata.xlsx')}}" download=""
                            class="btn btn-primary">{{ translate('without_Any_Data') }}</a>
                    </div>
                </div>
            </div>
        </div>


        <h4 class="mb-3 mt-4">{{ translate('Excel File Upload') }}</h4>
        <div class="card">
            <div class="card-body">
                <form class="product-form" id="import_form"  action="{{route('admin.addon.bulk-import')}}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="button" id="btn_value">
                <div class="row gy-2">
                    <div class="col-lg-6">
                        <h5 class="mb-3">{{ translate('Select Data Upload type') }}</h5>

                        <div class="border rounded">
                            <div class="radio-wrap d-flex justify-content-between gap-3 p-3 active">
                                <label class="form-check-label flex-grow-1" for="update_new_data">{{translate('Upload New Data')}}</label>
                                <input  value="import" type="radio" name="upload_type" id="update_new_data"  checked>
                            </div>
                            <div class="radio-wrap d-flex justify-content-between gap-3 p-3">
                                <label class="form-check-label flex-grow-1" for="update_ex_data">{{translate('Update Existing Data')}}</label>
                                <input value="update" type="radio" name="upload_type" id="update_ex_data">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex flex-column align-items-center">
                            <div class="mw-100">
                                <h5 class="mb-3">{{ translate('Import items file') }}</h5>

                                <div class="image-box banner">
                                    <label for="upload_excel" class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-2">
                                        <img width="54" class="upload-icon" src="{{dynamicAsset('public/assets/admin/img/excel-upload.png')}}" alt="Upload Icon">
                                        <span class="upload-text px-2 filename text-center">{{ translate('Must be Excel files using our Excel template above')}}</span>
                                    </label>

                                    <input type="file" id="upload_excel" name="upload_excel" accept=".xls, .xlsx" hidden>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-3 mt-3">
                            <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                            <button type="submit" name="button"  class="btn btn-primary update_or_import">{{translate('messages.Import')}}</button>
                        </div>
                    </div>
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
            $('#bulk__import').val(null);
        })

        $(document).on("click", ".submit_btn", function(e){
            e.preventDefault();
                let data = $(this).val();
                myFunction(data)
        });
        function myFunction(data) {
            Swal.fire({
            title: '{{ translate('messages.Are_you_sure?') }}' ,
            text: "{{ translate('You_want_to_') }}" +data,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ translate('No') }}',
            confirmButtonText: '{{ translate('Yes') }}',
            reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#btn_value').val(data);
                    $("#import_form").submit();
                }
            })
        }
        $('#reset_btn').click(function(){
            $('#upload_excel').val('');
            $('.filename').text('{{translate('Must_be_Excel_files_using_our_Excel_template_above')}}');
        })
        $('[name="upload_type"]').on('change', function() {
            $('[name="upload_type"]').parent().removeClass('active');
            $(this).parent().addClass('active');
        });

        $(document).on("click", ".update_or_import", function(e){
            e.preventDefault();
            let upload_type = $('input[name="upload_type"]:checked').val();
            myFunction(upload_type)
        });
    </script>
@endpush
