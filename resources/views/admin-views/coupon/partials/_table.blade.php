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
        <th>{{translate('messages.sl')}}</th>
        <th>{{translate('messages.title')}}</th>
        <th>{{translate('messages.code')}}</th>
        <th>{{translate('messages.min_purchase')}}</th>
        <th>{{translate('messages.max_discount')}}</th>
        <th>{{translate('messages.discount')}}</th>
        <th>{{translate('messages.discount_type')}}</th>
        <th>{{translate('messages.start_date')}}</th>
        <th>{{translate('messages.expire_date')}}</th>
        <th>{{translate('messages.Customer_type')}}</th>
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
            <td>{{\App\CentralLogics\Helpers::format_currency($coupon['min_purchase'])}}</td>
            <td>{{\App\CentralLogics\Helpers::format_currency($coupon['max_discount'])}}</td>
            <td>{{$coupon['discount']}}</td>
            <td>{{$coupon['discount_type']}}</td>
            <td>{{$coupon['start_date']}}</td>
            <td>{{$coupon['expire_date']}}</td>
            <td>
                <span class="d-block font-size-sm text-body">
                    @if (in_array('all', json_decode($coupon->customer_id)))
                    {{translate('messages.all_customers')}}
                    @else
                    {{translate('messages.Selected_customers')}}
                    @endif
                </span>
            </td>
            <td>
                <label class="toggle-switch toggle-switch-sm" for="couponCheckbox{{$coupon->id}}">
                    <input type="checkbox" data-url="{{route('admin.coupon.status',[$coupon['id'],$coupon->status?0:1])}}" class="toggle-switch-input redirect-url" id="couponCheckbox{{$coupon->id}}" {{$coupon->status?'checked':''}}>
                    <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                </label>
            </td>
            <td>
                <div class="btn--container justify-content-center">
                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('admin.coupon.update',[$coupon['id']])}}"title="{{translate('messages.edit_coupon')}}"><i class="tio-edit"></i>
                    </a>
                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="coupon-{{$coupon['id']}}" data-message="{{ translate('Want to delete this coupon ?') }}" title="{{translate('messages.delete_coupon')}}"><i class="tio-delete-outlined"></i>
                    </a>
                    <form action="{{route('admin.coupon.delete',[$coupon['id']])}}"
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
