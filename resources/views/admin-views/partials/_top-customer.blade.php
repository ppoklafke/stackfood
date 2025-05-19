<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <img src="{{dynamicAsset('/public/assets/admin/img/dashboard/statistics.png')}}" alt="dashboard" class="card-header-icon">
        <span>{{translate('messages.top_customers')}}</span>
    </h5>
    @php($params=session('dash_params'))
    @if($params['zone_id']!='all')
        @php($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name)
    @else
    @php($zone_name = translate('All'))
    @endif
    <span class="badge badge-soft--info my-2">{{ translate('messages.zone') }} : {{ $zone_name }}</span>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="row">
        @foreach($top_customer as $key=>$item)
            <div class="col-6 col-md-4 mt-2 initial-40 redirect-url" data-url="{{route('admin.customer.view',[$item['user_id']])}}">
                <div class="grid-card min-height-100">
                    <label class="label_1">{{ translate('messages.orders') }} : {{$item['count']}}</label>
                    <div class="text-center py-3 mt-6">
                        <img class="initial-41 onerror-image" data-onerror-image="{{dynamicAsset('public/assets/admin/img/160x160/img1.jpg')}}"
                             src="{{ $item->image_full_url ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg')}}">
                    </div>
                    <div class="text-center mt-2">
                        <span class="fz-10px">{{$item->customer['f_name']?? translate('messages.Not_exist')}}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<!-- End Body -->
