@extends('layouts.admin.app')
@section('title', translate('Join_Us_Form_Setup'))
@section('3rd_party')
    active
@endsection
@section('reg_page')
    active
@endsection
@section('content')
@php(  $page_data =  isset($page_data) ? json_decode($page_data ,true)  :[])
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset('/public/assets/admin/img/join_us.png')}}" alt="">
                {{translate('New_Join_Request_Form_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="js-nav-scroller hs-nav-scroller-horizontal">
                    <!-- Nav -->
                    <ul class="nav nav-tabs mb-3 border-0 nav--tabs">
                        <li class="nav-item">
                            <a class="nav-link {{  Request::is('admin/business-settings/restaurant/join-us/*') ? 'active' : '' }} " href="{{ route('admin.business-settings.restaurant_page_setup') }}"   aria-disabled="true">{{translate('messages.Restaurant_Registration_Form')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{  Request::is('admin/business-settings/deliveryman/join-us/*') ? 'active' : '' }}" href="{{  route('admin.business-settings.delivery_man_page_setup') }}"  aria-disabled="true">{{translate('messages.DeliveryMan_Registration_Form')}}</a>
                        </li>

                    </ul>
                    <!-- End Nav -->
                </div>
            </div>
        </div>


        <div class="card mt-3">
            <div class="card-header gap-2 flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="mb-0">{{ translate('Default_Input_Fields') }}</h5>
                </div>
            </div>
                <div class="card-body">
                    <ul  class="requirements-info-list">
                        <li > {{translate('First_Name')}} </li>
                        <li > {{translate('Last_name')}} </li>
                        <li > {{translate('Email')}} </li>
                        <li > {{translate('Deliveryman_Type')}} </li>
                        <li > {{translate('Zone')}} </li>
                        <li > {{translate('Vehicle')}} </li>
                        <li > {{translate('Identity_Type')}} </li>
                        <li > {{translate('Identity_Number')}} </li>
                        <li > {{translate('Identity_Image')}} </li>
                        <li > {{translate('Phone')}} </li>
                        <li > {{translate('Password')}} </li>
                        <li > {{translate('Deliveryman_Image')}} </li>
                    </ul>
                </div>

        </div>

        <form action="{{ route('admin.business-settings.delivery_man_page_setup_update') }}" method="POST">
            @csrf
            <div class="card mt-3">
                <div class="card-header gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="mb-0">{{ translate('Custom_Input_Fields') }}</h5>
                    </div>
                    <a href="javascript:"  class="btn btn-primary add-input-data-fields-group"><i class="tio-add"></i> {{ translate('Add_New_Field') }} </a>
                </div>
                <div class="card-body">
                    <div class="customer-input-fields-section" id="customer-input-fields-section">

                        @if( isset($page_data)  &&  count($page_data)  > 0)

                            @foreach ( data_get($page_data,'data',[])  as $key=>$item)
                                @php($cRandomNumber = rand())
                                @php($count = $key)

                                <div class="row align-items-end" id="{{ $key }}">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="fieldTypeSelect_{{$key}}"> {{ translate('messages.Type')}} </label>
                                            <select id="fieldTypeSelect_{{$key}}" class="form-control fieldTypeSelect" name="field_type[]"  data-key="{{ $key }}" required>
                                                <option  {{ $item['field_type'] ==  'text' ?  'selected' : '' }}  value="text">{{ translate('messages.Text')}}</option>
                                                <option  {{ $item['field_type'] ==  'number' ?  'selected' : '' }} value="number">{{ translate('messages.Number')}}</option>
                                                <option  {{ $item['field_type'] ==  'date' ?  'selected' : '' }} value="date">{{ translate('messages.Date')}}</option>
                                                <option {{ $item['field_type'] ==  'email' ?  'selected' : '' }}  value="email">{{ translate('messages.Email')}}</option>
                                                <option   {{ $item['field_type'] ==  'phone' ?  'selected' : '' }} value="phone">{{ translate('messages.Phone')}}</option>
                                                <option   {{ $item['field_type'] ==  'file' ?  'selected' : '' }} value="file">{{ translate('messages.File_Upload')}}</option>
                                                <option   {{ $item['field_type'] ==  'check_box' ?  'selected' : '' }} value="check_box">{{ translate('messages.Check_Box')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="input_data_{{$key}}" class="title_color">{{ translate('Input_Field_Title') }}</label>
                                            <input id="input_data_{{$key}}" type="text" name="input_data[]" class="form-control" placeholder="{{ translate('Ex:Enter_Input_Field_Title') }}" required value="{{ ucwords(str_replace('_',' ',$item['input_data'])) }}">
                                        </div>
                                    </div>
                                    <div class=" hide_place_Holder_{{ $key }}  {{ $item['field_type'] ==  'check_box' || $item['field_type'] ==  'file' ? 'd-none': "" }} col-md-3">
                                        <div class="form-group">
                                            <label for="placeholder_data{{$key}}"  class="title_color">{{ translate('place_Holder') }}</label>
                                            <input  id="placeholder_data{{$key}}" type="text" name="placeholder_data[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('enter_name') }}"  value="{{ $item['placeholder_data'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between gap-2">
                                                <div class="form-check text-start mb-3">

                                                    <label class="form-check-label text-dark" for="is_required_{{ $key }}">
                                                        <input type="checkbox" class="form-check-input" id="is_required_{{ $key }}" value="{{ $count }}" name="is_required[{{ $key }}]" {{ (isset($item['is_required']) && $item['is_required']) == 1 ? 'checked':'' }}> {{ translate('is_Required') }} ?
                                                    </label>
                                                </div>

                                                <a class="btn action-btn btn--danger btn-outline-danger remove-input-fields-group" data-id="{{ $key }}" title="Delete" >
                                                    <i class="tio-delete-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($item['field_type'] == 'file' )
                                    <div class="row mb-2" id="file_rows_data_{{ $key }}">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" {{  data_get($item['media_data'],'upload_multiple_files')  == 1 ? 'checked' : '' }} name="upload_multiple_files[{{ $key }}]" type="checkbox" value="{{ $key }}" id="upload_multiple_files_{{ $key }}" >
                                                <label class="form-check-label" for="upload_multiple_files_{{ $key }}">
                                                    {{ translate('Upload_Multiple_Files') }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md=4">
                                            <div>
                                                <label for=""> {{ translate('File_Format') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" {{  data_get($item['media_data'],'image')  == 1 ? 'checked' : '' }}  type="checkbox" id="inlineCheckbox1_{{ $key }}" value="{{ $key }}" name="image[{{ $key }}]">
                                                <label class="form-check-label" for="inlineCheckbox1_{{ $key }}">{{ translate('JPG,_JPEG_or_PNG') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" {{  data_get($item['media_data'],'pdf')  == 1 ? 'checked' : '' }}  type="checkbox" id="inlineCheckbox2_{{ $key }}" value="{{ $key }}" name="pdf[{{ $key }}]">
                                                <label class="form-check-label" for="inlineCheckbox2_{{ $key }}">{{ translate('PDF') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" {{  data_get($item['media_data'],'docs')  == 1 ? 'checked' : '' }}  type="checkbox" id="inlineCheckbox3_{{ $key }}" value="{{ $key }}" name="docs[{{ $key }}]">
                                                <label class="form-check-label" for="inlineCheckbox3_{{ $key }}">{{ translate('DOCS') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                @elseif ($item['field_type'] == 'check_box' )

                                    @foreach ($item['check_data'] ?? []  as $k => $check_data)
                                        <div class="delete_{{ $key }}">
                                            <div class="row mb-2 " id="check_box_data_{{ $key }}_{{ $k }}">
                                                <div class="col-md-3 mt-3" >
                                                    <label class="form-check-label" for="">
                                                        <h6>  {{ translate('Add_Checkmark_Option') }} </h6>
                                                    </label>
                                                </div>
                                                <div class="col-md-3 mt-3" >
                                                    <h6> {{ translate('messages.Option_Name') }}</h6>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>
                                                            <input type="text" name="check_box_input[{{ $key }}][]" class="form-control" placeholder="{{ translate('Ex:Enter_option') }}" required value="{{ $check_data }}">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            @if ($k == 0 )
                                                                <a class="btn btn-sm btn-outline-primary add-check-box"  data-id="{{ $key }}" title="{{ translate('add_new') }}" >
                                                                    {{ translate("messages.Add") }} +
                                                                    @else
                                                                        <a class="btn action-btn btn--danger btn-outline-danger remove-check-box" data-parent-id="{{ $key }}" data-child-id="{{ $k }}"  title="Delete" >
                                                                            <i class="tio-delete-outlined"></i>
                                                                        </a>
                                                                    @endif
                                                                </a>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div id="check_box_data_{{ $key }}_{{ $key+3 *99 }}"> </div>
                                @endif


                                <div id="file-data_{{$key }}"></div>
                                <div id="check_box_data_main_{{$key }}"></div>
                            @endforeach
                        @else

                            @php($cRandomNumber = 0)
                            <div class="row align-items-end" id="{{ $cRandomNumber }}">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label  for="fieldTypeSelect{{$cRandomNumber}}" >{{ translate('messages.Type')}} </label>
                                        <select id="fieldTypeSelect{{$cRandomNumber}}" class="form-control fieldTypeSelect" data-key="{{ $cRandomNumber }}" name="field_type[]" required>
                                            <option value="text">{{ translate('messages.Text')}}</option>
                                            <option value="number">{{ translate('messages.Number')}}</option>
                                            <option value="date">{{ translate('messages.Date')}}</option>
                                            <option value="email">{{ translate('messages.Email')}}</option>
                                            <option value="phone">{{ translate('messages.Phone')}}</option>
                                            <option value="check_box">{{ translate('messages.Check_Box')}}</option>
                                            <option value="file">{{ translate('messages.File_Upload')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label  for="input_data{{$cRandomNumber}}" class="title_color">{{ translate('Input_Field_Title') }}</label>
                                        <input id="input_data{{$cRandomNumber}}"  type="text" name="input_data[]" class="form-control" placeholder="{{ translate('Ex:Enter_Input_Field_Title') }}" required value="">
                                    </div>
                                </div>
                                <div class=" hide_place_Holder_{{ $cRandomNumber }} col-md-3">
                                    <div class="form-group">
                                        <label for="placeholder_data_{{$cRandomNumber}}" class="title_color">{{ translate('place_Holder') }}</label>
                                        <input id="placeholder_data_{{$cRandomNumber}}" type="text" name="placeholder_data[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('enter_name') }}"  value="">
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between gap-2">
                                            <div class="form-check text-start mb-3">

                                                <label class="form-check-label text-dark" for="is_required{{ $cRandomNumber+1 }}">
                                                    <input type="checkbox" class="form-check-input" value="1"  id="is_required{{ $cRandomNumber+1 }}" name="is_required[0]" > {{ translate('is_Required') }} ?
                                                </label>
                                            </div>

                                            <a class="btn action-btn btn--danger btn-outline-danger remove-input-fields-group" data-id="{{ $cRandomNumber }}" title="Delete">
                                                <i class="tio-delete-outlined"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="file-data_{{$cRandomNumber }}"> </div>
                            <div id="check_box_data_main_{{$cRandomNumber }}"></div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="btn--container justify-content-end mt-3">
                <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                <button type="submit"  class="btn btn--primary">{{translate('submit')}}</button>
            </div>
        </form>
    </div>
@endsection


@push('script_2')
<script src="{{dynamicAsset('public/assets/admin/js/view-pages/join-us-page.js')}}"></script>
<script>
"use strict";
    let count= {{$count ?? 0 }};

    $(document).on('click', '.add-input-data-fields-group', function () {
    count++;
    let new_field = `<div class="row mb-2 mt-2 align-items-end" id="`+count+`" style="display: none;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ translate('messages.Type')}} </label>
                                <select class="form-control fieldTypeSelect" data-key="${count}"  name="field_type[]"  required>

                                    <option value="text">{{ translate('messages.Text')}}</option>
                                    <option value="number">{{ translate('messages.Number')}}</option>
                                    <option value="date">{{ translate('messages.Date')}}</option>
                                    <option value="email">{{ translate('messages.Email')}}</option>
                                    <option value="phone">{{ translate('messages.Phone')}}</option>
                                    <option value="check_box">{{ translate('messages.Check_Box')}}</option>
                                    <option value="file">{{ translate('messages.File_Upload')}}</option>
                                </select>
                            </div>
                        </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="title_color">{{ translate('Input_Field_Title') }}</label>
                            <input type="text" name="input_data[]" class="form-control" placeholder="{{ translate('Ex:Enter_Input_Field_Title') }}" required>
                        </div>
                    </div>
                    <div class=" hide_place_Holder_${count} col-md-3">
                        <div class="form-group">
                            <label for="placeholder_data" class="title_color">{{ translate('place_Holder') }}</label>
                            <input type="text" name="placeholder_data[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('enter_name') }}" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="d-flex justify-content-between gap-2">
                                <div class="form-check text-start mb-3">

                                    <label class="form-check-label text-dark" for="is_required`+count+1+`">
                                        <input type="checkbox" class="form-check-input" id="is_required`+count+1+`" value="${count}" name="is_required[${count}]"> {{ translate('is_Required') }} ?
                                    </label>
                                </div>

                                <a class="btn action-btn btn--danger btn-outline-danger remove-input-fields-group" data-id="`+count+`"  title="Delete">
                                        <i class="tio-delete-outlined"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="file-data_${count}"> </div>
                <div id="check_box_data_main_${count}"></div>
                `;

    $('#customer-input-fields-section').append(new_field);
    $('#'+count).fadeIn();
});


        function optionSelected(data ,key) {
            let id=key;
            if(data === 'file'){
                $('#check_box_data_'+id).remove();
                $('.delete_'+id).remove();
                $('.hide_place_Holder_'+id).hide();
                let new_field =
                    ` <div class="row  mb-3" id="file_rows_data_${id}">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input"  name="upload_multiple_files[${id}]" type="checkbox" value="${id}" id="upload_multiple_files_${id}" >
                            <label class="form-check-label" for="upload_multiple_files_${id}">
                                {{ translate('Upload_Multiple_Files') }}
                    </label>
                </div>
            </div>

                <div class="col-md=4">
                    <div>
                        <label for=""> {{ translate('File_Format') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input"  type="checkbox" id="inlineCheckbox1_${id}" value="${id}" name="image[${id}]">
                                <label class="form-check-label" for="inlineCheckbox1_${id}">{{ translate('JPG,_JPEG_or_PNG') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input"  type="checkbox" id="inlineCheckbox2_${id}" value="${id}" name="pdf[${id}]">
                                <label class="form-check-label" for="inlineCheckbox2_${id}">{{ translate('PDF') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input"  type="checkbox" id="inlineCheckbox3_${id}" value="${id}" name="docs[${id}]">
                                <label class="form-check-label" for="inlineCheckbox3_${id}">{{ translate('DOCS') }}</label>
                            </div>
                        </div>
                </div>
                `

                $('#file-data_'+id).append(new_field);
            }else if(data === 'check_box'){

                let rand = Math.floor((Math.random() + 11 )* 999);
                let new_check_box_field =
                    `<div class="row mb-3" id="check_box_data_${id}">
                    <div class="col-md-3 mt-3" >
                            <label class="form-check-label" for="">
                                <h6>  {{ translate('Add_Checkmark_Option') }} </h6>
                            </label>
                    </div>
                        <div class="col-md-3 mt-3" >
                            <h6> {{ translate('messages.Option_Name') }}</h6>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>
                                <input type="text" name="check_box_input[${id}][]" class="form-control" placeholder="{{ translate('Ex:Enter_option') }}" required value="">
                                   </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-sm btn-outline-primary add-check-box"  data-id="${id}" title="{{ translate('messages.add_new') }}">
                                    {{ translate("messages.Add") }} +
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="check_box_data_${id}_${rand}"> </div>
                    `
                $('#check_box_data_main_'+id).append(new_check_box_field);
                $('#file_rows_data_'+id).remove();
                $('.hide_place_Holder_'+id).hide();
            }
            else{
                $('#file_rows_data_'+id).remove();
                $('#check_box_data_'+id).remove();
                $('.delete_'+id).remove();
                $('.hide_place_Holder_'+id).show().removeClass('d-none');
            }
        }


        function add_check_box(parent_id){
            let rand = Math.floor((Math.random() + 11 )* 999);
            let  new_check_box_field=
                `<div class="row delete_${parent_id} mb-2" id="check_box_data_${parent_id}_${rand}">
            <div class="col-md-3 mt-3" >
                    <label class="form-check-label" for="">
                        <h6>  {{ translate('Add_Checkmark_Option') }} </h6>
                    </label>
            </div>
                <div class="col-md-3 mt-3" >
                    <h6> {{ translate('messages.Option_Name') }}  </h6>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>
                        <input type="text" name="check_box_input[${parent_id}][]" class="form-control" placeholder="{{ translate('Ex:Enter_option') }}" required value="">
                       </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="d-flex justify-content-center gap-2">
                            <a class="btn action-btn btn--danger btn-outline-danger remove-check-box" data-parent-id="${parent_id}" data-child-id="${rand}"  title="Delete">
                                <i class="tio-delete-outlined"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            `
            $('#check_box_data_main_'+parent_id).append(new_check_box_field);
        }
    </script>

@endpush
