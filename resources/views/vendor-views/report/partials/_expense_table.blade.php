@foreach($expense as $k=>$exp)
<tr>
    <td>{{$k+1}}</td>
    <td><label class="text-uppercase">{{$exp['type']}}</label></td>
    <td><div class="pl-4">
        {{\App\CentralLogics\Helpers::format_currency($exp['amount'])}}
    </div></td>
    <td><div class="pl-4">
        {{$exp['description']}}
    </div></td>
    <td>{{$exp->created_at->format('Y-m-d '.config('timeformat'))}}</td>
</tr>
@endforeach
