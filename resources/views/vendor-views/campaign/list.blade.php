@extends('layouts.vendor.app')

@section('title', translate('messages.Campaign List'))

@push('css_or_js')
@endpush

@section('content')
    @php($restaurant_id = \App\CentralLogics\Helpers::get_restaurant_id())
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h2 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{ dynamicAsset('/public/assets/admin/img/resturant-panel/page-title/campaign.png') }}"
                                alt="public">
                        </div>
                        <span>
                            {{ translate('messages.campaign') }}
                        </span>
                        <span class="badge badge-soft-dark ml-2">{{ $campaigns->total() }}</span>
                    </h2>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <div class="card-header py-2">
                        <div class="search--button-wrapper justify-content-end">
                            <form >
                                <div class="input-group input--group">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                        placeholder="{{ translate('Ex : Search by Title name') }}" value="{{ request()?->search ?? null }}"
                                        aria-label="{{ translate('messages.search') }}">
                                    <button type="submit" class="btn btn--secondary">
                                        <i class="tio-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="w-20p">{{ translate('messages.sl') }}</th>
                                    <th class="w-20p">{{ translate('messages.title') }}</th>
                                    <th class="w-20p">{{ translate('messages.image') }}</th>
                                    <th>{{ translate('messages.date_duration') }}</th>
                                    <th>{{ translate('messages.time_duration') }}</th>
                                    <th class="w-20p">{{ translate('messages.status') }}</th>
                                    <th class="w-20p">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($campaigns as $key => $campaign)
                                    <tr>
                                        <td>{{ $key + $campaigns->firstItem() }}</td>
                                        <td>
                                            <span class="d-block font-size-sm text-body">
                                                {{ Str::limit($campaign['title'], 25, '...') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="overflow-hidden">
                                                <img class="initial-75 onerror-image" src="{{ $campaign['image_full_url'] }}"
                                                     data-onerror-image="{{dynamicAsset('public/assets/admin/img/160x160/img2.jpg')}}"  alt="image">
                                            </div>
                                        </td>
                                        <td>


                                            <span class="bg-gradient-light text-dark">{{$campaign->start_date?  \App\CentralLogics\Helpers::date_format($campaign->start_date)  : 'N/A'}}</span>

                                            <span class="bg-gradient-light text-dark">-</span>

                                            <span class="bg-gradient-light text-dark">{{$campaign->start_time?  \App\CentralLogics\Helpers::date_format($campaign->end_date) : 'N/A' }}</span>


                                        </td>
                                        <td>
                                            <span class="bg-gradient-light text-dark">{{$campaign->start_time?  \App\CentralLogics\Helpers::time_format($campaign->start_time). ' - ' .\App\CentralLogics\Helpers::time_format($campaign->end_time): 'N/A'}}</span>

                                        </td>


                                        <?php
                                        $restaurant_ids = [];
                                        $restaurant_status = '--';
                                        foreach ($campaign->restaurants as $restaurant) {
                                            if ($restaurant->id == $restaurant_id && $restaurant->pivot) {
                                                $restaurant_status = $restaurant->pivot->campaign_status;
                                            }
                                            $restaurant_ids[] = $restaurant->id;
                                        }
                                        ?>

                                        <td class="text-capitalize">
                                            @if ($restaurant_status == 'pending')
                                                <span class="badge badge-soft-info">
                                                    {{ translate('messages.not_approved') }}
                                                </span>
                                            @elseif($restaurant_status == 'confirmed')
                                                <span class="badge badge-soft-success">
                                                    {{ translate('messages.confirmed') }}
                                                </span>
                                            @elseif($restaurant_status == 'rejected')
                                                <span class="badge badge-soft-danger">
                                                    {{ translate('messages.rejected') }}
                                                </span>
                                            @else
                                                <span class="badge badge-soft-info">
                                                    {{ translate(str_replace('_', ' ', $restaurant_status)) }}
                                                </span>
                                            @endif

                                        </td>

                                        <td>
                                            @if ($restaurant_status == 'rejected')
                                            <span class="badge badge-pill badge-danger">{{ translate('Rejected') }}</span>
                                            @else
                                                @if (in_array($restaurant_id, $restaurant_ids))
                                                    <button type="button"
                                                        data-id="campaign-{{ $campaign['id'] }}" data-message="{{ translate('messages.alert_restaurant_out_from_campaign') }}"
                                                        title="You are already joined. Click to out from the campaign."
                                                        class="join--btn btn--danger text-white form-alert">{{ translate('Leave Campaign') }}</button>
                                                    <form
                                                        action="{{ route('vendor.campaign.remove-restaurant', [$campaign['id'], $restaurant_id]) }}"
                                                        method="GET" id="campaign-{{ $campaign['id'] }}">
                                                        @csrf
                                                    </form>
                                                @else
                                                    <button type="button" class="join--btn btn--primary text-white form-alert"
                                                       data-id="campaign-{{ $campaign['id'] }}" data-message="{{ translate('messages.alert_restaurant_join_campaign') }}"
                                                        title="Click to join the campaign">{{ translate('Join Campaign') }}</button>
                                                    <form
                                                        action="{{ route('vendor.campaign.addrestaurant', [$campaign['id'], $restaurant_id]) }}"
                                                        method="GET" id="campaign-{{ $campaign['id'] }}">
                                                        @csrf
                                                    </form>
                                                @endif
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        @if (count($campaigns) === 0)
                            <div class="empty--data">
                                <img src="{{ dynamicAsset('/public/assets/admin/img/empty.png') }}" alt="public">
                                <h5>
                                    {{ translate('no_data_found') }}
                                </h5>
                            </div>
                        @endif
                        <table class="page-area">
                            <tfoot>
                                {!! $campaigns->links() !!}
                            </tfoot>
                        </table>
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        "use strict";
        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function() {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function() {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

    </script>
@endpush
