@foreach($foods as $key=>$food)
    <tr>
        <td>{{$key+1}}</td>
        <td>
            <a class="media align-items-center" href="{{route('admin.food.view',[$food['id']])}}">
                <img class="avatar avatar-lg mr-3 onerror-image"
                     src="{{ $food['image_full_url'] ?? dynamicAsset('public/assets/admin/img/160x160/img2.jpg') }}"
                     data-onerror-image="{{dynamicAsset('public/assets/admin/img/160x160/img2.jpg')}}"
                     alt="{{ $food->name }} image">
                <div class="media-body">
                    <h5 class="text-hover-primary mb-0">{{$food['name']}}</h5>
                </div>
            </a>
        </td>
        <td>
        {{Str::limit($food->restaurant->name,25,'...')}}
        </td>
        <td>{{$food->restaurant->zone->name}}</td>
        <td>
            {{$food->order_count}}
        </td>
    </tr>
@endforeach
