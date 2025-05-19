@php use App\CentralLogics\Helpers;use Illuminate\Support\Str; @endphp
@extends('layouts.admin.app')
@section('title', translate('messages.Admin_Landing_Page'))
@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px"
                             alt="public">
                    </div>
                    <span>
                        {{ translate('Admin_Landing_Page') }}
                    </span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal"
                     data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.landing_page.top_menu.admin_landing_menu')
            </div>
        </div>


        @php($default_lang = str_replace('_', '-', app()->getLocale()))
        @if($language)
            <ul class="nav nav-tabs mb-4 border-0">
                <li class="nav-item">
                    <a class="nav-link lang_link active"
                       href="#"
                       id="default-link">{{translate('messages.default')}}</a>
                </li>
                @foreach (json_decode($language) as $lang)
                    <li class="nav-item">
                        <a class="nav-link lang_link"
                           href="#"
                           id="{{ $lang }}-link">{{ Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                    </li>
                @endforeach
            </ul>
        @endif


        <form action="{{ route('admin.landing_page.settings', 'testimonial-title') }}" method="POST">
            @csrf
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3 lang_form" id="default-form">
                        <div class="col-sm-12">
                            <label for="testimonial_title" class="form-label">{{translate('Title')}}
                                ({{translate('default')  }})
                                <span class="input-label-secondary text--title" data-toggle="tooltip"
                                      data-placement="right"
                                      data-original-title="{{translate('Write_the_title_within_60_characters')}}">
                                                                <i class="tio-info-outined"></i>
                                                            </span>
                            </label>
                            <input id="testimonial_title" type="text" name="testimonial_title[]" maxlength="60"
                                   class="form-control" value="{{$testimonial_title?->getRawOriginal('value') ?? ''}}"
                                   placeholder="{{translate('messages.title_here...')}}">
                        </div>
                    </div>
                    <input type="hidden" name="lang[]" value="default">
                    @if ($language)
                        @foreach(json_decode($language) as $lang)
                                <?php
                                if ($testimonial_title?->translations) {
                                    $testimonial_title_translate = [];
                                    foreach ($testimonial_title->translations as $t) {
                                        if ($t->locale == $lang && $t->key == 'testimonial_title') {
                                            $testimonial_title_translate[$lang]['value'] = $t->value;
                                        }
                                    }
                                }
                                ?>
                            <div class="row g-3 d-none lang_form" id="{{$lang}}-form">
                                <div class="col-sm-12">
                                    <label for="testimonial_title{{$lang}}" class="form-label">{{translate('Title')}}
                                        ({{strtoupper($lang)}})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                              data-placement="right"
                                              data-original-title="{{translate('Write_the_title_within_60_characters')}}">
                                                                <i class="tio-info-outined"></i>
                                                            </span>
                                    </label>
                                    <input id="testimonial_title{{$lang}}" type="text" name="testimonial_title[]"
                                           maxlength="60" class="form-control"
                                           value="{{ $testimonial_title_translate[$lang]['value']?? '' }}"
                                           placeholder="{{translate('messages.title_here...')}}">
                                </div>
                            </div>
                            <input type="hidden" name="lang[]" value="{{$lang}}">
                        @endforeach
                    @endif
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </div>
        </form>

        <form action="{{ route('admin.landing_page.testimonial_store') }}" method="post" enctype="multipart/form-data">
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
                                    <input id="name" type="text" maxlength="191" required name="name"
                                           class="form-control" placeholder="{{translate('Ex:_John')}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="designation" class="form-label">{{translate('Designation')}}</label>
                                    <input id="designation" type="text" maxlength="191" required name="designation"
                                           class="form-control" placeholder="{{translate('Ex:_CTO')}}">
                                </div>
                                <div class="col-md-12">
                                    <label for="review" class="form-label">{{translate('messages.review')}}
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                              data-placement="right"
                                              data-original-title="{{translate('Write_the_review_within_300_characters')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <textarea id="review" name="review" maxlength="300" required
                                              placeholder="{{translate('Very_Good_Company')}}"
                                              class="form-control h92px"></textarea>
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
                                    <label class="upload-img-3 m-0 d-block">
                                        <div class="img">
                                            <img src="{{dynamicAsset("/public/assets/admin/img/aspect-1.png")}}"
                                                 class="vertical-img max-w-187px" alt="">
                                        </div>
                                        <input type="file" name="reviewer_image" hidden="">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('Add')}}</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="card-body p-0">
            <div class="table-responsive pt-0">
                <table
                    class="table table-borderless table-thead-bordered table-align-middle table-nowrap card-table m-0">
                    <thead class="thead-light">
                    <tr>
                        <th class="border-top-0">{{translate('sl')}}</th>
                        <th class="border-top-0">{{translate('Reviewer_Name')}}</th>
                        <th class="border-top-0">{{translate('Designation')}}</th>
                        <th class="border-top-0">{{translate('Reviews')}}</th>
                        <th class="border-top-0">{{translate('Reviewer_Image')}}</th>
                        <th class="border-top-0">{{translate('Status')}}</th>
                        <th class="text-center border-top-0">{{translate('Action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($testimonial as $key=>$review)
                        <tr>
                            <td>{{ $key+$testimonial->firstItem() }}</td>
                            <td>
                                <div class="text--title">
                                    {{ $review->name }}
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    {{ $review->designation }}
                                </div>
                            </td>
                            <td>
                                <div class="word-break">
                                    {{ Str::limit($review->review, 50, $end='...')    }}
                                </div>
                            </td>
                            <td>
                                <img src="{{ $review?->reviewer_image_full_url ?? dynamicAsset('/public/assets/admin/img/upload.png') }}"
                                     data-onerror-image="{{dynamicAsset('/public/assets/admin/img/upload.png')}}"
                                     class="__size-105 onerror-image" alt="">
                            </td>
                            <td>
                                <label class="toggle-switch toggle-switch-sm">
                                    <input type="checkbox"
                                           data-id="testimonial_status_{{$review->id}}"
                                           data-type="status"
                                           data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/testimonial-on.png') }}"
                                           data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/testimonial-off.png') }}"
                                           data-title-on="{{translate('By_Turning_ON ')}} <strong>{{translate('This_testimonial')}}</strong>"
                                           data-title-off="{{translate('By_Turning_OFF')}} <strong>{{translate('This_testimonial')}}</strong>"
                                           data-text-on="<p>{{translate('This_section_will_be_enabled._You_can_see_this_section_on_your_landing_page')}}</p>"
                                           data-text-off="<p>{{translate('This_section_will_be_disabled._You_can_enable_it_in_the_settings')}}</p>"
                                           class="status toggle-switch-input dynamic-checkbox"
                                           id="testimonial_status_{{$review->id}}" {{$review->status?'checked':''}}>
                                    <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                </label>
                                <form
                                    action="{{route('admin.landing_page.testimonial_status',[$review->id,$review->status?0:1])}}"
                                    method="get" id="testimonial_status_{{$review->id}}_form">
                                </form>
                            </td>

                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--primary btn-outline-primary"
                                       href="{{route('admin.landing_page.testimonial_edit',[$review['id']])}}">
                                        <i class="tio-edit"></i>
                                    </a>
                                    <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                       href="javascript:"
                                       data-id="review-{{$review['id']}}"
                                       data-message="{{ translate('Want_to_delete_this_review_?') }}"
                                       title="{{translate('messages.delete_review')}}"><i
                                            class="tio-delete-outlined"></i>
                                    </a>
                                    <form action="{{route('admin.landing_page.testimonial_delete',[$review['id']])}}"
                                          method="post" id="review-{{$review['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty

                    @endforelse
                    </tbody>
                </table>
                @if(count($testimonial) === 0)
                    <div class="empty--data">
                        <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                @endif
            </div>
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div>
                        {!! $testimonial->appends(request()->all())->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

