@extends('layouts.vendor.app')
@section('title',translate('Employee Add'))

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
     <div class="page-header">
        <h2 class="page-header-title text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{dynamicAsset('/public/assets/admin/img/resturant-panel/page-title/employee-role.png')}}" alt="public">
            </div>
            <span>
                {{translate('Add New Employee')}}
            </span>
        </h2>
    </div>
    <!-- End Page Header -->

    <!-- Content Row -->
    <form action="{{route('vendor.employee.add-new')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="card-header-icon">
                        <i class="tio-user"></i>
                    </span>
                    <span>
                        {{ translate('General Information') }}
                    </span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="fname">{{translate('messages.first_name')}}</label>
                            <input type="text" name="f_name" class="form-control h--45px" id="fname"
                                    placeholder="{{ translate('Ex : Sakeef Ameer') }}" value="{{old('f_name')}}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="lname">{{translate('messages.last_name')}}</label>
                            <input type="text" name="l_name" class="form-control h--45px" id="lname" value="{{old('l_name')}}"
                                    placeholder="{{ translate('Ex : Prodhan') }}" value="{{old('name')}}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">{{translate('messages.phone')}}</label>
                            <input type="tel" name="phone" value="{{old('phone')}}" class="form-control h--45px" id="phone"
                                    placeholder="{{ translate('Ex : +88017********') }}" required>
                        </div>
                        <div class="form-group mb-md-0">
                            <label class="form-label" for="role_id">{{translate('messages.Role')}}</label>
                            <select class="form-control h--45px custom-select2 w-100" name="role_id" required>
                                <option value="" selected disabled>{{translate('messages.select_Role')}}</option>
                                @foreach($rls as $r)
                                    <option value="{{$r->id}}">{{$r->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="h-100 d-flex flex-column">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="form-label text-center mb-3" for="customFileUpload">
                                        {{translate('messages.employee_image')}}
                                        <span class="text-danger">{{ translate('Ratio (1:1)') }}</span>
                                    </h5>
                                    <center class="my-auto">
                                        <img class="initial-78" id="viewer"
                                            src="{{dynamicAsset('public\assets\admin\img\400x400\img2.jpg')}}" alt="Employee thumbnail"/>
                                    </center>
                                    <div class="form-group mb-0 mt-3">
                                        <label class="form-label">{{ translate('Employee image size max 2 MB') }} <span class="text-danger">*</span></label>
                                        <div class="custom-file">
                                            <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" value="{{old('image')}}" required>
                                            <label class="custom-file-label" for="customFileUpload">{{translate('messages.choose_file')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-user"></i>
                            </span>
                            <span>
                                {{translate('messages.account_info')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label" for="email">{{translate('messages.email')}}</label>
                                <input type="email" name="email" value="{{old('email')}}" class="form-control" id="email"
                                        placeholder="{{ translate('Ex : ex@gmail.com') }}" required>
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
                                    </div>
                                </div>
                            </div>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end mt-3">
                    <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                    <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@push('script_2')
    <script>
        "use strict";

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $('#reset_btn').click(function(){
            location.reload();
        });


    </script>
@endpush
