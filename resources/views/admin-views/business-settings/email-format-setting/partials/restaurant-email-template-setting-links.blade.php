<div class="d-flex flex-wrap justify-content-between align-items-center mb-5 mt-4 __gap-12px">
    <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
        <!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/registration') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','registration']) }}">
                    {{translate('New_Restaurant_Registration')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/approve') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','approve']) }}">
                    {{translate('New_Restaurant_Approval')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/deny') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','deny']) }}">
                    {{translate('New_Restaurant_Rejection')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/suspend') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','suspend']) }}">
                    {{translate('Account_Suspend')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/unsuspend') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','unsuspend']) }}">
                    {{translate('Account_Unsuspend')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/withdraw-approve') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','withdraw-approve']) }}">
                    {{translate('Withdraw_Approval')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/withdraw-deny') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','withdraw-deny']) }}">
                    {{translate('Withdraw_Rejection')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/campaign-request') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','campaign-request']) }}">
                    {{translate('Campaign_Join_Request')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/campaign-approve') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','campaign-approve']) }}">
                    {{translate('Campaign_Join_Approval')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/campaign-deny') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','campaign-deny']) }}">
                    {{translate('Campaign_Join_Rejection')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/advertisement-create') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','advertisement-create']) }}">
                    {{translate('Advertisement_Create_By_Admin')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/advertisement-approved') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','advertisement-approved']) }}">
                    {{translate('Advertisement_Approval')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/advertisement-deny') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','advertisement-deny']) }}">
                    {{translate('Advertisement_Deny')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/advertisement-resume') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','advertisement-resume']) }}">
                    {{translate('Advertisement_Resume')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/advertisement-pause') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','advertisement-pause']) }}">
                    {{translate('Advertisement_Pause')}}
                </a>
            </li>
            @if (\App\CentralLogics\Helpers::subscription_check())
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/subscription-successful') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','subscription-successful']) }}">
                    {{translate('Subscription_Successful')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/subscription-renew') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','subscription-renew']) }}">
                    {{translate('Subscription_Renew')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/subscription-shift') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','subscription-shift']) }}">
                    {{translate('Subscription_Shift')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/subscription-cancel') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','subscription-cancel']) }}">
                    {{translate('Subscription_Cancel')}}
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/subscription-deadline') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','subscription-deadline']) }}">
                    {{translate('Subscription_Deadline_Warning')}}
                </a>
            </li> --}}
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/subscription-plan_upadte') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','subscription-plan_upadte']) }}">
                    {{translate('Subscription_Plan_Upadte')}}
                </a>
            </li>
            @endif
        </ul>
        <!-- End Nav -->
    </div>
</div>
