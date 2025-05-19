<div class="modal-header border-0 pb-0 d-flex justify-content-end">
    <button
        type="button"
        class="btn-close border-0"
        data-dismiss="modal"
        aria-label="Close"
    ><i class="tio-clear"></i></button>
</div>
<div class="modal-body px-4 px-sm-5">
    <div class="mb-4 text-center">
        @php($logo=\App\Models\BusinessSetting::where('key','logo')->first())
        <img class="dark-support onerror-image"   id="viewer"  width="200"
        src="{{ \App\CentralLogics\Helpers::get_full_url('business',$logo->value,$logo?->storage[0]?->value ?? 'public', 'favicon') }}"
        data-onerror-image="{{ dynamicAsset('public/assets/admin/img/favicon.png') }}" alt="image">


    </div>
    <h2 class="text-center mb-4">{{$addon_name}}</h2>

    <form action="{{route('admin.business-settings.system-addon.activation')}}" method="post" id="customer_login_modal" autocomplete="off">
        @csrf
        <div class="form-group mb-4">
            <label for="username">{{ translate('Codecanyon_usename') }}</label>
            <input
                name="username" id="username"
                class="form-control"
                placeholder="{{translate('Ex:_Riad_Uddin')}}" required
            />
        </div>
        <div class="form-group mb-6">
            <label for="purchase_code">{{ translate('Purchase_Code') }}</label>
            <input
                name="purchase_code" id="purchase_code"
                class="form-control"
                placeholder="{{translate('Ex:_987652')}}" required
            />
            <input type="text" name="path" class="form-control" value="{{$path}}" hidden>
        </div>

        <div class="btn--container justify-content-center">
            <button type="button" class="btn btn--cancel min-w-120" data-dismiss="modal">{{ translate('cancel') }}</button>
            <button type="submit" class="btn btn--primary min-w-120">{{ translate('Activate') }}</button>
        </div>
    </form>
</div>
