@extends('layouts.vendor.app')
@section('title',translate('messages.restaurant_view'))
@section('content')
    <div class="content container-fluid">
        <div class="card card-from-sm">
            <div class="card-body">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="page--header-title">
                            <h1 class="page-header-title"> {{translate('Shop_Details')}} </h1>
                            <p class="page-header-text">  {{translate('Created_at')}} {{ \App\CentralLogics\Helpers::time_date_format($shop->created_at) }}</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{route('vendor.shop.edit')}}" class="btn btn-primary"><i class="tio-open-in-new"></i> {{translate('Edit_Shop')}} </a>
                        </div>
                    </div>
                </div>
                <!-- End Page Header -->
                <!-- Banner -->
                <section class="shop-details-banner">
                    <div class="card">
                        <div class="card-body px-0 pt-0">
                            <img  class="shop-details-banner-img"
                            src="{{ $shop?->cover_photo_full_url ?? dynamicAsset('public/assets/admin/img/900x400/img1.jpg') }}"
                            alt="image">

                            <div class="shop-details-banner-content">
                                <div class="shop-details-banner-content-thumbnail">
                                    <img class="thumbnail"
                                    src="{{ $shop?->logo_full_url ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
                                    alt="image">
                                    <h3 class="mt-4 pt-3 mb-4 d-sm-none">{{$shop->name}}</h3>
                                </div>
                                <div class="shop-details-banner-content-content">
                                    <h3 class="mt-sm-4 pt-sm-3 mb-4 d-none d-sm-block">{{$shop->name}}</h3>
                                    <div class="shop-details-model">
                                        <div class="shop-details-model-item">
                                            <img src="{{dynamicAsset('/public/assets/admin/new-img/icon-1.png')}}" alt="">
                                            <div class="shop-details-model-item-content">
                                                <h6>  {{ translate('Business_Model') }} </h6>
                                                @if($shop->restaurant_model == 'commission')
                                                    <div>{{translate('Commission_Base')}}</div>
                                                @elseif($shop->restaurant_model == 'none')
                                                    <div>{{translate('Not_chosen')}}</div>
                                                @else
                                                    <div>{{translate('Subscription')}}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="shop-details-model-item">
                                            <img src="{{dynamicAsset('/public/assets/admin/new-img/icon_6.png')}}" alt="">
                                            <div class="shop-details-model-item-content">
                                                <h6>  {{ translate('admin_Commission') }} </h6>
                                                <div> {{(isset($shop->comission)?$shop->comission:\App\Models\BusinessSetting::where('key','admin_commission')->first()?->value)}} %</div>
                                            </div>
                                        </div> <div class="shop-details-model-item">
                                            <img src="{{dynamicAsset('/public/assets/admin/new-img/icon-2.png')}}" alt="">
                                            <div class="shop-details-model-item-content">
                                                <h6>  {{ translate('VAT/TAX') }} </h6>
                                                <div> {{ $shop->tax  }} %</div>
                                            </div>
                                        </div>
                                        <div class="shop-details-model-item">
                                            <img src="{{dynamicAsset('/public/assets/admin/new-img/icon-3.png')}}" alt="">
                                            <div class="shop-details-model-item-content">
                                                <h6>{{ translate('Phone') }} </h6>
                                                <div>{{$shop->phone}}</div>
                                            </div>
                                        </div>
                                        <div class="shop-details-model-item">
                                            <img src="{{dynamicAsset('/public/assets/admin/new-img/icon-4.png')}}" alt="">
                                            <div class="shop-details-model-item-content">
                                                <h6> {{ translate('Address') }} </h6>
                                                <div>{{$shop->address}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header justify-content-between align-items-center">
                            <label class="input-label text-capitalize d-inline-flex align-items-center m-0">
                                <span class="line--limit-1"><img src="{{dynamicAsset('/public/assets/admin/img/company.png')}}" alt=""> <b> {{translate('Announcement')}} </b> </span>
                                <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('This_announcement_shown_in_the_user_app/web')}}" class="input-label-secondary">
                                    <img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="info"></span>
                            </label>
                            <label class="toggle-switch toggle-switch-sm m-0">
                                <input type="checkbox"  name="announcement" class="toggle-switch-input update-status" data-url="{{route('vendor.business-settings.toggle-settings',[$shop->id,$shop->announcement?0:1, 'announcement'])}}" id="announcement" {{$shop->announcement?'checked':''}} >
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                        <div class="card-body">
                            <form action="{{route('vendor.shop.update-message')}}" method="post">
                                @csrf
                                <textarea name="announcement_message" id="" maxlength="254" class="form-control h-100px" placeholder="{{ translate('messages.ex_:_ABC_Company') }}">{{ $shop->announcement_message??'' }}</textarea>
                                <div class="mt-3 text-right">
                                    <button type="submit" class="btn btn--primary">{{translate('publish')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <!-- Banner -->

            </div>
        </div>
    </div>
@endsection


@push('script_2')
    <script>
        "use strict";
        $('.update-status').on('click', function (){
            let route = $(this).data('url');
            let code = $(this).data('code');
            updateStatus(route, code);
        })

        function updateStatus(route, code) {
            $.get({
                url: route,
                data: {
                    code: code,
                },
                success: function (data) {
                    if (data.error == 403) {
                        toastr.error('{{translate('status_can_not_be_updated')}}');
                        location.reload();
                    }
                    else{
                        toastr.success('{{translate('messages.Restaurant settings updated!')}}');
                    }
                }
            });
        }
    </script>
    <!-- Page level plugins -->
@endpush
