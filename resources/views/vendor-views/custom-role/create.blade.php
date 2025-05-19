@extends('layouts.vendor.app')
@section('title',translate('Create Role'))
@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

    <!-- Page Header -->
     <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h2 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{dynamicAsset('/public/assets/admin/img/resturant-panel/page-title/employee-role.png')}}" alt="public">
                    </div>
                    <span>
                        {{ translate('Employee Role') }}
                    </span>
                </h2>
            </div>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title my-1">
                        <span class="card-header-icon">
                            <i class="tio-document-text-outlined"></i>
                        </span>
                        <span>
                            {{translate('messages.role_form')}}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="px-xl-2">
                        <form action="{{route('vendor.custom-role.create')}}" method="post">
                            @csrf
                            @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = str_replace('_', '-', app()->getLocale()))
                            @if ($language)
                            <div class="js-nav-scroller hs-nav-scroller-horizontal">
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
                            </div>
                            @endif

                            <input type="hidden" name="lang[]" value="default">

                            <div class="form-group lang_form" id="default-form">
                                <label class="form-label input-label qcont" for="name">{{ translate('messages.role_name') }} ({{ translate('messages.default') }})</label>
                                <input type="text" name="name[]" class="form-control" placeholder="{{translate('role_name_example')}}" maxlength="191"   >
                            </div>

                            @if ($language)
                            @foreach(json_decode($language) as $lang)
                                <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.role_name')}} ({{strtoupper($lang)}})</label>
                                    <input type="text" name="name[]" class="form-control" placeholder="{{translate('role_name_example')}}" maxlength="191"  >
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                            @endforeach
                            @endif

                            <div class="d-flex">
                                <h5 class="input-label m-0 text-capitalize">{{translate('messages.module_permission')}} : </h5>
                                <div class="check-item pb-0 w-auto">
                                    <div class="form-group form-check form--check m-0 ml-2">
                                        <input type="checkbox" class="form-check-input system-checkbox"
                                                id="allSystem">
                                        <label class="form-check-label ml-0" for="allSystem">{{ translate('Select_All') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="check--item-wrapper mx-0">
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="food" class="form-check-input system-checkbox"
                                            id="food">
                                        <label class="form-check-label input-label qcont" for="food">{{translate('messages.food')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="order" class="form-check-input system-checkbox"
                                            id="order">
                                        <label class="form-check-label input-label qcont" for="order">{{translate('messages.order')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="restaurant_setup" class="form-check-input system-checkbox"
                                            id="restaurant_setup">
                                        <label class="form-check-label input-label qcont" for="restaurant_setup">{{translate('messages.restaurant_setup')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="addon" class="form-check-input system-checkbox"
                                            id="addon">
                                        <label class="form-check-label input-label qcont" for="addon">{{translate('messages.addon')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="wallet" class="form-check-input system-checkbox"
                                            id="wallet">
                                        <label class="form-check-label input-label qcont" for="wallet">{{translate('messages.wallet')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="employee" class="form-check-input system-checkbox"
                                            id="employee">
                                        <label class="form-check-label input-label qcont" for="employee">{{translate('messages.Employee')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="my_shop" class="form-check-input system-checkbox"
                                            id="my_shop">
                                        <label class="form-check-label input-label qcont" for="my_shop">{{translate('messages.my_shop')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="chat" class="form-check-input system-checkbox"
                                            id="chat">
                                        <label class="form-check-label input-label qcont" for="chat">{{ translate('messages.chat')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="campaign" class="form-check-input system-checkbox"
                                            id="campaign">
                                        <label class="form-check-label input-label qcont" for="campaign">{{translate('messages.campaign')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="reviews" class="form-check-input system-checkbox"
                                            id="reviews">
                                        <label class="form-check-label input-label qcont" for="reviews">{{translate('messages.reviews')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="pos" class="form-check-input system-checkbox"
                                            id="pos">
                                        <label class="form-check-label input-label qcont" for="pos">{{translate('messages.pos')}}</label>
                                    </div>
                                </div>
                                @php($restaurant_data = \App\CentralLogics\Helpers::get_restaurant_data())
                                @if ($restaurant_data->restaurant_model != 'commission')
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="subscription" class="form-check-input system-checkbox"
                                        id="subscription">
                                        <label class="form-check-label input-label qcont" for="subscription">{{translate('messages.subscription')}}</label>
                                    </div>
                                </div>
                                @endif

                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="coupon" class="form-check-input system-checkbox"
                                            id="coupon">
                                        <label class="form-check-label input-label qcont" for="coupon">{{translate('messages.coupon')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="report" class="form-check-input system-checkbox"
                                            id="report">
                                        <label class="form-check-label input-label qcont" for="report">{{translate('messages.report')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container mt-4 justify-content-end">
                                <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0 py-2">
                    <div class="search--button-wrapper">
                        <h5 class="card-title">{{translate('messages.roles_table')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$rl->total()}}</span></h5>
                        <form >

                            <!-- Search -->
                            <div class="input-group input--group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control" value="{{ request()?->search ?? null }}" placeholder="{{ translate('messages.Ex :') }}  {{ translate('Search by Role Name') }}" aria-label="{{translate('messages.search')}}">
                                <button type="submit" class="btn btn--secondary">
                                    <i class="tio-search"></i>
                                </button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="w-70px">{{ translate('messages.sl') }}</th>
                                    <th class="w-100px">{{translate('messages.role_name')}}</th>
                                    <th class="w-200px">{{translate('messages.modules')}}</th>
                                    <th class="w-80px">{{translate('messages.created_at')}}</th>
                                    <th scope="col" class="w-80px text-center">{{translate('messages.action')}}</th>
                                </tr>
                            </thead>
                            <tbody  id="set-rows">
                            @foreach($rl as $k=>$r)
                                <tr>
                                    <td scope="row">{{$k+$rl->firstItem()}}</td>
                                    <td>{{Str::limit($r['name'],20,'...')}}</td>
                                    <td class="text-capitalize">
                                        @if($r['modules']!=null)
                                            @foreach((array)json_decode($r['modules']) as $key=>$m)
                                               {{translate(str_replace('_',' ',$m))}},
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                    {{  Carbon\Carbon::parse($r['created_at'])->locale(app()->getLocale())->translatedFormat('d M Y') }}
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn--primary btn-outline-primary"
                                                href="{{route('vendor.custom-role.edit',[$r['id']])}}" title="{{translate('messages.edit_role')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                                data-id="role-{{$r['id']}}" data-message="{{translate('messages.Want_to_delete_this_role')}}" title="{{translate('messages.delete_role')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('vendor.custom-role.delete',[$r['id']])}}"
                                                    method="post" id="role-{{$r['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($rl) === 0)
                        <div class="empty--data">
                            <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                        <div class="page-area">
                            <table>
                                <tfoot>
                                {!! $rl->links() !!}
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <script>
        "use strict";
        $(document).ready(function() {
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));
        });

        $('#allSystem').change(function () {
                var isChecked = $(this).is(':checked');
                $('.system-checkbox').prop('checked', isChecked);
            });

            $('.system-checkbox').not('#allSystem').change(function () {
                if (!$(this).is(':checked')) {
                    $('#allSystem').prop('checked', false);
                } else {
                    if ($('.system-checkbox').not('#allSystem').length === $('.system-checkbox:checked').not('#allSystem').length) {
                        $('#allSystem').prop('checked', true);
                    }
                }
            });
    </script>
@endpush
