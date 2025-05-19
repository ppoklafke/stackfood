<div class="form-group">
    <label class="form-label">
        {{translate('Page Links')}}
    </label>
    <ul class="page-links-checkgrp">
        <li>
            <label class="form-check form--check">
                <input class="form-check-input privacy-check check-mail-element"  data-id="privacy-check" type="checkbox" name="privacy" value ="1" {{ (isset($data['privacy']) && $data['privacy'] == 1)?'checked':'' }}>
                <span class="form-check-label">{{translate('Privacy Policy')}}</span>
            </label>
        </li>
        <li>
            <label class="form-check form--check">
                <input class="form-check-input refund-check check-mail-element" data-id="refund-check"  type="checkbox" name="refund" value ="1" {{ (isset($data['refund']) && $data['refund'] == 1)?'checked':'' }}>
                <span class="form-check-label">{{translate('Refund Policy')}}</span>
            </label>
        </li>
        <li>
            <label class="form-check form--check">
                <input class="form-check-input cancelation-check check-mail-element" data-id="cancelation-check"  type="checkbox" name="cancelation" value ="1" {{ (isset($data['cancelation']) && $data['cancelation'] == 1)?'checked':'' }}>
                <span class="form-check-label">{{translate('Cancelation Policy')}}</span>
            </label>
        </li>
        <li>
            <label class="form-check form--check">
                <input class="form-check-input contact-check check-mail-element" data-id="contact-check" type="checkbox" name="contact" value ="1" {{ (isset($data['contact']) && $data['contact'] == 1)?'checked':'' }}>
                <span class="form-check-label">{{translate('Contact Us')}}</span>
            </label>
        </li>
    </ul>
</div>
<div class="form-group">
    <label class="form-label">
        {{translate('Social Media Links')}}
    </label>
    <ul class="page-links-checkgrp">
        <li>
            <label class="form-check form--check">
                <input class="form-check-input facebook-check check-mail-element" type="checkbox" data-id="facebook-check" name="facebook" value="1" {{ (isset($data['facebook']) && $data['facebook'] == 1)?'checked':'' }}>
                <span class="form-check-label">{{translate('Facebook')}}</span>
            </label>
        </li>
        <li>
            <label class="form-check form--check">
                <input class="form-check-input instagram-check check-mail-element" type="checkbox" data-id="instagram-check"  name="instagram" value="1" {{ (isset($data['instagram']) && $data['instagram'] == 1)?'checked':'' }}>
                <span class="form-check-label">{{translate('Instagram')}}</span>
            </label>
        </li>
        <li>
            <label class="form-check form--check">
                <input class="form-check-input twitter-check check-mail-element" type="checkbox" data-id="twitter-check" name="twitter" value="1" {{ (isset($data['twitter']) && $data['twitter'] == 1)?'checked':'' }}>
                <span class="form-check-label">{{translate('Twitter')}}</span>
            </label>
        </li>
        <li>
            <label class="form-check form--check">
                <input class="form-check-input linkedin-check check-mail-element" type="checkbox" data-id="linkedin-check"  name="linkedin" value="1" {{ (isset($data['linkedin']) && $data['linkedin'] == 1)?'checked':'' }}>
                <span class="form-check-label">{{translate('LinkedIn')}}</span>
            </label>
        </li>
        <li>
            <label class="form-check form--check">
                <input class="form-check-input pinterest-check check-mail-element" type="checkbox" data-id="pinterest-check"  name="pinterest" value="1" {{ (isset($data['pinterest']) && $data['pinterest'] == 1)?'checked':'' }}>
                <span class="form-check-label">{{translate('Pinterest')}}</span>
            </label>
        </li>
    </ul>
</div>
