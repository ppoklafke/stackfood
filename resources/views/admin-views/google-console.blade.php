@extends('layouts.admin.app')

@section('title',translate('seo'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

    @include('admin-views._new-nav')

    <!-- Main Content -->
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('Google Search Console')}}</h4>
                        <p class="m-0 fs-12">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras a ullamcorper est. Nunc imperdiet efficitur eleifend. Integer accumsan tempus est et laoreet.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Single Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">Use default sorting list</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="Add dynamic secure login url for Admin">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">Currently sorting this section by top ratings</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="checkbox" name="status" value="1" class="toggle-switch-input collapse-div-toggler">
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by" checked>
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings +reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('All')}}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="border rounded p-3">
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If close store then show it last </span>
                                        </label>
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If store is temporary off then remove it from list</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">Use default sorting list</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="Add dynamic secure login url for Admin">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">Currently sorting this section by top ratings</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="checkbox" name="status" value="1" class="toggle-switch-input collapse-div-toggler">
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by" checked>
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings +reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('All')}}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="border rounded p-3">
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If close store then show it last </span>
                                        </label>
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If store is temporary off then remove it from list</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('Google Search Console')}}</h4>
                        <p class="m-0 fs-12">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras a ullamcorper est. Nunc imperdiet efficitur eleifend. Integer accumsan tempus est et laoreet.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Single Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">Use default sorting list</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="Add dynamic secure login url for Admin">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">Currently sorting this section by top ratings</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="checkbox" name="status" value="1" class="toggle-switch-input collapse-div-toggler">
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by" checked>
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings +reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('All')}}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="border rounded p-3">
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If close store then show it last </span>
                                        </label>
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If store is temporary off then remove it from list</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">Use default sorting list</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="Add dynamic secure login url for Admin">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">Currently sorting this section by top ratings</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="checkbox" name="status" value="1" class="toggle-switch-input collapse-div-toggler">
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by" checked>
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings +reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('All')}}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="border rounded p-3">
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If close store then show it last </span>
                                        </label>
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If store is temporary off then remove it from list</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('Google Search Console')}}</h4>
                        <p class="m-0 fs-12">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras a ullamcorper est. Nunc imperdiet efficitur eleifend. Integer accumsan tempus est et laoreet.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Single Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">Use default sorting list</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="Add dynamic secure login url for Admin">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">Currently sorting this section by top ratings</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="checkbox" name="status" value="1" class="toggle-switch-input collapse-div-toggler">
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by" checked>
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings +reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('All')}}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="border rounded p-3">
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If close store then show it last </span>
                                        </label>
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If store is temporary off then remove it from list</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">Use default sorting list</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="Add dynamic secure login url for Admin">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">Currently sorting this section by top ratings</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="checkbox" name="status" value="1" class="toggle-switch-input collapse-div-toggler">
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by" checked>
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings +reviews')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="sort-by">
                                            <span class="form-check-label">
                                                {{translate('All')}}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="border rounded p-3">
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If close store then show it last </span>
                                        </label>
                                        <label class="checkbox--item">
                                            <input type="checkbox" name="canonicals">
                                            <img class="unchecked" src="{{dynamicAsset('public/assets/admin/img/uncheck-icon.svg')}}" alt="">
                                            <img class="checked" src="{{dynamicAsset('public/assets/admin/img/check-icon.svg')}}" alt="">
                                            <span>If store is temporary off then remove it from list</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="btn--container justify-content-end position-sticky bottom-0 p-3 bg-white">
                <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                <button type="submit" class="btn btn--primary">{{translate('Save Information')}}</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('script_2')
<script>
    $(".collapse-div-toggler").on('change', function() {
        if ($(this).is(':checked')) {
            $(this).closest('.sorting-card').find('.inner-collapse-div').slideDown();
        } else {
            $(this).closest('.sorting-card').find('.inner-collapse-div').slideUp();
        }
    });
</script>
@endpush
