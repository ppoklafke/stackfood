@extends('layouts.admin.app')

@section('title',translate('messages.login_page_setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap align-items-center justify-content-between">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/app.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('login_setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <ul class="nav nav-tabs border-0 nav--tabs nav--pills mb-4">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.login-settings.index') }}">{{translate('Customer_Login')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.login_url.login_url_page') }}">{{translate('panel_login_page_Url')}}</a>
            </li>
        </ul>


        <form action="{{route('admin.login_url.login_url_page_update')}}" method="post">
        @csrf
            <h5 class="card-title mb-3 pt-3">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span> <span>{{ translate('Admin_login_url') }}</span>
            </h5>
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <input type="text" hidden  name="type" value="admin">
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group">
                                    <label  class="form-label">
                                        {{translate('messages.Admin_login_url')}}
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Add_dynamic_secure_login_url_for_Admin') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">{{ url('/') }}/login/</div>
                                        <input type="text" placeholder="{{translate('messages.admin_login_url')}}" class="form-control h--45px" name="admin_login_url"
                                                required value="{{ $data['admin_login_url'] ?? null  }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('messages.submit')}}</button>
                    </div>
                </div>
            </div>
        </form>
        <form action="{{route('admin.login_url.login_url_page_update')}}" method="post">
            @csrf
            <h5 class="card-title mb-3 pt-3">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span> <span>{{ translate('admin_employee_login_page') }}</span>
            </h5>
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <input type="text" hidden  name="type" value="admin_employee">

                            <div class="__bg-F8F9FC-card">
                                <div class="form-group">
                                    <label  class="form-label">
                                        {{translate('messages.Admin_employee_login_url')}}
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Add_dynamic_secure_login_url_for_Admin_Employee') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">{{ url('/') }}/login/</div>
                                        <input type="text" placeholder="{{translate('messages.admin_employee_login_url')}}" class="form-control h--45px" name="admin_employee_login_url"
                                                required value="{{ $data['admin_employee_login_url'] ?? null  }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('messages.submit')}}</button>
                    </div>
                </div>
            </div>
        </form>
        <form action="{{route('admin.login_url.login_url_page_update')}}" method="post">
            @csrf
            <h5 class="card-title mb-3 pt-3">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span> <span>{{ translate('restaurant_login_page') }}</span>
            </h5>
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <input type="text" hidden  name="type" value="restaurant">

                            <div class="__bg-F8F9FC-card">
                                <div class="form-group">
                                    <label  class="form-label">
                                        {{translate('messages.Restaurant_login_url')}}
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Add_dynamic_secure_login_url_for_Restaurant_Owners') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">{{ url('/') }}/login/</div>
                                        <input type="text" placeholder="{{translate('messages.restaurant_login_url')}}" class="form-control h--45px" name="restaurant_login_url"
                                        required value="{{ $data['restaurant_login_url'] ?? null  }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('messages.submit')}}</button>
                    </div>
                </div>
            </div>
        </form>
        <form action="{{route('admin.login_url.login_url_page_update')}}" method="post">
            @csrf
            <h5 class="card-title mb-3 pt-3">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span> <span>{{ translate('restaurant_employee_login_page') }}</span>
            </h5>
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <input type="text" hidden  name="type" value="restaurant_employee">

                            <div class="__bg-F8F9FC-card">
                                <div class="form-group">
                                    <label  class="form-label">
                                        {{translate('messages.Restaurant_employee_login_url')}}
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Add_dynamic_secure_login_url_for_Restaurant_Employee') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">{{ url('/') }}/login/</div>
                                        <input type="text" placeholder="{{translate('messages.restaurant_employee_login_url')}}" class="form-control h--45px" name="restaurant_employee_login_url"
                                                required value="{{ $data['restaurant_employee_login_url'] ?? null  }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('messages.submit')}}</button>
                    </div>
                </div>
            </div>
        </form>



    </div>


@endsection

@push('script_2')

@endpush
