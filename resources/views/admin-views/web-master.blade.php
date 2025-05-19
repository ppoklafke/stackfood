@extends('layouts.admin.app')

@section('title',translate('seo'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

    @include('admin-views._new-nav')

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <div class="w-100">
                <h4 class="title m-0">{{translate('Web Master Tool')}}</h4>
                <p class="m-0">{{translate("Website's performance, indexing status, and search visibility.")}} <a href="" class="text-underline text-primary font-semibold">{{translate('Learn more')}}</a> </p>
            </div>
        </div>
        <div class="card-body">
            <div class="p-4 border rounded mb-3">
                <div class="row g-3">
                    <div class="col-md-4 col-xl-3">
                        <img src="{{dynamicAsset('public/assets/admin/img/google-1.png')}}" alt="">
                        <h5 class="m-0 mt-3">{{translate('Google Search Console')}}</h5>
                    </div>
                    <div class="col-md-8 col-xl-9">
                        <input type="text" placeholder="{{translate('Enter your HTML code or ID')}}" class="form-control">
                        <div class="mt-10px fs-12">
                            <div>{{translate('Google Console verification HTML code or ID. Learn how to get it.')}} <a href="" class="text-primary text-underline font-semibold">{{translate('Search Console verification Page')}}</a></div>
                        </div>
                        <div class="mt-3">
                            <span class="badge badge-soft-danger">
                                &lt;meta name= “ google-site-verification” content=”your-id” /&gt;
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border rounded mb-20px">
                <div class="row g-3">
                    <div class="col-md-4 col-xl-3">
                        <img src="{{dynamicAsset('public/assets/admin/img/bing-1.png')}}" alt="">
                        <h5 class="m-0 mt-3">{{translate('Bing Webmaster Tools')}}</h5>
                    </div>
                    <div class="col-md-8 col-xl-9">
                        <input type="text" placeholder="{{translate('Enter your HTML code or ID')}}" class="form-control">
                        <div class="mt-10px fs-12">
                            <div>{{translate('Bing Webmaster Tools verification HTML code or ID. Learn how to get it.')}} <a href="" class="text-primary text-underline font-semibold">{{translate('Search Console verification Page')}}</a></div>
                        </div>
                        <div class="mt-3">
                            <span class="badge badge-soft-danger">
                                &lt;meta name= “ google-site-verification” content=”your-id” /&gt;
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn--container justify-content-end">
                <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                <button type="submit" class="btn btn--primary">{{translate('Submit')}}</button>
            </div>
        </div>
    </div>


</div>
@endsection

@push('script_2')

@endpush
