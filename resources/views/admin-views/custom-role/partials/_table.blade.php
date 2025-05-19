@foreach($rl as $k=>$r)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{Str::limit($r['name'],25,'...')}}</td>
        <td class="text-capitalize">
            @if($r['modules']!=null)
                @foreach((array)json_decode($r['modules']) as $key=>$m)
                    {{translate(str_replace('_',' ',$m)) }},
                @endforeach
            @endif
        </td>
        <td>
            {{   \App\CentralLogics\Helpers::date_format($r['created_at']) }}
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn btn--primary btn-outline-primary action-btn"
                   href="{{route('admin.custom-role.edit',[$r['id']])}}" title="{{translate('messages.edit_role')}}"><i class="tio-edit"></i>
                </a>
                <a class="btn btn--danger btn-outline-danger action-btn form-alert" href="javascript:"
                   data-id="role-{{$r['id']}}" data-message="{{translate('messages.Want_to_delete_this_role_?')}}" title="{{translate('messages.delete_role')}}"><i class="tio-delete-outlined"></i>
                </a>
            </div>
            <form action="{{route('admin.custom-role.delete',[$r['id']])}}"
                  method="post" id="role-{{$r['id']}}">
                @csrf @method('delete')
            </form>
        </td>
    </tr>
@endforeach
