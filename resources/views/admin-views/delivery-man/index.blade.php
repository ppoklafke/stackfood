@extends('layouts.admin.app')

@section('title', translate('messages.add_delivery_man'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mb-2 text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{dynamicAsset('/public/assets/admin/img/delivery-man.png')}}" alt="public">
                </div>
                <span>
                    {{ translate('messages.add_new_deliveryman') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <form id="deliveryman-form" method="post" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title gap-1">
                        <span class="card-title-icon"><i class="tio-user"></i></span>
                        <span>
                            {{ translate('messages.general_info') }}
                        </span>
                    </h5>
                </div>
                @csrf
                <div class="card-body pb-2">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.first_name') }}</label>
                                        <input type="text" name="f_name" class="form-control h--45px"
                                            placeholder="{{ translate('Ex:_Jhone') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.last_name') }}</label>
                                        <input type="text" name="l_name" class="form-control h--45px"
                                            placeholder="{{ translate('Ex:_Joe') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.email') }}</label>
                                        <input type="email" name="email" class="form-control h--45px"
                                            placeholder="{{ translate('Ex:_ex@example.com') }}" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.deliveryman_type') }}</label>
                                        <select name="earning" class="form-control h--45px">
                                            <option value="" readonly="true" hidden="true">{{ translate('messages.delivery_man_type') }}</option>
                                            <option value="1">{{ translate('messages.freelancer') }}</option>
                                            <option value="0">{{ translate('messages.salary_based') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.zone') }}</label>
                                        <select name="zone_id" class="form-control js-select2-custom h--45px" required
                                            data-placeholder="{{ translate('messages.select_zone') }}">
                                            <option value="" readonly="true" hidden="true">{{ translate('Ex:_XYZ_Zone') }}</option>
                                            @foreach (\App\Models\Zone::where('status',1)->get(['id','name']) as $zone)
                                                @if (isset(auth('admin')->user()->zone_id))
                                                    @if (auth('admin')->user()->zone_id == $zone->id)
                                                        <option value="{{ $zone->id }}" selected>{{ $zone->name }}
                                                        </option>
                                                    @endif
                                                @else
                                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-4">
                            <div class="d-flex flex-column align-items-center gap-3">
                                <p class="mb-0">{{ translate('image') }}</p>

                                <div class="image-box">
                                    <label for="image-input" class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-2">
                                    <img width="30" class="upload-icon" src="{{dynamicAsset('public/assets/admin/img/upload-icon.png')}}" alt="Upload Icon">
                                    <span class="upload-text">{{ translate('Upload Image')}}</span>
                                    <img src="#" alt="Preview Image" class="preview-image">
                                    </label>
                                    <button type="button" class="delete_image">
                                    <i class="tio-delete"></i>
                                    </button>
                                    <input type="file" id="image-input" name="image" accept="image/*" hidden>
                                </div>

                                <p class="opacity-75 max-w220 mx-auto text-center">
                                    {{ translate('Image format - jpg png jpeg gif Image Size -maximum size 2 MB Image Ratio - 1:1')}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title gap-1">
                        <span class="card-title-icon"><i class="tio-user"></i></span>
                        <span>
                            {{ translate('messages.Identification_Information') }}
                        </span>
                    </h5>
                </div>
                @csrf
                <div class="card-body pb-2">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="form-group m-0">
                                <label class="input-label">{{ translate('messages.Vehicle') }}</label>
                                <select name="vehicle_id" class="form-control js-select2-custom h--45px" required
                                    data-placeholder="{{ translate('messages.select_vehicle') }}">
                                    <option value="" readonly="true" hidden="true">{{ translate('messages.select_vehicle') }}</option>
                                    @foreach (\App\Models\Vehicle::where('status',1)->get(['id','type']) as $v)
                                                <option value="{{ $v->id }}" >{{ $v->type }}
                                                </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group m-0">
                                <label class="input-label">{{ translate('messages.identity_type') }}</label>
                                <select name="identity_type" class="form-control h--45px">
                                    <option value="passport">{{ translate('messages.passport') }}</option>
                                    <option value="driving_license">{{ translate('messages.driving_license') }}</option>
                                    <option value="nid">{{ translate('messages.nid') }}</option>
                                    <option value="restaurant_id">{{ translate('messages.restaurant_id') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group m-0">
                                <label class="input-label">{{ translate('messages.identity_number') }}</label>
                                <input type="text" name="identity_number" class="form-control h--45px"
                                    placeholder="{{ translate('Ex:_DH-23434-LS') }}" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group m-0">
                                <label class="input-label">{{ translate('messages.identity_image') }}</label>



                                <div class="row g-2" id="additional_Image_Section">
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <div class="custom_upload_input position-relative border-dashed-2">
                                            <input type="file" name="identity_image[]" class="custom-upload-input-file action-add-more-image"
                                                   data-index="1" data-imgpreview="additional_Image_1"
                                                   accept=".jpg, .png, .webp, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                   data-target-section="#additional_Image_Section"
                                            >

                                            <span class="delete_file_input delete_file_input_section btn btn-outline-danger btn-sm square-btn d-none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview z-index-2">
                                                <img id="additional_Image_1" class="bg-white d-none"
                                                     src="{{ dynamicAsset('public/assets/admin/img/upload-icon.png-dummy') }}" alt="">
                                            </div>
                                            <div
                                                class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div
                                                    class="d-flex flex-column justify-content-center align-items-center">
                                                    <img alt="" width="30"
                                                         src="{{ dynamicAsset('public/assets/admin/img/upload-icon.png') }}">
                                                    <div class="text-muted mt-3">{{ translate('Upload_Picture') }}</div>
                                                    <div class="fs-10 text-muted mt-1">{{translate('Upload jpg, png, jpeg, gif maximum 2 MB')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @if (isset($page_data) && count($page_data) > 0 )
                <div class="card shadow--card-2 mt-3">
                    <div class="card-header">
                        <h4 class="card-title m-0 d-flex align-items-center">
                             <span class="card-header-icon mr-2">
                                <i class="tio-user"></i>
                            </span>
                            <span>{{ translate('messages.Additional_Data') }}</span>
                        </h4>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            @foreach ( data_get($page_data,'data',[])  as $key=>$item)
                                @if (!in_array($item['field_type'], ['file' , 'check_box']) )
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="{{ $item['input_data'] }}">{{translate($item['input_data'])  }}</label>
                                            <input id="{{ $item['input_data'] }}" {{ $item['is_required']  == 1? 'required' : '' }} type="{{ $item['field_type'] == 'phone' ? 'tel': $item['field_type'] }}" name="additional_data[{{ $item['input_data'] }}]" class="form-control h--45px"
                                                placeholder="{{ translate($item['placeholder_data']) }}"
                                            >
                                        </div>
                                    </div>
                                @elseif ($item['field_type'] == 'check_box' )
                                    @if ($item['check_data'] != null)
                                    <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for=""> {{translate($item['input_data'])  }} </label>
                                        @foreach ($item['check_data'] as $k=> $i)
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" name="additional_data[{{ $item['input_data'] }}][]"  class="form-check-input" value="{{ $i }}"> {{ translate($i) }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    </div>
                                    @endif
                                @elseif ($item['field_type'] == 'file' )
                                    @if ($item['media_data'] != null)
                                    <?php
                                    $image= '';
                                    $pdf= '';
                                    $docs= '';
                                        if(data_get($item['media_data'],'image',null)){
                                            $image ='.jpg, .jpeg, .png,';
                                        }
                                        if(data_get($item['media_data'],'pdf',null)){
                                            $pdf =' .pdf,';
                                        }
                                        if(data_get($item['media_data'],'docs',null)){
                                            $docs =' .doc, .docs, .docx' ;
                                        }
                                        $accept = $image.$pdf. $docs ;
                                    ?>
                                        <div class="col-md-4 col-12 image_count_{{ $key }}" data-id="{{ $key }}" >
                                            <div class="form-group">
                                                <label class="form-label" for="{{ $item['input_data'] }}">{{translate($item['input_data'])  }}</label>
                                                <input id="{{ $item['input_data'] }}" {{ $item['is_required']  == 1? 'required' : '' }} type="{{ $item['field_type'] }}" name="additional_documents[{{ $item['input_data'] }}][]" class="form-control h--45px"
                                                    placeholder="{{ translate($item['placeholder_data']) }}"
                                                        {{ data_get($item['media_data'],'upload_multiple_files',null) ==  1  ? 'multiple' : '' }} accept="{{ $accept ??  '.jpg, .jpeg, .png'  }}"
                                                    >

                                            {{-- <div class="d-flex align-items-end d-flex flex-wrap gap-3 "id="additional_Image_Section2">
                                                    <div class="mw-100" >
                                                        <p class="mb-2 form-label">{{translate($item['input_data'])  }}</p>
                                                        <div class="image-box banner" >
                                                            <label class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-2">
                                                                <img  width='30' id="additional_data_Image_1" class="upload-icon " src="{{dynamicAsset('public/assets/admin/img/upload-icon.png')}}" alt="Upload Icon">
                                                                <span class="upload-text text-center px-2" data-text="{{ translate('Select a file')}}">{{ translate('Select a file')}}</span>
                                                                <span class="upload-text2 text-center px-2" data-text="{{ translate('JPG, PNG or PDF, file size no more than 2MB')}}">{{ translate('JPG, PNG or PDF, file size no more than 2MB')}}</span>
                                                                <img src="#" alt="Preview Image" class="preview-image">
                                                                <input id="{{ $item['input_data'] }}" {{ $item['is_required']  == 1? 'required' : '' }} type="file" name="additional_documents[{{ $item['input_data'] }}][]" class="image-input action-add-more-image2 custom-upload-input-file2"
                                                    placeholder="{{ translate($item['placeholder_data']) }}"
                                                        {{ data_get($item['media_data'],'upload_multiple_files',null) ==  1  ? 'multiple' : '' }} accept="{{ $accept ??  '.jpg, .jpeg, .png'  }}" hidden

                                                        data-target-section="#additional_Image_Section2"
                                                        data-index="1" data-imgpreview="additional_data_Image_1"
                                                    >
                                                            </label>
                                                            <button type="button" class="delete_image delete_file_input delete_file_input_section">
                                                                <i class="tio-delete"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div> --}}


                                                {{-- //new design --}}
                                                {{-- <div class="row g-2" id="additional_Image_Section2">
                                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                                        <p class="mb-2 form-label">{{translate($item['input_data'])  }}</p>
                                                        <div class="custom_upload_input position-relative border-dashed-2">
                                                            <input type="file" name="additional_documents[{{ $item['input_data'] }}][]" class="custom-upload-input-file2 action-add-more-image2"
                                                                   data-index="1" data-imgpreview="additional_data_Image_1"
                                                                   accept="{{ $accept ??  '.jpg, .jpeg, .png'  }}"
                                                                   data-media_accepts="{{ $accept ??  '.jpg, .jpeg, .png'  }}"
                                                                   data-target-section="#additional_Image_Section2"
                                                                   data-image_count_key="{{ $key }}"
                                                            >

                                                            <span class="delete_file_input delete_file_input_section btn btn-outline-danger btn-sm square-btn d-none">
                                                                <i class="tio-delete"></i>
                                                            </span>

                                                            <div class="img_area_with_preview z-index-2">
                                                                <img id="additional_data_Image_1" class="bg-white d-none"
                                                                     src="{{ dynamicAsset('public/assets/admin/img/upload-icon.png-dummy') }}" alt="">
                                                            </div>
                                                            <div
                                                                class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                                <div
                                                                    class="d-flex flex-column justify-content-center align-items-center">
                                                                    <img alt="" width="30"
                                                                         src="{{ dynamicAsset('public/assets/admin/img/upload-icon.png') }}">
                                                                    <div class="text-muted mt-3">{{ translate('Upload_Picture') }}</div>
                                                                    <div class="fs-10 text-muted mt-1">{{translate('Upload jpg, png, jpeg, gif maximum 2 MB')}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}




                                                </div>

                                        </div>
                                    @endif
                                @endif
                            @endforeach


                        </div>
                    </div>
                </div>
            @endif


            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon"><i class="tio-user"></i></span>
                        <span>{{ translate('messages.account_info') }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <label class="input-label" for="phone">{{ translate('messages.phone') }}</label>
                                <div class="input-group">
                                    <input type="tel" name="phone" id="phone" placeholder="{{ translate('Ex:_017********') }}"
                                        class="form-control h--45px" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <div class="js-form-message form-group">
                                    <label class="input-label"
                                        for="signupSrPassword">{{ translate('messages.password') }}
                                        <span class="input-label-secondary ps-1" data-toggle="tooltip" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>

                                    </label>

                                    <div class="input-group input-group-merge">
                                        <input type="password" class="js-toggle-password form-control h--45px" name="password"
                                            id="signupSrPassword"
                                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                            placeholder="{{ translate('messages.Ex:_8+_Character') }}"
                                            aria-label="{{translate('messages.password_length_8+')}}"
                                            required data-msg="Your password is invalid. Please try again."
                                            data-hs-toggle-password-options='{
                                                                                "target": [".js-toggle-password-target-1"],
                                                                                "defaultClass": "tio-hidden-outlined",
                                                                                "showClass": "tio-visible-outlined",
                                                                                "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                                                                }'>
                                        <div class="js-toggle-password-target-1 input-group-append">
                                            <a class="input-group-text" href="javascript:;">
                                                <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Password Rules: Hidden initially -->
                                    <ul id="password-rules" class="mt-2 small list-unstyled text-muted" style="display: none;">
                                        <li id="rule-length"><i class="text-danger">&#10060;</i> {{ translate('At least 8 characters') }}</li>
                                        <li id="rule-lower"><i class="text-danger">&#10060;</i> {{ translate('At least one lowercase letter') }}</li>
                                        <li id="rule-upper"><i class="text-danger">&#10060;</i> {{ translate('At least one uppercase letter') }}</li>
                                        <li id="rule-number"><i class="text-danger">&#10060;</i> {{ translate('At least one number') }}</li>
                                        <li id="rule-symbol"><i class="text-danger">&#10060;</i> {{ translate('At least one symbol') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- This is Static -->
                        <div class="col-md-4">
                            <div class="js-form-message form-group">
                                <label class="input-label"
                                    for="signupSrConfirmPassword">{{ translate('messages.confirm_password') }}</label>

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control h--45px" name="confirmPassword"
                                        id="signupSrConfirmPassword"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                        placeholder="{{ translate('messages.Ex:_8+_Character') }}"
                                        aria-label="{{translate('messages.password_length_8+')}}"
                                        required data-msg="Password does not match the confirm password."
                                        data-hs-toggle-password-options='{
                                                                                "target": [".js-toggle-password-target-2"],
                                                                                "defaultClass": "tio-hidden-outlined",
                                                                                "showClass": "tio-visible-outlined",
                                                                                "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                                                }'>
                                    <div class="js-toggle-password-target-2 input-group-append">
                                        <a class="input-group-text" href="javascript:;">
                                            <i class="js-toggle-passowrd-show-icon-2 tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- Feedback for match/mismatch -->
                                <small id="confirm-password-feedback" class="text-danger d-none">{{ translate('Passwords do not match.') }}</small>
                            </div>
                        </div>
                        <!-- This is Static -->
                    </div>
                </div>
            </div>
            <div class="btn--container mt-4 justify-content-end">
                <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                <button type="submit" class="btn btn--primary submitBtn">{{ translate('messages.submit') }}</button>
            </div>
        </form>
    </div>

</div>


@endsection

@push('script_2')

    <script>
        "use strict";
        let elementCustomUploadInputFileByID = $('.custom-upload-input-file');
        let elementCustomUploadInputFileByID2 = $('.custom-upload-input-file2');

        $('.action-add-more-image').on('change', function () {
            let parentDiv = $(this).closest('div');
            parentDiv.find('.delete_file_input').removeClass('d-none');
            parentDiv.find('.delete_file_input').fadeIn();
            addMoreImage(this, $(this).data('target-section'))
        })
        $('.action-add-more-image2').on('change', function () {
            let parentDiv = $(this).closest('div');
            parentDiv.find('.delete_file_input').removeClass('d-none');
            parentDiv.find('.delete_file_input').fadeIn();
            addMoreImage2(this, $(this).data('target-section') )
        })

        function addMoreImage(thisData, targetSection) {
            let $fileInputs = $(targetSection + " input[type='file']");
            let nonEmptyCount = 0;
            $fileInputs.each(function () {
                if (parseFloat($(this).prop('files').length) === 0) {
                    nonEmptyCount++;
                }
            });

            uploadColorImage(thisData)

            if (nonEmptyCount === 0) {

                let datasetIndex = thisData.dataset.index + 1;

                let newHtmlData = `<div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="custom_upload_input position-relative border-dashed-2">
                                    <input type="file" name="${thisData.name}" class="custom-upload-input-file action-add-more-image" data-index="${datasetIndex}" data-imgpreview="additional_Image_${datasetIndex}"
                                        accept=".jpg, .webp, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" data-target-section="${targetSection}">

                                    <span class="delete_file_input delete_file_input_section btn btn-outline-danger btn-sm square-btn d-none">
                                        <i class="tio-delete"></i>
                                    </span>

                                    <div class="img_area_with_preview position-absolute z-index-2 border-0">
                                        <img alt="" id="additional_Image_${datasetIndex}" class="bg-white d-none" src="img">
                                    </div>
                                    <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <img alt="" width="30"
                                                         src="{{ dynamicAsset('public/assets/admin/img/upload-icon.png') }}">
                                            <div class="text-muted mt-3">{{ translate('Upload_Picture') }}</div>
                                            <div class="fs-10 text-muted mt-1">{{translate('Upload jpg, png, jpeg, gif maximum 2 MB')}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                $(targetSection).append(newHtmlData);
            }

            elementCustomUploadInputFileByID.on('change', function () {
                if (parseFloat($(this).prop('files').length) !== 0) {
                    let parentDiv = $(this).closest('div');
                    parentDiv.find('.delete_file_input').fadeIn();
                }
            })

            $('.delete_file_input_section').click(function () {
                $(this).closest('div').parent().remove();
            });


            $('.action-add-more-image').on('change', function () {
                let parentDiv = $(this).closest('div');
                parentDiv.find('.delete_file_input').removeClass('d-none');
                parentDiv.find('.delete_file_input').fadeIn();
                addMoreImage(this, $(this).data('target-section'))
            })

        }
        function addMoreImage2(thisData, targetSection) {

            let $fileInputs = $(targetSection + " input[type='file']");
            let nonEmptyCount = 0;
            $fileInputs.each(function () {
                if (parseFloat($(this).prop('files').length) === 0) {
                    nonEmptyCount++;
                }
            });
          var  count=0;

          console.log(thisData.dataset.image_count_key);
            uploadColorImage(thisData)
            $('.image_count_'+thisData.dataset.image_count_key).each(function() {
                const dataIndexElements = $(this).find('[data-index]');

                count += dataIndexElements.length;
            });

            if(count ===  5){
              console.log('done');
              return true;
            }
            if (nonEmptyCount === 0) {

            let datasetIndex = thisData.dataset.index + 1;

            let newHtmlData = ` <div class="col-sm-6 col-md-4 col-lg-3">
                <p class="mb-2 form-label">&nbsp;</p>
                        <div class=" custom_upload_input position-relative border-dashed-2">
                            <input type="file" name="${thisData.name}" class="custom-upload-input-file2 action-add-more-image2"
                                    data-index="${datasetIndex}" data-imgpreview="additional_data_Image_${datasetIndex}"
                                    accept="${thisData.accept}"
                                    data-target-section="${targetSection}"
                                    data-image_count_key="${thisData.dataset.image_count_key}"
                            >

                            <span class="delete_file_input delete_file_input_section btn btn-outline-danger btn-sm square-btn d-none">
                                <i class="tio-delete"></i>
                            </span>

                            <div class="img_area_with_preview z-index-2">
                                <img id="additional_data_Image_${datasetIndex}" class="bg-white d-none"
                                        src="{{ dynamicAsset('public/assets/admin/img/upload-icon.png-dummy') }}" alt="">
                            </div>
                            <div
                                class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                <div
                                    class="d-flex flex-column justify-content-center align-items-center">
                                    <img alt="" width="30"
                                            src="{{ dynamicAsset('public/assets/admin/img/upload-icon.png') }}">
                                    <div class="text-muted mt-3">{{ translate('Upload_Picture') }}</div>
                                    <div class="fs-10 text-muted mt-1">{{translate('Upload jpg, png, jpeg, gif maximum 2 MB')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>`;







            $(targetSection).append(newHtmlData);
            }
            elementCustomUploadInputFileByID2.on('change', function () {
                if (parseFloat($(this).prop('files').length) !== 0) {
                    let parentDiv = $(this).closest('div');


                    parentDiv.find('.delete_file_input').fadeIn();
                }
            })

            $('.delete_file_input_section').click(function () {
                $(this).closest('div').parent().remove();
            });


            $('.action-add-more-image2').on('change', function () {
                let parentDiv = $(this).closest('div');
                parentDiv.find('.delete_file_input').removeClass('d-none');
                parentDiv.find('.delete_file_input').fadeIn();
                addMoreImage2(this,$(this).data('target-section') )
            })

        }

        $('.delete_file_input').on('click', function () {
            let $parentDiv = $(this).parent().parent();
            $parentDiv.find('input[type="file"]').val('');
            $parentDiv.find('.img_area_with_preview img').addClass("d-none");
            $(this).removeClass('d-flex');
            $(this).hide();
        });

        function uploadColorImage(thisData = null) {
            if (thisData) {
                document.getElementById(thisData.dataset.imgpreview).setAttribute("src", window.URL.createObjectURL(thisData.files[0]));
                document.getElementById(thisData.dataset.imgpreview).classList.remove('d-none');
            }
        }


        $('#deliveryman-form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.delivery-man.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.errors) {
                        $('#loading').hide();
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        $('#loading').hide();
                        toastr.success('{{ translate('deliveryman_added_successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.delivery-man.list')}}';
                        }, 2000);
                    }
                }
            });
        });



        $('#reset_btn').click(function(){
            location.reload();
            $('#viewer').attr('src','{{dynamicAsset('public/assets/admin/img/900x400/img1.jpg')}}');
            $('#coba').attr('src','{{dynamicAsset('public/assets/admin/img/900x400/img1.jpg')}}');
        })
    </script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const passwordInput = document.getElementById("signupSrPassword");
                const rulesContainer = document.getElementById("password-rules");

                const rules = {
                    length: document.getElementById("rule-length"),
                    lower: document.getElementById("rule-lower"),
                    upper: document.getElementById("rule-upper"),
                    number: document.getElementById("rule-number"),
                    symbol: document.getElementById("rule-symbol"),

                };

                passwordInput.addEventListener("input", function () {
                    const val = passwordInput.value;

                    // Show rules when user types something
                    if (val.length > 0) {
                        rulesContainer.style.display = "block";
                    } else {
                        rulesContainer.style.display = "none";
                    }

                    // Update validation rules
                    updateRule(rules.length, val.length >= 8);
                    updateRule(rules.lower, /[a-z]/.test(val));
                    updateRule(rules.upper, /[A-Z]/.test(val));
                    updateRule(rules.number, /\d/.test(val));
                    updateRule(rules.symbol, /[!@#$%^&*(),.?":{}|<>]/.test(val));

                });

                passwordInput.addEventListener("blur", function () {
                    // Optional: hide rules on blur if empty
                    if (passwordInput.value.length === 0) {
                        rulesContainer.style.display = "none";
                    }
                });

                function updateRule(element, isValid) {
                    const icon = element.querySelector("i");
                    icon.className = isValid ? "text-success" : "text-danger";
                    icon.innerHTML = isValid ? "&#10004;" : "&#10060;"; // ✓ or ✗
                }
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
            const confirmInput = document.getElementById("signupSrConfirmPassword");
            const passwordInput = document.getElementById("signupSrPassword");
            const feedback = document.getElementById("confirm-password-feedback");

            function validateMatch() {
                if (confirmInput.value.length === 0) {
                    feedback.classList.add("d-none");
                    return;
                }

                if (confirmInput.value === passwordInput.value) {
                    feedback.classList.remove("text-danger");
                    feedback.classList.add("text-success");
                    feedback.textContent = "{{ translate('Passwords match.') }}";
                    feedback.classList.remove("d-none");
                } else {
                    feedback.classList.remove("text-success");
                    feedback.classList.add("text-danger");
                    feedback.textContent = "{{ translate('Passwords do not match.') }}";
                    feedback.classList.remove("d-none");
                }
            }

            confirmInput.addEventListener("input", validateMatch);
            passwordInput.addEventListener("input", validateMatch); // In case password changes after confirm input
        });

        </script>
@endpush
