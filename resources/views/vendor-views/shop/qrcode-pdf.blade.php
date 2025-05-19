@extends('layouts.vendor.app')

@section('title', translate('QR code'))

@push('css_or_js')
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
        padding: 85px 0px 0;
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
        <section class="qr-code-section">
            <div class="card">
                <div class="card-body">
                    <div class="qr-area row gy-4">
                        <div class="col-lg-6 col-xl-5 left-side">
                            <div class="qr-wrapper" id="printableArea">
                                <div class="qr-wrap-top-bg" style="background-image: url({{dynamicAsset('public/assets/admin/img/qr-bg.svg')}})">
                                    <img width="429" src="{{dynamicAsset('public/assets/admin/img/bg-logo.png')}}" alt="" class="qr-code-bg-logo">
                                </div>
                                <div class="qr-wrap-bottom-bg" style="background-image: url({{dynamicAsset('public/assets/admin/img/line-shape.svg')}})"></div>
                                <div class="d-flex justify-content-center">
                                    <a href="" class="qr-logo">
                                                <img  class="mw-100" width="200"
                                                src="{{ $restaurant?->logo_full_url ?? dynamicAsset('public/assets/admin/img/logo2.png') }}"
                                                alt="image">
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
                                        {!! $code !!}
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

                                <div class="text-center bottom-txt py-3 mt-3">
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

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('script')

@endpush



