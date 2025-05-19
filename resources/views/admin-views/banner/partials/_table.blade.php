@foreach($banners as $key=>$banner)
    <tr>
        <td>{{$key+1}}</td>
        <td>
                                        <span class="media align-items-center">
                                            <img class="avatar avatar-lg mr-3 avatar--3-1 onerror-image" src="{{$banner['image_full_url']) }}"
                                                 data-onerror-image="{{dynamicAsset('/public/assets/admin/img/900x400/img1.jpg')}}" alt="{{$banner->name}} image">
                                            <div class="media-body">
                                                <h5 class="text-hover-primary mb-0">{{Str::limit($banner['title'], 25, '...')}}</h5>
                                            </div>
                                        </span>
            <span class="d-block font-size-sm text-body">

                                    </span>
        </td>
        <td>{{translate('messages.'.$banner['type'])}}</td>
        <td>
            <label class="toggle-switch toggle-switch-sm" for="statusCheckbox{{$banner->id}}">
                <input type="checkbox" data-url="{{route('admin.banner.status',[$banner['id'],$banner->status?0:1])}}" class="toggle-switch-input redirect-url" id="statusCheckbox{{$banner->id}}" {{$banner->status?'checked':''}}>
                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
            </label>
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('admin.banner.edit',[$banner['id']])}}" title="{{translate('messages.edit_banner')}}"><i class="tio-edit"></i>
                </a>
                <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="banner-{{$banner['id']}}" data-message="{{translate('messages.Want_to_delete_this_banner')}}" title="{{translate('messages.delete_banner')}}"><i class="tio-delete-outlined"></i>
                </a>
                <form action="{{route('admin.banner.delete',[$banner['id']])}}"
                      method="post" id="banner-{{$banner['id']}}">
                    @csrf @method('delete')
                </form>
            </div>
        </td>
    </tr>
@endforeach
