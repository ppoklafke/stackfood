@extends('layouts.admin.app')
@section('title',translate('Gallery'))

@section('content')
<div class="content container-fluid">
    <!-- Page Heading -->
    <div class="page-header d-flex flex-wrap justify-content-between">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{dynamicAsset('public/assets/admin/img/folder-logo.png')}}" class="w--26" alt="">
            </span>
            <span>
                {{translate('messages.file_manager')}}
            </span>
        </h1>
    </div>
    <div class="mb-4 mt-2">
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 __gap-12px">
                <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
                    <!-- Nav -->
                    <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
                        <li class="nav-item">
                            <a class="nav-link {{ $storage == 'local' ? 'active' : '' }}"
                               href="{{ route('admin.file-manager.index', ['folder_path'=>'cHVibGlj', 'storage'=>'local']) }}">{{translate('local_storage')}}</a>
                        </li>
                        @if(\App\CentralLogics\Helpers::getDisk() == 's3')
                            <li class="nav-item">
                                <a class="nav-link {{ $storage == 's3' ? 'active' : '' }}"
                                   href="{{ route('admin.file-manager.index', ['folder_path'=>'cHVibGlj', 'storage'=>'s3']) }}">{{translate('S3_bucket')}}</a>
                            </li>
                        @endif
                    </ul>
                    <!-- End Nav -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @php
                        $pwd = explode('/',base64_decode($folder_path));
//                        $awsUrl = config('filesystems.disks.s3.url');
//                        $awsBucket = config('filesystems.disks.s3.bucket');
                    @endphp
                    <h5 class="card-title">{{end($pwd) == ''?'Root':end($pwd)}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{count($data)}}</span></h5>
                    <div class="d-flex flex-wrap justify-content-between">
                        <button type="button" class="btn btn--primary modalTrigger mr-3" data-toggle="modal" data-target="#exampleModal">
                            <i class="tio-add-circle"></i>
                            <span class="text">{{translate('messages.add_new')}}</span>
                        </button>
                        <a class="btn btn-sm badge-soft-primary" href="{{url()->previous()}}"><i class="tio-arrow-long-left mr-2"></i>{{translate('messages.back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($data as $key=>$file)
                            <div class="col-6 col-sm-auto">
                                @if($file['type']=='folder')
                                    <a class="btn p-0 btn--folder"  href="{{route('admin.file-manager.index', [base64_encode($file['path']),$storage])}}">
                                        <img class="img-thumbnail border-0 p-0" src="{{dynamicAsset('public/assets/admin/img/folder.png')}}" alt="">
                                        <p>{{Str::limit($file['name'],10)}}</p>
                                    </a>
                                @elseif($file['type']=='file')
                                    <div class="folder-btn-item mx-auto">
                                        <button class="btn p-0 w-100" title="{{$file['name']}}">
                                            <div class="gallary-card">
                                                <img src="{{$storage == 's3'? Storage::disk($storage)->url($file['path']) : dynamicAsset('storage/app/'.$file['path'])}}" alt="{{$file['name']}}" class="w-100 rounded">
{{--                                                <img src="{{$storage == 's3'? rtrim($awsUrl, '/').'/'.ltrim($awsBucket.'/'.$file['path'], '/') : dynamicAsset('storage/app/'.$file['path'])}}" alt="{{$file['name']}}" class="w-100 rounded">--}}
                                            </div>
                                            <small class="overflow-hidden text-title">{{Str::limit($file['name'],10)}}</small>
                                        </button>
                                        <div class="btn-items">
                                            <a href="#" title="{{translate('View Image')}}" data-toggle="tooltip" data-placement="left">
                                                <img src="{{dynamicAsset('/public/assets/admin/img/download/view.png')}}" data-toggle="modal" data-target="#imagemodal{{$key}}" alt="">
                                            </a>
                                            <a href="#" title="{{translate('Copy Link')}}" class="copy-test" data-toggle="tooltip" data-placement="left" data-file-path="{{$file['db_path']}}">
                                                <img src="{{dynamicAsset('/public/assets/admin/img/download/link.png')}}" alt="">
                                            </a>
                                            <a title="{{translate('Download')}}" data-toggle="tooltip" data-placement="left" href="{{route('admin.file-manager.download', [base64_encode($file['path']),$storage])}}">
                                                <img src="{{dynamicAsset('/public/assets/admin/img/download/download.png')}}" alt="">
                                            </a>
                                            <form action="{{route('admin.file-manager.destroy',base64_encode($file['path']))}}" method="post"  class="form-submit-warning">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" title="{{translate('Delete')}}" data-toggle="tooltip" data-placement="left"><i class="tio-delete"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="imagemodal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog max-w-640">
                                            <div class="modal-content">
                                                <button type="button" class="close right-top-close-icon" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                <div class="modal-header p-1">
                                                    <div class="gallery-modal-header w-100">
                                                        <span>{{$file['name']}}</span>
                                                        <a href="#" class="d-block ml-auto copy-test" data-file-path="{{$file['db_path']}}">
                                                            {{translate('Copy Path')}} <i class="tio-link"></i>
                                                        </a>
                                                        <a class="d-block" href="{{route('admin.file-manager.download', [base64_encode($file['path']),$storage])}}">
                                                            {{translate('Download')}} <i class="tio-download-to"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="modal-body p-1 pt-0">
                                                    <img src="{{$storage == 's3'? Storage::disk($storage)->url($file['path']) : dynamicAsset('storage/app/'.$file['path'])}}" class="w-100">
{{--                                                    <img src="{{$storage == 's3'? rtrim($awsUrl, '/').'/'.ltrim($awsBucket.'/'.$file['path'], '/') : dynamicAsset('storage/app/'.$file['path'])}}" class="w-100">--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="indicator"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{translate('messages.upload_file')}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.file-manager.image-upload')}}"  method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="path" value = "{{base64_decode($folder_path)}}" hidden>
                        <input type="text" name="disk" value = "{{$storage}}" hidden>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" name="images[]" id="customFileUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" multiple>
                                <label class="custom-file-label" for="customFileUpload">{{translate('messages.choose_images')}}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" name="file" id="customZipFileUpload" class="custom-file-input"
                                       accept=".zip">
                                <label class="custom-file-label" id="zipFileLabel" for="customZipFileUpload">{{translate('messages.upload_zip_file')}}</label>
                            </div>
                        </div>

                        <div class="row" id="files"></div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="{{translate('messages.upload')}}">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade" id="how-it-works">
        <div class="modal-dialog modal-lg warning-modal">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h3 class="modal-title mb-3">{{translate('Check_how_the_settings_works')}}</h3>
                    </div>
                    <img src="{{dynamicAsset('/public/assets/admin/img/zone-instruction.png')}}" alt="admin/img" class="w-100">
                    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-end">
                        <div class="btn--container justify-content-end">
                            <button id="reset_btn" type="reset" class="btn btn--reset" data-dismiss="modal">{{translate("Close")}}</button>
                            <button type="submit" class="btn btn--primary" data-dismiss="modal">{{translate('Got_It')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/file-manager.js"></script>
    <script>
        "use strict";

        function copy_test(copyText) {
            navigator.clipboard.writeText(copyText);

            toastr.success('{{ translate('File_path_copied_successfully!') }}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        function form_submit_warrning(e) {
            e.preventDefault();
            Swal.fire({
                title: "{{translate('Are you sure?')}}",
                text: "{{translate('you_want_to_delete')}}",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{translate('messages.no')}}',
                confirmButtonText: '{{translate('messages.yes')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    e.target.submit();
                    // this.submit();
                }
            })
        };

    </script>
@endpush
