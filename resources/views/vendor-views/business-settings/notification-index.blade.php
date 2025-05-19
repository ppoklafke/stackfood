@extends('layouts.vendor.app')

@section('title', translate('messages.Notification_Setup'))
@section('content')
    <div class="content container-fluid">
{{--        <div class="page-header">--}}
{{--            <h1 class="page-header-title">--}}
{{--                <span class="page-header-icon">--}}
{{--                    <img src="{{dynamicAsset('public/assets/admin/img/api.png')}}" class="w--26" alt="image">--}}
{{--                </span>--}}
{{--                <span>--}}
{{--                    {{translate('messages.Notification_Setup')}}--}}
{{--                </span>--}}
{{--            </h1>--}}
{{--        </div>--}}

        <!-- Title -->
        <div class="mb-3 d-flex align-items-start gap-2">
            <img src="{{dynamicAsset('/public/assets/admin/img/bell-2.png')}}" alt="">
            <div class="w-0 flex-grow mb-2">
                 <h1 class="page-header-title m-0">{{ translate('Notification_Setup') }}</h1>
                {{ translate('From here you setup who can see what types of notification from') }} {{ $business_name }}
            </div>
        </div>

        <div class="card">

            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table class="font-size-sm table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ translate('sl') }}</th>
                            <th >{{translate('Topics')}}</th>
                            <th >{{translate('Push Notification')}}</th>
                            <th >{{translate('Mail')}}</th>
                            <th >{{translate('SMS')}}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($data as  $key => $item)
                            @php($item_admin_data = \App\CentralLogics\Helpers::getNotificationStatusData('restaurant',$item->key))
                            <tr>
                                <td>{{ $key +1 }}</td>
                                <td>
                                    <h5 class="text-capitalize">{{ translate($item->title) }}</h5>
                                    <div class="white-space-initial max-w-400px">
                                        {{ translate($item->sub_title) }}
                                    </div>
                                </td>
                                <td>
                                    @if ($item_admin_data->push_notification_status == 'disable')
                                        <span class="badge badge-pill badge--info">  {{ translate('messages.N/A') }}</span>
                                    @elseif($item_admin_data->push_notification_status == 'inactive')
                                        <label class="toggle-switch toggle-switch-sm" data-toggle="tooltip" title="{{ translate('This_notification_turned_off_by_admin.')  }}">
                                            <input type="checkbox"
                                                    class="status toggle-switch-input dynamic-checkbox"  disabled>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    @else

                                        <label class="toggle-switch toggle-switch-sm" data-toggle="tooltip" title="{{ translate('toggle_push_notification_for') .' '.translate($item->title)  }}">
                                            <input type="checkbox"
                                                   id="push_notification_{{$item->key}}"
                                                   data-id="push_notification_{{$item->key}}"
                                                   data-type="toggle" data-image-on="{{dynamicAsset('public/assets/admin/img/modal/mail-success.png')}}" data-image-off="{{dynamicAsset('public/assets/admin/img/modal/mail-warning.png')}}" data-title-on="{{ translate('Want to enable the Push Notification For') .' '.  translate($item->title) }} ?" data-title-off="{{ translate('Want to disable the Push Notification For') .' '.  translate($item->title) }} ?" data-text-on="<p>{{ translate('Push Notification Will Be Enabled For')  .' '.  translate($item->title) }}</p>" data-text-off="<p>{{ translate('Push Notification Will Be disabled For')  .' '.  translate($item->title) }}</p>" class="status toggle-switch-input dynamic-checkbox"  {{ $item->push_notification_status  == 'active' ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('vendor.business-settings.notification_status_change',['key'=> $item->key  ,'type' => 'push_notification'])}}" method="get" id="push_notification_{{$item->key}}_form">
                                        </form>
                                    @endif
                                </td>

                                <td>
                                    @if ($item_admin_data->mail_status == 'disable')
                                        <span class="badge badge-pill badge--info">  {{ translate('messages.N/A') }}</span>
                                    @elseif($item_admin_data->mail_status == 'inactive')
                                        <label class="toggle-switch toggle-switch-sm" data-toggle="tooltip" title="{{ translate('This_mail_turned_off_by_admin') }}">
                                            <input type="checkbox"
                                                   class="status toggle-switch-input dynamic-checkbox"  disabled>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    @else

                                        <label class="toggle-switch toggle-switch-sm" data-toggle="tooltip" title="{{ translate('toggle_Mail_for') .' '.translate($item->title)  }}">

                                            <input type="checkbox" data-type="toggle"
                                                   id="mail_{{ $item->key }}"
                                                   data-id="mail_{{ $item->key }}"
                                                   data-image-on="{{dynamicAsset('public/assets/admin/img/modal/mail-success.png')}}" data-image-off="{{dynamicAsset('public/assets/admin/img/modal/mail-warning.png')}}" data-title-on="{{ translate('Want to enable the Mail For') .' '.  translate($item->title) }} ?" data-title-off="{{ translate('Want to disable the Mail For') .' '.  translate($item->title) }} ?" data-text-on="<p>{{ translate('Mail Will Be Enabled For')  .' '.  translate($item->title) }}</p>" data-text-off="<p>{{ translate('Mail Will Be disabled For')  .' '.  translate($item->title) }}</p>" class="status toggle-switch-input dynamic-checkbox" {{ $item->mail_status  == 'active' ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                               <span class="toggle-switch-indicator"></span>
                                           </span>
                                        </label>
                                        <form action="{{route('vendor.business-settings.notification_status_change',['key'=> $item->key  ,'type' => 'Mail'])}}" method="get" id="mail_{{$item->key}}_form">
                                        </form>
                                    @endif
                                </td>

                                <td>
                                    @if ($item_admin_data->sms_status == 'disable')
                                        <span class="badge badge-pill badge--info">  {{ translate('messages.N/A') }}</span>
                                    @elseif($item_admin_data->sms_status == 'inactive')
                                        <label class="toggle-switch toggle-switch-sm" data-toggle="tooltip" title="{{ translate('This_sms_turned_off_by_admin')  }}">
                                            <input type="checkbox"
                                                   class="status toggle-switch-input dynamic-checkbox"  disabled>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    @else

                                        <label class="toggle-switch toggle-switch-sm" data-toggle="tooltip" title="{{ translate('toggle_SMS_for') .' '.translate($item->title)  }}">
                                            <input type="checkbox"
                                                   id="SMS_{{ $item->key }}"
                                                   data-id="SMS_{{ $item->key }}"
                                                   data-type="toggle" data-image-on="{{dynamicAsset('public/assets/admin/img/modal/mail-success.png')}}" data-image-off="{{dynamicAsset('public/assets/admin/img/modal/mail-warning.png')}}" data-title-on="{{ translate('Want to disable the SMS For') .' '.  translate($item->title) }} ?" data-title-off="{{ translate('Want to disable the SMS For') .' '.  translate($item->title) }} ?" data-text-on="<p>{{ translate('SMS Will Be Enabled For')  .' '.  translate($item->title) }}</p>" data-text-off="<p>{{ translate('SMS Will Be disabled For')  .' '.  translate($item->title) }}</p>" class="status toggle-switch-input dynamic-checkbox" {{ $item->sms_status  == 'active' ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                               <span class="toggle-switch-indicator"></span>
                                           </span>
                                        </label>
                                        <form action="{{route('vendor.business-settings.notification_status_change',['key'=> $item->key ,'type' => 'SMS'])}}" method="get" id="SMS_{{$item->key}}_form">
                                        </form>
                                    @endif
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

@endsection
