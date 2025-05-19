<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <img src="{{ dynamicAsset('/public/assets/admin/img/dashboard/most-popular.png') }}" alt="dashboard"
            class="card-header-icon">
        {{ translate('Most_Popular_Restaurants') }}
        <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('most_popular_restaurants_based_on_users_wishlisted_Foods')}}" class="input-label-secondary"><img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Most_Popular_Restaurants_Based_on_Users_Wishlisted_Foods.') }}"></span>

    </h5>
    @php($params = session('dash_params'))
    @if ($params['zone_id'] != 'all')
        @php($zone_name = \App\Models\Zone::where('id', $params['zone_id'])->first()->name)
    @else
        @php($zone_name = translate('All'))
    @endif
    <span class="badge badge-soft--info my-2">{{ translate('messages.zone') }} : {{ $zone_name }}</span>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    @if($popular->count() > 0)
         <ul class="most-popular most-popular__restaurant">
            @foreach ($popular as $key => $item)
                <li data-url="{{ route('admin.restaurant.view', $item->restaurant_id) }}" class="cursor-pointer redirect-url">
                    <div class="img-container">
                        <img class="onerror-image" data-onerror-image="{{dynamicAsset('public/assets/admin/img/100x100/1.png')}}"
                             src="{{ $item->restaurant['logo_full_url'] }}" alt="{{translate('store')}}">
                        <span class="ml-2">
                            {{ Str::limit($item->restaurant->name ?? translate('messages.Restaurant_deleted!'), 20, '...') }} </span>
                    </div>
                    <span class="count">
                        {{ $item['count'] }} <i class="tio-heart"></i>
                    </span>
                </li>
            @endforeach
        </ul>
    @else
        <div class="d-flex justify-content-center align-items-center h-100 min-h-200">
            <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                <img src="{{dynamicAsset('public/assets/admin/img/dashboard/mp_restaurant.svg')}}" alt="">
                <h4>{{translate('No restaurant available in this zone')}}</h4>
            </div>
        </div>
    @endif
</div>

