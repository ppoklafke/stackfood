@php
$logs = $subscription->pause()->latest()->get();
@endphp

@if($logs->count() === 0)

<div class="py-5 text-center">
    <div class="py-sm-5">
        <img src="{{dynamicAsset('public/assets/admin/img/empty-log.png')}}" alt="">
        <div class="mt-3">{{translate('messages.No pause logs found!')}}</div>
    </div>
</div>

@else

<div class="delivery-logs-area h-25 overflow-hidden scroll-bar">
    @foreach($logs as $key=>$log)
    <div class="item pause-log-item">
        <div>#{{ $key+1 }}</div>
        <div class="inner px-lg-4">
            <div data-toggle="tooltip" data-placement="top" title="{{ translate('New_Order_won’t_be_created_at_this_period')}} " >
                <h6>{{ translate('Order_Id_#') }}{{ $subscription->order->id }}</h6>
                <small>{{  \App\CentralLogics\Helpers::date_format($log->from) }} - {{  \App\CentralLogics\Helpers::date_format($log->to) }}</small>
            </div>
            <div class="text-right">
                @php
                $current_date = date('Y-m-d');
                $from = Carbon\Carbon::parse($log->from);
            @endphp

            @if ( $from->gt($current_date) && ($subscription->status != 'expired' && $subscription->status != 'canceled'))
            <a class="btn btn--primary border-0 form-alert" href="javascript:"
            data-id="role-{{$log['id']}}" data-message="{{translate('messages.Want_to_Resume_the_subscription_?')}}" title="{{translate('messages.Resume')}}">
            {{ translate('Resume') }}
            </a>
            <form action="{{route('admin.order.subscription.pause_log_delete',[$log['id']])}}"
            method="post" id="role-{{$log['id']}}">
            @csrf @method('delete')
            </form>
            @else
            <button title="{{ translate('resume_period_is_over')}} " class="btn btn-secondary border-0" disabled="disabled">{{ translate('Resume') }}</button>
            @endif
            </div>
        </div>
    </div>

    @endforeach

</div>

@endif
