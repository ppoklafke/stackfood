@foreach($provide_dm_earning as $k=>$at)
<tr>
    <td>{{$k+1}}</td>
    <td>@if($at->delivery_man)<a href="{{route('admin.delivery-man.preview', $at->delivery_man_id)}}">{{$at->delivery_man->f_name.' '.$at->delivery_man->l_name}}</a> @else <label class="text-capitalize text-danger">{{translate('messages.deliveryman_deleted')}}</label> @endif </td>
    <td>
        {{  \App\CentralLogics\Helpers::time_date_format($at->created_at) }}</td>
    <td>{{$at['amount']}}</td>
    <td>{{$at['method']}}</td>
    <td>{{$at['ref']}}</td>
</tr>
@endforeach
