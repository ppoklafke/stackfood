<div id="headerMain" class="d-none">
    <header id="header"
        class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                <!-- Logo Div-->
                @php($restaurant_logo = \App\CentralLogics\Helpers::get_restaurant_data()?->logo_full_url)
                <a class="navbar-brand" href="{{ route('vendor.dashboard') }}" aria-label="">
                    <img class="navbar-brand-logo logo--design" src="{{ $restaurant_logo }}" alt="image">
                    <img class="navbar-brand-logo-mini logo--design" src="{{ $restaurant_logo }}" alt="image">
                </a>
                <!-- End Logo -->
            </div>
            <div class="navbar-nav-wrap-content-left ml-auto d--xl-none">
                <!-- Navbar Vertical Toggle -->
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                    <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                        data-placement="right" title="Collapse"></i>
                    <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                        data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'></i>
                </button>
                <!-- End Navbar Vertical Toggle -->
            </div>






            <!-- Secondary Content -->
            <div class="navbar-nav-wrap-content-right flex-grow-1">
                <!-- Navbar -->
                <ul class="navbar-nav align-items-center flex-row justify-content-end">

                    <li class="nav-item max-sm-m-0 w-md-200px">
                        <button type="button" id="modalOpener" class="title-color bg--secondary border-0 rounded justify-content-between w-100 align-items-center py-2 px-2 px-md-3 d-flex gap-1" data-toggle="modal" data-target="#staticBackdrop">
                            <div class="d-flex gap-1 align-items-center">
                                <i class="tio-search"></i>
                                <span class="d-none d-md-block text-muted">{{translate('Search')}}</span>
                            </div>
                            <span class="bg-card text-muted border rounded-3 p-1 fs-12 fw-bold lh-1 ms-1 ctrlplusk d-none d-md-block">Ctrl+K</span>
                        </button>
                    </li>

                    <li class="nav-item max-sm-m-0">
                        <div class="hs-unfold">
                            <div>
                                @php($local = session()->has('vendor_local') ? session('vendor_local') : null)
                                @php($lang = \App\CentralLogics\Helpers::getSettingsDataFromConfig(settings: 'system_language'))
                                @if ($lang)
                                    <div class="topbar-text dropdown disable-autohide text-capitalize d-flex">
                                        <a class="text-dark dropdown-toggle d-flex align-items-center nav-link"
                                            href="#" data-toggle="dropdown">
                                            @foreach (json_decode($lang['value'], true) as $data)
                                                @if ($data['code'] == $local)
                                                    <img class="rounded mr-1" width="20"
                                                        src="{{ dynamicAsset('/public/assets/admin/img/lang.png') }}"
                                                        alt="">
                                                    {{ $data['code'] }}
                                                @elseif(!$local && $data['default'] == true)
                                                    <img class="rounded mr-1" width="20"
                                                        src="{{ dynamicAsset('/public/assets/admin/img/lang.png') }}"
                                                        alt="">
                                                    {{ $data['code'] }}
                                                @endif
                                            @endforeach
                                        </a>
                                        <ul class="dropdown-menu">
                                            @foreach (json_decode($lang['value'], true) as $key => $data)
                                                @if ($data['status'] == 1)
                                                    <li>
                                                        <a class="dropdown-item py-1"
                                                            href="{{ route('vendor.lang', [$data['code']]) }}">

                                                            <span class="text-capitalize">{{ $data['code'] }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </li>

                    <li class="nav-item d-none d-sm-inline-block mr-4">
                        <!-- Notification -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-icon btn-soft-secondary rounded-circle"
                                href="{{ route('vendor.message.list', ['tab' => 'customer']) }}">
                                <i class="tio-messages-outlined"></i>
                                @php(  $message = \App\Models\Conversation::whereUser(\App\CentralLogics\Helpers::get_loggedin_user()->id)->where('unread_message_count', '>', '0')->count())
                                @if ($message != 0)
                                    <span class="btn-status btn-sm-status btn-status-danger"></span>
                                @endif
                            </a>
                        </div>
                        <!-- End Notification -->
                    </li>
                    <li class="nav-item">
                        <!-- Notification -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-icon navbar--cart btn-soft-secondary rounded-circle"
                                href="{{ route('vendor.order.list', ['status' => 'pending']) }}">
                                <i class="tio-shopping-basket-outlined"></i>
                            </a>
                        </div>
                        <!-- End Notification -->
                    </li>

                    <li class="nav-item nav--item">
                        <!-- Account -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper p-0" href="javascript:;"
                                data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>

                                <div class="cmn--media right-dropdown-icon d-flex align-items-center">
                                    <div class="media-body pl-0 pr-2">
                                        <span class="card-title h5 text-right pr-2">
                                            {{ \App\CentralLogics\Helpers::get_loggedin_user()->f_name }}
                                        </span>
                                        <span
                                            class="card-text card--text">{{ \App\CentralLogics\Helpers::get_loggedin_user()->email }}</span>
                                    </div>
                                    <div class="">
                                        <img class="avatar avatar-sm avatar-circle"
                                            src="{{ \App\CentralLogics\Helpers::get_loggedin_user()?->image_full_url ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
                                            alt="image">

                                        <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                    </div>
                                </div>

                            </a>

                            <div id="accountNavbarDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account w-16rem">
                                <div class="dropdown-item-text">
                                    <div class="media cmn--media align-items-center">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            <img class="avatar-img"
                                                src="{{ \App\CentralLogics\Helpers::get_loggedin_user()?->image_full_url ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
                                                alt="image">
                                        </div>
                                        <div class="media-body">
                                            <span
                                                class="card-title h5">{{ \App\CentralLogics\Helpers::get_loggedin_user()->f_name }}</span>
                                            <span
                                                class="card-text">{{ \App\CentralLogics\Helpers::get_loggedin_user()->email }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('vendor.profile.view') }}">
                                    <span class="text-truncate pr-2"
                                        title="Settings">{{ translate('messages.settings') }}</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="javascript:"
                                    onclick="Swal.fire({
                                    title: '{{ translate('Do_You_Want_To_Sign_Out_?') }}',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#FC6A57',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: '{{ translate('messages.Yes') }}',
                                    cancelButtonText: '{{ translate('messages.cancel') }}',
                                    }).then((result) => {
                                    if (result.value) {
                                        location.href='{{ route('logout') }}';
                                    } else{
                                    Swal.fire('{{ translate('messages.canceled') }}', '', 'info')
                                    }
                                    })">
                                    <span class="text-truncate pr-2"
                                        title="Sign out">{{ translate('messages.sign_out') }}</span>
                                </a>
                            </div>
                        </div>
                        <!-- End Account -->
                    </li>
                </ul>
                <!-- End Navbar -->
            </div>
            <!-- End Secondary Content -->
        </div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>

<?php
$wallet = \App\Models\RestaurantWallet::where('vendor_id', \App\CentralLogics\Helpers::get_vendor_id())->first();
$Payable_Balance = $wallet?->collected_cash > 0 ? 1 : 0;

$cash_in_hand_overflow = \App\Models\BusinessSetting::where('key', 'cash_in_hand_overflow_restaurant')->first()?->value;
$cash_in_hand_overflow_restaurant_amount = \App\Models\BusinessSetting::where('key', 'cash_in_hand_overflow_restaurant_amount')->first()?->value;
$val = round($cash_in_hand_overflow_restaurant_amount - ($cash_in_hand_overflow_restaurant_amount * 10) / 100, 8);
?>

@if ($Payable_Balance == 1 && $cash_in_hand_overflow && $wallet?->balance < 0 && $val <= abs($wallet?->collected_cash))
    <div class="alert __alert-2 alert-warning m-0 py-1 px-2" role="alert">
        <img class="rounded mr-1" width="25"
            src="{{ dynamicAsset('/public/assets/admin/img/header_warning.png') }}" alt="">
        <div class="cont">
            <h4 class="m-0">{{ translate('Attention_Please') }} </h4>
            {{ translate('The_Cash_in_Hand_amount_is_about_to_exceed_the_limit._Please_pay_the_due_amount._If_the_limit_exceeds,_your_account_will_be_suspended.') }}
        </div>
    </div>
@endif

@if (
    $Payable_Balance == 1 &&
        $cash_in_hand_overflow &&
        $wallet?->balance < 0 &&
        $cash_in_hand_overflow_restaurant_amount < $wallet?->collected_cash)
    <div class="alert __alert-2 alert-warning m-0 py-1 px-2" role="alert">
        <img class="mr-1" width="25" src="{{ dynamicAsset('/public/assets/admin/img/header_warning.png') }}"
            alt="">
        <div class="cont">
            <h4 class="m-0">{{ translate('Attention_Please') }} </h4>
            {{ translate('The_Cash_in_Hand_amount_limit_is_exceeded._Your_account_is_now_suspended._Please_pay_the_due_amount_to_receive_new_order_requests_again.') }}<a
                href="{{ route('vendor.wallet.index') }}" class="alert-link"> &nbsp;
                {{ translate('Pay_the_due') }}</a>
        </div>
    </div>
@endif

<?php
$restaurant_data = \App\CentralLogics\Helpers::get_restaurant_data();
$subscription_deadline_warning_days = \App\Models\BusinessSetting::where('key', 'subscription_deadline_warning_days')->first()?->value ?? 7;
$subscription_deadline_warning_message = \App\Models\BusinessSetting::where('key', 'subscription_deadline_warning_message')->first()?->value ?? null;
?>


<div id="hide-subscription-warnings">



    @if (
        !in_array($restaurant_data->restaurant_model, ['none', 'commission']) &&
            !Request::is('restaurant-panel/subscription/*'))

        <?php
        $pers = 10;
        if ($restaurant_data?->restaurant_sub) {
            $validity = $restaurant_data?->restaurant_sub?->validity;
            $remaining_days = Carbon\Carbon::now()->diffInDays($restaurant_data?->restaurant_sub?->expiry_date_parsed->format('Y-m-d'), false);
            $pers = $validity - $remaining_days > 0 ? (($validity - $remaining_days) / $validity) * 100 : 1;
            $pers = (439.6 * $pers) / 100;
        }
        ?>
@if (
    $restaurant_data?->restaurant_sub?->is_trial == 0 &&
        $restaurant_data?->restaurant_sub?->expiry_date_parsed &&
        $restaurant_data?->restaurant_sub->expiry_date_parsed->subDays($subscription_deadline_warning_days)->isBefore(now()) &&
        Request::is('restaurant-panel'))

    <!--Always in header Renew -->
    <div class="renew-badge mx-3 mt-3" id="renew-badge">
        <div class="renew-content d-flex align-items-center">

            <img src="{{ dynamicAsset('/public/assets/admin/img/timer.svg') }}" alt="">
            <div class="txt">
                {{ $subscription_deadline_warning_message != null ? $subscription_deadline_warning_message : translate('Your subscription ending soon. Please renew to continue access') }}
            </div>
        </div>
        <div>
            <a href="{{ route('vendor.subscriptionackage.subscriberDetail', ['renew_now' => true]) }}"
                class="btn btn--danger">{{ translate('Renew') }}</a>
        </div>
    </div>
@elseif (Session::get('subscription_renew_close_btn') !== true &&
        $restaurant_data?->restaurant_sub?->is_trial == 0 &&
        $restaurant_data?->restaurant_sub?->expiry_date_parsed &&
        $restaurant_data?->restaurant_sub->expiry_date_parsed->subDays($subscription_deadline_warning_days)->isBefore(now()) &&
        !Request::is('restaurant-panel'))
    <div class="renew-badge mx-3 mt-3 hide-warning" id="renew-badge">
        <div class="renew-content d-flex align-items-center">

            <img src="{{ dynamicAsset('/public/assets/admin/img/timer.svg') }}" alt="">
            <div class="txt">
                {{ $subscription_deadline_warning_message != null ? $subscription_deadline_warning_message : translate('Your subscription ending soon. Please renew to continue access') }}
            </div>
        </div>
        <div>
            @if ($restaurant_data?->restaurant_sub?->is_canceled == 1)
                <a href="{{ route('vendor.subscriptionackage.subscriberDetail', ['open_plans' => true]) }}"
                    class="btn btn--danger">{{ translate('Change_Subscription') }}</a>
            @else
                <a href="{{ route('vendor.subscriptionackage.subscriberDetail', ['renew_now' => true]) }}"
                    class="btn btn--danger">{{ translate('Renew') }}</a>
            @endif
            <button data-id="subscription_renew_close_btn" id="subs-hide-warning"
                class="btn btn-sm btn-primary add-to-session">{{ translate('remind_me_later') }}</button>
        </div>
    </div>
    <!-- Renew -->


@endif




        @if (Session::get('subscription_free_trial_close_btn') !== true &&
                $restaurant_data?->restaurant_sub?->status == 1 &&
                $restaurant_data?->restaurant_sub?->is_trial == 1 &&
                $restaurant_data?->restaurant_sub?->is_canceled == 0)
            <div class="free-trial trial success-bg">
                <div class="inner-div">
                    <div class="left">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/icon-puck.svg') }}" alt="">
                        <div class="left-content">
                            <h6>{{ translate('Get the best experience of your business') }}</h6>
                            <div>{{ translate('Run your business with the most popular platform') }}</div>
                        </div>
                    </div>
                    <div class="right">
                        <a href="#" class="btn btn-2">
                            <span class="circle-progress-container">
                                <svg width="40" viewBox="0 0 160 160">
                                    <circle r="70" cx="80" cy="80" fill="transparent"
                                        stroke="#ffffff20" stroke-width="12px"></circle>
                                    <circle r="70" cx="80" cy="80" fill="transparent" stroke="#ffffff"
                                        stroke-width="12px" stroke-dasharray="439.6px"
                                        stroke-dashoffset="{{ $pers }}px"></circle>
                                </svg>
                                {{1+ Carbon\Carbon::now()->diffInDays($restaurant_data?->restaurant_sub?->expiry_date_parsed->format('Y-m-d'), false) }}
                            </span>
                            {{ translate('Days_left_in_free_trial') }}
                        </a>
                        <a href="{{ route('vendor.subscriptionackage.subscriberDetail', ['open_plans' => true]) }}"
                            class="btn btn-light">{{ translate('Choose_Subscription_Plan') }} <i
                                class="tio-arrow-forward"></i></a>
                    </div>

                    <button type="button" data-id="subscription_free_trial_close_btn"
                        class="trial-close add-to-session ">
                        <i class="tio-clear-circle"></i>
                    </button>
                </div>
            </div>
        @elseif ($restaurant_data?->restaurant_sub == null && $restaurant_data?->restaurant_sub_update_application?->is_trial == 1)
            <div class="modal fade show trial-ended-modal" id="free-trial-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <div class="trial-ended-modal-wrapper">
                                {{-- <button type="button" class="trial-ended-close-btn text-md-white" data-dismiss="modal">
                                <i class="tio-clear-circle"></i>
                            </button> --}}
                                <div class="trial-ended-modal-content align-self-center">
                                    <h3 class="title">{{ translate('Your_Free_Trial_Has_Been_Ended') }}</h3>
                                    <p class="mb-4">
                                        {{ translate('Purchase a subscription plan or contact with the admin to settle the payment and unblock the access to service.') }}
                                    </p>
                                    <a href="{{ route('vendor.subscriptionackage.subscriberDetail', ['open_plans' => true]) }}"
                                        class="btn btn--primary">{{ translate('Choose Subscription Plan') }} <i
                                            class="tio-arrow-forward"></i></a>
                                    <div class="blocked-subscription mt-5">
                                        <img src="{{ dynamicAsset('/public/assets/admin/img/WarningOctagon.svg') }}"
                                            alt="">
                                        <span>{{ translate('All Access to service has been blocked due to no active subscription') }}</span>
                                    </div>
                                </div>
                                <div class="trial-ended-modal-img d-none d-md-block">
                                    <img src="{{ dynamicAsset('/public/assets/admin/img/trial-ended-bg.png') }}"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="free-trial trial danger-bg">
                <div class="inner-div">
                    <div class="left">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/timer-2.svg') }}" alt="">
                        <div class="left-content">
                            <h6>{{ translate('Free_Trial_Has_Been_Ended') }}</h6>
                            <div>{{ translate('Get_a_subscription_plan_to_continue_with_your_business') }}</div>
                        </div>
                    </div>
                    <div class="right">
                        <a href="{{ route('vendor.subscriptionackage.subscriberDetail', ['open_plans' => true]) }}"
                            class="btn btn-light">{{ translate('Choose_Subscription_Plan') }} <i
                                class="tio-arrow-forward"></i></a>
                    </div>
                    {{-- <button type="button" class="trial-close">
                    <i class="tio-clear-circle"></i>
                </button> --}}
                </div>
            </div>
        @elseif (Session::get('subscription_cancel_close_btn') !== true &&
                $restaurant_data?->restaurant_sub &&
                $restaurant_data?->restaurant_sub?->is_canceled == 1)
            <div class="free-trial trial danger-bg">
                <div class="inner-div">
                    <div class="left">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/timer-2.svg') }}" alt="">
                        <div class="left-content">
                            <h6>{{ translate('Your_Subscription_Has_Been_Cnaceled_by') }}
                                {{ $restaurant_data?->restaurant_sub?->canceled_by == 'admin' ? translate($restaurant_data?->restaurant_sub?->canceled_by) : translate('Yourself') }}
                            </h6>
                            <div>{{ translate('You_can_not_consume_your_subscription_after') }}
                                {{ \App\CentralLogics\Helpers::date_format($restaurant_data?->restaurant_sub?->expiry_date_parsed) }}
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <a href="#" class="btn btn-2">
                            <span class="circle-progress-container">
                                <svg width="40" viewBox="0 0 160 160">
                                    <circle r="70" cx="80" cy="80" fill="transparent"
                                        stroke="#ffffff20" stroke-width="12px"></circle>
                                    <circle r="70" cx="80" cy="80" fill="transparent" stroke="#ffffff"
                                        stroke-width="12px" stroke-dasharray="439.6px"
                                        stroke-dashoffset="{{ $pers }}px"></circle>
                                </svg>
                                {{1+ Carbon\Carbon::now()->diffInDays($restaurant_data?->restaurant_sub?->expiry_date_parsed->format('Y-m-d'), false) }}
                            </span>
                            {{ translate('Days_left_in_this_subscription') }}
                        </a>
                        <a href="{{ route('vendor.subscriptionackage.subscriberDetail', ['open_plans' => true]) }}"
                            class="btn btn-light">{{ translate('Change_Subscription_Plan') }} <i
                                class="tio-arrow-forward"></i></a>
                    </div>

                    <button type="button" data-id="subscription_cancel_close_btn"
                        class="trial-close add-to-session ">
                        <i class="tio-clear-circle"></i>
                    </button>
                </div>
            </div>
        @elseif (Session::get('subscription_plan_update_close_btn') !== true &&
                $restaurant_data?->restaurant_sub &&
                $restaurant_data?->restaurant_sub?->package?->status != 1)
            <div class="free-trial trial danger-bg">
                <div class="inner-div">
                    <div class="left">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/timer-2.svg') }}" alt="">
                        <div class="left-content">
                            <h6>{{ translate('Your_Current_Subscription_Package_has_been_Disable_By_Admin.') }} </h6>
                            <div>{{ translate('You_can_not_renew_this_Package_after') }}
                                {{ \App\CentralLogics\Helpers::date_format($restaurant_data?->restaurant_sub?->expiry_date_parsed) }}.
                                {{ translate('to_continue_your_subscription_please_chose_another_package.') }}</div>
                        </div>
                    </div>
                    <div class="right">
                        <a href="#" class="btn btn-2">
                            <span class="circle-progress-container">
                                <svg width="40" viewBox="0 0 160 160">
                                    <circle r="70" cx="80" cy="80" fill="transparent"
                                        stroke="#ffffff20" stroke-width="12px"></circle>
                                    <circle r="70" cx="80" cy="80" fill="transparent" stroke="#ffffff"
                                        stroke-width="12px" stroke-dasharray="439.6px"
                                        stroke-dashoffset="{{ $pers }}px"></circle>
                                </svg>
                                {{1+ Carbon\Carbon::now()->diffInDays($restaurant_data?->restaurant_sub?->expiry_date_parsed->format('Y-m-d'), false) }}
                            </span>
                            {{ translate('Days_left_in_this_subscription') }}
                        </a>
                        <a href="{{ route('vendor.subscriptionackage.subscriberDetail', ['open_plans' => true]) }}"
                            class="btn btn-light">{{ translate('Change_Subscription_Plan') }} <i
                                class="tio-arrow-forward"></i></a>
                    </div>

                    <button type="button" data-id="subscription_plan_update_close_btn"
                        class="trial-close add-to-session ">
                        <i class="tio-clear-circle"></i>
                    </button>
                </div>
            </div>
        @elseif ($restaurant_data?->restaurant_model == 'unsubscribed' && !$restaurant_data?->restaurant_sub_update_application )
            <div class="free-trial trial danger-bg">
                <div class="inner-div">
                    <div class="left">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/timer-2.svg') }}" alt="">
                        <div class="left-content">
                            <h6>{{ translate('Your_are_not_subscribed') }}
                                {{-- {{ \App\CentralLogics\Helpers::date_format($restaurant_data?->restaurant_sub_update_application?->expiry_date_parsed) }} --}}
                            </h6>
                            <div>
                                {{ translate('Purchase a subscription plan or contact with the admin to settle the payment and unblock the access to service') }}
                            </div>
                        </div>
                    </div>
                    <div class="right">

                        <a href="{{ route('vendor.subscriptionackage.subscriberDetail', ['open_plans' => true]) }}"
                            class="btn btn-light">{{ translate('Choose Subscription_Plan') }} <i
                                class="tio-arrow-forward"></i></a>
                    </div>

                </div>
            </div>

        @elseif ($restaurant_data?->restaurant_sub == null)
            <div class="free-trial trial danger-bg">
                <div class="inner-div">
                    <div class="left">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/timer-2.svg') }}" alt="">
                        <div class="left-content">
                            <h6>{{ translate('Your_Subscription_Has_Been_Expired_on') }}
                                {{ \App\CentralLogics\Helpers::date_format($restaurant_data?->restaurant_sub_update_application?->expiry_date_parsed) }}
                            </h6>
                            <div>
                                {{ translate('Purchase a subscription plan or contact with the admin to settle the payment and unblock the access to service') }}
                            </div>
                        </div>
                    </div>
                    <div class="right">

                        <a href="{{ route('vendor.subscriptionackage.subscriberDetail', ['open_plans' => true]) }}"
                            class="btn btn-light">{{ translate('Change/Renew Subscription_Plan') }} <i
                                class="tio-arrow-forward"></i></a>
                    </div>

                </div>
            </div>
        @endif

    @endif
</div>


<div class="modal fade removeSlideDown" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered max-w-520">
        <div class="modal-content modal-content__search border-0">
            <div class="d-flex flex-column gap-3 rounded-20 bg-card py-2 px-3">
                <div class="d-flex gap-2 align-items-center position-relative">
                    <form class="flex-grow-1" id="searchForm" action="{{ route('vendor.search.routing') }}">
                        @csrf
                        <div class="d-flex align-items-center global-search-container">
                            <input class="form-control flex-grow-1 rounded-10 search-input" id="searchInput" name="search" type="search" placeholder="Search" aria-label="Search" autofocus>
                        </div>
                    </form>
                    <div class="position-absolute right-0 pr-2">
                        <button class="border-0 rounded px-2 py-1" type="button" data-dismiss="modal">{{ translate('Esc') }}</button>
                    </div>
                </div>

                <div class="min-h-350">
                    <div class="search-result" id="searchResults">
                        <div class="text-center text-muted py-5">{{translate('It appears that you have not yet searched.')}}.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('click', '.add-to-session', function() {
            var session_data = $(this).data("id");
            $.ajax({
                url: '{{ route('vendor.subscriptionackage.addToSession') }}',
                method: 'POST',
                data: {
                    value: session_data,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#hide-subscription-warnings').addClass('d-none')
                }
            });
        });
    });
</script>
