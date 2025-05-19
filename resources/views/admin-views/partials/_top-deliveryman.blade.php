<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <img src="{{dynamicAsset('/public/assets/admin/img/dashboard/top-deliveryman.png')}}" alt="dashboard" class="card-header-icon">
        <span>{{ translate('Top_Deliveryman') }}</span>
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
    @if($top_deliveryman->count() > 0)
        <div class="row g-2">
            @foreach($top_deliveryman as $key=>$item)
                <div class="col-md-4 col-6 redirect-url" data-url="{{route('admin.delivery-man.preview',[$item['id']])}}">
                    <div class="grid-card top--deliveryman cursor-pointer">
                        <div class="text-center py-3">
                            <img class="initial-41 onerror-image" data-onerror-image="{{dynamicAsset('public/assets/admin/img/160x160/img1.jpg')}}"
                                 src="{{ $item['image_full_url'] ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}" alt="{{$item['f_name']}}" >
                        </div>
                        <div class="text-center mt-2">
                            <h5 class="name m-0">{{$item['f_name'] ?? translate('messages.Not_exist')}}</h5>
                            <h5 class="info m-0 mt-1"><span class="text-warning">{{$item['order_count']}}</span> {{ translate('Orders') }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="d-flex justify-content-center align-items-center h-100 min-h-200">
            <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                <img src="{{dynamicAsset('public/assets/admin/img/dashboard/top_deliveryman.svg')}}" alt="">
                <h4>{{translate('No Delivery Man in This Zone')}}</h4>
            </div>
        </div>
    @endif
</div>
<!-- End Body -->
