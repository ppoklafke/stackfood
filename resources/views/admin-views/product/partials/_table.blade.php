@foreach($foods as $key=>$food)
    <tr>
        <td>{{$key+1}}</td>
        <td>
            <a class="media align-items-center"
                href="{{ route('admin.food.view', [$food['id']]) }}">
                <img class="avatar avatar-lg mr-3 onerror-image"
                     src="{{ $food['image_full_url'] ?? dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}"
                     data-onerror-image="{{dynamicAsset('public/assets/admin/img/100x100/food-default-image.png')}}"
                     alt="{{ $food->name }} image">
                <div class="media-body">
                    <h5 class="text-hover-primary mb-0">
                        {{ Str::limit($food['name'], 20, '...') }}</h5>
                </div>
            </a>
        </td>
        <td>
            {{ Str::limit(($food?->category?->parent ? $food?->category?->parent?->name : $food?->category?->name )  ?? translate('messages.uncategorize')
            , 20, '...') }}
        </td>
        <td>
            {{ Str::limit($food->restaurant ? $food->restaurant->name : translate('messages.Restaurant_deleted!'), 20, '...') }}
        </td>
        <td>{{ \App\CentralLogics\Helpers::format_currency($food['price']) }}</td>
        <td>
            <label class="toggle-switch toggle-switch-sm"
                for="stocksCheckbox{{ $food->id }}">
                <input type="checkbox"
                    data-url="{{ route('admin.food.status', [$food['id'], $food->status ? 0 : 1]) }}"
                    class="toggle-switch-input redirect-url" id="stocksCheckbox{{ $food->id }}"
                    {{ $food->status ? 'checked' : '' }}>
                <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                    href="{{ route('admin.food.edit', [$food['id']]) }}"
                    title="{{ translate('messages.edit_food') }}"><i
                        class="tio-edit"></i>
                </a>
                <a class="btn btn-sm btn--warning btn-outline-warning action-btn form-alert" href="javascript:"
                    data-id="food-{{ $food['id'] }}" data-message="{{ translate('messages.Want_to_delete_this_item_?') }}"
                    title="{{ translate('messages.delete_food') }}"><i
                        class="tio-delete-outlined"></i>
                </a>
            </div>
            <form action="{{ route('admin.food.delete', [$food['id']]) }}" method="post"
                id="food-{{ $food['id'] }}">
                @csrf @method('delete')
            </form>
        </td>
    </tr>
@endforeach
