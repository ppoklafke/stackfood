@extends('layouts.admin.app')

@section('title',translate('Food_Bulk_Import'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{dynamicAsset('/public/assets/admin/img/export.png')}}" alt="">
                </div>
                {{translate('messages.foods_bulk_import')}}
            </h1>
        </div>










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
                                    {{ translate('Fill_up_the_data_according_to_the_format_and_validations.') }}
                                </li>
                                <li>
                                    {{ translate('You_can_get_restaurant_id_from_their_list,_please_input_the_right_ids.') }}
                                </li>
                                <li>
                                    {{ translate('For_veg_food_enter_1_and_for_non-veg_enter_0_on_veg_field.') }}
                                </li>

                                <li>
                                    {{ translate('If_you_want_to_create_a_food_with_variation_just_create_variations_from_the_generate_variation_section_below_and_click_generate_value.') }}
                                </li>
                                <li>
                                    {{ translate('Copy_the_value_and_paste_the_the_spread_sheet_file_column_name_variation_in_the_selected_product_row.') }}
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
                                <li>
                                    {{ translate('You_can_upload_your_product_images_in_product_folder_from_gallery,_and_copy_image’s_path.') }}
                                </li>
                                <li>
                                    {{ translate('Image_file_name_must_be_in_30_character.') }}
                                 </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="text-center pb-4 mt-4">
                    <h3 class="mb-3 export--template-title">{{ translate('Download_Spreadsheet_Template')}}</h3>
                    <div class="btn--container justify-content-center export--template-btns">
                            <a href="{{dynamicAsset('public/assets/foods_bulk_format.xlsx')}}" download=""
                            class="btn btn-outline-primary">{{ translate('with_Current_Data') }}</a>
                        <a href="{{dynamicAsset('public/assets/foods_bulk_format_nodata.xlsx')}}" download=""
                            class="btn btn-primary">{{ translate('without_Any_Data') }}</a>
                    </div>
                </div>
            </div>
        </div>


        <h4 class="mb-3 mt-4">{{ translate('Excel File Upload') }}</h4>
        <div class="card">
            <div class="card-body">
                <form class="product-form" id="import_form"  action="{{route('admin.food.bulk-import')}}" method="POST"
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


        <h3 class="mb-3 mt-4">{{ translate('generate_Variation') }}</h3>
        <div class="alert alert-primary-light alert-dismissible fade show" role="alert">
            <div class="media gap-3 align-items-center">
                <img width="18" src="{{dynamicAsset('public/assets/admin/img/info-bulb.png')}}" alt="">
                <div class="media-body">
                    {{ translate('You_must_generate_variations_from_this_generator_if_you_want_to_add_variations_to_your_foods.You_must_copy_from_the_specific_filed_and_past_it_to_the_specific_column_at_your_excel_sheet.Otherwise_you_might_get_500_error_if_you_swap_or_entered_invalid_data.And_if_you_want_to_make_it_empty_then_you_have_to_enter_an_empty_array_[_]_.') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="javascript:" method="post" id="item_form" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        <div class="d-flex gap-2 align-items-center">
                            <h5 class="mb-0">{{ translate('make_Variation') }}</h5>
                            <i class="tio-info-outined cursor-pointer" data-toggle="tooltip" title="Tooltip on top"></i>
                        </div>

                        <div class="d-flex flex-wrap gap-2 align-items-center flex-grow-1 justify-content-end">
                            <div class="form-control show-code-actions d-flex gap-1">
                                <textarea name="" id="food_variation_outpot" class="w-100 border-0 bg-transparent resize-none" rows="1" readonly></textarea>
                                <div class="d-flex gap-2 align-items-center">
                                    <button type="reset" class="border rounded-circle cursor-pointer tio-clear-wrap">
                                        <i class="tio-clear" data-toggle="tooltip" title="Clear"></i>
                                    </button>
                                    <div class="copy_button cursor-pointer">
                                        <i class="tio-copy" data-toggle="tooltip" title="Copy"></i>
                                    </div>
                                    <div class="cursor-pointer" data-toggle="modal" data-target="#viewModal">
                                        <i class="tio-invisible" data-toggle="tooltip" title="View"></i>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn--warning">{{translate('Generate Value')}}</button>
                        </div>
                    </div>

                    <div id="food_variation_section">
                        <div id="add_new_option">
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <a class="btn btn-primary px-4" id="add_new_option_button">{{ translate('add_new_variation') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">{{ translate('generated_Value') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div  class="modal-body">
                <textarea name="" id="modal_body" rows="6"  class="w-100 border-0 bg-transparent resize-none"  readonly></textarea>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/product-import.js"></script>
    <script>
        "use strict";

        $(document).ready(function() {
            $(".copy_button").click(function() {
                var textareaValue = $("#food_variation_outpot").val();
                copyToClipboard(textareaValue);
                $(this).addClass("copied").find('i').attr('title', 'Copied');
            });

            function copyToClipboard(value) {
                var $temp = $("<textarea>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
            }


            $("#add_new_option_button").click(function(e) {
                count++;
                let add_option_view = `
                <div class="card view_new_option mb-2" >
                    <div class="card-header">
                        <label for="" id=new_option_name_` + count + `> {{ translate('add_new') }}</label>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-lg-3 col-md-6">
                                <label for="">{{ translate('name') }}</label>
                                <input required name=options[` + count + `][name] class="form-control new_option_name" type="text" data-count="`+ count +`">
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label class="input-label text-capitalize d-flex align-items-center gap-2">
                                        <span class="line--limit-1">{{ translate('messages.selcetion_type') }} </span>
                                        <i class="tio-info-outined cursor-pointer" data-toggle="tooltip" title="Tooltip on top"></i>
                                    </label>
                                    <select  name="options[` + count + `][type]" id="type` + count +`" data-count="`+count+`" class="form-control select_options">
                                        <option value="multi">{{translate('multiple_Selcetion')}}</option>
                                        <option value="single">{{translate('single_Selcetion')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="row g-2">
                                    <div class="col-sm-6 col-md-4">
                                        <label class="input-label text-capitalize d-flex align-items-center gap-2">
                                            <span class="line--limit-1">{{ translate('messages.min') }} </span>
                                            <i class="tio-info-outined cursor-pointer" data-toggle="tooltip" title="Tooltip on top"></i>
                                        </label>

                                        <input id="min_max1_` + count + `" required  name="options[` + count + `][min]" class="form-control" type="number" min="1">
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <label class="input-label text-capitalize d-flex align-items-center gap-2">
                                            <span class="line--limit-1">{{ translate('messages.max') }} </span>
                                            <i class="tio-info-outined cursor-pointer" data-toggle="tooltip" title="Tooltip on top"></i>
                                        </label>

                                        <input id="min_max2_` + count + `"   required name="options[` + count + `][max]" class="form-control" type="number" min="1">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="d-md-block d-none">&nbsp;</label>
                                            <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <input id="options[` + count + `][required]" name="options[` + count + `][required]" type="checkbox">
                                                <label for="options[` + count + `][required]" class="m-0">{{ translate('Required') }}</label>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-danger btn-sm delete_input_button"
                                                    title="{{ translate('Delete') }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="option_price_` + count + `" class="bg-light">
                            <div class="border rounded p-3 pb-0 mt-3">
                                <div  id="option_price_view_` + count + `">
                                    <div class="row g-3 add_new_view_row_class mb-3">
                                        <div class="col-md-4 col-sm-6">
                                            <label for="">{{ translate('Option_name') }}</label>
                                            <input class="form-control" required type="text" name="options[` + count + `][values][0][label]" id="">
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <label for="">{{ translate('Additional_price') }}</label>
                                            <input class="form-control" required type="number" min="0" step="0.01" name="options[` + count + `][values][0][optionPrice]" id="">
                                        </div>
                                    </div>
                                </div>
                                <div class="pb-3"  id="add_new_button_` + count + `">
                                    <button type="button" class="btn add_new_row_button p-0 d-flex align-items-center gap-2 pb-3" data-count="`+ count +`" >
                                        <i class="tio-add-square text-primary"></i>
                                        {{ translate('add_More_Option') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;

                $("#add_new_option").append(add_option_view);
            });
        });

        function add_new_row_button(data) {
            count = data;
            countRow = 1 + $('#option_price_view_' + data).children('.add_new_view_row_class').length;
            let add_new_row_view = `
            <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-sm-0">
                <div class="col-md-4 col-sm-5">
                        <label for="">{{ translate('Option_name') }}</label>
                        <input class="form-control" required type="text" name="options[` + count + `][values][` +
                countRow + `][label]" id="">
                    </div>
                    <div class="col-md-4 col-sm-5">
                        <label for="">{{ translate('Additional_price') }}</label>
                        <input class="form-control"  required type="number" min="0" step="0.01" name="options[` +
                count +
                `][values][` + countRow + `][optionPrice]" id="">
                    </div>
                    <div class="col-sm-2 max-sm-absolute">
                        <label class="d-none d-sm-block">&nbsp;</label>
                        <div class="mt-1">
                            <button type="button" class="btn btn-danger btn-sm deleteRow"
                                title="{{ translate('Delete') }}">
                                <i class="tio-add-to-trash"></i>
                            </button>
                        </div>
                </div>
            </div>`;
            $('#option_price_view_' + data).append(add_new_row_view);

        }

        $('#item_form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.food.food-variation-generate') }}',
                data: $('#item_form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#loading').hide();
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        $('#food_variation_outpot').val(data.variation)
                        $('#modal_body').val(data.variation)
                    }
                }
            });
        });

        $('[name="upload_type"]').on('change', function() {
            $('[name="upload_type"]').parent().removeClass('active');
            $(this).parent().addClass('active');
        });

        $(document).on("click", ".update_or_import", function(e){
            e.preventDefault();
            let upload_type = $('input[name="upload_type"]:checked').val();
            myFunction(upload_type)
        });

        function myFunction(data) {
            Swal.fire({
            title: '{{ translate('Are_you_sure?') }}' ,
            text: "{{ translate('You_want_to_') }}" +data,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ translate('no') }}',
            confirmButtonText: '{{ translate('yes') }}',
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
        $(document).on('change', '.select_options', function () {
            if($(this).val() == 'single'){
                hide_min_max($(this).data('count'));
            }
            else{
                show_min_max($(this).data('count'));
            }
        });

    </script>
@endpush
