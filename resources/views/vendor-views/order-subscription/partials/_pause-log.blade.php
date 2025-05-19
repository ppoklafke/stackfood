@php
$logs = $subscription->pause()->latest()->paginate(config('default_pagination'));
@endphp
<div class="card">
<div class="card-header">
    <h5 class="card-header-title">{{translate('messages.subscription_pause_logs')}}<span class="badge badge-soft-dark ml-2">{{$logs->total()}}</span></h5>
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
            <th class="w-20p">{{translate('messages.#')}}</th>
            <th class="w-40p text-center">{{translate('messages.from')}}</th>
            <th class="w-40p text-center">{{translate('messages.to')}}</th>
        </tr>
        </thead>

        <tbody>
        @foreach($logs as $key=>$log)
            <tr>
                <td>{{$key+$logs->firstItem()}}</td>
                <td class="table-column-pl-0 text-center">
                    {{  Carbon\Carbon::parse($log->from)->locale(app()->getLocale())->translatedFormat('d M Y ') }}
                </td>
                <td class="table-column-pl-0 text-center">
                    {{  Carbon\Carbon::parse($log->to)->locale(app()->getLocale())->translatedFormat('d M Y ') }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <!-- Footer -->
    <div class="card-footer">
        <!-- Pagination -->
    {!! $logs->links() !!}
    <!-- End Pagination -->
    </div>
    <!-- End Footer -->
</div>
</div>
