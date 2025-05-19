@php use App\CentralLogics\Helpers;use App\Models\ReactFaq; @endphp
@extends('layouts.admin.app')

@section('title', translate('messages.landing_page_settings'))
@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title text-capitalize">
                    <span>
                        {{ translate('React_Registration_Page') }}
                    </span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.registration-page.top_menu.react_registration_menu')
            </div>
        </div>
        <div class="">
            <div class="registration-form-wrapper">
                @php($default_lang = str_replace('_', '-', app()->getLocale()))
                @if($language)
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link lang_link active"
                            href="#"
                            id="default-link">{{translate('messages.default')}}</a>
                        </li>
                        @foreach (json_decode($language) as $lang)
                            <li class="nav-item">
                                <a class="nav-link lang_link"
                                    href="#"
                                    id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="steeper-header-title mb-3">
                    <div class="page--header-title">
                        <h1 class="page-header-title">{{ translate('Add_FAQ') }}</h1>
                    </div>
                </div>
                <div class="card p-sm-4 p-3">
                    <form id="faq-form" action="{{ route('admin.business-settings.react-registration-page.faq_store') }}" method="post">
                        @csrf
                        <div class="px-xl-4 pt-xl-3">
                            <div class="row g-3 get-checked-required-field faq-from-list">
                                <div class="col-md-6">
                                    <div class="form-group lang_form default-form">
                                        <input type="hidden" name="lang[]" value="default">
                                        <label class="form-label tooltip-label" for="question">{{ translate('Question') }}
                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter question" data-title="
                                                <div class='text-start'>{{ translate('Write_the_title_within_50_characters') }}</div>
                                                ">
                                                <i class="tio-info"></i>
                                            </span>
                                        </label>
                                        <textarea class="form-control" name="question[]" rows="1" placeholder="{{translate('Enter_Question')}}" data-maxlength="50"></textarea>
                                        <div class="d-flex justify-content-end">
                                            <span class="text-body-light">0/50</span>
                                        </div>
                                    </div>
                                    @if($language)
                                        @foreach(json_decode($language) as $lang)

                                                <div class="form-group d-none lang_form" id="{{$lang}}-form1">
                                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                                    <label class="form-label tooltip-label" for="question">{{ translate('Question') }} ({{strtoupper($lang)}})
                                                        <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter question" data-title="
                                                            <div class='text-start'>{{ translate('Write_the_title_within_50_characters') }}</div>
                                                            ">
                                                            <i class="tio-info"></i>
                                                        </span>
                                                    </label>
                                                    <textarea class="form-control" name="question[]" rows="1" placeholder="{{translate('Enter_Question')}}" data-maxlength="50"></textarea>
                                                    <div class="d-flex justify-content-end">
                                                        <span class="text-body-light">0/50</span>
                                                    </div>
                                                </div>
                                        @endforeach
                                    @endif
                                    <div class="">
                                        <label class="form-label tooltip-label" for="priority">{{ translate('messages.Priority') }}
                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter priority" data-title="
                                                <div class='text-start'>Priority...</div>
                                                ">
                                                <i class="tio-info"></i>
                                            </span>
                                        </label>
                                        @php($max = $faqCount > 0 ? $faqCount + 1 : 1)

                                        <label for="priority"></label>
                                        <select name="priority" class="form-control js-select2-custom h--45x select2-hidden-accessible" id="priority">
                                            @for ($i = 1; $i <= $max; $i++)
                                                <option value="{{ $i }}" {{ $i === $max ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group lang_form default-form">
                                        <label class="form-label tooltip-label" for="answer">{{ translate('answer') }}
                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter answer" data-title="
                                                <div class='text-start'>{{ translate('Write_the_title_within_150_characters') }}</div>
                                                ">
                                                <i class="tio-info"></i>
                                            </span>
                                        </label>
                                        <textarea class="form-control" name="answer[]" rows="6" data-maxlength="150" placeholder="{{ translate('Step text...') }}"></textarea>
                                        <div class="d-flex justify-content-end">
                                            <span class="text-body-light">0/150</span>
                                        </div>
                                    </div>
                                    @if($language)
                                        @foreach(json_decode($language) as $lang)

                                                <div class="form-group d-none lang_form" id="{{$lang}}-form2">
                                                    <label class="form-label tooltip-label" for="answer">{{ translate('answer') }} ({{strtoupper($lang)}})
                                                        <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter answer" data-title="
                                                            <div class='text-start'>{{ translate('Write_the_title_within_150_characters') }}</div>
                                                            ">
                                                            <i class="tio-info"></i>
                                                        </span>
                                                    </label>
                                                    <textarea class="form-control" name="answer[]" rows="6" data-maxlength="150" placeholder="{{ translate('Step text...') }}"></textarea>
                                                    <div class="d-flex justify-content-end">
                                                        <span class="text-body-light">0/150</span>
                                                    </div>
                                                </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="btn--container justify-content-sm-end mt-lg-4 mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('messages.Reset') }}</button>
                                <button type="submit" id="form-submit" class="btn btn--primary">{{ translate('messages.Add') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card mt-4">
                    <div class="card-header pb-1 pt-3 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">{{ translate('faq_list') }}</h5>
                            <form>
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch" value="{{ request()?->search ?? null }}" type="search" name="search" class="form-control" placeholder="{{ translate('Search_here') }}" aria-label="{{ translate('Search_here') }}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive pt-0">
                        <table class="table table-borderless table-thead-bordered table-align-middle table-nowrap card-table m-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-top-0">{{translate('sl')}}</th>
                                    <th class="border-top-0">{{translate('question')}}</th>
                                    <th class="border-top-0">{{translate('messages.answer')}}</th>
                                    <th class="border-top-0">{{translate('messages.priority')}}</th>
                                    <th class="border-top-0">{{translate('Status')}}</th>
                                    <th class="text-center border-top-0">{{translate('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($faqs as $key=>$faq)
                                <tr>
                                    <td>{{ $key+$faqs->firstItem() }}</td>
                                    <td>
                                        <p class="question-pragraph word-break">
                                            {{ $faq->question }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="opportunities-pragraph">
                                            {{ \Illuminate\Support\Str::limit($faq->answer, 50, $end='...')    }}
                                        </p>
                                    </td>
                                    <td>
                                        {{ $faq->priority }}
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm">
                                            <input type="checkbox"
                                                   data-id="faq_status_{{$faq->id}}"
                                                   data-type="status"
                                                   data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/testimonial-on.png') }}"
                                                   data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/testimonial-off.png') }}"
                                                   data-title-on="{{translate('Want_to_enable_the_status_of_this ')}} <strong>{{translate('faq')}}</strong>"
                                                   data-title-off="{{translate('Want_to_disable_the_status_of_this')}} <strong>{{translate('faq')}}</strong>"
                                                   data-text-on="<p>{{translate('If_enabled,_everyone_can_see_it_on_the_landing_page')}}</p>"
                                                   data-text-off="<p>{{translate('If_disabled,_it_will_be_hidden_from_the_landing_page')}}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox"
                                                   id="faq_status_{{$faq->id}}" {{$faq->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.business-settings.react-registration-page.faq_status',[$faq->id,$faq->status?0:1])}}" method="get" id="faq_status_{{$faq->id}}_form">
                                        </form>
                                    </td>

                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <button class="btn btn-sm btn--primary btn-outline-primary action-btn" data-placement="top" data-toggle="modal" data-target="#quick_faq-modal{{ $faq->id }}" class=" edit-shift" data-original-title="{{ translate('Quick_View') }}"> <i class="tio-invisible font-weight-bold"></i> </button>
                                            <a class="btn action-btn btn--primary btn-outline-primary edit-faq" data-toggle="modal" data-target="#edit_oportunities-modal_{{$faq->id}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            {{-- edit modal --}}
                                            <div class="modal fade" id="edit_oportunities-modal_{{$faq->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content p-4">
                                                        <div class="modal-header p-0">
                                                            <h3 class="modal-title" id="exampleModalLabel">{{ translate('Edit_Faq') }}  </h3>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        @php($faq=  ReactFaq::withoutGlobalScope('translate')->with('translations')->find($faq->id))
                                                        <form action="{{ route('admin.business-settings.react-registration-page.faq_update',[$faq->id]) }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="">
                                                                <ul class="nav nav-tabs mb-4">
                                                                    <li class="nav-item">
                                                                        <a class="nav-link update-lang_link add_active active"
                                                                        href="#"

                                                                        id="default-link">{{ translate('Default') }}</a>
                                                                    </li>
                                                                    @if($language)
                                                                        @foreach (json_decode($language) as $lang)
                                                                            <li class="nav-item">
                                                                                <a class="nav-link update-lang_link"
                                                                                href="#"
                                                                                data-faq-id="{{$faq->id}}"
                                                                                id="{{ $lang }}-link">{{ Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                </ul>
                                                                <div class="add_active_2  update-lang_form" id="default-form_{{$faq->id}}">
                                                                    <div class="form-group mb-0">
                                                                        <label class="form-label tooltip-label" for="question{{$faq->id}}">{{ translate('Question') }}
                                                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter question" data-title="
                                                                                <div class='text-start'>{{ translate('Write_the_title_within_50_characters') }}</div>
                                                                                ">
                                                                                <i class="tio-info"></i>
                                                                            </span>
                                                                        </label>
                                                                        <textarea class="form-control" name="question[]" rows="1" placeholder="{{translate('Enter_Question')}}" data-maxlength="50">{{$faq?->getRawOriginal('question')}}</textarea>
                                                                        <div class="d-flex justify-content-end">
                                                                            <span class="text-body-light">0/50</span>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="lang[]" value="default">
                                                                </div>
                                                                @if($language)
                                                                    @forelse(json_decode($language) as $lang)
                                                                            <?php
                                                                            if ($faq?->translations) {
                                                                                $question_translate = [];
                                                                                foreach ($faq?->translations as $t) {
                                                                                    if ($t->locale == $lang && $t->key == "faq_question") {
                                                                                        $question_translate[$lang]['faq_question'] = $t->value;
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        <div class="d-none update-lang_form"
                                                                            id="{{$lang}}-langform_{{$faq->id}}">
                                                                            <div class="form-group mb-0">
                                                                                <label class="form-label tooltip-label" for="question{{$faq->id}}{{$lang}}">{{translate('Title')}} ({{strtoupper($lang)}})
                                                                                    <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter question" data-title="
                                                                                    <div class='text-start'>{{ translate('Write_the_title_within_50_characters') }}</div>
                                                                                    ">
                                                                                    <i class="tio-info"></i>
                                                                                </span>
                                                                                </label>
                                                                                <textarea id="question{{$faq->id}}{{$lang}}"  class="form-control" name="question[]" rows="1" placeholder="{{translate('Enter_Question')}}" data-maxlength="50">{{ $question_translate[$lang]['faq_question'] ?? null }}</textarea>
                                                                                <div class="d-flex justify-content-end">
                                                                                    <span class="text-body-light">0/50</span>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="lang[]" value="{{$lang}}">
                                                                        </div>
                                                                    @empty
                                                                    @endforelse
                                                                @endif
                                                                <div class="d-flex flex-column mb-3">
                                                                    <label class="form-label tooltip-label" for="priority{{$faq->id}}">{{ translate('messages.Priority') }}
                                                                        <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter priority" data-title="
                                                                            <div class='text-start'>Priority...</div>
                                                                            ">
                                                                            <i class="tio-info"></i>
                                                                        </span>
                                                                    </label>

                                                                    <label for="priority{{$faq->id}}"></label>
                                                                    <select name="priority" class="form-control js-select2-custom h--45x select2-hidden-accessible" id="priority{{$faq->id}}">
                                                                        @foreach ($priorities as $priority)
                                                                            <option value="{{ $priority }}" {{ $priority === $faq->priority ? 'selected' : '' }}>
                                                                                {{ $priority }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="add_active_2  update-lang_form" id="default-form1_{{$faq->id}}">
                                                                    <div class="form-group">
                                                                        <label class="form-label tooltip-label" for="answer{{$faq->id}}">{{ translate('answer') }}
                                                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter answer" data-title="
                                                                                <div class='text-start'>{{ translate('Write_the_title_within_150_characters') }}</div>
                                                                                ">
                                                                                <i class="tio-info"></i>
                                                                            </span>
                                                                        </label>
                                                                        <textarea class="form-control" name="answer[]" rows="6" placeholder="{{translate('Enter_answer')}}" data-maxlength="150">{{$faq?->getRawOriginal('answer')}}</textarea>
                                                                        <div class="d-flex justify-content-end">
                                                                            <span class="text-body-light">0/150</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if($language)
                                                                    @forelse(json_decode($language) as $lang)
                                                                            <?php
                                                                            if ($faq?->translations) {
                                                                                $answer_translate = [];
                                                                                foreach ($faq?->translations as $t) {
                                                                                    if ($t->locale == $lang && $t->key == "faq_answer") {
                                                                                        $answer_translate[$lang]['faq_answer'] = $t->value;
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        <div class="d-none update-lang_form"
                                                                            id="{{$lang}}-langform1_{{$faq->id}}">
                                                                            <div class="form-group">
                                                                                <label class="form-label tooltip-label" for="answer{{$faq->id}}{{$lang}}">{{translate('answer')}} ({{strtoupper($lang)}})
                                                                                    <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter answer" data-title="
                                                                                    <div class='text-start'>{{ translate('Write_the_title_within_150_characters') }}</div>
                                                                                    ">
                                                                                    <i class="tio-info"></i>
                                                                                </span>
                                                                                </label>
                                                                                <textarea id="answer{{$faq->id}}{{$lang}}"  class="form-control" name="answer[]" rows="6" placeholder="{{translate('Enter_answer')}}" data-maxlength="150">{{ $answer_translate[$lang]['faq_answer'] ?? null }}</textarea>
                                                                                <div class="d-flex justify-content-end">
                                                                                    <span class="text-body-light">0/150</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @empty
                                                                    @endforelse
                                                                @endif



                                                                <div class="btn--container justify-content-sm-end mt-lg-4 mt-3">
                                                                    <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                                                                    <button type="submit" class="btn btn--primary">{{ translate('Update') }}</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert-faq" href="javascript:"
                                               data-id="faq-{{$faq['id']}}"
                                               data-message="{{ translate('Want_to_Delete_this_faq') }}"
                                               data-message-2="{{ translate('If_yes,_the_faq_will_be_removed_from_this_list') }}" title="{{translate('messages.delete_faq')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.business-settings.react-registration-page.faq_delete',[$faq['id']])}}" method="post" id="faq-{{$faq['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                    {{-- quick view modal --}}
                                    <div class="modal fade" id="quick_faq-modal{{ $faq->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content p-sm-4 p-3">
                                                <div class="modal-header p-0 pb-4">
                                                    <h3 class="modal-title" id="exampleModalLabel">{{ translate('Quick_View') }}  </h3>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="quick-view-box p-sm-4 p-2">
                                                    <div class="mb-lg-4 mb-3">
                                                        <h5>{{ translate('messages.Question') }}</h5>
                                                        <p class="mb-0">
                                                            {{ $faq->question }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <h5>{{ translate('messages.answer') }}</h5>
                                                        <p class="mb-0">
                                                            {{ $faq->answer }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                        @if(count($faqs) === 0)
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
                                {!! $faqs->appends(request()->all())->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

@endsection

@push('script_2')
<script src="{{dynamicAsset('public/assets/admin/js/view-pages/react-registration-faq-page.js')}}"></script>
            <script>
                "use strict";
                $(document).on('click', '.form-alert-faq', function () {
                    Swal.fire({
                        title: $(this).data('message'),
                        text: $(this).data('message-2'),
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: 'default',
                        confirmButtonColor: '#FC6A57',
                        cancelButtonText: '{{ translate('messages.No') }}',
                        confirmButtonText: '{{ translate('messages.Yes') }}',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            $('#' + $(this).data('id')).submit()
                        }
                    })
                });

                $('#faq-form').on('submit', function () {
                    $('#form-submit').prop('disabled', true).text("{{ translate('Submitting') }}"+'...');
                });
            </script>
@endpush
