<?php
$company_name = App\Models\BusinessSetting::where('key', 'business_name')->first()->value;
$logo = \App\Models\BusinessSetting::where('key','logo')->first()?->value;
?>
<table class="email-template-table-style">
    <tr>
        <td class="email-template-table-td-style">
            <img class="mail-img-2 onerror-image" data-onerror-image="{{ dynamicAsset('/public/assets/admin/img/blank3.png') }}"

            src="{{ $data['icon_full_url'] ?? dynamicAsset('/public/assets/admin/img/blank1.png') }}"


            id="iconViewer" alt="">
            <h3  class="mt-2 email-template-table-td-title-style" id="mail-title">{{ $data['title']?? translate('Main_Title_or_Subject_of_the_Mail') }}</h3>

        </td>
    </tr>
    <tr>
        <td class="email-template-table-td-style-2">
            <span class="email-template-table-td-span" id="mail-body">{!! $data['body']??'Please click the link below to change your password' !!}</span>
            <span class="email-template-table-td-span-2">
                <a href="#" class="email-template-table-td-span-h-ref">{{ translate('generated_link') }}</a>
            </span>
            <span class="border-top"></span>
            <span class="d-block" id="mail-footer" class="email-template-table-td-span-3  mail-footer">{{ $data['footer_text'] ?? translate('Please_contact_us_for_any_queries,_we’re_always_happy_to_help.') }}</span>
            <span class="d-block">{{ translate('Thanks_&_Regards') }},</span>
            <span class="d-block" class="email-template-table-td-span-4">{{ $company_name }}</span>
            @php($store_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first())
            <img class="email-template-img onerror-image" data-onerror-image="{{ dynamicStorage('storage/app/public/business/' . $store_logo) }}"

            src="{{ $data?->logo ? $data->logo_full_url : \App\CentralLogics\Helpers::get_full_url('business',$store_logo?->value,$store_logo?->storage[0]?->value ?? 'public', 'favicon') }}"

            alt="public/img">

            <span class="privacy">
                <a href="#" id="privacy-check" style="{{ (isset($data['privacy']) && $data['privacy'] == 1)?'':'display:none;' }}"><span class="dot"></span>{{ translate('Privacy_Policy')}}</a>
                <a href="#" id="refund-check" style="{{ (isset($data['refund']) && $data['refund'] == 1)?'':'display:none;' }}"><span class="dot"></span>{{ translate('Refund_Policy') }}</a>
                <a href="#" id="cancelation-check" style="{{ (isset($data['cancelation']) && $data['cancelation'] == 1)?'':'display:none;' }}"><span class="dot"></span>{{ translate('Cancelation_Policy') }}</a>
                <a href="#" id="contact-check" style="{{ (isset($data['contact']) && $data['contact'] == 1)?'':'display:none;' }}"><span class="dot"></span>{{ translate('Contact_us') }}</a>
            </span>

            <span class="social email-template-social-span">
                <a href="#" id="facebook-check" class="email-template-social-media" style="{{ (isset($data['facebook']) && $data['facebook'] == 1)?'':'display:none;' }}">
                    <img src="{{dynamicAsset('/public/assets/admin/img/img/facebook.png')}}" alt="">
                </a>
                <a href="#" id="instagram-check" class="email-template-social-media" style="{{ (isset($data['instagram']) && $data['instagram'] == 1)?'':'display:none;' }}">
                    <img src="{{dynamicAsset('/public/assets/admin/img/img/instagram.png')}}" alt="">
                </a>
                <a href="#" id="twitter-check" class="email-template-social-media" style="{{ (isset($data['twitter']) && $data['twitter'] == 1)?'':'display:none;' }}">
                    <img src="{{dynamicAsset('/public/assets/admin/img/img/twitter.png')}}" alt="">
                </a>
                <a href="#" id="linkedin-check" class="email-template-social-media" style="{{ (isset($data['linkedin']) && $data['linkedin'] == 1)?'':'display:none;' }}">
                    <img src="{{dynamicAsset('/public/assets/admin/img/img/linkedin.png')}}" alt="">
                </a>
                <a href="#" id="pinterest-check" class="email-template-social-media" style="{{ (isset($data['pinterest']) && $data['pinterest'] == 1)?'':'display:none;' }}">
                    <img src="{{dynamicAsset('/public/assets/admin/img/img/pinterest.png')}}" alt="">
                </a>
            </span>
            <span class="copyright" id="mail-copyright">
                {{ $data['copyright_text']?? translate('Copyright 2023 6ammart. All right reserved') }}
            </span>
        </td>
    </tr>
</table>
<script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/common.js"></script>
