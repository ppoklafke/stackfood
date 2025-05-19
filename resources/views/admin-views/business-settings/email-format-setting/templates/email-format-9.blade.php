<?php
$company_name = App\Models\BusinessSetting::where('key', 'business_name')->first()->value;
?>
<table class="main-table">
    <tbody>
        <tr>
            <td class="main-table-td">
                <h2 class="mb-3" id="mail-title">{{ $data['title']?? translate('Main_Title_or_Subject_of_the_Mail') }}</h2>
                <div class="mb-1" id="mail-body">{!! $data['body']?? translate('Hi_Sabrina,') !!}</div>
                <table class="bg-section p-10 w-100">
                    <tbody>
                        <tr>
                            <td class="p-10">
                                <span class="d-block text-center">
                                    @php($restaurant_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first())
                                    <img class="mb-2 mail-img-2 onerror-image" data-onerror-image="{{ dynamicStorage('storage/app/public/business/' . $restaurant_logo) }}"

                                    src="{{ $data?->logo ? $data->logo_full_url : \App\CentralLogics\Helpers::get_full_url('business',$restaurant_logo?->value,$restaurant_logo?->storage[0]?->value ?? 'public', 'favicon') }}"

                                    id="logoViewer" alt="">
                                    <h3 class="mb-3 mt-0">{{ translate('Order_Info') }}</h3>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="order-table w-100">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <h3 class="subtitle">{{ translate('Order_Summary') }}</h3>
                                                <span class="d-block">{{ translate('Order') }}{{ translate('#_48573') }}</span>
                                                <span class="d-block">{{ translate('23 Jul, 2023 4:30 am') }}</span>
                                            </td>
                                            <td class="email-template-09-max-width">
                                                <h3 class="subtitle">{{ translate('Delivery_Address') }}</h3>
                                                <span class="d-block">{{ translate('Munam_Shahariar') }}</span>
                                                <span class="d-block" >{{ translate('4517_Washington_Ave._Manchester,_Kentucky_39495') }}</span>
                                            </td>
                                        </tr>
                                        <td colspan="2">
                                            <table class="w-100">
                                                <thead class="bg-section-2">
                                                    <tr>
                                                        <th class="text-left p-1 px-3">{{ translate('Product') }}</th>
                                                        <th class="text-right p-1 px-3">{{ translate('Price') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-left p-2 px-3">
                                                            {{ translate('1._The_school_of_life_-_emotional_baggage_tote_bag_-_canvas_tote_bag_(navy)_x_1') }}
                                                        </td>
                                                        <td class="text-right p-2 px-3">
                                                            <h4>
                                                                {{ translate('$5,465') }}
                                                            </h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left p-2 px-3">
                                                            {{ translate('2._3USB_Head_Phone_x_1') }}
                                                        </td>
                                                        <td class="text-right p-2 px-3">
                                                            <h4>
                                                                {{ translate('$354') }}
                                                            </h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <hr class="mt-0">
                                                            <table class="w-100">
                                                                <tr>
                                                                    <td class="email-template-table-td-width"></td>
                                                                    <td class="p-1 px-3">{{ translate('Item_Price') }}</td>
                                                                    <td class="text-right p-1 px-3">{{ translate('$85') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="email-template-table-td-width"></td>
                                                                    <td class="p-1 px-3">{{ translate('Addon') }}</td>
                                                                    <td class="text-right p-1 px-3">{{ translate('$85') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="email-template-table-td-width"></td>
                                                                    <td class="p-1 px-3">{{ translate('Sub_total') }}</td>
                                                                    <td class="text-right p-1 px-3">{{ translate('$90') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="email-template-table-td-width"></td>
                                                                    <td class="p-1 px-3">{{ translate('Discount') }}</td>
                                                                    <td class="text-right p-1 px-3">{{ translate('$10') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="email-template-table-td-width"></td>
                                                                    <td class="p-1 px-3">{{ translate('Coupon_Discount') }}</td>
                                                                    <td class="text-right p-1 px-3">{{ translate('$00') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="email-template-table-td-width"></td>
                                                                    <td class="p-1 px-3">{{ translate('VAT_/_Tax') }}</td>
                                                                    <td class="text-right p-1 px-3">{{ translate('$15') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="email-template-table-td-width"></td>
                                                                    <td class="p-1 px-3">{{ translate('Delivery_Charge') }}</td>
                                                                    <td class="text-right p-1 px-3">{{ translate('$20') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="email-template-table-td-width"></td>
                                                                    <td class="p-1 px-3">
                                                                        <h4>{{ translate('Total') }}</h4>
                                                                    </td>
                                                                    <td class="text-right p-1 px-3">
                                                                        <span class="text-base">{{ translate('$105') }}</span>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div class="mb-2" id="mail-footer">
                    {{ $data['footer_text'] ?? translate('Please_contact_us_for_any_queries,_we’re_always_happy_to_help.') }}
                </div>
                <div>
                    {{ translate('Thanks_&_Regards') }},
                </div>
                <div class="mb-4">
                    {{ $company_name }}
                </div>
            </td>
        </tr>
        <tr>
            <td>
            <span class="privacy">
                <a href="#" id="privacy-check" style="{{ (isset($data['privacy']) && $data['privacy'] == 1)?'':'display:none;' }}"><span class="dot"></span>{{ translate('Privacy_Policy')}}</a>
                <a href="#" id="refund-check" style="{{ (isset($data['refund']) && $data['refund'] == 1)?'':'display:none;' }}"><span class="dot"></span>{{ translate('Refund_Policy') }}</a>
                <a href="#" id="cancelation-check" style="{{ (isset($data['cancelation']) && $data['cancelation'] == 1)?'':'display:none;' }}"><span class="dot"></span>{{ translate('Cancelation_Policy') }}</a>
                <a href="#" id="contact-check" style="{{ (isset($data['contact']) && $data['contact'] == 1)?'':'display:none;' }}"><span class="dot"></span>{{ translate('Contact_us') }}</a>
            </span>
                <span class="social email-template-social-span">
                    <a href="#" id="facebook-check"  class="email-template-social-media"  style="{{ (isset($data['facebook']) && $data['facebook'] == 1)?'':'display:none;' }}">
                        <img src="{{dynamicAsset('/public/assets/admin/img/img/facebook.png')}}" alt="">
                    </a>
                    <a href="#" id="instagram-check"  class="email-template-social-media"  style="{{ (isset($data['instagram']) && $data['instagram'] == 1)?'':'display:none;' }}">
                        <img src="{{dynamicAsset('/public/assets/admin/img/img/instagram.png')}}" alt="">
                    </a>
                    <a href="#" id="twitter-check"  class="email-template-social-media"  style="{{ (isset($data['twitter']) && $data['twitter'] == 1)?'':'display:none;' }}">
                        <img src="{{dynamicAsset('/public/assets/admin/img/img/twitter.png')}}" alt="">
                    </a>
                    <a href="#" id="linkedin-check"  class="email-template-social-media"  style="{{ (isset($data['linkedin']) && $data['linkedin'] == 1)?'':'display:none;' }}">
                        <img src="{{dynamicAsset('/public/assets/admin/img/img/linkedin.png')}}" alt="">
                    </a>
                    <a href="#" id="pinterest-check"  class="email-template-social-media"  style="{{ (isset($data['pinterest']) && $data['pinterest'] == 1)?'':'display:none;' }}">
                        <img src="{{dynamicAsset('/public/assets/admin/img/img/pinterest.png')}}" alt="">
                    </a>
                </span>
                <span class="copyright" id="mail-copyright">
                    {{ $data['copyright_text']?? translate('Copyright_2023_Stackfood._All_right_reserved') }}
                </span>
            </td>
        </tr>
    </tbody>
</table>
<script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/common.js"></script>
