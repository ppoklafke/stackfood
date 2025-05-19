
            <!-- Nav -->
            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{request('tab')==null?'active':''}}"  href="{{route('admin.restaurant.view', $restaurant->id)}}">{{translate('messages.overview')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab')=='order'?'active':''}}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'order'])}}"  aria-disabled="true">{{translate('messages.orders')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab')=='product'?'active':''}}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'product'])}}"  aria-disabled="true">{{translate('messages.foods')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab')=='reviews'?'active':''}}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'reviews'])}}"  aria-disabled="true">{{translate('messages.reviews')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab')=='discount'?'active':''}}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'discount'])}}"  aria-disabled="true">{{translate('discounts')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab')=='transaction'?'active':''}}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{translate('messages.transactions')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab')=='settings'?'active':''}}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'settings'])}}"  aria-disabled="true">{{translate('messages.settings')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab')=='conversations'?'active':''}}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'conversations'])}}"  aria-disabled="true">{{translate('messages.conversations')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab') =='business_plan' || request('tab') =='subscriptions-transactions' ?'active':'' }}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'business_plan'])}}"  aria-disabled="true">{{translate('messages.Business Plan')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab')=='meta-data' ?'active':''}}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'meta-data'])}}"  aria-disabled="true">{{translate('messages.meta-data')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab')=='qr-code' ?'active':''}}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'qr-code'])}}"  aria-disabled="true">{{translate('messages.QR_code')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{request('tab')=='disbursements' ?'active':''}}" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'disbursements'])}}"  aria-disabled="true">{{translate('messages.disbursements')}}</a>
                </li>
            </ul>
            {{-- @if (request('tab') =='business_plan' || request('tab') =='subscriptions-transactions')
            <ul class="nav nav-tabs page-header-tabs mb-0 mt-3">
                <li class="nav-item">
                    <a class="nav-link {{request('tab')=='business_plan'?'active':''}}" href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'business_plan']) }}">{{ translate('messages.subscription_details') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{request('tab')=='subscriptions-transactions'?'active':''}}"  href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'subscriptions-transactions']) }}">{{ translate('messages.transactions') }}</a>
                </li>
            </ul>
            @endif --}}
            <!-- End Nav -->
