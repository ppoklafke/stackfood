@php
use App\CentralLogics\Helpers;
use App\Models\AddOn;
use App\Scopes\RestaurantScope;
@endphp
<div class="initial-49">
    <div class="modal-header p-0">
        <h4 class="modal-title product-title">
        </h4>
        <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="d-flex flex-row align-items-center">

            <div class="d-flex align-items-center justify-content-center active position-relative">
                @if (config('toggle_veg_non_veg'))
                <span class="badge badge-{{ $product->veg ? 'success' : 'danger' }} position-absolute left-0 top-0">{{ $product->veg ?
                    translate('messages.veg') : translate('messages.non_veg') }}</span>
                @endif
                @if ($product?->stock_type !=='unlimited' && $product->item_stock <= 0)
                <span class="badge badge-danger position-absolute left-0 top-0">{{ translate('messages.Out_of_Stock') }}</span>
                @endif
                <img class="img-responsive mr-3 img--100 onerror-image" src="{{ data_get($product, 'image_full_url') ?? dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}"
                    data-onerror-image="{{ dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}"
                    data-zoom="{{ dynamicStorage('storage/app/public/product') }}/{{ data_get($product, 'image') }}"
                    alt="Product image">

                <div class="cz-image-zoom-pane"></div>
            </div>
            <div class="details pl-2">
                <a href="{{ route('admin.food.view', $product->id) }}"
                    class="h3 mb-2 product-title text-capitalize text-break">{{ $product->name }}</a>

                <div class="mb-3 text-dark">
                    <span class="h3 font-weight-normal text-accent mr-1">
                        {{ Helpers::get_price_range($product, true) }}
                    </span>
                    @if ($product->discount > 0 || Helpers::get_restaurant_discount($product->restaurant))
                    <span class="fz-12px line-through">
                        {{ Helpers::get_price_range($product) }}
                    </span>
                    @endif
                </div>
                @if ($product->discount > 0)
                <div class="mb-3 text-dark">
                    <strong>{{ translate('messages.discount') }} : </strong>
                    <strong id="set-discount-amount">{{ Helpers::get_product_discount($product) }}</strong>
                </div>
                @endif

            </div>
        </div>

        <div class="row pt-2">
            <div class="col-12">
                    <?php
                        $cart = false;
                        if (session()->has('cart')) {
                            foreach (session()->get('cart') as $key => $cartItem) {
                                if (is_array($cartItem) && $cartItem['id'] == $product['id']) {
                                    $cart = $cartItem;
                                }
                            }
                        }
                    ?>
                <h2>{{ translate('messages.description') }}</h2>
                <span class="d-block text-dark text-break">
                    {!! $product->description !!}
                </span>
                <form id="add-to-cart-form" class="mb-2">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">

                    @foreach (json_decode($product->variations) as $key => $choice)
                    @if (isset($choice->price) == false)
                    <div class="h3 p-0 pt-2">{{ $choice->name }} <small class="text-muted fs-12">
                            ({{ $choice->required == 'on' ? translate('messages.Required') :
                            translate('messages.optional') }}
                            ) </small>
                    </div>
                    @if ($choice->min != 0 && $choice->max != 0)
                    <small class="d-block mb-3">
                        {{ translate('You_need_to_select_minimum_ ') }} {{ $choice->min }}
                        {{ translate('to_maximum_ ') }} {{ $choice->max }} {{ translate('options') }}
                    </small>
                    @endif

                    <div>
                        <input type="hidden" name="variations[{{ $key }}][min]" value="{{ $choice->min }}">
                        <input type="hidden" name="variations[{{ $key }}][max]" value="{{ $choice->max }}">
                        <input type="hidden" name="variations[{{ $key }}][required]" value="{{ $choice->required }}">
                        <input type="hidden" name="variations[{{ $key }}][name]" value="{{ $choice->name }}">
                        @foreach ($choice->values as $k => $option)
                        <div class="form-check form--check d-flex pr-5 mr-6">
                            <input
                                class="form-check-input input-element {{ data_get($option, 'stock_type') && data_get($option, 'stock_type') !== 'unlimited' && data_get($option, 'current_stock') <= 0? 'stock_out' : '' }}"
                                type="{{ $choice->type == 'multi' ? 'checkbox' : 'radio' }}"
                                id="choice-option-{{ $key }}-{{ $k }}"
                                data-option_id="{{ data_get($option, 'option_id') }}"
                                name="variations[{{ $key }}][values][label][]" value="{{ $option->label }}"
                                {{ data_get($option, 'stock_type') && data_get($option, 'stock_type') !== 'unlimited' && data_get($option, 'current_stock') <= 0? 'disabled' : '' }} autocomplete="off">

                            <label
                                class="form-check-label  {{ data_get($option, 'stock_type') && data_get($option, 'stock_type') !== 'unlimited' && data_get($option, 'current_stock') <= 0? 'stock_out text-muted' : 'text-dark' }}"
                                for="choice-option-{{ $key }}-{{ $k }}">{{ Str::limit($option->label, 20, '...') }}
                                &nbsp;
                                <span
                                    class="input-label-secondary text--title text--warning {{ data_get($option, 'stock_type') && data_get($option, 'stock_type') !== 'unlimited' && data_get($option, 'current_stock') <= 0? '' : 'd-none' }}"
                                    title="{{ translate('Currently_you_need_to_manage_discount_with_the_Restaurant.') }}">
                                    <i class="tio-info-outined"></i>
                                    <small>{{ translate('stock_out') }}</small>
                                </span>
                            </label>
                            <span class="ml-auto">{{ Helpers::format_currency($option->optionPrice) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @endforeach

                    <input type="hidden" hidden name="option_ids" id="option_ids" >
                    <div class="d-flex justify-content-between mt-4">
                        <div class="product-description-label mt-2 text-dark h3">{{ translate('messages.quantity') }}:
                        </div>
                        <div class="product-quantity d-flex align-items-center">
                            <div class="input-group input-group--style-2 pr-3 w-160px">
                                <span class="input-group-btn">
                                    <button class="btn btn-number text-dark p--10px" type="button" data-type="minus"
                                        data-field="quantity" disabled="disabled">
                                        <i class="tio-remove  font-weight-bold"></i>
                                    </button>
                                </span>
                                <input type="text" name="quantity"  id="add_new_product_quantity"
                                    class="form-control input-number text-center cart-qty-field" placeholder="1"
                                    value="1" min="1" data-maximum_cart_quantity='{{ min( $product?->maximum_cart_quantity ?? '9999999999',$product?->stock_type =='unlimited' ? '999999999' : $product?->item_stock)  }}' max="{{ $product->maximum_cart_quantity ?? '9999999999' }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-number text-dark p--10px" id="quantity_increase_button" type="button" data-type="plus"
                                        data-field="quantity">
                                        <i class="tio-add  font-weight-bold"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    @php($add_ons = json_decode($product->add_ons))
                    @if (count($add_ons) > 0 && $add_ons[0])
                    <div class="h3 p-0 pt-2">{{ translate('messages.addon') }}</div>

                    <div class="d-flex justify-content-left flex-wrap">
                        @foreach (AddOn::withoutGlobalScope(RestaurantScope::class)->whereIn('id',
                        $add_ons)->active()->get() as $key => $add_on)
                        <div class="flex-column pb-2">
                            <input type="hidden" name="addon-price{{ $add_on->id }}" value="{{ $add_on->price }}">
                            <input class="btn-check addon-chek addon-quantity-input-toggle" type="checkbox"
                                id="addon{{ $key }}" name="addon_id[]" value="{{ $add_on->id }}" autocomplete="off">
                            <label class="d-flex align-items-center btn btn-sm check-label mx-1 addon-input text-break"
                                for="addon{{ $key }}">{{ Str::limit($add_on->name, 20, '...') }}
                                <br>
                                {{ Helpers::format_currency($add_on->price) }}</label>
                            <label class="input-group addon-quantity-input mx-1 shadow bg-white rounded px-1"
                                for="addon{{ $key }}">
                                <button class="btn btn-sm h-100 text-dark px-0 decrease-button"
                                    data-id="{{ $add_on->id }}" type="button"><i
                                        class="tio-remove  font-weight-bold"></i></button>
                                <input type="number" name="addon-quantity{{ $add_on->id }}"
                                    id="addon_quantity_input{{ $add_on->id }}"
                                    class="form-control text-center border-0 h-100" placeholder="1" value="1" min="1"
                                    max="9999999999" readonly>
                                <button class="btn btn-sm h-100 text-dark px-0 increase-button" id="addon_quantity_button{{ $add_on->id }}"
                                    data-id="{{ $add_on->id }}" type="button"><i
                                        class="tio-add  font-weight-bold"></i></button>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <div class="row no-gutters d-none mt-2 text-dark" id="chosen_price_div">
                        <div class="col-2">
                            <div class="product-description-label">{{ translate('messages.Total_Price') }}:</div>
                        </div>
                        <div class="col-10">
                            <div class="product-price">
                                <strong id="chosen_price"></strong>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-2">
                        @if ($product?->stock_type !=='unlimited' && $product->item_stock <= 0)
                            <button class="btn btn-secondary h--45px w-40p " type="button">
                                <i class="tio-shopping-cart"></i>
                                {{ translate('messages.Out_Of_Stock') }}
                            </button>
                        @else
                            <button class="btn btn--primary h--45px w-40p add-To-Cart" type="button">
                                <i class="tio-shopping-cart"></i>
                                {{ translate('messages.add_to_cart') }}
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    "use strict";
    cartQuantityInitialize();
    getVariantPrice();
    $('#add-to-cart-form input').on('change', function() {
        getVariantPrice();
    });


    function getCheckedInputs() {
        var checkedInputs = [];
    var checkedElements = document.querySelectorAll('.input-element:checked');
    checkedElements.forEach(function(element) {
        checkedInputs.push(element.getAttribute('data-option_id'));
    });

    $('#option_ids').val(checkedInputs.join(','));

    }
    var inputElements = document.querySelectorAll('.input-element');
    inputElements.forEach(function(element) {
        element.addEventListener('change', getCheckedInputs);
    });
</script>
