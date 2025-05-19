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
                <h4 class="title m-0">{{translate('Robot.txt Editor')}}</h4>
                <p class="m-0">{{translate("Control search engine crawlers' access to specific pages on a website.")}}</p>
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
            <form>
                <div class="mb-20px">
                    <textarea class="ckeditor form-control" name="robot_txt[]"></textarea>
                </div>
                <div class="btn--container justify-content-end">
                    <button type="submit" class="btn btn--primary">{{translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>


</div>
@endsection

@push('script_2')
<script src="{{dynamicAsset('public/assets/admin/ckeditor/ckeditor.js')}}"></script>
@endpush
