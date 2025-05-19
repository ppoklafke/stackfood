@foreach($employees as $k=>$e)
<tr>
    <th scope="row">{{$k+1}}</th>
    <td class="text-capitalize">{{$e['f_name']}} {{$e['l_name']}}</td>
    <td>{{$e['phone']}}</td>
    <td >
      {{$e['email']}}
    </td>
    <td>
        {{$e['created_at']->format('d M, Y')}}
    </td>
    <td>
        @if (auth('admin')->id()  != $e['id'])
        <div class="btn--container">
            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                href="{{route('admin.employee.edit',[$e['id']])}}" title="{{translate('messages.edit_Employee')}}"><i class="tio-edit"></i>
            </a>
            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:"
               data-id="employee-{{$e['id']}}" data-message="{{translate('messages.Want_to_delete_this_employee_?')}}" title="{{translate('messages.delete_Employee')}}"><i class="tio-delete-outlined"></i>
            </a>
        </div>
        <form action="{{route('admin.employee.delete',[$e['id']])}}"
                method="post" id="employee-{{$e['id']}}">
            @csrf @method('delete')
        </form>
        @endif
    </td>
</tr>
@endforeach
