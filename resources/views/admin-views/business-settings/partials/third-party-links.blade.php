<div class="d-flex flex-wrap justify-content-between align-items-center mb-5 mt-4 __gap-12px">
    <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
        <!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a class="nav-link   {{ Request::is('admin/business-settings/payment-method') ? 'active' : '' }}" href="{{ route('admin.business-settings.payment-method') }}"   aria-disabled="true">{{translate('Payment_Methods')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/sms-module') ? 'active' : '' }}" href="{{ route('admin.business-settings.sms-module') }}"  aria-disabled="true">{{translate('SMS_Module')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/mail-config*') ? 'active' : '' }}" id="modal_active" href="{{ route('admin.business-settings.mail-config') }}"  aria-disabled="true">{{translate('Mail_Config')}}</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/notification-setup*') ? 'active' : '' }}" id="modal_active" href="{{ route('admin.business-settings.notification_setup') }}"  aria-disabled="true">{{translate('Notification_Setup')}}</a>
            </li> --}}
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/config-setup') ?'active':'' }}" href="{{ route('admin.business-settings.config-setup') }}"  aria-disabled="true">{{translate('Map_APIs')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('admin/social-login/view')?'active':''}}" href="{{route('admin.social-login.view')}}"  aria-disabled="true">{{translate('Social_Logins')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/firebase-otp*') ? 'active' : '' }}" href="{{route('admin.business-settings.firebase_otp_index')}}"  aria-disabled="true">{{translate('Firebase OTP')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/recaptcha*') ? 'active' : '' }}" href="{{route('admin.business-settings.recaptcha_index')}}"  aria-disabled="true">{{translate('Recaptcha')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/storage-connection*') ? 'active' : '' }}" href="{{route('admin.business-settings.storage_connection_index')}}"  aria-disabled="true">{{translate('Storage_Connection')}}</a>
            </li>
        </ul>
        <!-- End Nav -->
    </div>
</div>
