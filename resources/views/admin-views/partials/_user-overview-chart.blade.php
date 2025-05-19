@php($params = session('dash_params'))
@if ($params['zone_id'] != 'all')
    @php($zone_name = \App\Models\Zone::where('id', $params['zone_id'])->first()->name)
@else
@php($zone_name=translate('All'))
@endif



<div class="card-body">
     <div class="row justify-content-end">
        <div class="col-sm-6 col-md-4">
            <div class="ml-auto mb-2 pb-xl-5">
            <select class="custom-select user-overview-stats-update" name="user_overview">
                <option
                    value="overall" {{$params['user_overview'] == 'overall'?'selected':''}}>
                    {{translate('messages.Overall')}}
                </option>

                <option
                    value="this_year" {{$params['user_overview'] == 'this_year'?'selected':''}}>
                    {{translate('This_Year')}}
                </option>
                <option
                    value="this_month" {{$params['user_overview'] == 'this_month'?'selected':''}}>
                    {{translate('This_Month')}}
                </option>
                <option
                    value="this_week" {{$params['user_overview'] == 'this_week'?'selected':''}}>
                    {{translate('This_Week')}}
                </option>

                <option
                value="today" {{$params['user_overview'] == 'today'?'selected':''}}>
                {{translate('Today')}}
            </option>
            </select>
        </div>
        </div>
    </div>

    @if($data['customer'] || $data['restaurants'] || $data['delivery_man'])

     <div class="position-relative" >
        <div id="user-overview-board">

            <div class="chartjs-custom mx-auto">
                <div data-id="#user-overview" data-value="{{ $data['customer'].','. $data['restaurants'].','. $data['delivery_man'] }}"
                data-labels="{{translate('messages.Customer')}}, {{translate('messages.Restaurant')}},{{ translate('messages.Delivery_man') }}" id="user-overview"></div>
            </div>
        </div>
    </div>
    @else
    <div class="d-flex justify-content-center align-items-center h-100 min-h-200">
        <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
            <img src="{{dynamicAsset('public/assets/admin/img/dashboard/user_stat.svg')}}" alt="">
            <h4>{{translate('No user available in this zone')}}</h4>
        </div>
    </div>
    @endif
</div>

