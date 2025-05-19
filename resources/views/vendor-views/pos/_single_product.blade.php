@php use App\CentralLogics\Helpers; @endphp
<div class="product-card card cursor-pointer quick-View" data-id="{{$product->id}}">
    <div class="card-header inline_product clickable p-0 initial-34">
        <img class="w-100 rounded onerror-image"
            src="{{ $product?->image_full_url ?? dynamicAsset('public/assets/admin/img/100x100/food-default-image.png')}}"
            data-onerror-image="{{ dynamicAsset('public/assets/admin/img/100x100/food-default-image.png') }}"
            alt="Product image">
    </div>

    <div class="card-body inline_product text-center px-2 py-2 clickable">
        <div class="product-title1 position-relative text-dark font-weight-bold text-capitalize">
            {{ Str::limit($product['name'], 12,'...') }}
        </div>
        <div class="justify-content-between text-center">
            <div class="product-price text-center">
                <div class="justify-content-between text-center">
                    <div class="product-price text-center">
                        <span class="text-accent font-weight-bold color-f8923b">
                            {{Helpers::format_currency($product['price']-Helpers::product_discount_calculate($product, $product['price'], $restaurant_data))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
