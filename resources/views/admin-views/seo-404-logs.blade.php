@extends('layouts.admin.app')

@section('title',translate('seo'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

    @include('admin-views._new-nav')

    <!-- Main Content -->
    <div class="card">
        <div class="card-header flex-wrap gap-2">
            <div class="">
                <h4 class="title m-0">{{translate('404 Logs')}}</h4>
                <p class="m-0">{{translate("Logs track instances where users encounter 'page not found' errors on a website")}} <a href="" class="text-primary text-underline font-semibold">{{translate('Learn more')}}</a></p>
            </div>
            <div>
                <a href="" class="btn bg-soft-danger text-danger border border-danger">{{translate('Clear All Log')}}</a>
            </div>
        </div>
        <div class="card-body px-0">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer table-hover">
                <thead class="thead-light">
                    <tr>
                        <th class="w-95px">
                            <div class="d-flex align-items-center gap-2">
                                <span class="check-item-2">
                                    <input type="checkbox" name="url">
                                </span>
                                <span>URL</span>
                            </div>
                        </th>
                        <th class="w-45px text-center">Hits</th>
                        <th class="w-200px text-center">Last Hit Date</th>
                        <th class="text-center w-60px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="check-item-2">
                                    <input type="checkbox" name="url">
                                </span>
                                <a href="" class="text-primary text-underline">https://Stackfood.6amtech.com </a>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="font-semibold text-title">2</span>
                        </td>
                        <td class="text-center">
                            <span>July, 2024 <small>11:30am</small></span>
                        </td>
                        <td>    
                            <div class="d-flex flex-wrap justify-content-center">
                                <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:">
                                    <i class="tio-delete-outlined"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="check-item-2">
                                    <input type="checkbox" name="url">
                                </span>
                                <a href="" class="text-primary text-underline">https://Stackfood.6amtech.com </a>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="font-semibold text-title">2</span>
                        </td>
                        <td class="text-center">
                            <span>July, 2024 <small>11:30am</small></span>
                        </td>
                        <td>    
                            <div class="d-flex flex-wrap justify-content-center">
                                <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:">
                                    <i class="tio-delete-outlined"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="check-item-2">
                                    <input type="checkbox" name="url">
                                </span>
                                <a href="" class="text-primary text-underline">https://Stackfood.6amtech.com </a>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="font-semibold text-title">2</span>
                        </td>
                        <td class="text-center">
                            <span>July, 2024 <small>11:30am</small></span>
                        </td>
                        <td>    
                            <div class="d-flex flex-wrap justify-content-center">
                                <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:">
                                    <i class="tio-delete-outlined"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="page-area px-4">
                <div class="d-flex align-items-center justify-content-end">
                    <div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                                    <span class="page-link" aria-hidden="true">‹</span>
                                </li>
                                <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" rel="next" aria-label="Next »">›</a>
                                </li>
                            </ul>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>
    

</div>
@endsection

@push('script_2')
@endpush
