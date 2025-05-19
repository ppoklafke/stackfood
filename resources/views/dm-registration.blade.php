
@extends('layouts.landing.app')
@section('title', translate('messages.deliveryman_registration'))
@push('css_or_js')
        <link rel="stylesheet" href="{{ dynamicAsset('public/assets/landing') }}/css/style.css" />
@endpush

@section('content')
     <!-- Page Header Gap -->
     <div class="h-148px"></div>
     <!-- Page Header Gap -->

    <section class="m-0">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <h1 class="page-header-title text-center"><i class="tio-add-circle-outlined"></i>
                            {{ translate('messages.deliveryman_application') }}</h1>
                    </div>
                </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
                <div class="card shadow-sm col-12">
                    <form class="card-body" id="deliveryman-form" method="post" enctype="multipart/form-data">
                        @csrf
                        <small class="nav-subtitle">{{ translate('messages.deliveryman_info') }}</small>
                        <br>
                        <div class="row mt-3">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.first_name') }}</label>
                                    <input type="text" name="f_name" class="form-control"
                                        placeholder="{{ translate('messages.first_name') }}" required
                                        value="{{ old('f_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.last_name') }}</label>
                                    <input type="text" name="l_name" class="form-control"
                                        placeholder="{{ translate('messages.last_name') }}"
                                        value="{{ old('l_name') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.email') }}</label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="{{ translate('messages.Ex :') }} ex@example.com" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.deliveryman_type') }}</label>
                                    <select name="earning" class="form-control">
                                        <option value="1">{{ translate('messages.freelancer') }}</option>
                                        <option value="0">{{ translate('messages.salary_based') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.zone') }}</label>
                                    <select name="zone_id" class="form-control js-select2-custom" required
                                        data-placeholder="{{ translate('messages.select_zone') }}">
                                        <option value="" readonly="true" hidden="true">{{ translate('messages.select_zone') }}</option>
                                        @foreach (\App\Models\Zone::active()->get(['id','name']) as $zone)
                                            @if (isset(auth('admin')->user()->zone_id))
                                                @if (auth('admin')->user()->zone_id == $zone->id)
                                                    <option value="{{ $zone->id }}" selected>{{ $zone->name }}
                                                    </option>
                                                @endif
                                            @else
                                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                  <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.Vehicle') }}</label>
                                        <select name="vehicle_id" class="form-control js-select2-custom h--45px" required
                                            data-placeholder="{{ translate('messages.select_vehicle') }}">
                                            <option value="" readonly="true" hidden="true">{{ translate('messages.select_vehicle') }}</option>
                                            @foreach (\App\Models\Vehicle::where('status',1)->get(['id','type']) as $v)
                                                        <option value="{{ $v->id }}" >{{ $v->type }}
                                                        </option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity_type') }}</label>
                                    <select name="identity_type" class="form-control">
                                        <option value="passport">{{ translate('messages.passport') }}</option>
                                        <option value="driving_license">{{ translate('messages.driving_license') }}</option>
                                        <option value="nid">{{ translate('messages.nid') }}</option>
                                        <option value="restaurant_id">{{ translate('messages.restaurant_id') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity_number') }}</label>
                                    <input type="text" name="identity_number" class="form-control"
                                        value="{{ old('identity_number') }}" placeholder="{{ translate('messages.Ex :') }} DH-23434-LS" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity_image') }}</label>
                                    <div>
                                        <div class="row" id="coba"></div>
                                    </div>
                                </div>
                            </div>
                        </div>





                        @if (isset($page_data) && count($page_data) > 0 )
                        <div class="col-lg-12">
                                    <h5 class="card-title my-1 text--primary text-capitalize mt-4 pt-1">
                                        {{ translate('messages.Additional_Data') }}
                                    </h5>
                                    <div class="row">
                                        @foreach ( data_get($page_data,'data',[])  as $key=>$item)

                                            @if (!in_array($item['field_type'], ['file' , 'check_box']) )

                                                <div class="col-md-4 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="{{ $item['input_data'] }}">{{translate($item['input_data'])  }}</label>
                                                        <input id="{{ $item['input_data'] }}" {{ $item['is_required']  == 1? 'required' : '' }} type="{{ $item['field_type'] }}" name="additional_data[{{ $item['input_data'] }}]" class="form-control h--45px"
                                                            placeholder="{{ translate($item['placeholder_data']) }}"
                                                            value="" >
                                                    </div>
                                                </div>

                                                @elseif ($item['field_type'] == 'check_box' )
                                                    @if ($item['check_data'] != null)
                                                    <div class="col-md-4 col-12">
                                                    <div class="form-group">
                                                        <label for=""> {{translate($item['input_data'])  }} </label>
                                                        @foreach ($item['check_data'] as $k=> $i)
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" name="additional_data[{{ $item['input_data'] }}][]"  class="form-check-input" value="{{ $i }}"> {{ translate($i) }}
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    </div>
                                                    @endif



                                                @elseif ($item['field_type'] == 'file' )
                                                    @if ($item['media_data'] != null)
                                                    <?php
                                                    $image= '';
                                                    $pdf= '';
                                                    $docs= '';
                                                        if(data_get($item['media_data'],'image',null)){
                                                            $image ='.jpg, .jpeg, .png,';
                                                        }
                                                        if(data_get($item['media_data'],'pdf',null)){
                                                            $pdf =' .pdf,';
                                                        }
                                                        if(data_get($item['media_data'],'docs',null)){
                                                            $docs =' .doc, .docs, .docx' ;
                                                        }
                                                        $accept = $image.$pdf. $docs ;
                                                    ?>
                                                        <div class="col-md-4 col-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="{{ $item['input_data'] }}">{{translate($item['input_data'])  }}</label>
                                                                <input id="{{ $item['input_data'] }}" {{ $item['is_required']  == 1? 'required' : '' }} type="{{ $item['field_type'] }}" name="additional_documents[{{ $item['input_data'] }}][]" class="form-control h--45px"
                                                                    placeholder="{{ translate($item['placeholder_data']) }}"
                                                                        {{ data_get($item['media_data'],'upload_multiple_files',null) ==  1  ? 'multiple' : '' }} accept="{{ $accept ??  '.jpg, .jpeg, .png'  }}"
                                                                    >
                                                            </div>
                                                        </div>

                                                    @endif
                                            @endif
                                        @endforeach
                                    </div>
                        </div>
                        @endif










                        <small class="nav-subtitle text-capitalize">{{ translate('messages.login_info') }}</small>
                        <br>
                        <div class="row mt-3">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label" for="phone">{{ translate('messages.phone') }}</label>
                                    <div class="input-group">
                                        <input type="tel" name="phone" id="phone" placeholder="{{ translate('messages.Ex :') }} 017********"
                                            class="form-control" value="{{ old('tel') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.password') }}
                                        <span class="input-label-secondary ps-1" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                    </label>

                                    <input type="text" id="password" name="password" class="form-control"
                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                    placeholder="{{ translate('messages.Ex :') }} Abc@1234"
                                        value="{{ old('password') }}" required>

                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img class="initial-95" style="max-width: 130px" id="viewer"
                                            src="{{ dynamicAsset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label  class="input-label">{{ translate('messages.deliveryman_image') }}<small class="text-danger">* ( {{ translate('messages.ratio') }} 1:1 )</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="form-control"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary float-right submitBtn">{{ translate('messages.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>

    </section>
  <!-- Page Header Gap -->
  <div class="h-148px"></div>
  <!-- Page Header Gap -->
@endsection

@push('script_2')
    {{-- <script src="{{ dynamicAsset('public/assets/admin') }}/js/toastr.js"></script>
    {!! Toastr::message() !!} --}}

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                CloseButton: true,
                ProgressBar: true
                });
            @endforeach
        </script>
    @endif

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this);
        });

        $('#deliveryman-form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('deliveryman.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.errors) {
                        $('#loading').hide();
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        $('#loading').hide();
                        toastr.success('{{ translate('application_placed_successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });
    </script>

    <script src="{{ dynamicAsset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ dynamicAsset('public/assets/admin/img/400x400/img2.jpg') }}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error('{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ translate('messages.file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>

@endpush
