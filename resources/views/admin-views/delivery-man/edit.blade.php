@extends('layouts.admin.app')

@section('title',translate('Update_Deliveryman'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mb-2 text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{dynamicAsset('/public/assets/admin/img/delivery-man.png')}}" alt="public">
                </div>
                <span>
                    {{translate('messages.Update_Deliveryman')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <form id="deliveryman-form" method="post" enctype="multipart/form-data">
        <form id="deliveryman-form" method="post"
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-title-icon"><i class="tio-user"></i></span>
                        <span>
                            {{ translate('general_info') }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">

                    <div class="row g-3">
                        <div class="col-lg-8">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.first_name')}}</label>
                                        <input type="text" value="{{$delivery_man['f_name']}}" name="f_name"
                                                class="form-control h--45px" placeholder="{{translate('messages.first_name')}}"
                                                required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.last_name')}}</label>
                                        <input type="text" value="{{$delivery_man['l_name']}}" name="l_name"
                                                class="form-control h--45px" placeholder="{{translate('messages.last_name')}}"
                                                required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.email')}}</label>
                                        <input type="email" value="{{$delivery_man['email']}}" name="email" class="form-control h--45px"
                                                placeholder="{{ translate('Ex:_ex@example.com') }}"
                                                required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.deliveryman_type')}}</label>
                                        <select name="earning" class="form-control h--45px" required>
                                            <option value="1" {{$delivery_man->earning?'selected':''}}>{{translate('messages.freelancer')}}</option>
                                            <option value="0" {{$delivery_man->earning?'':'selected'}}>{{translate('messages.salary_based')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.zone')}}</label>
                                        <select name="zone_id" class="form-control js-select2-custom h--45px">
                                        @foreach(\App\Models\Zone::where('status',1)->get(['id','name']) as $zone)
                                            @if(isset(auth('admin')->user()->zone_id))
                                                @if(auth('admin')->user()->zone_id == $zone->id)
                                                    <option value="{{$zone->id}}" {{$zone->id == $delivery_man->zone_id?'selected':''}}>{{$zone->name}}</option>
                                                @endif
                                            @else
                                            <option value="{{$zone->id}}" {{$zone->id == $delivery_man->zone_id?'selected':''}}>{{$zone->name}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="d-flex flex-column align-items-center gap-3">
                                <p class="mb-0">{{ translate('image') }}</p>  <small class="text-danger">* ( {{translate('messages.ratio_1:1')}} )</small>

                                <div class="image-box">
                                    <label for="image-input" class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-2">
                                    <img class="upload-icon initial-26"

                                    src="{{ $delivery_man['image_full_url'] }}"

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
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.vehicle')}}</label>
                                <select name="vehicle_id" class="form-control js-select2-custom h--45px">
                                    <option value="" readonly="true" hidden="true">{{ translate('messages.select_vehicle') }}</option>
                                @foreach(\App\Models\Vehicle::where('status',1)->get(['id','type']) as $v)
                                    <option value="{{$v->id}}" {{$v->id == $delivery_man->vehicle_id?'selected':''}}>{{$v->type}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                                    <div class="form-group m-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.identity_type')}}</label>
                                        <select name="identity_type" class="form-control h--45px">
                                            <option
                                                value="passport" {{$delivery_man['identity_type']=='passport'?'selected':''}}>
                                                {{translate('messages.passport')}}
                                            </option>
                                            <option
                                                value="driving_license" {{$delivery_man['identity_type']=='driving_license'?'selected':''}}>
                                                {{translate('messages.driving_license')}}
                                            </option>
                                            <option value="nid" {{$delivery_man['identity_type']=='nid'?'selected':''}}>{{translate('messages.nid')}}
                                            </option>
                                            <option
                                                value="restaurant_id" {{$delivery_man['identity_type']=='restaurant_id'?'selected':''}}>
                                                {{translate('messages.restaurant_id')}}
                                            </option>
                                        </select>
                                </div>
                            </div>
                                <div class="col-lg-4">
                                    <div class="form-group m-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.identity_number')}}</label>
                                        <input type="text" name="identity_number" value="{{$delivery_man['identity_number']}}"
                                                class="form-control h--45px"
                                                placeholder="{{ translate('messages.Ex:DH-23434-LS') }} "
                                                required>
                                    </div>
                                </div>
                        <div class="col-lg-8">
                            <div class="form-group mb-0">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.identity_image')}}</label>

                            </div>
                            @foreach($delivery_man['identity_image_full_url'] as $img)
                                <img class="ml-2" width='230' height="150"
                                src="{{ $img }}"
                               >
                            @endforeach
                        </div>


                        <div class="col-12">
                            <div class="form-group m-0">
                                <label class="input-label">{{ translate('messages.upload_new_identity_image') }}</label>


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
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon"><i class="tio-user"></i></span>
                        <span>{{ translate('messages.account_info') }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group m-0">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.phone')}}</label>
                                <input type="tel" id="phone" name="phone" value="{{$delivery_man['phone']}}" class="form-control h--45px"
                                        placeholder="{{ translate('Ex:_017********') }}"
                                        required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">

                            <div class="js-form-message form-group">
                                <label class="input-label" for="signupSrPassword">{{translate('messages.password')}}
                                    <span class="input-label-secondary ps-1" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                </label>

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control h--45px" name="password"
                                        id="signupSrPassword"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                        placeholder="{{ translate('messages.password_length_8+')  }}"
                                        aria-label="8+ characters required"
                                        data-msg="Your password is invalid. Please try again."
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
                        <!-- Static -->
                        <div class="col-sm-6 col-lg-4">

                            <div class="js-form-message form-group">
                                <label class="input-label" for="signupSrConfirmPassword">{{translate('messages.confirm_password')}}
                                </label>

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control h--45px"
                                        name="confirmPassword" id="signupSrConfirmPassword"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                        placeholder="{{translate('messages.password_length_8+')}}"
                                        aria-label="8+ characters required"
                                        data-msg="Password does not match the confirm password."
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
                        <!-- Static -->
                    </div>
                </div>
            </div>
            <div class="btn--container mt-4 justify-content-end">
                <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
            </div>
        </form>
    </div>

@endsection

@push('script_2')
    <script src="{{dynamicAsset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script>
        "use strict";
             $('#exampleInputPassword ,#exampleRepeatPassword').on('keyup', function() {
                let pass = $("#exampleInputPassword").val();
                let passRepeat = $("#exampleRepeatPassword").val();
                if (pass == passRepeat) {
                    $('.pass').hide();
                } else {
                    $('.pass').show();
                }
            });

        "use strict";
        let elementCustomUploadInputFileByID = $('.custom-upload-input-file');

        $('.action-add-more-image').on('change', function () {
            let parentDiv = $(this).closest('div');
            parentDiv.find('.delete_file_input').removeClass('d-none');
            parentDiv.find('.delete_file_input').fadeIn();
            addMoreImage(this, $(this).data('target-section'))
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
                url: '{{route('admin.delivery-man.update',[$delivery_man['id']])}}',
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
                        toastr.success('{{ translate('deliveryman_updated_successfully!') }}', {
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
            $('#viewer').attr('src','{{dynamicStorage('storage/app/public/delivery-man')}}/{{$delivery_man['image']}}');
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
