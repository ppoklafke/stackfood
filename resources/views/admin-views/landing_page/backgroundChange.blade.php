@extends('layouts.admin.app')
@section('title', translate('messages.Admin_Landing_Page'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                    </div>
                    <span>
                        {{ translate('Admin_Landing_Page') }}
                    </span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.landing_page.top_menu.admin_landing_menu')
            </div>
        </div>
        <div class="card my-2">
            <div class="card-body">
                <form action="{{ route('admin.business-settings.landing-page-settings', 'background-change') }}"
                    method="POST">

                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <label  for="primary_1_hex" class="form-label d-block text-center">{{ translate('Primary_Color') }}</label>
                            <input  id="primary_1_hex" name="header-bg" type="color" class="form-control form-control-color" value="{{ data_get($backgroundChange,'primary_1_hex','#EF7822') }}" required>
                        </div>
{{--                        <div class="col-sm-6">--}}
{{--                            <label for="primary_2_hex"  class="form-label d-block text-center">{{ translate('Primary_Color_2') }}</label>--}}
{{--                            <input id="primary_2_hex"  name="footer-bg" type="color" class="form-control form-control-color"--}}
{{--                                   value="{{ data_get($backgroundChange,'primary_2_hex','#333E4F') }}" required>--}}
{{--                        </div>--}}

                    </div>
                    <div class="form-group text-right mt-3 mb-0">
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

