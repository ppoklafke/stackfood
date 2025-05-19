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
                <h4 class="title m-0">{{translate('Site Map')}}</h4>
                <p class="m-0">{{translate("Organized for navigation and search engine optimization.")}}</p>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-20px">
                <div class="d-flex align-items-center gap-2 fs-12 p-3 rounded badge--info">
                    <img src="{{dynamicAsset('public/assets/admin/img/idea.png')}}" alt="">
                    <div class="w-0 flex-grow">
                        {{translate('A sitemap for an e-commerce website organises product pages, categories, and other essential URLs in a structured format. It helps search engines crawl and index the site efficiently, improving its visibility and search engine rankings. Additionally, a well-designed sitemap enhances user experience by providing easy navigation and access to all parts of the online store.')}}
                    </div>
                </div>
            </div>
            <div class="text-center py-3">
                <h6 class="fs-16 mb-3">Download Generate Sitemap</h6>
                <a href="" download class="btn btn--primary">Download</a>
            </div>
        </div>
    </div>


</div>
@endsection

@push('script_2')
@endpush
