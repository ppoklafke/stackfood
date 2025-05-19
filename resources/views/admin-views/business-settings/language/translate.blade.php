@extends('layouts.admin.app')

@section('title',translate('messages.language'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{dynamicAsset('/public/assets/admin/img/notes.png')}}" class="mw-26px" alt="public">
                    </div>
                    <span>
                        {{ translate('messages.business_setup') }}
                    </span>
                </h1>
                {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div> --}}
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.business-settings.partials.nav-menu')
            </div>
        </div>

        <input type="hidden" value="0" id="translating-count">

        <div class="row __mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="search--button-wrapper justify-content-between">
                            <h5 class="m-0">{{translate('language_content_table')}}</h5>
                            <form class="search-form min--260">
                                <div class="input-group input--group">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control h--40px"
                                            placeholder="{{ translate('messages.Ex : Search') }}" aria-label="{{translate('messages.search')}}" value="{{ request()?->search ?? null }}" required>
                                    <input type="hidden">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                            </form>
                            </div>
                            @if ($lang !== 'en')
                            <button class="btn btn--primary ml-2" id="translate-confirm-btn" >{{ translate('Translate_All') }}</button>
                            @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable"  >
                                <thead>
                                <tr>
                                    <th >{{translate('SL#')}}</th>
                                    <th >{{translate('Current_value')}}</th>
                                    <th >{{translate('translated_value')}}</th>
                                    <th > {{translate('auto_translate')}}</th>
                                    <th >{{translate('update')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php($count=0)
                                @foreach($full_data as $key=>$value)
                                @php($count++)

                                <tr id="lang-{{$count}}">
                                    <td>{{ $count+$full_data->firstItem() -1}}</td>
                                    <td >
                                        <input type="text" name="key[]"
                                        value="{{$key}}" hidden>
                                        <div style="max-inline-size: 450px"> {{translate($key) }}</div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="value[]"
                                        id="value-{{$count}}"
                                        value="{{$full_data[$key]}}">
                                    </td>
                                    <td >
                                        <button type="button"
                                                data-key="{{$key}}" data-id="{{$count}}"
                                                class="btn btn-ghost-success btn-block auto-translate-btn"><i class="tio-globe"></i>
                                        </button>
                                    </td>
                                    <td >
                                        <button type="button"
                                                data-key="{{$key}}"
                                                data-id="{{$count}}"
                                                class="btn btn--primary btn-block update-language-btn"><i class="tio-save-outlined"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                                </tbody>
                            </table>
                            @if(count($full_data) !== 0)
                            <hr>
                            @endif
                            <div class="page-area">
                                {!! $full_data->appends(request()->query())->links() !!}
                            </div>
                            @if(count($full_data) === 0)
                            <div class="empty--data">
                                <img src="{{dynamicAsset('/public/assets/admin/img/empty.png')}}" alt="public">
                                <h5>
                                    {{translate('no_data_found')}}
                                </h5>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade language-complete-modal" id="translate-confirm-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered max-w-450px">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="py-5">
                            <div class="mb-4">
                                <img src="{{dynamicAsset('/public/assets/admin/img/language-complete.png')}}" alt="">
                            </div>
                            <h4 class="mb-3">{{ translate('messages.Are you sure ?') }}</h4>
                            <p class="mb-4 text-9EADC1 max-w-362px mx-auto">
                                {{ translate('You_want_to_auto_translate_all._It_may_take_a_while_to_complete_the_translation') }}
                            </p>
                            <div class="d-flex justify-content-center gap-3 pt-1">

                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                                <button type="button" class="btn btn--primary auto_translate_all" data-dismiss="modal" >{{ translate('Yes,_Translate_All') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade language-complete-modal" id="complete-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered max-w-450px">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="py-5">
                            <div class="mb-4">
                                <img src="{{dynamicAsset('/public/assets/admin/img/language-complete.png')}}" alt="">
                            </div>
                            <h4 class="mb-3">{{ translate('Your_file_has_been_successfully_translated') }}</h4>
                            <p class="mb-4 text-9EADC1 max-w-362px mx-auto">
                                {{ translate('All_your_items_has_been_translated.') }}
                            </p>
                            <div class="d-flex justify-content-center gap-3 pt-1">
                                <button type="button" class="btn btn--primary location_reload" data-dismiss="modal">{{ translate('messages.Okay') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade language-warning-modal" id="warning-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex gap-3 align-items-start">
                            <img src="{{dynamicAsset('/public/assets/admin/img/invalid-icon.png')}}" alt="">
                            <div class="w-0 flex-grow-1">
                                <h3>{{ translate('Warning!') }}</h3>
                                <p>
                                   {{ translate('Translating_in_progress._Are_you_sure,_want_to_close_this_tab?_If_you_close_the_tab,_then_some_translated_items_will_be_unchanged.') }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-3">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                            <button type="button" class="btn btn--primary" id="close-tab" >{{ translate('Yes,_Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade language-complete-modal " id="translating-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="py-5 px-sm-2">
                            <div class="progress-circle-container mb-4">
                                <img width="80px" src="{{dynamicAsset('/public/assets/admin/img/loader-icon.gif')}}" alt="">
                            </div>
                            <h4 class="mb-2">{{ translate('Translating_may_take_up_to') }} <span id="time-data"> {{ translate('Hours') }}</span></h4>
                            <p class="mb-4">
                                {{ translate('Please_wait_&_don’t_close/terminate_your_tab_or_browser') }}
                            </p>
                            <div class="max-w-215px mx-auto">
                                <div class="d-flex flex-wrap mb-1 justify-content-between font-semibold text--title">
                                    <span>{{ translate('In_Progress') }}</span>
                                    <span class="translating-modal-success-rate">0.4%</span>
                                </div>
                                <div class="progress mb-3 h-5px">
                                    <div class="progress-bar bg-success rounded-pill translating-modal-success-bar" style="width: 0.4%"></div>
                                </div>
                            </div>
                            <p class="mb-4 text-9EADC1">
                                <span class="text-dark">{{ translate('note:') }}</span> {{ translate('All_the_translations_may_not_be_fully_accurate.') }}
                            </p>
                            <div class="d-flex justify-content-center gap-3 pt-1">
                                <button type="button" class="btn btn--primary location_reload"  >{{ translate('messages.Cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')

    <script>
        "use strict"
        $(document).on('click', '.update-language-btn', function () {
            let key = $(this).data('key');
            let id = $(this).data('id');
            let value = $('#value-'+id).val() ;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.language.translate-submit',[$lang])}}",
                method: 'POST',
                data: {
                    key: key,
                    value: value
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function () {
                    toastr.success('{{translate('text_updated_successfully')}}');
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });

        $(document).on('click', '.auto-translate-btn', function () {
            let key = $(this).data('key');
            let id = $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.language.auto-translate',[$lang])}}",
                method: 'POST',
                data: {
                    key: key
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    toastr.success('{{translate('Key translated successfully')}}');
                    $('#value-'+id).val(response.translated_data);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });





        $(document).on('click', '#translate-confirm-btn', function () {
            $('#translate-confirm-modal').modal('show')

        });
        $(document).on('click', '.auto_translate_all', function () {
            auto_translate_all();

        });
        $(document).on('click', '.location_reload', function () {
            location.reload();

        });
        $(document).on('click', '.close-tab', function () {
            $('#translating-modal').removeClass('prevent-close')
            window.close();

        });

        function  auto_translate_all(){
            var total_count;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.language.auto_translate_all',[$lang])}}",
                method: 'get',
                data: {
                    translating_count: $('#translating-count').val(),
                },
                beforeSend: function () {
                    $('#translating-modal').addClass('prevent-close')
                    $('#translating-modal').modal('show')
                },
                success: function (response) {

                    if(response.data === 'data_prepared'){
                        $('#translating-modal').modal('show')
                        $('#translating-count').val(response.total)
                        auto_translate_all();
                    } else if(response.data === 'translating' &&  response.status === 'pending' ){
                        if($('#translating-count').val() == 0  ){
                            $('#translating-count').val(response.total)
                        }

                        $('.translating-modal-success-rate').html(response.percentage + '%');
                        $('.translating-modal-success-bar').attr('style', 'width:' + response.percentage + '%');


                            if(response.hours > 0){
                                $('#time-data').html(response.hours + ' {{ translate('hours') }} ' + response.minutes + ' {{ translate('min') }}' );
                            }
                            if(response.minutes > 0 && response.hours <= 0){
                                $('#time-data').html(response.minutes + ' {{ translate('min') }} ' +  response.seconds + ' {{ translate('seconds') }}');
                            }
                            if(response.seconds > 0 && response.minutes <= 0){
                                $('#time-data').html(response.seconds + ' {{ translate('seconds') }}');
                            }

                        auto_translate_all();

                        $('#translating-modal').modal('show')
                        } else if((response.data === 'translating' &&  response.status === 'done') || response.data === 'success' || response.data === 'error'  ){
                            $('#translating-modal').removeClass('prevent-close')
                            $('#translating-modal').modal('hide')
                            $('#translating-count').val(0)
                            if(response.data !== 'error'){
                                $('#complete-modal').modal('show')
                            } else{
                                toastr.error(response.message);
                            }
                        }
                },
                complete: function () {
                },
            });
        }

        const modal = document.getElementById('translating-modal');
        window.addEventListener('beforeunload', (event) => {

            if (modal.classList.contains('prevent-close')) {
                // $('#warning-modal').modal('show')
                event.preventDefault();
                event.returnValue = '';
            }
        });
    </script>

@endpush
