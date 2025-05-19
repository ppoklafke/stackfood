<table id="columnSearchDatatable"
        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
        data-hs-datatables-options='{
        "order": [],
        "orderCellsTop": true,

        "entries": "#datatableEntries",
        "isResponsive": false,
        "isShowPaging": false,
        "pagination": "datatablePagination"
        }'>
    <thead class="thead-light">
    <tr>
        <th>{{translate('messages.#')}}</th>
        <th>{{translate('messages.title')}}</th>
        <th>{{translate('messages.code')}}</th>
        <th>{{translate('messages.min_purchase')}}</th>
        <th>{{translate('messages.max_discount')}}</th>
        <th>{{translate('messages.discount')}}</th>
        <th>{{translate('messages.discount_type')}}</th>
        <th>{{translate('messages.start_date')}}</th>
        <th>{{translate('messages.expire_date')}}</th>
        <th>{{translate('messages.status')}}</th>
        <th>{{translate('messages.action')}}</th>
    </tr>
    </thead>

    <tbody id="set-rows">
    @foreach($coupons as $key=>$coupon)
        <tr>
            <td>{{$key+1}}</td>
            <td>
                            <span class="d-block font-size-sm text-body">
                                {{Str::limit($coupon['title'],15,'...')}}
                            </span>
            </td>
            <td>{{$coupon['code']}}</td>
            <td>{{translate('messages.'.$coupon->coupon_type)}}</td>
            <td>{{$coupon->total_uses}}</td>
            <td>
                <div class="text-right mw-87px">
                    {{\App\CentralLogics\Helpers::format_currency($coupon['min_purchase'])}}
                </div>
            </td>
            <td>
                <div class="text-right mw-87px">
                    {{\App\CentralLogics\Helpers::format_currency($coupon['max_discount'])}}
                </div>
            </td>
            <td>
                <div class="text-center">
                    {{$coupon['discount']}}
                </div>
            </td>
            @if ($coupon['discount_type'] == 'percent')
                <td>{{ translate('messages.percent')}}</td>
            @elseif ($coupon['discount_type'] == 'amount')
                <td>{{ translate('messages.amount')}}</td>
            @else
                <td>{{$coupon['discount_type']}}</td>
            @endif

            <td>{{$coupon['start_date']}}</td>
            <td>{{$coupon['expire_date']}}</td>
            <td>
                <label class="toggle-switch toggle-switch-sm" for="couponCheckbox{{$coupon->id}}">
                    <input type="checkbox" data-url="{{route('vendor.coupon.status',[$coupon['id'],$coupon->status?0:1])}}" class="toggle-switch-input redirect-url" id="couponCheckbox{{$coupon->id}}" {{$coupon->status?'checked':''}}>
                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                </label>
            </td>
            <td>
                <div class="btn--container justify-content-center">
                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('vendor.coupon.update',[$coupon['id']])}}" title="{{translate('messages.edit_coupon')}}"><i class="tio-edit"></i>
                    </a>
                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:" data-id="coupon-{{$coupon['id']}}" data-message="{{ translate('Want_to_delete_this_coupon_?') }}" title="{{translate('messages.delete_coupon')}}"><i class="tio-delete-outlined"></i>
                    </a>
                    <form action="{{route('vendor.coupon.delete',[$coupon['id']])}}"
                          method="post" id="coupon-{{$coupon['id']}}">
                        @csrf @method('delete')
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<hr>
<table>
    <tfoot>

    </tfoot>
</table>
