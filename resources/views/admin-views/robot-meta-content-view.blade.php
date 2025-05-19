@extends('layouts.admin.app')

@section('title',translate('seo'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

    @include('admin-views._new-nav')

    <!-- Main Content -->
    <div class="card">
        <div class="card-header flex-wrap gap-2">
            <div class="">
                <h4 class="title m-0">{{translate('Robots Meta Content & OG Meta content')}}</h4>
                <p class="m-0">{{translate("Website's performance, indexing status, and search visibility.")}} <a href="" class="text-primary text-underline font-semibold">{{translate('Learn more')}}</a></p>
            </div>
            <div>
                <a href="" class="text-primary text-underline font-semibold">{{translate('Back to list')}}</a>
            </div>
        </div>
        <div class="card-body p-xl-30">
            <form>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">{{translate('Meta Title')}}</label>
                                <input type="text" placeholder="{{translate('Meta Title')}}" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{translate('Meta Description')}}</label>
                                <textarea placeholder="{{translate('Write Description...')}}" class="form-control" rows="5" class=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column align-items-center gap-3">
                            <p class="mb-0"> <span class="font-semibold text-title">{{ translate('Banner_image') }}</span> <small class="text-danger">(size: 2:1)</small></p>
                            <div class="image-box banner2">
                                <label for="image-input" class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-2">
                                    <img width="30" class="upload-icon" src="{{dynamicAsset('public/assets/admin/img/upload-icon.png')}}" alt="Upload Icon">
                                    <span class="upload-text">{{ translate('Upload Image')}}</span>
                                    <img src="#" alt="Preview Image" class="preview-image">
                                </label>
                                <button type="button" class="delete_image">
                                    <i class="tio-delete"></i>
                                </button>
                                <input type="file" id="image-input" name="image" accept="image/*" hidden>
                            </div>

                            <p class="opacity-75 max-w220 mx-auto text-center">
                                {{ translate('Image format : Jpg, png, jpeg Image Size : Max 2 MB')}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-4 border rounded my-4">
                    <div class="row g-3">
                        <div class="col-md-4 col-xl-2">
                            <h5 class="m-0 mt-3">{{translate('Canonicals URL')}}</h5>
                        </div>
                        <div class="col-md-8 col-xl-8">
                            <input type="text" placeholder="{{translate('Enter url...')}}" class="form-control">
                            <div class="mt-10px fs-12">
                                <div>{{translate('Learn how to get it.')}} <a href="" class="text-primary text-underline font-semibold">{{translate('Learn more')}}</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6 col-xl-5">
                        <div class="robots-meta-checkbox-card d-flex flex-wrap gap-2 justify-content-between h-100">
                            <div class="item">
                                <label class="checkbox--item">
                                    <input type="checkbox" name="canonicals">
                                    <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                    <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                    <span>Index</span>
                                    <span data-toggle="tooltip" title="add new page">
                                        <img src="{{dynamicAsset('public/assets/admin/img/query.png')}}" alt="">
                                    </span>
                                </label>
                                <label class="checkbox--item">
                                    <input type="checkbox" name="canonicals">
                                    <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                    <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                    <span>No Follow</span>
                                    <span data-toggle="tooltip" title="add new page">
                                        <img src="{{dynamicAsset('public/assets/admin/img/query.png')}}" alt="">
                                    </span>
                                </label>
                                <label class="checkbox--item">
                                    <input type="checkbox" name="canonicals">
                                    <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                    <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                    <span>No Image Index</span>
                                    <span data-toggle="tooltip" title="add new page">
                                        <img src="{{dynamicAsset('public/assets/admin/img/query.png')}}" alt="">
                                    </span>
                                </label>
                            </div>
                            <div class="item">
                                <label class="checkbox--item">
                                    <input type="checkbox" name="canonicals">
                                    <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                    <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                    <span>No Index</span>
                                    <span data-toggle="tooltip" title="add new page">
                                        <img src="{{dynamicAsset('public/assets/admin/img/query.png')}}" alt="">
                                    </span>
                                </label>
                                <label class="checkbox--item">
                                    <input type="checkbox" name="canonicals">
                                    <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                    <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                    <span>No Archive</span>
                                    <span data-toggle="tooltip" title="add new page">
                                        <img src="{{dynamicAsset('public/assets/admin/img/query.png')}}" alt="">
                                    </span>
                                </label>
                                <label class="checkbox--item">
                                    <input type="checkbox" name="canonicals">
                                    <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                    <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                    <span>No Snippet</span>
                                    <span data-toggle="tooltip" title="add new page">
                                        <img src="{{dynamicAsset('public/assets/admin/img/query.png')}}" alt="">
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-5">
                        <div class="robots-meta-checkbox-card d-flex flex-column gap-2 h-100">
                            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                                <div class="item">
                                    <label class="checkbox--item m-0">
                                        <input type="checkbox" name="canonicals">
                                        <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                        <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                        <span>Max Snippet</span>
                                        <span data-toggle="tooltip" title="add new page">
                                            <img src="{{dynamicAsset('public/assets/admin/img/query.png')}}" alt="">
                                        </span>
                                    </label>
                                </div>
                                <div class="item w-120px flex-grow-0">
                                    <input type="text" placeholder="-1" class="form-control h-30 py-0">
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                               <div class="item">
                                    <label class="checkbox--item m-0">
                                        <input type="checkbox" name="canonicals">
                                        <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                        <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                        <span>Max Video Preview</span>
                                        <span data-toggle="tooltip" title="add new page">
                                            <img src="{{dynamicAsset('public/assets/admin/img/query.png')}}" alt="">
                                        </span>
                                    </label>
                               </div>
                                <div class="item w-120px flex-grow-0">
                                    <input type="text" placeholder="-1" class="form-control h-30 py-0">
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                               <div class="item">
                                    <label class="checkbox--item m-0">
                                        <input type="checkbox" name="canonicals">
                                        <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                        <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                        <span>Max Image Preview</span>
                                        <span data-toggle="tooltip" title="add new page">
                                            <img src="{{dynamicAsset('public/assets/admin/img/query.png')}}" alt="">
                                        </span>
                                    </label>
                               </div>
                                <div class="item w-120px flex-grow-0">
                                    <select class="form-control h-30 py-0" name="">
                                        <option>Large</option>
                                        <option>Medium</option>
                                        <option>Small</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end mt-4">
                    <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                    <button type="submit" class="btn btn--primary">{{translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>


</div>
@endsection

@push('script_2')
@endpush
