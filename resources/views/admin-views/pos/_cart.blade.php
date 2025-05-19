<div class="d-flex flex-row initial-47">
    <table class="table table--vertical-middle">
        <thead class="thead-light border-0 ">
        <tr>
            <th class="py-2" scope="col">{{ translate('Item') }}</th>
            <th class="py-2" scope="col" class="text-center">{{ translate('Qty') }}</th>
            <th class="py-2 text-center" scope="col" class="text-right">{{ translate('Price') }}</th>
            <th class="py-2 text-center" scope="col">{{ translate('Delete') }}</th>
        </tr>
        </thead>
        <tbody>
        <?php

        use App\CentralLogics\Helpers;

        $subtotal = 0;
        $addon_price = 0;
        $tax = isset($restaurant_data) ? $restaurant_data->tax : 0;
        $discount = 0;
        $discount_type = 'amount';
        $discount_on_product = 0;
        $variation_price = 0;
        ?>
        @if (session()->has('cart') && count(session()->get('cart')) > 0)
                <?php
                $cart = session()->get('cart');
                if (isset($cart['tax'])) {
                    $tax = $cart['tax'];
                }
                if (isset($cart['discount'])) {
                    $discount = $cart['discount'];
                    $discount_type = $cart['discount_type'];
                }
                ?>
            @foreach (session()->get('cart') as $key => $cartItem)
                @if (is_array($cartItem))

                        <?php
                        $variation_price += $cartItem['variation_price'];
                        $product_subtotal = ($cartItem['price'] * $cartItem['quantity']);
                        $discount_on_product += $cartItem['discount'] * $cartItem['quantity'];
                        $subtotal += $product_subtotal;
                        $addon_price += $cartItem['addon_price'];
                        $food_id= $cartItem['id'];
                        ?>
                    <tr>
                        <td class="media cart--media align-items-center cursor-pointer quick-View-Cart-Item"
                            data-product-id="{{$cartItem['id']}}" data-item-key="{{$key}}">
                            <img class="avatar avatar-sm mr-2 onerror-image"
                                 src="{{ $cartItem['image_full_url'] }}"
                                 data-onerror-image="{{dynamicAsset('public/assets/admin/img/100x100/food-default-image.png')}}"
                                 alt="{{data_get($cartItem,'image')}} image">
                            <div class="media-body">
                                <h5 class="text-hover-primary mb-0">{{ Str::limit($cartItem['name'], 12) }}</h5>
                                <small>{{ Str::limit($cartItem['variant'], 20) }}</small>
                            </div>
                        </td>
                        <td class="align-items-center">
                            <label>
                                <input type="number" data-key="{{ $key }}"  data-value="{{$cartItem['quantity']}}"
                                       value="{{ $cartItem['quantity'] }}"
                                       data-option_ids="{{  $cartItem['variation_option_ids']  }}"
                                       data-food_id="{{  $food_id  }}"

                                       min="1" max="{{$cartItem['maximum_cart_quantity'] ?? '9999999999'}}"
                                       class="rounded border border-secondary initial-48  update-Quantity">
                            </label>
                        </td>
                        <td class="px-0 py-1 text-center">
                            <div class="btn">
                                {{ Helpers::format_currency($product_subtotal) }}
                            </div>
                        </td>
                        <td class="align-items-center">
                            <div class="btn--container justify-content-center">
                                <a href="javascript:"
                                   data-product-id="{{$key}}"
                                   class="btn btn-sm btn--danger action-btn btn-outline-danger remove-From-Cart"> <i
                                        class="tio-delete-outlined"></i></a>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<?php
if (session()->get('address') && count(session()->get('address')) > 0) {
    $delivery_fee = session()->get('address')['delivery_fee'];
} else {
    $delivery_fee = 0;
}
$total = $subtotal + $addon_price;

$total = $total - $discount_on_product;
$tax_included =  Helpers::get_mail_status('tax_included')  ?? 0;
$total_tax_amount = $tax > 0 ? ($total * $tax) / 100 : 0;
$total = $total + $delivery_fee;
?>
<div class="box p-3">
    <dl class="row">

        <dt class="col-6">{{ translate('messages.addon') }}:</dt>
        <dd class="col-6 text-right">{{ Helpers::format_currency($addon_price) }}</dd>

        <dt class="col-6">{{ translate('messages.subtotal') }}
            @php($tax_a=$total_tax_amount)
            @if ($tax_included ==  1)
                ({{ translate('messages.TAX_Included') }})
                @php($tax_a=0)
            @endif
            :
        </dt>
        <dd class="col-6 text-right">{{ Helpers::format_currency($subtotal + $addon_price) }}</dd>

        <dt class="col-6">{{ translate('messages.discount') }} :</dt>
        <dd class="col-6 text-right">-{{ Helpers::format_currency(round($discount_on_product, 2)) }}</dd>

        <dt class="col-6">{{ translate('messages.delivery_fee') }} :</dt>
        <dd class="col-6 text-right" id="delivery_price">
            {{ Helpers::format_currency($delivery_fee) }}</dd>
        @if ($tax_included !=  1)
            <dt class="col-6">{{ translate('messages.tax') }} :</dt>
            <dd class="col-6 text-right">
                {{ Helpers::format_currency(round($total_tax_amount, 2)) }}
            </dd>

        @endif
        @php( $additional_charge= 0.00)
        @if (\App\CentralLogics\Helpers::get_business_data('additional_charge_status'))
            <dt class="col-6">{{ \App\CentralLogics\Helpers::get_business_data('additional_charge_name')??translate('messages.additional_charge')  }} :</dt>
            <dd class="col-6 text-right">
                @if ($subtotal + $addon_price > 0)
                @php( $additional_charge= \App\CentralLogics\Helpers::get_business_data('additional_charge'))
                {{ Helpers::format_currency(round($additional_charge, 2)) }}
                @else
                {{Helpers::format_currency($additional_charge)  }}
                @endif
            </dd>
        @endif

        <dt class="col-6 pr-0">
            <hr class="mt-0"/>
        </dt>
        <dt class="col-6 pl-0">
            <hr class="mt-0"/>
        </dt>
        <dt class="col-6">{{ translate('Total') }}:</dt>
        <dd class="col-6 text-right h4 b">
            {{ Helpers::format_currency(round($total + $additional_charge + $tax_a, 2)) }} </dd>
    </dl>
    <form
        action="{{ route('admin.pos.order') }}?restaurant_id={{  $restaurant_data?->id ?? '' }}"
        id='order_place' method="post" >
        @csrf
        <input type="hidden" name="user_id" id="customer_id">
        <input type="hidden" value="{{ $food_id ?? null }}" id="cart_food_id">
        <div class="pos--payment-options mt-3 mb-3">
            <h5 class="mb-3">{{ translate('Payment_Method') }}</h5>
            <ul>

                @php($cod=Helpers::get_business_settings('cash_on_delivery'))
                @if ($cod['status'])
                    <li>
                        <label>
                            <input type="radio" {{ old('type') == 'cash' ? 'checked' :'' }} name="type" value="cash" hidden checked>
                            <span>{{ translate('Cash_On_Delivery') }}</span>
                        </label>
                    </li>
                @endif
                @php($wallet=Helpers::get_business_settings('wallet_status'))
                @if ($wallet)
                    <li>
                        <label>
                            <input type="radio" {{ old('type') == 'wallet' ? 'checked' :'' }} name="type" value="wallet" hidden>
                            <span>{{ translate('Wallet') }}</span>
                        </label>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Static Data -->
        <div class="row button--bottom-fixed g-1 bg-white">
            <div class="col-sm-6">
                <button type="submit"
                        class="btn  btn--primary btn-sm btn-block place-order-submit">{{ translate('messages.place_order') }} </button>
            </div>
            <div class="col-sm-6">
                <a href="#" class="btn btn--reset btn-sm btn-block empty-Cart">{{  translate('Clear_Cart') }}</a>
            </div>
        </div>
    </form>
</div>







