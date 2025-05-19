@extends('layouts.admin.app')

@section('title', translate('messages.reCaptcha Setup'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{dynamicAsset('public/assets/admin/img/captcha.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.reCaptcha_credentials_setup')}}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-header">
                <h4 class="m-0">
                    {{translate('Google Recaptcha Information')}}
                </h4>
                <button type="button" class="btn btn--primary btn-outline-primary btn-sm px-3" data-toggle="modal" data-target="#setup-information">
                    {{translate('Credential Setup Information')}} <i class="tio-info"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="alert alert-soft-secondary">
                    <div class="d-flex gap-2">
                        <div class="w-0 flex-grow-1">
                            <h4 class="m-0">{{ translate('V3 Version is available now. Must setup for ReCAPTCHA V3') }}</h4>
                            <div>{{ translate('You must setup for V3 version. Otherwise the default reCAPTCHA will be displayed automatically') }}</div>
                        </div>
                        <div>
                            <button type="button" class="btn p-0 text-danger" data-dismiss="alert">
                                <i class="tio-clear"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @php($config=\App\CentralLogics\Helpers::get_business_settings('recaptcha'))
                <form
                    action="{{env('APP_MODE')!='demo'?route('admin.business-settings.recaptcha_update',['recaptcha']):'javascript:'}}"
                    method="post">
                    @csrf
                    <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control mb-4">
                        <span class="pr-1 d-flex align-items-center switch--label">
                            <span class="line--limit-1">
                                @if (isset($config) && $config['status'] == 1)
                                    {{translate('ReCAPTCHA Status Turn OFF')}}
                                @else
                                    {{translate('ReCAPTCHA Status Turn ON')}}
                                @endif
                            </span>
                        </span>
                        <input type="checkbox"
                               data-id="recaptcha_status"
                               data-type="toggle"
                               data-image-on="{{ asset('/public/assets/admin/img/modal/important-recapcha.png') }}"
                               data-image-off="{{ asset('/public/assets/admin/img/modal/warning-recapcha.png') }}"
                               data-title-on="{{ translate('Important!') }}"
                               data-title-off="{{ translate('Warning!') }}"
                               data-text-on="<p>{{ translate('reCAPTCHA is now enabled for added security. Users may be prompted to complete a reCAPTCHA challenge to verify their human identity and protect against spam and malicious activity.') }}</p>"
                               data-text-off="<p>{{ translate('Disabling reCAPTCHA may leave your website vulnerable to spam and malicious activity and suspects that a user may be a bot. It is highly recommended to keep reCAPTCHA enabled to ensure the security and integrity of your website.') }}</p>"
                               class="status toggle-switch-input dynamic-checkbox-toggle"
                               name="status" id="recaptcha_status" value="1" {{isset($config) && $config['status'] == 1 ? 'checked':''}}>
                        <span class="toggle-switch-label text p-0">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="site_key" class="form-label">{{translate('messages.Site Key')}}</label><br>
                                <input id="site_key" type="text" class="form-control" name="site_key"
                                       value="{{env('APP_MODE')!='demo'?$config['site_key']??"":''}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="site_key" class="form-label">{{translate('messages.Secret Key')}}</label><br>
                                <input id="site_key" type="text" class="form-control" name="secret_key"
                                       value="{{env('APP_MODE')!='demo'?$config['secret_key']??"":''}}">
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary call-demo">{{translate('messages.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="setup-information" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center">
                        <img src="{{dynamicAsset('/public/assets/admin/img/modal/warning-recapcha-2.png')}}" width="80" alt="">
                    </div>
                    <h4 class="modal-title">Instructions</h4>
                    <ol class="list-gap-5 fs-13 mt-3">
                        <li>{{translate('messages.Go to the Credentials page')}}
                            ({{translate('messages.Click')}} <a
                                href="https://www.google.com/recaptcha/admin/create"
                                target="_blank">{{translate('messages.here')}}</a>)
                        </li>
                        <li>{{translate('messages.Add a ')}}
                            <b>{{translate('messages.label')}}</b> {{translate('messages.(Ex: Test Label)')}}
                        </li>
                        <li>
                            {{translate('messages.Select reCAPTCHA v3 as ')}}
                            <b>{{translate('messages.reCAPTCHA Type')}}</b>
                            ({{translate("Sub type: I'm not a robot Checkbox")}}
                            )
                        </li>
                        <li>
                            {{translate('messages.Add')}}
                            <b>{{translate('messages.domain')}}</b>
                            {{translate('messages.(For ex: demo.6amtech.com)')}}
                        </li>
                        <li>
                            {{translate('messages.Check in ')}}
                            <b>{{translate('messages.Accept the reCAPTCHA Terms of Service')}}</b>
                        </li>
                        <li>
                            {{translate('messages.Press')}}
                            <b>{{translate('messages.Submit')}}</b>
                        </li>
                        <li>{{translate('messages.Copy')}} <b>{{ translate('Site') }}
                                {{ translate('Key') }}</b> {{translate('messages.and')}} <b>{{ translate('Secret') }}
                                {{ translate('Key') }}</b>, {{translate('messages.paste in the input filed below and')}}
                            <b>{{ translate('Save') }}</b>.
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>





@endsection

