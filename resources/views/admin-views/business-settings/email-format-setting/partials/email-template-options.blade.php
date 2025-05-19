<div class="d-flex justify-content-end mt-2">
    <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center"   id="see-how-it-works"  >
    </div>
</div>
<select id="mail-route-selector" class="custom-select w-auto min-width-170px">
    <option value="admin" {{ Request::is('admin/business-settings/email-setup/admin*') ? 'selected' : '' }}>{{ translate('Admin_Mail_Templates') }}</option>
    <option value="restaurant" {{ Request::is('admin/business-settings/email-setup/restaurant*') ? 'selected' : '' }}>{{ translate('Restaurant_Mail_Templates') }}</option>
    <option value="dm" {{ Request::is('admin/business-settings/email-setup/dm*') ? 'selected' : '' }}>{{ translate('Deliveryman_Mail_Templates') }}</option>
    <option value="user" {{ Request::is('admin/business-settings/email-setup/user*') ? 'selected' : '' }}>{{ translate('Customer_Mail_Templates') }}</option>
</select>

