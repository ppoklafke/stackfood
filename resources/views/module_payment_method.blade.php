<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <!-- SEO Meta Tags-->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <!-- Viewport-->
    <meta name="_token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and Touch Icons-->
    <link rel="shortcut icon" href="favicon.ico">
    <!-- Font -->
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{dynamicAsset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{dynamicAsset('public/assets/admin')}}/vendor/icon-set/style.css">
    <link rel="stylesheet" href="{{dynamicAsset('public/assets/admin')}}/css/custom.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{dynamicAsset('public/assets/admin')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{dynamicAsset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{dynamicAsset('public/assets/admin/css/style.css')}}">

    <script
        src="{{dynamicAsset('public/assets/admin')}}/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js"></script>
    <link rel="stylesheet" href="{{dynamicAsset('public/assets/admin')}}/css/toastr.css">
    {{--stripe--}}
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
    .image_class {
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

    .gap-1 {
    gap: .25rem !important;
    }
    .gap-2 {
    gap: .5rem !important;
    }
    .gap-3 {
    gap: 1rem !important;
    }
    .gap-4 {
    gap: 1.5rem !important;
    }
    .gap-5 {
    gap: 3rem !important;
    }
    </style>
    {{--stripe--}}
</head>
<!-- Body-->
<body>
<!-- Page Content-->
<div class="container pb-5 mb-2 mb-md-4">

    <div class="row">
        <div class="col-md-12 mb-5 pt-5">
            <center class="">
                <h1>{{ translate('Payment_method') }}</h1>
            </center>
        </div>

        <section class="col-lg-12">
            <div class="checkout_details mt-3">



                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-5">{{ translate('Pay_Via_Online') }}<small>({{ translate('Faster_&_secure_way_to_pay_bill') }})</small></h5>


                            <form action="{{ route('vendor.subscription.digital_payment') }}" method="POST" class="needs-validation">
                                @csrf
                                <input type="hidden" value="{{$subscription_transaction_id }}" name="subscription_id"/>
                                <input type="hidden" value="{{$type ?? null}}" name="type"/>


                                <div class="row g-3">
                                    @forelse ($data as $item)
                                        <div class="col-sm-6">
                                            <div class="d-flex gap-3 align-items-center">
                                                <input type="radio" required id="{{$item['gateway'] }}" name="payment_gateway" value="{{$item['gateway'] }}">
                                            
                                                <label for="{{$item['gateway'] }}" class="d-flex align-items-center gap-3 mb-0">
                                                    <img height="24" src="{{ dynamicStorage('storage/app/public/payment_modules/gateway_image/'. $item['gateway_image']) }}" alt="">
                                                    {{ $item['gateway_title'] }}
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                    <h1>{{ translate('no_payment_gateway_found') }}</h1>
                                    @endforelse
                                </div>

                                @if(count($data) !== 0)
                                    <div class="d-flex justify-content-end gap-3 mt-4">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ translate('Proceed') }}</button>
                                    </div>
                                @endif
                            </div>


                    </form>
                    </div>

            </div>
        </section>
    </div>
</div>

<!-- JS Front -->
<script src="{{dynamicAsset('public/assets/admin')}}/js/custom.js"></script>
<script src="{{dynamicAsset('public/assets/admin')}}/js/vendor.min.js"></script>
<script src="{{dynamicAsset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{dynamicAsset('public/assets/admin')}}/js/sweet_alert.js"></script>
<script src="{{dynamicAsset('public/assets/admin')}}/js/toastr.js"></script>
<script src="{{dynamicAsset('public/assets/admin')}}/js/bootstrap.min.js"></script>

{!! Toastr::message() !!}




</body>
</html>
