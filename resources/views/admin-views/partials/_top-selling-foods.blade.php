<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <img src="{{dynamicAsset('/public/assets/admin/img/dashboard/top-selling.png')}}" alt="dashboard" class="card-header-icon">
        <span>{{translate('messages.top_selling_foods')}}</span>
    </h5>
    @php($params=session('dash_params'))
    @if($params['zone_id']!='all')
        @php($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name)
    @else
    @php($zone_name=translate('All'))
    @endif
    <span class="badge badge-soft--info my-2">{{translate('messages.zone')}} : {{$zone_name}}</span>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    @if($top_sell->count() > 0)
        <div class="row g-2">
            @foreach($top_sell as $key=>$item)
                <div class="col-md-4 col-sm-6">
                    <div class="grid-card top-selling-food-card pb-3 redirect-url" data-url="{{route('admin.food.view',[$item['id']])}}">
                        <div class="position-relative">
                            <span class="sold--count-badge"><span>{{translate('messages.sold')}}</span> <span>:</span> <span>{{$item['order_count']}}</span></span>
                            <img class="initial-43 onerror-image"
                                 src="{{ $item['image_full_url'] ?? dynamicAsset('public/assets/admin/img/100x100/food.png') }}"  data-onerror-image="{{dynamicAsset('public/assets/admin/img/100x100/food.png')}}"
                                alt="{{$item->name}} image">
                        </div>
                        <div class="text-center mt-3">
                            <span>{{Str::limit($item['name'],20,'...')}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="d-flex justify-content-center align-items-center h-100 min-h-200">
            <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                <img src="{{dynamicAsset('public/assets/admin/img/dashboard/top_food.svg')}}" alt="">
                <h4>{{translate('No items available in this zone')}}</h4>
            </div>
        </div>
    @endif
</div>
<!-- End Body -->
