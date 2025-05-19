@php use App\CentralLogics\Helpers; @endphp
@extends('layouts.admin.app')

@section('title',translate('messages.Admin_Landing_Page'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-0">
            <div class="d-flex flex-wrap justify-content-between">
                <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ dynamicAsset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                </span>
                    <span>
                    {{ translate('messages.Admin Landing Page') }}
                </span>
                </h1>
            </div>
        </div>
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            @include('admin-views.landing_page.top_menu.admin_landing_menu')
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active">
                <form action="{{ route('admin.landing_page.testimonial_update',[$review->id]) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <h5 class="card-title mb-3 mt-3">
                        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                        <span>{{translate('Testimonial_List_Section')}}</span>
                    </h5>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">

                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">{{translate('Reviewer_Name')}}</label>
                                            <input id="name" type="text" name="name" maxlength="191" required
                                                   value="{{ $review->name }}" class="form-control"
                                                   placeholder="{{translate('Ex:_John')}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="designation"
                                                   class="form-label">{{translate('Designation')}}</label>
                                            <input id="designation" type="text" maxlength="191" required
                                                   name="designation" value="{{ $review->designation }}"
                                                   class="form-control" placeholder="{{translate('Ex:_CTO')}}">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="review" class="form-label">{{translate('messages.review')}}
                                                <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                      data-placement="right"
                                                      data-original-title="{{translate('Write_the_review_within_300_characters')}}">
                                                <i class="tio-info-outined"></i>
                                            </span></label>
                                            <textarea id="review" name="review" maxlength="300" required
                                                      placeholder="{{translate('Very_Good_Company')}}"
                                                      class="form-control h92px">{{ $review->review }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-40px">
                                        <div>
                                            <label class="form-label d-block mb-2">
                                                {{translate('Reviewer_Image*')}} <span
                                                    class="text--primary">{{translate('(1:1)')}}</span>
                                            </label>
                                            <div class="position-relative">
                                                <label class="upload-img-3 m-0 d-block">
                                                    <div class="img">
                                                        <img src="{{ $review?->reviewer_image_full_url ?? dynamicAsset('/public/assets/admin/img/aspect-1.png')}}"
                                                             data-onerror-image="{{dynamicAsset('/public/assets/admin/img/aspect-1.png')}}"
                                                             class="vertical-img max-w-187px onerror-image" alt="">
                                                    </div>
                                                    <input type="file" name="reviewer_image" hidden="">
                                                </label>
                                                @if ($review->reviewer_image)
                                                    <span id="remove_image_1"
                                                          class="remove_image_button remove-image"
                                                          data-id="remove_image_1"
                                                          data-title="{{translate('Warning!')}}"
                                                          data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"
                                                    > <i class="tio-clear"></i></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                                <button type="submit"
                                        class="btn btn--primary">{{translate('messages.Update')}}</button>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <form id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{  $review?->id}}">
        <input type="hidden" name="model_name" value="AdminTestimonial">
        <input type="hidden" name="image_path" value="reviewer_image">
        <input type="hidden" name="field_name" value="reviewer_image">
    </form>
@endsection
