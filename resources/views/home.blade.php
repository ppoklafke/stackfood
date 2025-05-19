@extends('layouts.landing.app')
@section('home','active')

@section('title', $landing_data['business_name'] )
@section('content')

    <!-- ======= Footer Section ======= -->
    <!-- ======= Banner Section ======= -->
    <section class="banner-section"
        style="background: url('{{$landing_data['header_bg_image_full_url']}}') no-repeat center center / cover">

        <!-- Main Banner Content -->
        <div class="container">
            <div class="banner-wrapper">
                <div class="banner-thumb wow fadeInUp">
                    <img class="main-img"
                    src="{{ $landing_data['header_content_image_full_url']   }}"

                    alt="">
                    <div class="img-data-1">
                        <img src="{{dynamicAsset('/public/assets/landing/assets_new/img/banner/icon-1.png')}}" alt="Review">
                        <span>{{ translate('Review') }} {{ $landing_data['header_floating_total_reviews']}} +</span>
                    </div>
                    <div class="img-data-2">
                        <img src="{{dynamicAsset('/public/assets/landing/assets_new/img/banner/icon-2.png')}}" alt="Order">
                        <span>{{ translate('Order') }} {{ $landing_data['header_floating_total_order']}} +</span>
                    </div>
                    <div class="img-data-3">
                        <img src="{{dynamicAsset('/public/assets/landing/assets_new/img/banner/icon-3.png')}}" alt="User">
                        <span>{{ translate('User') }} {{  $landing_data['header_floating_total_user']}} +</span>
                    </div>
                </div>
                <div class="banner-content wow fadeInRight">
                    <h2 class="title"> {{ $landing_data['header_title'] }}</h2>
                    <h3 class="subtitle">{{ $landing_data['header_sub_title'] }}</h3>
                    <h1 class="name">{{ $landing_data['business_name'] }}</h1>
                     <div class="txt">
                        {{ $landing_data['header_tag_line']  }}
                    </div>
                    @if ($landing_data['header_app_button_status'])
                   <a href="{{ $landing_data['header_button_redirect_link']  ??  '#' }}"
                    class="btn-base btn-sm">
                        <span>{{    $landing_data['header_app_button_name']   }}</span>
                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.875 7.50006C0.875 7.38403 0.921094 7.27275 1.00314 7.19071C1.08519 7.10866 1.19647 7.06256 1.3125 7.06256H11.6314L8.87775 4.30981C8.7956 4.22766 8.74945 4.11624 8.74945 4.00006C8.74945 3.88389 8.7956 3.77247 8.87775 3.69031C8.9599 3.60816 9.07132 3.56201 9.1875 3.56201C9.30368 3.56201 9.4151 3.60816 9.49725 3.69031L12.9972 7.19031C13.038 7.23095 13.0703 7.27923 13.0924 7.33239C13.1144 7.38554 13.1258 7.44252 13.1258 7.50006C13.1258 7.55761 13.1144 7.61459 13.0924 7.66774C13.0703 7.7209 13.038 7.76917 12.9972 7.80981L9.49725 11.3098C9.4151 11.392 9.30368 11.4381 9.1875 11.4381C9.07132 11.4381 8.9599 11.392 8.87775 11.3098C8.7956 11.2277 8.74945 11.1162 8.74945 11.0001C8.74945 10.8839 8.7956 10.7725 8.87775 10.6903L11.6314 7.93756H1.3125C1.19647 7.93756 1.08519 7.89147 1.00314 7.80942C0.921094 7.72738 0.875 7.6161 0.875 7.50006Z"
                                fill="white" />
                        </svg>

                    </a>
                    @endif
                </div>
            </div>
        </div>
        <!-- Main Banner Content -->

        <!-- Shape 1 -->
        <div class="shape-1"><img src="{{dynamicAsset('/public/assets/landing/assets_new/img/banner/1.png')}}" alt=""></div>
        <div class="shape-2"><img src="{{dynamicAsset('/public/assets/landing/assets_new/img/banner/2.png')}}" alt=""></div>
        <div class="shape-3"><img src="{{dynamicAsset('/public/assets/landing/assets_new/img/banner/3.png')}}" alt=""></div>
        <!-- Glass 1 -->
        <svg class="glass-1" width="439" height="477" viewBox="0 0 439 477" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <circle cx="273" cy="204" r="273" fill="url(#paint0_radial_3_2080)" />
            <defs>
                <radialGradient id="paint0_radial_3_2080" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                    gradientTransform="translate(273 204) rotate(90) scale(273)">
                    <stop stop-color="#FFBD3C" stop-opacity="0.3" />
                    <stop offset="1" stop-color="#D9D9D9" stop-opacity="0" />
                </radialGradient>
            </defs>
        </svg>
        <!-- Glass 2 -->
        <svg class="glass-2" width="311" height="407" viewBox="0 0 311 407" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <circle cx="239" cy="146" r="146" fill="url(#paint0_radial_0_1)" />
            <circle cx="146" cy="328" r="146" fill="url(#paint1_radial_0_1)" />
            <defs>
                <radialGradient id="paint0_radial_0_1" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                    gradientTransform="translate(239 146) rotate(90) scale(146)">
                    <stop stop-color="#FFBD3C" stop-opacity="0.3" />
                    <stop offset="1" stop-color="#D9D9D9" stop-opacity="0" />
                </radialGradient>
                <radialGradient id="paint1_radial_0_1" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                    gradientTransform="translate(146 328) rotate(90) scale(146)">
                    <stop stop-color="#FFBD3C" stop-opacity="0.3" />
                    <stop offset="1" stop-color="#D9D9D9" stop-opacity="0" />
                </radialGradient>
            </defs>
        </svg>
        <!-- Glass 3 -->
        <svg class="glass-3" width="546" height="537" viewBox="0 0 546 537" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <circle cx="273" cy="273" r="273" fill="url(#paint0_radial_3_2079)" />
            <defs>
                <radialGradient id="paint0_radial_3_2079" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                    gradientTransform="translate(273 273) rotate(90) scale(273)">
                    <stop stop-color="#FFBD3C" stop-opacity="0.3" />
                    <stop offset="1" stop-color="#D9D9D9" stop-opacity="0" />
                </radialGradient>
            </defs>
        </svg>
    </section>
    <!-- ======= Banner Section ======= -->
    @if (isset($landing_data['about_us_title']) && (isset($landing_data['about_us_sub_title']) || isset($landing_data['about_us_text']) || isset($landing_data['about_us_image_content']) ))

    <!-- ======= About Section ======= -->
    <section class="about-section pt-80">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="about-content wow fadeInDown">
                        <h2 class="title text-base">{{ $landing_data['about_us_title']   }}</h2>
                        <h3 class="subtitle">{{ $landing_data['about_us_sub_title']}}</h3>
                        <p class="txt">
                            {{ $landing_data['about_us_text'] }}
                        </p>
                        @if ($landing_data['about_us_app_button_status'] &&  $landing_data['about_us_app_button_name'] )
                        <a href="{{  $landing_data['about_us_redirect_link'] ?? '#' }}" class="btn-base btn-sm">
                            <span>
                                {{ $landing_data['about_us_app_button_name'] }}
                            </span>
                            <svg width="14" height="15" viewBox="0 0 14 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0.875 7.50006C0.875 7.38403 0.921094 7.27275 1.00314 7.19071C1.08519 7.10866 1.19647 7.06256 1.3125 7.06256H11.6314L8.87775 4.30981C8.7956 4.22766 8.74945 4.11624 8.74945 4.00006C8.74945 3.88389 8.7956 3.77247 8.87775 3.69031C8.9599 3.60816 9.07132 3.56201 9.1875 3.56201C9.30368 3.56201 9.4151 3.60816 9.49725 3.69031L12.9972 7.19031C13.038 7.23095 13.0703 7.27923 13.0924 7.33239C13.1144 7.38554 13.1258 7.44252 13.1258 7.50006C13.1258 7.55761 13.1144 7.61459 13.0924 7.66774C13.0703 7.7209 13.038 7.76917 12.9972 7.80981L9.49725 11.3098C9.4151 11.392 9.30368 11.4381 9.1875 11.4381C9.07132 11.4381 8.9599 11.392 8.87775 11.3098C8.7956 11.2277 8.74945 11.1162 8.74945 11.0001C8.74945 10.8839 8.7956 10.7725 8.87775 10.6903L11.6314 7.93756H1.3125C1.19647 7.93756 1.08519 7.89147 1.00314 7.80942C0.921094 7.72738 0.875 7.6161 0.875 7.50006Z"
                                    fill="white" />
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-5 text-lg-end text-center wow fadeInLeft">
                    <img
                    src="{{ $landing_data['about_us_image_content_full_url']  }}"
                     alt="about" class="about-img">
                </div>
            </div>
        </div>
    </section>
    <!-- ======= About Section ======= -->
    @endif

    @if($landing_data['available_zone_status'] && $landing_data['available_zone_list']  && count($landing_data['available_zone_list']) >0 )
    <!-- ======= About Section ======= -->
    <section class="about-section pt-80">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5 text-lg-end text-center wow fadeInLeft">
                    <img src="{{$landing_data['available_zone_image_full_url']}}" alt="about" class="about-img">
                </div>
                <div class="col-lg-7">
                    <div class="about-content wow fadeInDown ms-lg-auto">
                        <h2 class="title text-base">{{  $landing_data['available_zone_title'] }}</h2>
                        <p class="txt pt-3">
                            {{ $landing_data['available_zone_short_description'] }}
                        </p>
                        <div class="zone-list-container">
                            <div class="zone-list-wrapper mt-4">
                                <div class="zone-list">
                                    @foreach($landing_data['available_zone_list'] as $zone)

                                    <span class="item"
                                        data-bs-trigger="hover"
                                        data-bs-toggle="popover"
                                        data-bs-placement="top"
                                        title="{{ $zone['display_name'] }}"
                                        data-bs-content="And here's some amazing content. It's very engaging. Right?"
                                    >
                                    {{ $zone['display_name'] }}
                                    </span>

                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- ======= About Section ======= -->

    <!-- ======= Feature Section ======= -->

    @if (isset($landing_data['features']) && count($landing_data['features']) > 0)

    <section class="feature-section overflow-hidden pt-80">
        <div class="container">
            <div class="section-header text-center wow fadeInUp">
                <h2 class="title">
                        <span class="text-base">{{$landing_data['feature_title']}}</span>
                </h2>
                <p>
                    {{$landing_data['feature_sub_title'] }}
                </p>
            </div>
            <div class="feature-slider owl-theme owl-carousel">
                @foreach ($landing_data['features'] as $feature_data)
                <div class="feature-item wow fadeInUp">
                    <div class="feature-item-icon">
                        <img
                        src="{{ $feature_data['image_full_url'] }}" alt="">
                    </div>
                    <h4 class="title">{{ $feature_data['title'] }}</h4>
                    <div class="txt">
                        {{ $feature_data['description'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    <!-- ======= Feature Section ======= -->
    @if (isset($landing_data['services_title']) && (

        (isset($landing_data['services_order_title_1'] ) || isset($landing_data['services_order_title_2'] )) ||
        (isset($landing_data['services_manage_restaurant_title_1'] ) || isset($landing_data['services_manage_restaurant_title_2'] )) ||
        (isset($landing_data['services_manage_delivery_title_2'] ) || isset($landing_data['services_manage_delivery_title_1'] ))

    ))


    <!-- ======= Platform Section ======= -->
    <section class="platform-section pt-80 overflow-hidden">
        <div class="container">
            <div class="section-header text-center wow fadeInUp">
                <h2 class="title">
                    <span class="text-base">{{ $landing_data['services_title'] }}</span>
                </h2>
                <p>
                    {{ $landing_data['services_sub_title'] }}
                </p>
            </div>

            <!-- Tab Menu Starts-->
            <ul class="nav nav-tabs nav--tabs wow fadeInUp">
               @if(isset($landing_data['services_order_title_1'] ) || isset($landing_data['services_order_title_2'] ))
               <li>
                   <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#order-your-food">
                       <img class="svg" src="{{ dynamicAsset('/public/assets/landing/assets_new/img/platform/1.svg') }}" alt="">

                       {{  translate('Order_your_food') }}
                   </button>
               </li>
               @endif

                @if(isset($landing_data['services_manage_restaurant_title_1'] ) || isset($landing_data['services_manage_restaurant_title_2'] ))
                <li>
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#manage-restaurant">
                        <img class="svg" src="{{ dynamicAsset('/public/assets/landing/assets_new/img/platform/2.svg') }}"
                        alt="">
                        {{  translate('manage_your_restaurant') }}
                    </button>
                </li>
                @endif
                @if(isset($landing_data['services_manage_delivery_title_2'] ) || isset($landing_data['services_manage_delivery_title_1'] ))
                <li>
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#earn-delivery">
                        <img class="svg" src="{{ dynamicAsset('/public/assets/landing/assets_new/img/platform/3.svg') }}"
                        alt="">
                        {{  translate('earn_by_delivery') }}

                    </button>
                </li>
                @endif
            </ul>
            <!-- Tab Menu Ends -->

            <!-- Tab Content -->
            <div class="tab-content tab--content">
                @if(isset($landing_data['services_order_title_1'] ) || isset($landing_data['services_order_title_2'] ))
                <!-- Tab Pan -->
                <div class="tab-pane fade show active" id="order-your-food">
                    <div class="row gy-5 align-items-center flex-wrap-reverse">
                        <div class="col-lg-7">
                            <div class="platform-content wow fadeInUp">

                                <h4 class="subtitle">{{$landing_data['services_order_title_1']}}</h4>
                                <p>
                                    {{$landing_data['services_order_description_1']}}
                                </p>

                                <h4 class="subtitle">{{$landing_data['services_order_title_2']}}</h4>
                                <p>
                                    {{$landing_data['services_order_description_2']}}
                                </p>


                               @if($landing_data['services_order_button_status'] ==1 )
                                <a href="{{  $landing_data['services_order_button_link']  ??'#'}}" class="btn-base btn-sm">
                                    <span>{{ $landing_data['services_order_button_name'] }}</span>
                                    <svg width="14" height="15" viewBox="0 0 14 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.875 7.50006C0.875 7.38403 0.921094 7.27275 1.00314 7.19071C1.08519 7.10866 1.19647 7.06256 1.3125 7.06256H11.6314L8.87775 4.30981C8.7956 4.22766 8.74945 4.11624 8.74945 4.00006C8.74945 3.88389 8.7956 3.77247 8.87775 3.69031C8.9599 3.60816 9.07132 3.56201 9.1875 3.56201C9.30368 3.56201 9.4151 3.60816 9.49725 3.69031L12.9972 7.19031C13.038 7.23095 13.0703 7.27923 13.0924 7.33239C13.1144 7.38554 13.1258 7.44252 13.1258 7.50006C13.1258 7.55761 13.1144 7.61459 13.0924 7.66774C13.0703 7.7209 13.038 7.76917 12.9972 7.80981L9.49725 11.3098C9.4151 11.392 9.30368 11.4381 9.1875 11.4381C9.07132 11.4381 8.9599 11.392 8.87775 11.3098C8.7956 11.2277 8.74945 11.1162 8.74945 11.0001C8.74945 10.8839 8.7956 10.7725 8.87775 10.6903L11.6314 7.93756H1.3125C1.19647 7.93756 1.08519 7.89147 1.00314 7.80942C0.921094 7.72738 0.875 7.6161 0.875 7.50006Z"
                                            fill="white" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="platform-img">
                                <img src="{{asset('/public/assets/landing/assets_new/img/platform/order.svg')}}" alt="" class="svg">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab Pan -->
                @endif
                @if(isset($landing_data['services_manage_restaurant_title_1'] ) || isset($landing_data['services_manage_restaurant_title_2'] ))
                <!-- Tab Pan -->
                <div class="tab-pane fade show" id="manage-restaurant">
                    <div class="row gy-5 align-items-center flex-wrap-reverse">
                        <div class="col-lg-7">
                            <div class="platform-content">
                                <h4 class="subtitle">{{$landing_data['services_manage_restaurant_title_1']}}</h4>
                                <p>
                                    {{$landing_data['services_manage_restaurant_description_1']}}
                                </p>

                                <h4 class="subtitle">{{$landing_data['services_manage_restaurant_title_2']}}</h4>
                                <p>
                                    {{$landing_data['services_manage_restaurant_description_2']}}
                                </p>
                                @if($landing_data['services_manage_restaurant_button_status'] ==1 )
                                <a href="{{$landing_data['services_manage_restaurant_button_link']  ?? '#'}}"class="btn-base btn-sm">
                                    <span>{{ $landing_data['services_manage_restaurant_button_name'] }}</span>
                                    <svg width="14" height="15" viewBox="0 0 14 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.875 7.50006C0.875 7.38403 0.921094 7.27275 1.00314 7.19071C1.08519 7.10866 1.19647 7.06256 1.3125 7.06256H11.6314L8.87775 4.30981C8.7956 4.22766 8.74945 4.11624 8.74945 4.00006C8.74945 3.88389 8.7956 3.77247 8.87775 3.69031C8.9599 3.60816 9.07132 3.56201 9.1875 3.56201C9.30368 3.56201 9.4151 3.60816 9.49725 3.69031L12.9972 7.19031C13.038 7.23095 13.0703 7.27923 13.0924 7.33239C13.1144 7.38554 13.1258 7.44252 13.1258 7.50006C13.1258 7.55761 13.1144 7.61459 13.0924 7.66774C13.0703 7.7209 13.038 7.76917 12.9972 7.80981L9.49725 11.3098C9.4151 11.392 9.30368 11.4381 9.1875 11.4381C9.07132 11.4381 8.9599 11.392 8.87775 11.3098C8.7956 11.2277 8.74945 11.1162 8.74945 11.0001C8.74945 10.8839 8.7956 10.7725 8.87775 10.6903L11.6314 7.93756H1.3125C1.19647 7.93756 1.08519 7.89147 1.00314 7.80942C0.921094 7.72738 0.875 7.6161 0.875 7.50006Z"
                                            fill="white" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="platform-img">
                                <img src="{{asset('/public/assets/landing/assets_new/img/platform/restaurant.svg')}}" alt="" class="svg">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab Pan -->
                @endif
                @if(isset($landing_data['services_manage_delivery_title_2'] ) || isset($landing_data['services_manage_delivery_title_1'] ))
                <!-- Tab Pan -->
                <div class="tab-pane fade show" id="earn-delivery">
                    <div class="row gy-5 align-items-center flex-wrap-reverse">
                        <div class="col-lg-7">
                            <div class="platform-content">
                                <h4 class="subtitle">{{$landing_data['services_manage_delivery_title_1']}}</h4>
                                <p>
                                    {{$landing_data['services_manage_delivery_description_1']}}
                                </p>

                                <h4 class="subtitle">{{$landing_data['services_manage_delivery_title_2']}}</h4>
                                <p>
                                    {{$landing_data['services_manage_delivery_description_2']}}
                                </p>
                                @if($landing_data['services_manage_delivery_button_status'] ==1 )
                                <a href="{{$landing_data['services_manage_delivery_button_link']  ??  '#'}}"class="btn-base btn-sm">
                                    <span>{{ $landing_data['services_manage_delivery_button_name'] }}</span>
                                    <svg width="14" height="15" viewBox="0 0 14 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.875 7.50006C0.875 7.38403 0.921094 7.27275 1.00314 7.19071C1.08519 7.10866 1.19647 7.06256 1.3125 7.06256H11.6314L8.87775 4.30981C8.7956 4.22766 8.74945 4.11624 8.74945 4.00006C8.74945 3.88389 8.7956 3.77247 8.87775 3.69031C8.9599 3.60816 9.07132 3.56201 9.1875 3.56201C9.30368 3.56201 9.4151 3.60816 9.49725 3.69031L12.9972 7.19031C13.038 7.23095 13.0703 7.27923 13.0924 7.33239C13.1144 7.38554 13.1258 7.44252 13.1258 7.50006C13.1258 7.55761 13.1144 7.61459 13.0924 7.66774C13.0703 7.7209 13.038 7.76917 12.9972 7.80981L9.49725 11.3098C9.4151 11.392 9.30368 11.4381 9.1875 11.4381C9.07132 11.4381 8.9599 11.392 8.87775 11.3098C8.7956 11.2277 8.74945 11.1162 8.74945 11.0001C8.74945 10.8839 8.7956 10.7725 8.87775 10.6903L11.6314 7.93756H1.3125C1.19647 7.93756 1.08519 7.89147 1.00314 7.80942C0.921094 7.72738 0.875 7.6161 0.875 7.50006Z"
                                            fill="white" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="platform-img">
                                <img src="{{asset('/public/assets/landing/assets_new/img/platform/delivery.svg')}}" alt="" class="svg">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab Pan -->
                @endif


            </div>
            <!-- Tab Content -->

        </div>
    </section>
    <!-- ======= Platform Section ======= -->
    @endif
    @if(isset($landing_data['why_choose_us_title']) && (isset($landing_data['why_choose_us_image_1']) || isset($landing_data['why_choose_us_image_2']) || isset($landing_data['why_choose_us_image_3']) || isset($landing_data['why_choose_us_image_4'])))
    <!-- ======= Choose Section ======= -->
    <section class="choose-section overflow-hidden pt-80">
        <div class="container">
            <div class="section-header text-center wow fadeInUp">
                <h2 class=" title">
                    <span class="text-base">  {{ $landing_data['why_choose_us_title']  }}
                    </span>
                </h2>
                <p>
                    {{ $landing_data['why_choose_us_sub_title'] }}
                </p>
            </div>
            <div class="choose-wrapper">
                {{-- @foreach ($speciality as $sp) --}}
            @if (isset($landing_data['why_choose_us_image_1']))

                <div class="choose-item wow animate__dropIn">
                                <img
                    src="{{ $landing_data['why_choose_us_image_1_full_url'] }}"
                    alt="Image">
                    <div class="choose-item-content">
                        <div class="cont">
                            <h4 class="title">
                                {{ $landing_data['why_choose_us_title_1'] }}
                            </h4>
                        </div>
                    </div>
                </div>

            @endif
            @if (isset($landing_data['why_choose_us_image_2']))

                <div class="choose-item wow animate__dropIn">
                                <img
                    src="{{ $landing_data['why_choose_us_image_2_full_url'] }}"
                    alt="Image">
                    <div class="choose-item-content">
                        <div class="cont">
                            <h4 class="title">
                                {{ $landing_data['why_choose_us_title_2'] }}
                            </h4>
                        </div>
                    </div>
                </div>

            @endif
            @if (isset($landing_data['why_choose_us_image_3']))

                <div class="choose-item wow animate__dropIn">
                                <img
                    src="{{ $landing_data['why_choose_us_image_3_full_url'] }}"
                    alt="Image">
                    <div class="choose-item-content">
                        <div class="cont">
                            <h4 class="title">
                                {{ $landing_data['why_choose_us_title_3'] }}
                            </h4>
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($landing_data['why_choose_us_image_4']))
                <div class="choose-item wow animate__dropIn">
                                <img
                    src="{{ $landing_data['why_choose_us_image_4_full_url'] }}"
                    alt="Image">
                    <div class="choose-item-content">
                        <div class="cont">
                            <h4 class="title">
                                {{ $landing_data['why_choose_us_title_4'] }}
                            </h4>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>
    @endif
    <!-- ======= Choose Section ======= -->
    @if (isset($landing_data['earn_money_title']) )
    <!-- ======= Lets Start Section ======= -->
    <section class="lets-start-section pt-80 pb-80">
        <div class="container">
            <div class="section-header text-center wow fadeInUp">
                <h2 class="title mb-lg-5">
                    <span class="text-base">  {{  $landing_data['earn_money_title'] }}
                    </span>
                    </h2>
                <p>
                    {{ $landing_data['earn_money_sub_title']  }}
                </p>
            </div>
        </div>
        <!-- Lets Start SVG Start -->
        <div class="wow fadeInUp text-center">
            <img src="{{asset('/public/assets/landing/assets_new/img/business.svg')}}" alt="" class="svg">
        </div>
        <!-- Lets Start SVG Ends -->
    </section>
    <!-- ======= Lets Start Section ======= -->
    @endif
    @if (isset($landing_data['earn_money_reg_title']) || isset($landing_data['earn_money_reg_image'])  )

    <!-- ======= CTA Section ======= -->
    <div class="container">
        <section class="cta-section overflow-hidden">
            <div class="row align-items-end justify-content-end">
                <div class="col-md-6">
                    <div class="cta-content wow fadeInUp">
                        <div class="section-header ms-0">
                            <h2 class="title">
                                {{ $landing_data['earn_money_reg_title'] }}
                            </h2>
                        </div>
                        <div class="cta-btn-container d-flex flex-wrap">
                            @if ($landing_data['earn_money_restaurant_req_button_status'] )
                            <a href="{{  $landing_data['earn_money_restaurant_req_button_link'] ?? '#' }}" class="btn-base">
                                <span> {{ $landing_data['earn_money_restaurant_req_button_name']   }}</span>
                                <svg width="14" height="15" viewBox="0 0 14 15" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.875 7.67487C0.875 7.55884 0.921094 7.44756 1.00314 7.36551C1.08519 7.28346 1.19647 7.23737 1.3125 7.23737H11.6314L8.87775 4.48462C8.7956 4.40247 8.74945 4.29105 8.74945 4.17487C8.74945 4.05869 8.7956 3.94727 8.87775 3.86512C8.9599 3.78297 9.07132 3.73682 9.1875 3.73682C9.30368 3.73682 9.4151 3.78297 9.49725 3.86512L12.9973 7.36512C13.038 7.40576 13.0703 7.45404 13.0924 7.50719C13.1144 7.56034 13.1258 7.61732 13.1258 7.67487C13.1258 7.73242 13.1144 7.7894 13.0924 7.84255C13.0703 7.8957 13.038 7.94398 12.9973 7.98462L9.49725 11.4846C9.4151 11.5668 9.30368 11.6129 9.1875 11.6129C9.07132 11.6129 8.9599 11.5668 8.87775 11.4846C8.7956 11.4025 8.74945 11.291 8.74945 11.1749C8.74945 11.0587 8.7956 10.9473 8.87775 10.8651L11.6314 8.11237H1.3125C1.19647 8.11237 1.08519 8.06628 1.00314 7.98423C0.921094 7.90218 0.875 7.7909 0.875 7.67487V7.67487Z"
                                        fill="white" />
                                </svg>
                            </a>
                            @endif
                            @if ($landing_data['earn_money_delivery_man_req_button_status'] )
                            <a href="{{  $landing_data['earn_money_delivery_req_button_link'] ?? '#' }}" class="btn-base">
                                <span>{{ $landing_data['earn_money_delivety_man_req_button_name']  }}</span>
                                <svg width="14" height="15" viewBox="0 0 14 15" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.875 7.67487C0.875 7.55884 0.921094 7.44756 1.00314 7.36551C1.08519 7.28346 1.19647 7.23737 1.3125 7.23737H11.6314L8.87775 4.48462C8.7956 4.40247 8.74945 4.29105 8.74945 4.17487C8.74945 4.05869 8.7956 3.94727 8.87775 3.86512C8.9599 3.78297 9.07132 3.73682 9.1875 3.73682C9.30368 3.73682 9.4151 3.78297 9.49725 3.86512L12.9973 7.36512C13.038 7.40576 13.0703 7.45404 13.0924 7.50719C13.1144 7.56034 13.1258 7.61732 13.1258 7.67487C13.1258 7.73242 13.1144 7.7894 13.0924 7.84255C13.0703 7.8957 13.038 7.94398 12.9973 7.98462L9.49725 11.4846C9.4151 11.5668 9.30368 11.6129 9.1875 11.6129C9.07132 11.6129 8.9599 11.5668 8.87775 11.4846C8.7956 11.4025 8.74945 11.291 8.74945 11.1749C8.74945 11.0587 8.7956 10.9473 8.87775 10.8651L11.6314 8.11237H1.3125C1.19647 8.11237 1.08519 8.06628 1.00314 7.98423C0.921094 7.90218 0.875 7.7909 0.875 7.67487V7.67487Z"
                                        fill="white" />
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-center">
                    <img class="cta-img wow fadeInUp"
                    src="{{  $landing_data['earn_money_reg_image_full_url']  }}"
                    alt="">
                </div>
            </div>
        </section>
    </div>
    <!-- ======= CTA Section ======= -->
    @endif
    <!-- ======= Testimonial Section ======= -->
    @if ($landing_data['testimonials'])
    <section class="client-section pt-80 pb-80">
        <div class="container">
            <div class="section-header mw-100 text-center wow fadeInUp">
                <h2 class="title mb-lg-5">
                    <span class="text-base"> {{$landing_data['testimonial_title'] }}
                    </span>
                    </h2>
            </div>
            <div class="testimonial-slider wow fadeInUp">
                <div class="testimonial-item" dir="ltr">
                    <div class="position-relative">
                        <!-- Owl Nav Start -->
                        <a href="#0" class="client-nav client-prev">
                            <svg width="31" height="31" viewBox="0 0 31 31" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle r="15.175" transform="matrix(-1 0 0 1 15.8248 15.5246)"
                                    fill="url(#paint0_radial_3_2525)" />
                                <path
                                    d="M18.3286 7.93701L20.1117 9.72007L14.3199 15.5245L20.1117 21.3289L18.3286 23.112L10.7411 15.5245L18.3286 7.93701Z"
                                    fill="white" />
                                <defs>
                                    <radialGradient id="paint0_radial_3_2525" cx="0" cy="0" r="1"
                                        gradientUnits="userSpaceOnUse"
                                        gradientTransform="translate(15.175 15.175) rotate(90) scale(15.175)">
                                        <stop stop-color="#FFBE0B" />
                                        <stop offset="1" stop-color="#FB5607" />
                                    </radialGradient>
                                </defs>
                            </svg>
                        </a>
                        <a href="#0" class="client-nav client-next">
                            <svg width="31" height="31" viewBox="0 0 31 31" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="15.175" cy="15.5246" r="15.175" fill="url(#paint0_radial_3_2528)" />
                                <path
                                    d="M12.6711 7.93701L10.8881 9.72007L16.6799 15.5245L10.8881 21.3289L12.6711 23.112L20.2586 15.5245L12.6711 7.93701Z"
                                    fill="white" />
                                <defs>
                                    <radialGradient id="paint0_radial_3_2528" cx="0" cy="0" r="1"
                                        gradientUnits="userSpaceOnUse"
                                        gradientTransform="translate(15.175 15.5246) rotate(90) scale(15.175)">
                                        <stop stop-color="#FFBE0B" />
                                        <stop offset="1" stop-color="#FB5607" />
                                    </radialGradient>
                                </defs>
                            </svg>

                        </a><!-- Owl Nav End -->

                        <div id="sync2" class="owl-theme owl-carousel mb-4 mb-md-5">
                            @foreach ($landing_data['testimonials'] as $data)
                            <div class="img">
                                <img src="{{ $data['reviewer_image_full_url'] }}" alt="">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div id="sync1" class="owl-theme owl-carousel">
                        @foreach ($landing_data['testimonials'] as $data)
                        <div class="slide-item">
                            <blockquote class="quote">
                                “{{ $data['review'] }}”
                            </blockquote>
                            <h4 class="name">
                                {{ $data['name'] }}
                            </h4>
                            <span class="designation">{{ $data['designation'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


    </section>
    @endif
    @if (isset($new_user) && $new_user ==  true)

    <!-- Modal -->
    <div class="modal fade show" id="welcome-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header border-0 pt-4 px-4">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 pb-5">
                    <div class="text-center">
                        <img src="{{dynamicAsset('/public/assets/landing/img/welcome.svg')}}" class="svg mw-100 mb-3" alt="">
                        <h5 class="mb-3">{{ translate('Welcome_to') }} {{ $landing_data['business_name'] }}!</h5>
                        <p class="m-0 mb-4">{{ translate('Thanks for joining us! Your registration is under review. Hang tight, we’ll notify you once approved!') }}</p>
                        <a href="" data-bs-dismiss="modal" class="btn-base btn-sm">
                            <span>{{ translate('okay') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @endif
    <!-- ======= Testimonial Section ======= -->
    @endsection
    @push('script_2')
    @if (isset($new_user) && $new_user ==  true)
    <script>
        $(document).ready(function() {
            $('#welcome-modal').modal('show');
        });
    </script>
     @endif
    @endpush
