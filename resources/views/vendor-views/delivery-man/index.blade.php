@extends('layouts.vendor.app')

@section('title','Add new delivery-man')

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h2 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <i class="tio-add-circle-outlined"></i>
                </div>
                <span>
                    {{translate('messages.add_new_deliveryman')}}
                </span>
            </h2>
        </div>
        <!-- End Page Header -->
        <form  action="javascript:" method="post" enctype="multipart/form-data" id="deliaveryman_form">
            @csrf
            <div class="row g-2">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-title-icon"><i class="tio-user"></i></span>
                                <span>
                                    {{translate('messages.General_Information')}}

                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="exampleFormControlInput1">{{translate('messages.first_name')}}</label>
                                        <input type="text" name="f_name" class="form-control h--45px" placeholder="{{translate('messages.first_name')}}"
                                                required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="exampleFormControlInput1">{{translate('messages.last_name')}}</label>
                                        <input type="text" name="l_name" class="form-control h--45px" placeholder="{{translate('messages.last_name')}}"
                                                required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="exampleFormControlInput1">{{translate('messages.identity_type')}}</label>
                                        <select name="identity_type" class="form-control h--45px">
                                            <option value="passport">{{translate('messages.passport')}}</option>
                                            <option value="driving_license">{{translate('messages.driving_license')}}</option>
                                            <option value="nid">{{translate('messages.nid')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="exampleFormControlInput1">{{translate('messages.identity_number')}}</label>
                                        <input type="text" name="identity_number" class="form-control h--45px"
                                                placeholder="{{ translate('messages.Ex : DH-23434-LS') }} "
                                                required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="form-label m-0" for="exampleFormControlInput1">{{translate('messages.identity_image')}}
                                <small class="text-danger">* ( {{translate('messages.ratio')}} 190x120 )</small></h5>
                        </div>
                        <div class="card-body">
                            <div class="row gx-2" id="coba"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header py-3">
                            <h5 class="form-label mb-0">
                                {{translate('messages.deliveryman_image')}}
                                <small class="text-danger">* ( {{translate('messages.ratio')}} 1:1 )</small>
                            </h5>
                        </div>
                        <div class="card-body pt-0 d-flex flex-column">
                            <center class="py-3 my-auto">
                                <img class="initial-78" id="viewer" src="{{dynamicAsset('public/assets/admin/img/100x100/user2.png')}}" alt="delivery-man image"/>
                            </center>
                            <div class="custom-file mt-0">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose_file')}}</label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-title-icon"><i class="tio-user"></i></span>
                                <span>
                                    {{translate('messages.account_info')}}
                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="phone">{{translate('messages.phone')}}</label>
                                        <div class="input-group">
                                            <input type="tel" name="phone" id="phone" placeholder="{{ translate('messages.Ex : 017********') }} " class="form-control h--45px" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="exampleFormControlInput1">{{translate('messages.email')}}</label>
                                        <input type="email" name="email" class="form-control h--45px" placeholder="{{ translate('messages.Ex : ex@example.com') }} "
                                                required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container mt-3 justify-content-end">
                                <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script_2')
    <script src="{{dynamicAsset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
   <script>
       "use strict";
        $("#customFileEg1").change(function () {
            readURL(this);
        });
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-6 col-sm-4',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{dynamicAsset('public/assets/admin/img/100x100/user2.png')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('Please only input png or jpg type file', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('File size too big', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        $('#deliaveryman_form').on('submit', function () {
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('vendor.delivery-man.store')}}',
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
                    } else if(data.message){
                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('vendor.delivery-man.list')}}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush
