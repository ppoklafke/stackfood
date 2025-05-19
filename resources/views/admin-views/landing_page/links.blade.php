@extends('layouts.admin.app')

@section('title',translate('messages.Admin_Landing_Page'))

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
            <form action="{{route('admin.business-settings.landing-page-settings', 'links')}}" method="POST">
                @csrf
                <div class="row">
                        <div class="col-md-6">
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-capitalize m-0">
                                                {{translate('messages.app_url')}} ({{translate('messages.play_store')}})
                                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Your_play_store_url')}}">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox" {{(isset($landing_page_links) && $landing_page_links['app_url_android_status'])?'checked':''}}  id="app_url_android_status" value="1"
                                                   data-id="app_url_android_status"
                                                   data-type="toggle"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/play-store-on.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/play-store-off.png') }}"
                                                   data-title-on="<strong>{{translate('By_Turning_ON_The_Button')}}</strong>"
                                                   data-title-off="<strong>{{translate('By_Turning_OFF_The_Button')}}</strong>"
                                                   data-text-on="<p>{{translate('This_button_will_be_enabled_now_everyone_can_use_or_see_the_button')}}</p>"
                                                   data-text-off="<p>{{translate('This_button_will_be_disabled_now_no_one_can_use_or_see_the_button')}}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox-toggle"
                                                   name="app_url_android_status"
                                            >
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                    <input id="app_url_android" {{(isset($landing_page_links) && $landing_page_links['app_url_android_status'])? '':'readonly'}} type="text" value="{{isset($landing_page_links)?$landing_page_links['app_url_android']:''}}" placeholder="{{translate('Ex:_Order_now')}}" class="form-control h--45px" name="app_url_android" >
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-capitalize m-0">
                                                {{translate('messages.app_url')}}  ({{translate('messages.app_store')}})
                                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Your_app_store_url')}}">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox" {{(isset($landing_page_links) && $landing_page_links['app_url_ios_status'])?'checked':''}} id="app_url_ios_status" value="1"
                                                   data-id="app_url_ios_status"
                                                   data-type="toggle"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/apple-on.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/apple-off.png') }}"
                                                   data-title-on="<strong>{{translate('By_Turning_ON_The_Button')}}</strong>"
                                                   data-title-off="<strong>{{translate('By_Turning_OFF_The_Button')}}</strong>"
                                                   data-text-on="<p>{{translate('This_button_will_be_enabled_now_everyone_can_use_or_see_the_button')}}</p>"
                                                   data-text-off="<p>{{translate('This_button_will_be_disabled_now_no_one_can_use_or_see_the_button')}}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox-toggle" name="app_url_ios_status"
                                            >
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                    <input id="app_url_ios" {{(isset($landing_page_links) && $landing_page_links['app_url_ios_status'])? '':'readonly'}} type="text" value="{{isset($landing_page_links)?$landing_page_links['app_url_ios']:''}}" placeholder="{{translate('Ex:_Order_now')}}" class="form-control h--45px" name="app_url_ios" >
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-capitalize m-0">
                                                {{translate('messages.web_app_url')}}
                                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Your_web_app_url')}}">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox" {{(isset($landing_page_links) && $landing_page_links['web_app_url_status'])?'checked':''}}  id="web_app_url_status" value="1"
                                                   data-id="web_app_url_status"
                                                   data-type="toggle"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/promotional-on.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/promotional-off.png') }}"
                                                   data-title-on="<strong>{{translate('By_Turning_ON_The_Button')}}</strong>"
                                                   data-title-off="<strong>{{translate('By_Turning_OFF_The_Button')}}</strong>"
                                                   data-text-on="<p>{{translate('This_button_will_be_enabled_now_everyone_can_use_or_see_the_button')}}</p>"
                                                   data-text-off="<p>{{translate('This_button_will_be_disabled_now_no_one_can_use_or_see_the_button')}}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox-toggle"
                                                   name="web_app_url_status" >
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                    <input id="web_app_url" {{(isset($landing_page_links) && $landing_page_links['web_app_url_status'])?'':'readonly'}} type="text" value="{{isset($landing_page_links)?$landing_page_links['web_app_url']:''}}" placeholder="{{translate('Ex:_Order_now')}}" class="form-control h--45px" name="web_app_url" >
                                </div>
                            </div>
                        </div>
                </div>

                <div class="form-group mb-0">
                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

