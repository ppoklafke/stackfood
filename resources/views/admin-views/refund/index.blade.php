@php use App\CentralLogics\Helpers;use App\Models\BusinessSetting;use App\Models\RefundReason; @endphp
@extends('layouts.admin.app')

@section('title', translate('Refund_Settings'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title mr-3">
                    <span class="page-header-icon">
                        <img src="{{ dynamicAsset('public/assets/admin/img/business.png') }}" class="w--20" alt="">
                    </span>
                    <span>
                        {{ translate('messages.business_setup') }}
                    </span>
                </h1>
                <div class="d-flex flex-wrap justify-content-end align-items-center flex-grow-1">
                    <div class="blinkings active">
                        <i class="tio-info-outined"></i>
                        <div class="business-notes">
                            <h6><img src="{{dynamicAsset('/public/assets/admin/img/notes.png')}}" alt=""> {{translate('Note')}}
                            </h6>
                            <div>
                                {{translate('Click_on_the_Add_Now_button_to_add_a_refund_reason_to_the_list')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin-views.business-settings.partials.nav-menu')
        </div>

        <div class="row gx-2 gx-lg-3">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <form action="{{ route('admin.refund.refund_mode') }}" id="refund_mode_form" method="get"></form>
                    <div class="card-body mb-3">
                        <div
                            class="maintainance-mode-toggle-bar d-flex flex-wrap justify-content-between border blue-border rounded align-items-center">
                            @php($config = $refund_active_status?->value)
                            <h5 class="card-title text-capitalize mr-3 m-0 text--primary">
                                <span class="card-header-icon">
                                    <i class="tio-settings-outlined"></i>
                                </span>
                                <span>
                                    {{ translate('messages.Refund_Request_Mode') }}
                                </span>
                            </h5>
                            <label class="switch m-0">
                                <input type="checkbox" class="status dynamic-checkbox" id="refund_mode"
                                       data-id="refund_mode"
                                       data-type="status"
                                       data-image-on='{{dynamicAsset('/public/assets/admin/img/modal')}}/mail-success.png'
                                       data-image-off="{{dynamicAsset('/public/assets/admin/img/modal')}}/mail-warning.png"
                                       data-title-on="{{translate('Important!')}}"
                                       data-title-off="{{translate('Warning!')}}"
                                       data-text-on="<p>{{translate('By_turning_on_refund_request_mode,_customer_can_place_refund_requests.')}}</p>"
                                       data-text-off="<p>{{translate('By_turning_off_refund_request_mode,_customer_can_not_place_refund_requests')}}</p>"
                                    {{ isset($config) && $config ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <p class="mt-2 mb-0">
                            {{ translate('*_Customers_cannot_request_a_Refund_if_the_Admin_does_not_specify_a_cause_for_Refund._So_Admin_MUST_provide_a_proper_Refund_Reason.') }}
                        </p>
                    </div>
                </div>
            </div>

        </div>


        <div class="col-lg-12 pt-sm-3">
            <div class="report-card-inner mb-4 pt-3 mw-100">
                <form action="{{route('admin.refund.refund_reason')}}" method="post">
                    @csrf
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-md-0 mb-3">
                        <div class="mx-1">
                            <h5 class="form-label mb-0">
                                {{translate('messages.Add_a_Refund_Reason')}}
                            </h5>
                        </div>
                    </div>
                    @php($language=BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @if($language)
                        <ul class="nav  nav--tabs ml-3 mt-3 mb-3  w-100 ">
                            <li class="nav-item">
                                <a class="nav-link lang_link1 active"
                                   href="#"
                                   id="default-link1">{{ translate('Default') }}</a>
                            </li>
                            @foreach (json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link1"
                                       href="#"
                                       id="{{ $lang }}-link1">{{ Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="row align-items-end">


                        <div class="col-md-10 lang_form1 default-form1">
                            <label for="reason" class="form-label">{{translate('Reason')}} ({{ translate('Default') }}
                                )</label>
                            <input id="reason" type="text" class="form-control h--45px" name="reason[]"
                                   maxlength="191" placeholder="{{ translate('Ex:_Item_is_Broken') }}">
                            <input type="hidden" name="lang[]" value="default">
                        </div>

                        @if ($language)
                            @foreach(json_decode($language) as $lang)
                                <div class="col-md-10 d-none lang_form1" id="{{$lang}}-form1">
                                    <label for="reason{{$lang}}" class="form-label">{{translate('Reason')}}
                                        ({{strtoupper($lang)}})</label>
                                    <input id="reason{{$lang}}" type="text" class="form-control h--45px" name="reason[]"
                                           maxlength="191" placeholder="{{ translate('Ex:_Item_is_Broken') }}">
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                </div>
                            @endforeach
                        @endif


                        <div class="col-md-auto">
                            <button type="submit"
                                    class="btn btn--primary h--45px btn-block">{{translate('messages.Add Now')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body mb-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-md-0 mb-3">
                    <div class="mx-1">
                        <h5 class="form-label mb-5">
                            {{translate('Refund_Reason_List_Section')}}
                        </h5>
                    </div>
                </div>


                <!-- Table -->
                <div class="card-body p-0">
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-align-middle"
                               data-hs-datatables-options='{
                        "isResponsive": false,
                        "isShowPaging": false,
                        "paging":false,
                    }'>
                            <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{ translate('messages.sl') }}</th>
                                <th class="border-0">{{translate('messages.Reason')}}</th>
                                <th class="border-0">{{translate('messages.status')}}</th>
                                <th class="border-0 text-center">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="table-div">
                            @foreach($reasons as $key=>$reason)
                                <tr>
                                    <td>{{$key+$reasons->firstItem()}}</td>

                                    <td>
                                <span class="d-block font-size-sm text-body">
                                    {{Str::limit($reason->reason, 25,'...')}}
                                </span>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm"
                                               for="stocksCheckbox{{$reason->id}}">
                                            <input type="checkbox"
                                                   data-url="{{route('admin.refund.reason_status',[$reason['id'],$reason->status?0:1])}}"
                                                   class="toggle-switch-input redirect-url"
                                                   id="stocksCheckbox{{$reason->id}}" {{$reason->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                        </label>
                                    </td>

                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn edit-reason"
                                               title="{{ translate('messages.edit') }}"
                                               data-toggle="modal" data-target="#add_update_reason_{{$reason->id}}"
                                            ><i class="tio-edit"></i>
                                            </a>

                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert"
                                               href="javascript:"
                                               data-id="refund_reason-{{$reason['id']}}"
                                               data-message="{{ translate('Want to delete this refund reason ?') }}"

                                               title="{{translate('messages.delete')}}">
                                                <i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.refund.reason_delete',[$reason['id']])}}"
                                                  method="post" id="refund_reason-{{$reason['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="add_update_reason_{{$reason->id}}" tabindex="-1"
                                     role="dialog" aria-labelledby="exampleModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="exampleModalLabel">{{ translate('messages.Refund_Reason_Update') }}</label></h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.refund.reason_edit') }}" method="post">
                                                <div class="modal-body">
                                                    @csrf
                                                    @method('put')

                                                    @php($reason=  RefundReason::withoutGlobalScope('translate')->with('translations')->find($reason->id))
                                                    @php($language=BusinessSetting::where('key','language')->first())
                                                    @php($language = $language->value ?? null)
                                                    <div class="js-nav-scroller hs-nav-scroller-horizontal">
                                                        <ul class="nav nav-tabs nav--tabs mb-3 border-0">
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
                                                                        data-reason-id="{{$reason->id}}"
                                                                        id="{{ $lang }}-link">{{ Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <input type="hidden" name="reason_id" value="{{$reason->id}}"/>

                                                    <div class="form-group mb-3 add_active_2  update-lang_form"
                                                         id="default-form_{{$reason->id}}">
                                                        <label for="reason" class="form-label">{{translate('Reason')}}
                                                            ({{translate('messages.default')}}) </label>
                                                        <input id="reason" class="form-control" name='reason[]'
                                                               value="{{$reason?->getRawOriginal('reason')}}"
                                                               type="text">
                                                        <input type="hidden" name="lang1[]" value="default">
                                                    </div>
                                                    @if($language)
                                                        @forelse(json_decode($language) as $lang)
                                                                <?php
                                                                if ($reason?->translations) {
                                                                    $translate = [];
                                                                    foreach ($reason?->translations as $t) {
                                                                        if ($t->locale == $lang && $t->key == "reason") {
                                                                            $translate[$lang]['reason'] = $t->value;
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            <div class="form-group mb-3 d-none update-lang_form"
                                                                 id="{{$lang}}-langform_{{$reason->id}}">
                                                                <label for="reason{{$lang}}"
                                                                       class="form-label">{{translate('Reason')}}
                                                                    ({{strtoupper($lang)}})</label>
                                                                <input id="reason{{$lang}}" class="form-control"
                                                                       name='reason[]'
                                                                       value="{{ $translate[$lang]['reason'] ?? null }}"
                                                                       type="text">
                                                                <input type="hidden" name="lang1[]" value="{{$lang}}">
                                                            </div>
                                                        @empty
                                                        @endforelse
                                                    @endif

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ translate('Close') }}</button>
                                                    <button type="submit"
                                                            class="btn btn-primary">{{ translate('Save_changes') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($reasons) === 0)
                            <div class="empty--data">
                                <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                                <h5>
                                    {{translate('no_data_found')}}
                                </h5>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer pt-0 border-0">
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $reasons->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('script_2')
    <script src="{{dynamicAsset('public/assets/admin/js/view-pages/business-settings-refund-page.js')}}"></script>
@endpush
