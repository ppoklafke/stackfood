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
                <h4 class="title m-0">{{translate('Page of Robots Meta Content')}}</h4>
                <p class="m-0">{{translate("Website's performance, indexing status, and search visibility.")}}</p>
            </div>
            <div>
                <button class="btn add-outline-btn" data-toggle="modal" data-target="#modal ">
                    <img src="{{dynamicAsset('public/assets/admin/img/add-btn.png')}}" alt="">
                    <span class="txt">Add Page</span>
                    <span data-toggle="tooltip" title="add new page">
                        <img src="{{dynamicAsset('public/assets/admin/img/query.png')}}" alt="">
                    </span>
                </button>
            </div>
        </div>
        <div class="card-body px-0">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer">
                <thead class="thead-light">
                    <tr>
                        <th>SL</th>
                        <th>Pages</th>
                        <th>URL</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>
                            <span class="font-semibold text-title">Brands</span>
                        </td>
                        <td>
                            <a href="" class="text-primary text-underline">https://Stackfood.6amtech.com </a>
                        </td>
                        <td>
                            <div class="text-center">
                                <a href="" class="btn btn--primary btn-outline-primary add-content-btn"><i class="tio-add"></i> <span>Add Content</span></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>
                            <span class="font-semibold text-title">Category</span>
                        </td>
                        <td>
                            <a href="" class="text-primary text-underline">https://Stackfood.6amtech.com </a>
                        </td>
                        <td>
                            <div class="text-center">
                                <a href="" class="btn btn--primary btn-outline-primary edit-content-btn"><i class="tio-add"></i> <span>Edit Content</span></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="page-area">
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

    <!-- modal -->
    <div class="modal fadeIn show" tabindex="-1" role="dialog" id="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title w-100 text-center">{{translate('Add Page')}}</h2>
                    <div type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.47 2 2 6.47 2 12C2 17.53 6.47 22 12 22C17.53 22 22 17.53 22 12C22 6.47 17.53 2 12 2ZM16.3 16.3C16.2075 16.3927 16.0976 16.4662 15.9766 16.5164C15.8557 16.5666 15.726 16.5924 15.595 16.5924C15.464 16.5924 15.3343 16.5666 15.2134 16.5164C15.0924 16.4662 14.9825 16.3927 14.89 16.3L12 13.41L9.11 16.3C8.92302 16.487 8.66943 16.592 8.405 16.592C8.14057 16.592 7.88698 16.487 7.7 16.3C7.51302 16.113 7.40798 15.8594 7.40798 15.595C7.40798 15.4641 7.43377 15.3344 7.48387 15.2135C7.53398 15.0925 7.60742 14.9826 7.7 14.89L10.59 12L7.7 9.11C7.51302 8.92302 7.40798 8.66943 7.40798 8.405C7.40798 8.14057 7.51302 7.88698 7.7 7.7C7.88698 7.51302 8.14057 7.40798 8.405 7.40798C8.66943 7.40798 8.92302 7.51302 9.11 7.7L12 10.59L14.89 7.7C14.9826 7.60742 15.0925 7.53398 15.2135 7.48387C15.3344 7.43377 15.4641 7.40798 15.595 7.40798C15.7259 7.40798 15.8556 7.43377 15.9765 7.48387C16.0975 7.53398 16.2074 7.60742 16.3 7.7C16.3926 7.79258 16.466 7.90249 16.5161 8.02346C16.5662 8.14442 16.592 8.27407 16.592 8.405C16.592 8.53593 16.5662 8.66558 16.5161 8.78654C16.466 8.90751 16.3926 9.01742 16.3 9.11L13.41 12L16.3 14.89C16.68 15.27 16.68 15.91 16.3 16.3Z" fill="#BFBFBF"/>
                        </svg>
                    </div>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">{{translate('Page Name')}}</label>
                            <input type="text" class="form-control" placeholder="Enter Page Name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{translate('Page URL')}}</label>
                            <input type="text" class="form-control" placeholder="Enter URL">
                        </div>
                        <div class="mb-3 btn--container justify-content-end">
                            <button type="submit" class="btn btn--primary">Save</button>
                        </div>
                    </form>
                </div>
                <div class="btn--container"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
@endpush
