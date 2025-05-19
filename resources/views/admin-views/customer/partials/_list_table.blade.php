@foreach($orders as $key=>$order)
<tr>
    <td>{{$key+$orders->firstItem()}}</td>
    <td class="table-column-pl-0 text-center">
        <a href="{{route('admin.order.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
    </td>
    <td class="text-capitalize text-center">
        @if($order['order_status']=='pending')
            <span class="badge badge-soft-info mb-1">
                                      {{translate('messages.pending')}}
                                    </span>
        @elseif($order['order_status']=='confirmed')
            <span class="badge badge-soft-info mb-1">
                                      {{translate('messages.confirmed')}}
                                    </span>
        @elseif($order['order_status']=='processing')
            <span class="badge badge-soft-warning mb-1">
                                      {{translate('messages.processing')}}
                                    </span>
        @elseif($order['order_status']=='picked_up')
            <span class="badge badge-soft-warning mb-1">
                                      {{translate('messages.out_for_delivery')}}
                                    </span>
        @elseif($order['order_status']=='delivered')
            <span class="badge badge-soft-success mb-1">
                {{$order?->order_type == 'dine_in' ? translate('messages.Completed') : translate('messages.delivered')}}

                                    </span>
        @elseif($order['order_status']=='failed')
            <span class="badge badge-soft-danger mb-1">
                                      {{translate('messages.payment_failed')}}
                                    </span>
        @else
            <span class="badge badge-soft-danger mb-1">
                                      {{translate(str_replace('_',' ',$order['order_status']))}}
                                    </span>
        @endif
{{--        <div class="text-capitalze opacity-7">--}}
{{--            @if($order['order_type']=='take_away')--}}
{{--                <span>--}}
{{--                                        {{translate('messages.take_away')}}--}}
{{--                                    </span>--}}
{{--            @else--}}
{{--                <span>--}}
{{--                                        {{translate('home_delivery')}}--}}
{{--                                    </span>--}}
{{--            @endif--}}
{{--        </div>--}}
    </td>
    <td>
        <div class="text-center">
            {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
        </div>
    </td>
    <td>
        <div class="btn--container justify-content-center">
        <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                    href="{{route('admin.order.details',['id'=>$order['id']])}}" title="{{translate('messages.view')}}"><i
                            class="tio-visible-outlined"></i></a>
        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" target="_blank"
                    href="{{route('admin.order.generate-invoice',[$order['id']])}}" title="{{translate('messages.invoice')}}"><i
                            class="tio-print"></i> </a>
        </div>
    </td>
</tr>
@endforeach
