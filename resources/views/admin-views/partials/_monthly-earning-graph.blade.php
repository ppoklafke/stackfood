
<div class="card-body">
    <div class="row mb-3">
        <div class="col-12">
            @php($params=session('dash_params'))
            @if($params['zone_id']!='all')
                @php($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name)
            @else
            @php($zone_name=translate('All'))
        @endif
            <div class="d-flex flex-wrap justify-content-center align-items-center">
                <span class="h5 m-0 mr-3 fz--11 d-flex align-items-center mb-2 mb-md-0">
                    <span class="legend-indicator bg-0661CB"></span>
                    <span>{{translate('messages.admin_commission')}}</span> <span>:</span> <span>{{\App\CentralLogics\Helpers::format_currency(array_sum($commission))}}</span>
                </span>
                <span class="h5 m-0 fz--11 d-flex align-items-center mb-2 mb-md-0">
                    <span class="legend-indicator bg-sell-green"></span>
                    <span>{{translate('messages.total_sell')}}</span> <span>:</span> <span>{{\App\CentralLogics\Helpers::format_currency(array_sum($total_sell))}}</span>
                </span> &nbsp;&nbsp;
        @if (\App\CentralLogics\Helpers::subscription_check())
        <span class="h5 m-0 fz--11 d-flex align-items-center mb-2 mb-md-0">
            <span class="legend-indicator bg-subs-orange"></span>
            <span>{{translate('Subscription')}}</span> <span>:</span> <span>{{\App\CentralLogics\Helpers::format_currency(array_sum($total_subs))}}</span>
        </span>
        @endif

        </div>
          </div>
          <div class="col-12">
              <div class="text-right mt--xl--10"><span class="badge badge-soft--info">{{translate('messages.zone')}} : {{$zone_name}}</span>
              </div>
          </div>
    </div>

    <div class="d-flex align-items-center">
      <div class="chartjs-custom w-75 flex-grow-1">

          <div  data-commission="{{$commission[1]}},{{$commission[2]}},{{$commission[3]}},{{$commission[4]}},{{$commission[5]}},{{$commission[6]}},{{$commission[7]}},{{$commission[8]}},{{$commission[9]}},{{$commission[10]}},{{$commission[11]}},{{$commission[12]}}"

            data-subscription="{{$total_subs[1]}},{{$total_subs[2]}},{{$total_subs[3]}},{{$total_subs[4]}},{{$total_subs[5]}},{{$total_subs[6]}},{{$total_subs[7]}},{{$total_subs[8]}},{{$total_subs[9]}},{{$total_subs[10]}},{{$total_subs[11]}},{{$total_subs[12]}}"

            data-total_sell="{{$total_sell[1]}},{{$total_sell[2]}},{{$total_sell[3]}},{{$total_sell[4]}},{{$total_sell[5]}},{{$total_sell[6]}},{{$total_sell[7]}},{{$total_sell[8]}},{{$total_sell[9]}},{{$total_sell[10]}},{{$total_sell[11]}},{{$total_sell[12]}}"
                    id="updatingData"  ></div>

      </div>
    </div>

</div>




