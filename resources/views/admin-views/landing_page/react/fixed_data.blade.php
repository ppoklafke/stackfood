@extends('layouts.admin.app')

@section('title', translate('messages.landing_page_settings'))

@section('content')

<div class="content container-fluid">
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-start">
            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{ dynamicAsset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                </div>
                <span>
                    {{ translate('React_Landing_Page') }}
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
            @include('admin-views.landing_page.top_menu.react_landing_menu')
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
                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
            <div class="d-flex justify-content-between __gap-12px mb-3">
                <h5 class="card-title d-flex align-items-center">
                    <span class="card-header-icon mr-2">
                        <img src="{{dynamicAsset('public/assets/admin/img/fixed_data1.png')}}" alt="" class="mw-100">
                    </span>
                    {{translate('messages.Newsletter_Section')}}
                </h5>
            </div>
            <div class="card">
                <form action="{{ route('admin.react_landing_page.settings', 'fixed-data-newsletter') }}" method="post">
                    @csrf
                <div class="card-body">
                    <div class="row g-3 lang_form default-form" id="default-form">
                        <input type="hidden" name="lang[]" value="default">
                        <div class="col-sm-6">
                            <label for="title" class="form-label">{{translate('Title')}} ({{ translate('messages.default') }})
                                                          <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                            </label>
                            <input  id="title" maxlength="30" type="text"  name="title[]" value="{{ $news_letter_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('Enter_Title')}}">
                            <input type="hidden" name="key" value="news_letter_title" >
                        </div>
                        <div class="col-sm-6">
                            <label for="sub_title" class="form-label">{{translate('Subtitle')}} ({{ translate('messages.default') }})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                            </label>
                            <input id="sub_title"  maxlength="70" type="text"  name="sub_title[]" value="{{ $news_letter_sub_title?->getRawOriginal('value') ?? null}}"  class="form-control" placeholder="{{translate('Enter_Sub_Title')}}">
                            <input type="hidden" name="key_2" value="news_letter_sub_title" >
                        </div>
                    </div>

                    @forelse(json_decode($language) as $lang)
                    <?php
                    if($news_letter_title?->translations){
                            $news_letter_title_translate = [];
                            foreach($news_letter_title->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='news_letter_title'){
                                    $news_letter_title_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                    if($news_letter_sub_title?->translations){
                            $news_letter_sub_title_translate = [];
                            foreach($news_letter_sub_title->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='news_letter_sub_title'){
                                    $news_letter_sub_title_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                        ?>

                    <div class="row g-3 d-none lang_form" id="{{$lang}}-form">
                        <input type="hidden" name="lang[]" value="{{$lang}}">
                        <div class="col-sm-6">
                            <label for="title{{$lang}}" class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})

                                    <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </label>
                            <input  id="title{{$lang}}" type="text" maxlength="30" name="title[]" value="{{ $news_letter_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter_Title')}}">
                        </div>
                        <div class="col-sm-6">
                            <label for="sub_title{{$lang}}" class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})

                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </label>
                            <input id="sub_title{{$lang}}"  type="text" maxlength="70" name="sub_title[]" value="{{ $news_letter_sub_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter_Title')}}">
                        </div>
                    </div>
                    @empty
                    @endforelse

                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit"   class="btn btn--primary">{{translate('Save')}}</button>
                    </div>
                </div>
                </form>
            </div>
            <br>
            <br>
            <div class="d-flex justify-content-between __gap-12px mb-3">
                <h5 class="card-title d-flex align-items-center">
                    <span class="card-header-icon mr-2">
                        <img src="{{dynamicAsset('public/assets/admin/img/fixed_data2.png')}}" alt="" class="mw-100">
                    </span>
                    {{translate('messages.Footer_Section')}}
                </h5>

            </div>



            <div class="card">
                <form action="{{ route('admin.react_landing_page.settings', 'fixed-data-footer') }}" method="post">
                    @csrf
                <div class="card-body">
                    <div class="row g-3 lang_form default-form" >
                        <input type="hidden"  name="lang[]" value="default">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="footer_data" class="form-label">{{translate('messages.Footer_Description')}} ({{ translate('messages.default') }})
                                    <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_footer_description_within_300_characters') }}">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </label>
                                <input type="hidden" name="footer_key" value="footer_data" >
                                <textarea id="footer_data" rows="5" maxlength="150"  class="form-control" name="footer_data[]" placeholder="{{translate('messages.Short_Description')}}">{{ $footer_data?->getRawOriginal('value') ?? null}}</textarea>
                            </div>
                        </div>
                    </div>

                    @forelse(json_decode($language) as $lang)
                        <input type="hidden" name="lang[]" value="{{$lang}}">
                        <?php
                            if($footer_data?->translations){
                                    $footer_data_translate = [];
                                    foreach($footer_data->translations as $t)
                                    {
                                        if($t->locale == $lang && $t->key=='footer_data'){
                                            $footer_data_translate[$lang]['value'] = $t->value;
                                        }
                                    }
                                }
                            ?>
                        <div class="row g-3  d-none lang_form" id="{{$lang}}-form1">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="footer_data{{$lang}}" class="form-label">{{translate('messages.Footer_description')}} ({{strtoupper($lang)}})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_footer_description_within_300_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <textarea id="footer_data{{$lang}}" rows="5" class="form-control"  maxlength="150" name="footer_data[]" placeholder="{{translate('messages.Short_Description')}}">{{ $footer_data_translate[$lang]['value'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @empty
                    @endforelse
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit"   class="btn btn--primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>
            </div>
@endsection

