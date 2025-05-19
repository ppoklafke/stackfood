

<div class="__bg-F8F9FC-card view_new_option mb-2">
    <div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <label class="form-check form--check">
                <input class="form-check-input" name="options[{{ $key }}][required]" type="checkbox" {{ isset($item['required']) ? ($item['required'] == 'on' ? 'checked	' : '') : '' }}>
                <span class="form-check-label">{{ translate('Required') }}</span>
            </label>
            <div>
                <button type="button" data-id='{{  data_get($item,'variation_id') }}' class="btn btn-danger btn-sm delete_input_button remove_variation"
                        title="{{ translate('Delete') }}">
                    <i class="tio-add-to-trash"></i>
                </button>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-xl-4 col-lg-6">
                <label for="">{{ translate('name') }}  &nbsp;<span class="form-label-secondary text-danger"
                    data-toggle="tooltip" data-placement="right"
                    data-original-title="{{ translate('messages.Required.')}}"> *
                    </span></label>
                <input required name="options[{{ $key }}][name]" class="form-control new_option_name"
                       type="text" data-count="{{ $key }}"
                       value="{{ $item['name'] }}">
            </div>
            <input type="hidden" hidden name="options[{{ $key }}][variation_id]" value="{{  data_get($item,'variation_id')  }}" >

            <div class="col-xl-4 col-lg-6">
                <div class="form-group">
                    <label class="input-label text-capitalize d-flex alig-items-center"><span
                                class="line--limit-1">{{ translate('messages.selcetion_type') }} </span>
                    </label>
                    <div class="resturant-type-group border">
                        <label class="form-check form--check mr-2 mr-md-4">
                            <input class="form-check-input show_min_max" type="radio" value="multi"
                                   name="options[{{ $key }}][type]" id="type{{ $key }}"
                                   {{ $item['type'] == 'multi' ? 'checked' : '' }}
                                   data-count="{{ $key }}">
                            <span class="form-check-label">
                                {{ translate('Multiple') }}
                            </span>
                        </label>

                        <label class="form-check form--check mr-2 mr-md-4">
                            <input class="form-check-input hide_min_max" type="radio" value="single"
                                   {{ $item['type'] == 'single' ? 'checked' : '' }} name="options[{{ $key }}][type]"
                                   id="type{{ $key }}" data-count="{{ $key }}">
                            <span class="form-check-label">
                                {{ translate('Single') }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6">
                <div class="row g-2">
                    <div class="col-6">
                        <label for="">{{ translate('Min') }}</label>
                        <input id="min_max1_{{ $key }}" {{ $item['type'] == 'single' ? 'readonly ' : 'required' }}
                        value="{{ ($item['min'] != 0) ? $item['min']:''  }}" name="options[{{ $key }}][min]"
                               class="form-control" type="number" min="1">
                    </div>
                    <div class="col-6">
                        <label for="">{{ translate('Max') }}</label>
                        <input id="min_max2_{{ $key }}" {{ $item['type'] == 'single' ? 'readonly ' : 'required' }}
                        value="{{ ($item['max'] != 0) ? $item['max']:''  }}" name="options[{{ $key }}][max]"
                               class="form-control" type="number" min="2">
                    </div>

                </div>
            </div>
        </div>

        <div id="option_price_{{ $key }}">
            <div class="bg-white border rounded p-3 pb-0 mt-3">
                <div id="option_price_view_{{ $key }}">
                    @if (isset($item['values']))
                        @foreach ($item['values'] as $key_value => $value)
                            <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-md-0">
                                <div class="col-md-3 col-sm-6">
                                    <label for="">{{ translate('Option_name') }}  &nbsp;<span class="form-label-secondary text-danger"
                                        data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('messages.Required.')}}"> *
                                        </span></label>
                                    <input class="form-control" required type="text"
                                           name="options[{{ $key }}][values][{{ $key_value }}][label]"
                                           value="{{ $value['label'] }}">
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label for="">{{ translate('Additional_price') }}  &nbsp;<span class="form-label-secondary text-danger"
                                        data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('messages.Required.')}}"> *
                                        </span></label>
                                    <input class="form-control" required type="number" min="0" step="0.01"
                                           name="options[{{ $key }}][values][{{ $key_value }}][optionPrice]"
                                           value="{{ $value['optionPrice'] }}">
                                </div>
                                <div class="col-md-3 col-sm-6 hide_this">
                                    <label for="">{{ translate('Stock') }}</label>
                                    <input class="form-control stock_disable count_stock " required type="number" min="0" step="0.01"
                                           name="options[{{ $key }}][values][{{ $key_value }}][total_stock]"
                                           value="{{  data_get($value,'current_stock', 100)  }}">
                                </div>
                                <input type="hidden" hidden name="options[{{ $key }}][values][{{ $key_value }}][option_id]" value="{{  data_get($value,'option_id')  }}" >
                                <div class="col-sm-2 max-sm-absolute">
                                    <label class="d-none d-md-block">&nbsp;</label>
                                    <div class="mt-1">
                                        <button type="button" data-id="{{   data_get($value,'option_id') }}" class="btn btn-danger btn-sm deleteRow remove_variation_option"
                                                title="{{translate('Delete')}}">
                                            <i class="tio-add-to-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @endif
                </div>
                <div class="row mt-3 p-3 mr-1 d-flex" id="add_new_button_{{ $key }}">
                    <button type="button"
                            class="btn btn--primary btn-outline-primary add_new_row_button" data-count="{{ $key }}">{{ translate('Add_New_Option') }}</button>
                </div>

            </div>




        </div>
    </div>
</div>
