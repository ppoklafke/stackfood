@extends('layouts.admin.app')

@section('title',$restaurant->name."'s".translate('messages.QR code'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{dynamicAsset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
    <style>
        @media print {
            * {
                -webkit-print-color-adjust: exact;
            }
        }
        .qr-wrapper .view-menu {
            display: block;
            text-align: center;
            color: #000;
            font-size: 16.809px;
            font-weight: 400;
            padding-block: 4px;
            border-top: 1px solid #f7c446;
            border-bottom: 1px solid #f7c446;
        }
        .qr-wrapper {
            padding: 70px 0px 0;
            background-color: #FFFCF8;
            position: relative;
            z-index: 1;
        }
        .qr-wrap-top-bg {
            opacity: 0.05;
        }
        .qr-wrap-top-bg,
        .qr-wrap-bottom-bg {
            background-color: #FFFCF8;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 48%;
            z-index: -1;
            background-size: cover;
            background-repeat: no-repeat;
        }
        .qr-wrap-bottom-bg {
            background-color: #27364B;
            height: 52%;
            top: 48%;
        }
        .qr-wrapper .subtext {
            border-top: 1px solid rgba(247, 196, 70, 0.40);
            border-bottom: 1px solid rgba(247, 196, 70, 0.40);
            padding-block: 6px;
            text-align: center;
            max-width: 290px;
            margin: 0 auto;
        }
        .qr-wrapper .bottom-txt {
            background-color: #2E3F55;
            display: flex;
        }
        .qr-wrapper .bottom-txt > * {
            flex-grow: 1;
            color: #fff;
        }
        .qr-wrapper .bottom-txt .border-right {
            border-right: 1px solid #fff;
        }
        .qr-code-bg-logo {
            position: absolute;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            z-index: -2;
        }
    </style>

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h1 class="page-header-title text-break">
                <i class="tio-museum"></i> <span>{{$restaurant->name}}</span>
            </h1>
        </div>
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <span class="hs-nav-scroller-arrow-prev initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-left"></i>
                </a>
            </span>

            <span class="hs-nav-scroller-arrow-next initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-right"></i>
                </a>
            </span>

            <!-- Nav -->
            @include('admin-views.vendor.view.partials._header',['restaurant'=>$restaurant])

            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
    </div>
    <!-- End Page Header -->

    <section class="qr-code-section">
        <div class="card">
            <div class="card-body">
                <div class="qr-area row gy-4">
                    <div class="col-lg-6 col-xl-5 left-side">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3 class="text-dark w-0 flex-grow-1">{{ translate('QR Card Design') }}</h3>
                            <div class="btn--container flex-nowrap print-btn-grp">
                                <a type="button" href="{{ route('admin.restaurant.qrcode.print',[$restaurant['id']]) }}" class="btn btn-primary pt-1"><i class="tio-print"></i> {{translate('Print')}}</a>
                            </div>
                        </div>

                        <div class="qr-wrapper">
                            <div class="qr-wrap-top-bg" style="background-image: url({{dynamicAsset('public/assets/admin/img/qr-bg.svg')}})">
                                <img width="429" src="{{dynamicAsset('public/assets/admin/img/bg-logo.png')}}" alt="" class="qr-code-bg-logo">
                            </div>
                            <div class="qr-wrap-bottom-bg" style="background-image: url({{dynamicAsset('public/assets/admin/img/line-shape.svg')}})"></div>
                            <div class="d-flex justify-content-center">
                                <a href="" class="qr-logo">
                                            <img width="200" class="img--110 onerror-image"
                                            src="{{ $restaurant?->logo_full_url ?? dynamicAsset('public/assets/admin/img/logo2.png') }}"
                                            data-onerror-image="{{ dynamicAsset('public/assets/admin/img/logo2.png') }}" alt="image">
                                </a>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <a class="view-menu" href="">
                                    {{ isset($data) ? $data['title'] : translate('view_out_menu') }}
                                </a>
                            </div>
                            <div class="text-center mt-4">
                                <div>
                                    <img src="{{dynamicAsset('public/assets/admin/img/scan-me.png')}}" class="mw-100" alt="">
                                </div>
                                <div class="my-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="bg-white rounded">
                                            {!! $code !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="subtext text-white">
                                <span>
                                    {{ isset($data) ? $data['description'] : translate('Check our menu online, just open your phone & scan this QR Code') }}
                                </span>
                            </div>

                            <div class="phone-number d-flex justify-content-center mt-10 text-white">
                                {{ translate('phone_Number') }} : {{ isset($data) ? $data['phone'] : '+00 123 4567890' }}
                            </div>

                            <div class="text-center bottom-txt mt-3 py-3">
                                <div class="border-right px-2">
                                    {{ isset($data) ? $data['website'] : 'www.website.com' }}
                                </div>
                                <div class="px-2">
                                    {{$restaurant->email}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-7 right-side">
                        <form method="post" action="{{ route('admin.restaurant.qrcode.store',[$restaurant['id']]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('Title')}}</label>
                                        <input type="text" name="title" placeholder="{{ translate('Ex : Title') }}" class="form-control" value="{{isset($data) ? $data['title']:old('title')}}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('Description')}}</label>
                                        <input type="text" name="description" placeholder="{{ translate('Ex : Description') }}" value="{{isset($data) ? $data['description']:old('description')}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('Phone')}}</label>
                                        <input type="text" name="phone" placeholder="{{ translate('Ex : +123456') }}" value="{{isset($data) ? $data['phone']:old('phone')}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('Website Link')}}</label>
                                        <input type="text" name="website" value="{{isset($data) ? $data['website']:old('website')}}" placeholder="{{ translate('Ex : www.website.com') }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="justify-content-end btn--container">
                                        <button type="reset" class="btn btn-secondary">{{translate('clear')}}</button>
                                        <button type="submit" class="btn btn-primary">{{translate('save')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('script_2')
@endpush

