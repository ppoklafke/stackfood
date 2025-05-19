@php
$logs = $subscription->logs()->WhereIn('order_status',['delivered','failed','canceled', 'refund_requested','refund_request_canceled', 'refunded'])->latest()->get();

$ongoingOrder = $subscription->log()->WhereNotIn('order_status',['delivered','failed','canceled', 'refund_requested','refund_request_canceled', 'refunded'])->first();
$schedules= $subscription->schedules ?? [];
$pauselogs= $subscription->pause ?? [];
$scheduleType= $subscription->type;

                foreach ($schedules as $schedule) {
                    if($schedule->type != 'daily'){
                        $scheduleDates[] = $schedule->day;
                        $scheduleTime[$schedule->day] = $schedule->time;
                    } else{
                        $scheduleDates =['daily'];
                        $scheduleTime = [$schedule->time];
                    }
                }
                foreach ($pauselogs as $pause_log) {
                    $pauseArray[$pause_log->from]=$pause_log->to;
                }
                $start_at =$subscription->start_at;
                $end_at =$subscription->end_at;

                $dates = \App\CentralLogics\Helpers::generateDatesForSubscriptionOrders($start_at, $end_at, $scheduleDates,$scheduleTime ,$pauseArray??[] ,$scheduleType);
@endphp

@if($logs->count() == 0 && !$ongoingOrder && count($dates))
<div class="py-5 text-center">
    <div class="py-sm-5">
        <img src="{{dynamicAsset('public/assets/admin/img/empty-log.png')}}" alt="">
        <div class="mt-3">{{translate('messages.No delivery logs found!')}}</div>
    </div>
</div>

@else

<div class="delivery-logs-area">
    @if ($ongoingOrder)

    <div class="p-3 rounded bg--F7F9FD">
        <h5 class="mb-3">{{ translate('Ongoing') }}</h5>
        <div class="item">
            <div class="inner">
                <div>
                    <h6>{{ translate('Order_Id_#') }}<a href="{{route('admin.order.details',['id'=>$subscription->order->id])}}">{{ $ongoingOrder->order_id }}</a> </h6>
                    <small> {{ \App\CentralLogics\Helpers::time_date_format($ongoingOrder->{$ongoingOrder->order_status} ?? $ongoingOrder->updated_at) }}</small>
                </div>
                <div class="text-right">
                    <h6>{{  \App\CentralLogics\Helpers::format_currency($subscription->order->order_amount)  }}</h6>
                    <span class="badge badge--info">{{  translate($ongoingOrder->order_status)  }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif
        @if (count($logs) > 0)
        <div>
            <h5 class="mb-3 pt-2">{{ translate('Delivered') }}</h5>
            <div class="h-25 overflow-hidden scroll-bar">
            @foreach($logs as $key=>$log)

            <div class="item">
                <div>#{{ $key+1 }}</div>
                <div class="inner">
                    <div>
                        <h6>{{ translate('Order_Id_#') }}<a href="{{route('admin.order.details',['id'=> $log->order_id])}}">{{ $log->order_id }}</a> </h6>

                        <small>  {{  \App\CentralLogics\Helpers::time_date_format($log?->{$log?->order_status} ?? $log?->updated_at) }}</small>
                    </div>
                    <div class="text-right">
                        <h6>{{  \App\CentralLogics\Helpers::format_currency($subscription->order->order_amount)  }}</h6>
                        <span class="badge {{ $log->order_status == 'delivered'  ? 'badge--info' : 'badge-soft-danger'}}">{{  translate($log->order_status)  }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        </div>
        @endif
        @if (count($dates) > 0)
        <div>
            <h5 class="mb-3 pt-2"> {{ translate('Upcoming') }} </h5>
            <div class="h-25 overflow-hidden scroll-bar">
                @foreach ($dates as $key => $date)
                <div class="item" >
                    <div>#{{ $key+1 }}</div>
                    <div class="inner">
                        <div data-toggle="tooltip" data-placement="top" title="{{ translate('Order_will_be_created_at')}} {{  \App\CentralLogics\Helpers::date_format($date) }} {{ translate('after_that_you_will_able_to_see_the_order')}}" >
                            <h6>{{ translate('Order_Id_#') }}{{ $subscription->order->id }}</h6>
                            <small>{{  \App\CentralLogics\Helpers::date_format($date) }}</small>
                        </div>
                        <div class="text-right">
                            <h6>{{  \App\CentralLogics\Helpers::format_currency($subscription->order->order_amount)  }}</h6>
                            <span class="badge badge--success">{{ translate('Pending') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
    @endif
