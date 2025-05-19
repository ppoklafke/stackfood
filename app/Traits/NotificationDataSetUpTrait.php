<?php

namespace App\Traits;

use App\Models\NotificationSetting;
use App\Models\RestaurantNotificationSetting;


trait NotificationDataSetUpTrait
{
    public static function getAdminNotificationSetupData(): array
    {
        $data[] = [
            'title' => 'forget_password',
            'key' => 'forget_password',
            'type' => 'admin',
            'mail_status' => 'active',
            'sms_status' => 'active',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_forget_password',
        ];
        $data[] = [
            'title' => 'deliveryman_self_registration',
            'key' => 'deliveryman_self_registration',
            'type' => 'admin',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_deliveryman_self_registration',
        ];
        $data[] = [
            'title' => 'restaurant_self_registration',
            'key' => 'restaurant_self_registration',
            'type' => 'admin',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_restaurant_self_registration',
        ];
        $data[] = [
            'title' => 'campaign_join_request',
            'key' => 'campaign_join_request',
            'type' => 'admin',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_campaign_join_request',
        ];
        $data[] = [
            'title' => 'withdraw_request',
            'key' => 'withdraw_request',
            'type' => 'admin',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_withdraw_request',
        ];
        $data[] = [
            'title' => 'order_refund_request',
            'key' => 'order_refund_request',
            'type' => 'admin',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_order_refund_request',
        ];

        $data[] = [
            'title' => 'advertisement_add',
            'key' => 'advertisement_add',
            'type' => 'admin',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_advertisement_add',
        ];
        $data[] = [
            'title' => 'advertisement_update',
            'key' => 'advertisement_update',
            'type' => 'admin',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_advertisement_update',
        ];

        //delivery man

        $data[] = [
            'title' => 'deliveryman_registration',
            'key' => 'deliveryman_registration',
            'type' => 'deliveryman',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_deliveryman_registration',
        ];
        $data[] = [
            'title' => 'deliveryman_registration_approval',
            'key' => 'deliveryman_registration_approval',
            'type' => 'deliveryman',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_deliveryman_registration_approval',
        ];
        $data[] = [
            'title' => 'deliveryman_registration_deny',
            'key' => 'deliveryman_registration_deny',
            'type' => 'deliveryman',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_deliveryman_registration_deny',
        ];
        $data[] = [
            'title' => 'deliveryman_account_block',
            'key' => 'deliveryman_account_block',
            'type' => 'deliveryman',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_deliveryman_account_block',
        ];
        $data[] = [
            'title' => 'deliveryman_account_unblock',
            'key' => 'deliveryman_account_unblock',
            'type' => 'deliveryman',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_deliveryman_account_unblock',
        ];
        $data[] = [
            'title' => 'deliveryman_forget_password',
            'key' => 'deliveryman_forget_password',
            'type' => 'deliveryman',
            'mail_status' => 'active',
            'sms_status' => 'active',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_deliveryman_forget_password',
        ];
        $data[] = [
            'title' => 'deliveryman_collect_cash',
            'key' => 'deliveryman_collect_cash',
            'type' => 'deliveryman',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_deliveryman_collect_cash',
        ];

        $data[] = [
            'title' => 'deliveryman_order_notification',
            'key' => 'deliveryman_order_notification',
            'type' => 'deliveryman',
            'mail_status' => 'disable',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_order_notification_to_deliveryman',
        ];
        $data[] = [
            'title' => 'deliveryman_order_assign_or_unassign',
            'key' => 'deliveryman_order_assign_unassign',
            'type' => 'deliveryman',
            'mail_status' => 'disable',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_deliveryman_order_assign_or_unassign',
        ];



        // restaurant

        $data[] = [
            'title' => 'restaurant_registration',
            'key' => 'restaurant_registration',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_restaurant_registration',
        ];
        $data[] = [
            'title' => 'restaurant_registration_approval',
            'key' => 'restaurant_registration_approval',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_restaurant_registration_approval',
        ];
        $data[] = [
            'title' => 'restaurant_registration_deny',
            'key' => 'restaurant_registration_deny',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_restaurant_registration_deny',
        ];
        $data[] = [
            'title' => 'restaurant_account_block',
            'key' => 'restaurant_account_block',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_account_block',
        ];
        $data[] = [
            'title' => 'restaurant_account_unblock',
            'key' => 'restaurant_account_unblock',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_account_unblock',
        ];
        $data[] = [
            'title' => 'restaurant_withdraw_approve',
            'key' => 'restaurant_withdraw_approve',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_withdraw_approve',
        ];
        $data[] = [
            'title' => 'restaurant_withdraw_rejaction',
            'key' => 'restaurant_withdraw_rejaction',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_withdraw_rejaction',
        ];
        $data[] = [
            'title' => 'restaurant_campaign_join_request',
            'key' => 'restaurant_campaign_join_request',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_restaurant_campaign_join_request',
        ];
        $data[] = [
            'title' => 'restaurant_campaign_join_rejaction',
            'key' => 'restaurant_campaign_join_rejaction',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_campaign_join_rejaction',
        ];
        $data[] = [
            'title' => 'restaurant_campaign_join_approval',
            'key' => 'restaurant_campaign_join_approval',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_campaign_join_approval',
        ];
        $data[] = [
            'title' => 'restaurant_order_notification',
            'key' => 'restaurant_order_notification',
            'type' => 'restaurant',
            'mail_status' => 'disable',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_order_notification',
        ];

        $data[] = [
            'title' => 'restaurant_advertisement_create_by_admin',
            'key' => 'restaurant_advertisement_create_by_admin',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_advertisement_create_by_admin',
        ];
        $data[] = [
            'title' => 'restaurant_advertisement_approval',
            'key' => 'restaurant_advertisement_approval',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_advertisement_approval',
        ];
        $data[] = [
            'title' => 'restaurant_advertisement_deny',
            'key' => 'restaurant_advertisement_deny',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_advertisement_deny',
        ];
        $data[] = [
            'title' => 'restaurant_advertisement_resume',
            'key' => 'restaurant_advertisement_resume',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_advertisement_resume',
        ];
        $data[] = [
            'title' => 'restaurant_advertisement_pause',
            'key' => 'restaurant_advertisement_pause',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_advertisement_pause',
        ];

        // Customer
        $data[] = [
            'title' => 'customer_registration',
            'key' => 'customer_registration',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_customer_registration',
        ];
        $data[] = [
            'title' => 'customer_pos_registration',
            'key' => 'customer_pos_registration',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_customer_pos_registration',
        ];

        $data[] = [
            'title' => 'customer_order_notification',
            'key' => 'customer_order_notification',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_order_notification',
        ];

        $data[] = [
            'title' => 'customer_delivery_verification',
            'key' => 'customer_delivery_verification',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_delivery_verification',
        ];

        $data[] = [
            'title' => 'customer_refund_request_approval',
            'key' => 'customer_refund_request_approval',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_refund_request_approval',
        ];
        $data[] = [
            'title' => 'customer_refund_request_rejaction',
            'key' => 'customer_refund_request_rejaction',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_refund_request_rejaction',
        ];
        $data[] = [
            'title' => 'customer_add_fund_to_wallet',
            'key' => 'customer_add_fund_to_wallet',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_add_fund_to_wallet',
        ];
        $data[] = [
            'title' => 'customer_offline_payment_approve',
            'key' => 'customer_offline_payment_approve',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_offline_payment_approve',
        ];
        $data[] = [
            'title' => 'customer_offline_payment_deny',
            'key' => 'customer_offline_payment_deny',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_offline_payment_deny',
        ];
        $data[] = [
            'title' => 'customer_account_block',
            'key' => 'customer_account_block',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_account_block',
        ];
        $data[] = [
            'title' => 'customer_account_unblock',
            'key' => 'customer_account_unblock',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_account_unblock',
        ];
        $data[] = [
            'title' => 'customer_cashback',
            'key' => 'customer_cashback',
            'type' => 'customer',
            'mail_status' => 'disable',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_cashback',
        ];
        $data[] = [
            'title' => 'customer_referral_bonus_earning',
            'key' => 'customer_referral_bonus_earning',
            'type' => 'customer',
            'mail_status' => 'disable',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_referral_bonus_earning',
        ];
        $data[] = [
            'title' => 'customer_new_referral_join',
            'key' => 'customer_new_referral_join',
            'type' => 'customer',
            'mail_status' => 'disable',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_new_referral_join',
        ];

        return $data;
    }
    public static function getRestaurantNotificationSetupData($id): array
    {
        $data[] = [
            'title' => 'restaurant_account_block',
            'key' => 'restaurant_account_block',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_account_block',
        ];
        $data[] = [
            'title' => 'restaurant_account_unblock',
            'key' => 'restaurant_account_unblock',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_account_unblock',
        ];
        $data[] = [
            'title' => 'restaurant_withdraw_approve',
            'key' => 'restaurant_withdraw_approve',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_withdraw_approve',
        ];
        $data[] = [
            'title' => 'restaurant_withdraw_rejaction',
            'key' => 'restaurant_withdraw_rejaction',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_withdraw_rejaction',
        ];
        $data[] = [
            'title' => 'restaurant_campaign_join_request',
            'key' => 'restaurant_campaign_join_request',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'disable',
            'sub_title' => 'Get_notification_on_restaurant_campaign_join_request',
        ];
        $data[] = [
            'title' => 'restaurant_campaign_join_rejaction',
            'key' => 'restaurant_campaign_join_rejaction',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_campaign_join_rejaction',
        ];
        $data[] = [
            'title' => 'restaurant_campaign_join_approval',
            'key' => 'restaurant_campaign_join_approval',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_campaign_join_approval',
        ];
        $data[] = [
            'title' => 'restaurant_order_notification',
            'key' => 'restaurant_order_notification',
            'restaurant_id' => $id,
            'mail_status' => 'disable',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_order_notification',
        ];

        $data[] = [
            'title' => 'restaurant_advertisement_create_by_admin',
            'key' => 'restaurant_advertisement_create_by_admin',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_advertisement_create_by_admin',
        ];
        $data[] = [
            'title' => 'restaurant_advertisement_approval',
            'key' => 'restaurant_advertisement_approval',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_advertisement_approval',
        ];
        $data[] = [
            'title' => 'restaurant_advertisement_deny',
            'key' => 'restaurant_advertisement_deny',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_advertisement_deny',
        ];
        $data[] = [
            'title' => 'restaurant_advertisement_resume',
            'key' => 'restaurant_advertisement_resume',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_advertisement_resume',
        ];
        $data[] = [
            'title' => 'restaurant_advertisement_pause',
            'key' => 'restaurant_advertisement_pause',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_restaurant_advertisement_pause',
        ];

        return $data;
    }


    public static function updateAdminNotificationSetupData()
    {
        $data[] = [];

        foreach ($data as $item) {
            NotificationSetting::where('key', $item['key'])->where('type', $item['type'])->update([
                'push_notification_status' => $item['push_notification_status']
            ]);
        }
        return true;
    }
    public static function deleteAdminNotificationSetupData()
    {
        $data[] = [
            'title' => 'customer_forget_password',
            'key' => 'customer_forget_password',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'active',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_customer_forget_password',
        ];
        $data[] = [
            'title' => 'customer_registration_otp',
            'key' => 'customer_registration_otp',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'active',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_customer_registration_otp',
        ];
        $data[] = [
            'title' => 'customer_login_otp',
            'key' => 'customer_login_otp',
            'type' => 'customer',
            'mail_status' => 'active',
            'sms_status' => 'active',
            'push_notification_status' => 'disable',
            'sub_title' => 'Sent_notification_on_customer_login_otp',
        ];
        foreach ($data as $item) {
            NotificationSetting::where('key', $item['key'])->where('type', $item['type'])->delete();
        }
        return true;
    }
    public static function addNewAdminNotificationSetupData()
    {

        $data[] = [
            'title' => 'restaurant_subscription_success',
            'key' => 'restaurant_subscription_success',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_subscription_success',
        ];
        $data[] = [
            'title' => 'restaurant_subscription_renew',
            'key' => 'restaurant_subscription_renew',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_subscription_renew',
        ];
        $data[] = [
            'title' => 'restaurant_subscription_shift',
            'key' => 'restaurant_subscription_shift',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_subscription_shift',
        ];
        $data[] = [
            'title' => 'restaurant_subscription_cancel',
            'key' => 'restaurant_subscription_cancel',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_restaurant_subscription_cancel',
        ];
        $data[] = [
            'title' => 'restaurant_subscription_plan_update',
            'key' => 'restaurant_subscription_plan_update',
            'type' => 'restaurant',
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'inactive',
            'sub_title' => 'Sent_notification_on_restaurant_subscription_plan_update',
        ];
        $data[] = [
            'title' => 'customer_dine_in_table_or_token',
            'key' => 'customer_dine_in_table_or_token',
            'type' => 'customer',
            'mail_status' => 'disable',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Sent_notification_on_customer_dine_in_table_or_token',
        ];

        foreach ($data as $item) {

            if (NotificationSetting::where('key', $item['key'])->where('type', $item['type'])->doesntExist()) {
                $notificationsetting = NotificationSetting::firstOrNew(
                    ['key' => $item['key'], 'type' => $item['type']]
                );
                $notificationsetting->title = $item['title'];
                $notificationsetting->sub_title = $item['sub_title'];
                $notificationsetting->mail_status = $item['mail_status'];
                $notificationsetting->sms_status = $item['sms_status'];
                $notificationsetting->push_notification_status = $item['push_notification_status'];
                $notificationsetting->save();
            }
        }

        self::deleteAdminNotificationSetupData();
        return true;
    }
    public static function addNewRestaurantNotificationSetupData($id)
    {


        $data[] = [
            'title' => 'subscription_success',
            'key' => 'restaurant_subscription_success',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_subscription_success',
        ];
        $data[] = [
            'title' => 'subscription_renew',
            'key' => 'restaurant_subscription_renew',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_subscription_renew',
        ];
        $data[] = [
            'title' => 'subscription_shift',
            'key' => 'restaurant_subscription_shift',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_subscription_shift',
        ];
        $data[] = [
            'title' => 'subscription_cancel',
            'key' => 'restaurant_subscription_cancel',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_subscription_cancel',
        ];
        $data[] = [
            'title' => 'subscription_plan_update',
            'key' => 'restaurant_subscription_plan_update',
            'restaurant_id' => $id,
            'mail_status' => 'active',
            'sms_status' => 'disable',
            'push_notification_status' => 'active',
            'sub_title' => 'Get_notification_on_subscription_plan_update',
        ];




        foreach ($data as $item) {

            if (RestaurantNotificationSetting::where('key', $item['key'])->where('restaurant_id', $id)->doesntExist()) {
                $notificationsetting = RestaurantNotificationSetting::firstOrNew(
                    ['key' => $item['key'], 'restaurant_id' => $id]
                );
                $notificationsetting->title = $item['title'];
                $notificationsetting->sub_title = $item['sub_title'];
                $notificationsetting->mail_status = $item['mail_status'];
                $notificationsetting->sms_status = $item['sms_status'];
                $notificationsetting->push_notification_status = $item['push_notification_status'];
                $notificationsetting->save();
            }
        }
        return true;
    }
}
