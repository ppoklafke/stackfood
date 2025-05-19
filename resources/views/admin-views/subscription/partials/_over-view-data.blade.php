<div class="card-body">
    <div class="mb-20">
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <a class="__card-2 __bg-1" href="#">
                    <h4 class="title">{{ $over_view_data['total_subscribed_user'] }}</h4>
                    <span class="subtitle">{{ translate('Total_Subscribed_User') }}</span>
                    <img src="{{dynamicAsset('public/assets/admin/img/subscription-plan/subscribed-user.png')}}"
                        alt="report/new" class="card-icon" width="35px">
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a class="__card-2 __bg-3" href="#">
                    <h4 class="title">{{ $over_view_data['active_subscription'] }}</h4>
                    <span class="subtitle">{{ translate('Active_Subscriptions') }}</span>
                    <img src="{{dynamicAsset('public/assets/admin/img/subscription-plan/active-user.png')}}"
                        alt="report/new" class="card-icon" width="35px">
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a class="__card-2 __bg-6" href="#">
                    <h4 class="title">{{ $over_view_data['expired_subscription'] }}</h4>
                    <span class="subtitle">{{ translate('Expired_Subscription') }}</span>
                    <img src="{{dynamicAsset('public/assets/admin/img/subscription-plan/expired-user.png')}}"
                        alt="report/new" class="card-icon" width="35px">
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a class="__card-2 __bg-4" href="#">
                    <h4 class="title">{{ $over_view_data['expired_soon'] }}</h4>
                    <span class="subtitle">{{ translate('Expiring_Soon') }} </span>
                    <img src="{{dynamicAsset('public/assets/admin/img/subscription-plan/expired-soon.png')}}"
                        alt="report/new" class="card-icon" width="35px">
                </a>
            </div>
        </div>
    </div>
    <div class="row g-2">
        <div class="col-sm-6 col-lg-4">
            <a class="order--card h-100" href="#">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                        <img src="{{dynamicAsset('public/assets/admin/img/plan/free.png')}}" alt="dashboard"
                            class="oder--card-icon" width="20">
                        <span>{{ translate('Free_Trial') }}</span>
                    </h6>
                    <span class="card-title text-success">
                        {{ $over_view_data['total_free_trials'] }}
                    </span>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-4">
            <a class="order--card h-100" href="#">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                        <img src="{{dynamicAsset('public/assets/admin/img/plan/renewed.png')}}" alt="dashboard"
                            class="oder--card-icon" width="20">
                        <span>{{ translate('Total_Renewed') }}</span>
                    </h6>
                    <span class="card-title text-0661CB">
                        {{ $over_view_data['total_renewed'] }}
                    </span>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-4">
            <a class="order--card h-100" href="#">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                        <img src="{{dynamicAsset('public/assets/admin/img/plan/total.png')}}" alt="dashboard"
                            class="oder--card-icon" width="20">
                        <span>{{ translate('Total Earning') }}</span>
                    </h6>
                    <span class="card-title text-danger">
                        {{ \App\CentralLogics\Helpers::format_currency($over_view_data['total_amount'])  }}
                    </span>
                </div>
            </a>
        </div>
    </div>
</div>
