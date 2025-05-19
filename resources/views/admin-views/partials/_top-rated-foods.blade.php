<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <img src="{{dynamicAsset('/public/assets/admin/img/dashboard/most-rated.png')}}" alt="dashboard" class="card-header-icon">
        <span>{{translate('messages.top_rated_foods')}}</span>
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
     @if($top_rated_foods->count() > 0)
         <div class="row g-2">
            @foreach($top_rated_foods as $key=>$item)
                <div class="col-md-4 col-6">
                    <div class="grid-card top--rated-food pb-4 cursor-pointer redirect-url" data-url="{{route('admin.food.view',[$item['id']])}}">
                        <div class="text-center py-3">
                            <img class="initial-42 onerror-image"
                                src="{{ $item['image_full_url'] ??  dynamicAsset('public/assets/admin/img/100x100/2.png') }}" data-onerror-image="{{dynamicAsset('public/assets/admin/img/100x100/2.png')}}"
                                alt="{{$item->name}} image">
                        </div>
                        <div class="text-center mt-3">
                            <h5 class="name m-0 mb-1">{{Str::limit($item->name??translate('messages.Food_deleted!'),20,'...')}}</h5>
                            <div class="rating">
                                <span class="text-warning"><i class="tio-star"></i> {{round($item['avg_rating'],1)}}</span>
                                <span class="text--title">({{$item['rating_count']}}  {{ translate('Reviews') }})</span>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    @else
        <div class="d-flex justify-content-center align-items-center h-100 min-h-200">
            <div class="d-flex flex-column ga-2 justify-content-center align-items-center">
                <img class="mb-3" src="{{dynamicAsset('public/assets/admin/img/dashboard/top_food.svg')}}" alt="">
                <h4>{{translate('No items available in this zone')}}</h4>
            </div>
        </div>
    @endif
</div>
<!-- End Body -->
