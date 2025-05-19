<div class="row">
    <div class="col-lg-12 text-center "><h1 > {{ translate('messages.Order_Refund_List') }}</h1></div>
    <div class="col-lg-12">



    <table>
        <thead>
            <tr>
                <th>{{ translate('filter_criteria') }} -</th>
                <th></th>
                <th></th>
                <th>
                    {{ translate('Refund_status' )}} : {{ translate($data['refund_status'] ?? $data['status']) }}
                    @if ($data['search'])
                    <br>
                    {{ translate('search_bar_content' )}} : {{ $data['search'] }}
                    @endif
                    @if ($data['zones'])
                    <br>
                    {{ translate('zones' )}} : {{ $data['zones'] }}
                    @endif
                    @if ($data['restaurant'])
                    <br>
                    {{ translate('restaurant' )}} : {{ $data['restaurant'] }}
                    @endif
                    @if ($data['type'])
                    <br>
                    {{ translate('order_type' )}} : {{ translate($data['type']) }}
                    @endif
                    @if ($data['from'])
                    <br>
                    {{ translate('from' )}} : {{ $data['from']?Carbon\Carbon::parse($data['from'])->format('d M Y'):'' }}
                    @endif
                    @if ($data['to'])
                    <br>
                    {{ translate('to' )}} : {{ $data['to']?Carbon\Carbon::parse($data['to'])->format('d M Y'):'' }}
                    @endif

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>{{ translate('messages.sl') }}</th>
                <th>{{ translate('messages.Order_Id') }}</th>
                <th>{{ translate('messages.Order_Date') }}</th>
                <th>{{ translate('messages.Customer_Name') }}</th>
                <th>{{ translate('messages.Refund_Reason') }}</th>
                <th>{{ translate('messages.Restaurant_Name') }}</th>
                <th>{{ translate('messages.Total_Items') }}</th>
                <th>{{ translate('messages.Order_Amount') }}</th>
                <th>{{ translate('messages.Delivery_Charge') }}</th>
                <th>{{ translate('messages.Refund_Amount') }}</th>
                <th>{{ translate('messages.Refund_Status') }}</th>
                <th>{{ translate('messages.Customer_Note') }}</th>
                <th>{{ translate('messages.Admin_Note') }}</th>
                <th>{{ translate('messages.Payment_Satus') }}</th>
                <th>{{ translate('messages.Order_Type') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['orders'] as $key => $order)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $order->id }}</td>
                <td>{{ \App\CentralLogics\Helpers::time_date_format($order->created_at) }}</td>
                <td>
                    @if ($order->customer)
                        {{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}
                    @else
                        {{ translate('not_found') }}
                    @endif
                </td>
                <td>
                    {{ $order?->refund?->customer_reason ?? translate('messages.N/A') }}
                </td>
                <td>
                    @if($order->restaurant)
                        {{$order->restaurant->name}}
                    @else
                        {{ translate('messages.not_found') }}
                    @endif
                </td>
                <td>{{ $order->details->count() }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['order_amount']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order['delivery_charge']) }}</td>
                <td>{{ \App\CentralLogics\Helpers::number_format_short($order?->refund?->refund_amount) }}</td>
                <td>
                    {{  translate($order?->refund?->refund_status) ?? translate('messages.N/A') }}
                </td>
                <td>
                    {{ $order?->refund?->customer_note ?? translate('messages.N/A') }}
                </td>
                <td>
                    {{ $order?->refund?->admin_note ?? translate('messages.N/A') }}
                </td>
                <td>{{ translate($order->payment_status) }}</td>
                <td>{{ translate($order->order_type) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
