@foreach ($subscriptions as $key => $transcation)
    <tr>
        <td>
            {{ $key + $subscriptions->firstItem() }}
        </td>
        <td>{{ Str::limit($transcation->id, 40, '...') }}</td>
        <td>
            {{ $transcation->created_at->format('d M Y h:i A') }}
        </td>
        <td>
            <a href="{{ route('admin.restaurant.view', $transcation->restaurant->id) }}" alt="view restaurant"
                class="table-rest-info">
                <h5 class="text-hover-primary text-dark">
                    {{ Str::limit($transcation->restaurant->name, 20, '...') }}
                </h5>
            </a>
        </td>
        <td>
            {{ Str::limit($transcation->package->package_name, 20, '...') }}
        </td>
        <td>{{ $transcation->validity }} {{ translate('messages.Days') }}</td>
        <td>{{ \App\CentralLogics\Helpers::format_currency($transcation->package->price) }}</td>

        <td>
            <div>
                {{ \App\CentralLogics\Helpers::format_currency($transcation->paid_amount) }}
            </div>
            @if ($transcation->payment_status == 'success')
                <small class="text-success text-capitalize">
                    {{ translate('messages.paid') }}
                </small>
            @elseif($transcation->payment_status == 'on_hold')
                <small class="text-warning text-capitalize">
                    {{ translate('messages.Payment_On_Hold') }}
                </small>
            @elseif($transcation->payment_status == 'failed')
                <small class="text-danger text-capitalize">
                    {{ translate('messages.Payment_Failed') }}
                </small>
            @endif
        </td>
        <td>
            <div class="text-success text-capitalize">
                @if ($transcation->payment_method == 'wallet')
                    {{ translate('messages.Wallet_payment') }}
                @elseif($transcation->payment_method == 'manual_payment_admin')
                    {{ translate('messages.Manual_payment') }}
                @elseif($transcation->payment_method == 'manual_payment_by_restaurant')
                    {{ translate('messages.Manual_payment') }}
                @elseif($transcation->payment_method == 'free_trial')
                    {{ translate('messages.free_trial') }}
                @elseif($transcation->payment_method == 'pay_now')
                    {{ translate('messages.Digital_Payment') }}
                @else
                    {{ translate($transcation->payment_method) }}
                @endif

            </div>
        </td>

        <td>
            @if ($transcation->payment_status == 'success')
                <div class="btn--container justify-content-center">
                    <a class="btn btn-outline-success square-btn btn-sm mr-1 action-btn"
                        href="{{ route('admin.report.subscription.generate-statement', [$transcation['id']]) }}"> <i
                            class="tio-download-to"></i>
                    </a>
                </div>
            @endif
        </td>
    </tr>
@endforeach
