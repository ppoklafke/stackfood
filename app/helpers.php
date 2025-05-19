<?php

use App\Models\Admin;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\AdminWallet;
use App\Models\DeliveryMan;
use App\Models\WalletPayment;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\CentralLogics\OrderLogic;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderVerificationMail;
use App\CentralLogics\CustomerLogic;
use Illuminate\Support\Facades\Mail;
use App\Models\SubscriptionBillingAndRefundHistory;
use Brian2694\Toastr\Facades\Toastr;


if (! function_exists('translate')) {
    function translate($key, $replace = [])
    {
        if (strpos($key, 'validation.') === 0 || strpos($key, 'passwords.') === 0 || strpos($key, 'pagination.') === 0 || strpos($key, 'order_texts.') === 0) {
            return trans($key, $replace);
        }

        $key = strpos($key, 'messages.') === 0 ? substr($key, 9) : $key;
        $local = app()->getLocale();
        try {
            $lang_array = include(base_path('resources/lang/' . $local . '/messages.php'));
            $processed_key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));

            if (!array_key_exists($key, $lang_array)) {
                $lang_array[$key] = $processed_key;
                $str = "<?php return " . var_export($lang_array, true) . ";";
                file_put_contents(base_path('resources/lang/' . $local . '/messages.php'), $str);
                $result = $processed_key;
            } else {
                $result = trans('messages.' . $key, $replace);
            }
        } catch (\Exception $exception) {
            info([$exception->getFile(), $exception->getLine(), $exception->getMessage()]);
            $result = trans('messages.' . $key, $replace);
        }

        return $result;
    }
}

if (! function_exists('order_place')) {

    function order_place($data)
    {
        $order = Order::find($data->attribute_id);
        $order->order_status = 'confirmed';
        if ($order->payment_method != 'partial_payment') {
            $order->payment_method = $data->payment_method;
        }
        // $order->transaction_reference=$data->transaction_ref;
        $order->payment_status = 'paid';
        $order->confirmed = now();
        $order->save();

        if ($order->restaurant->restaurant_model == 'subscription' && isset($order->restaurant->restaurant_sub)) {
            if ($order->restaurant->restaurant_sub->max_order != "unlimited" && $order->restaurant->restaurant_sub->max_order > 0) {
                $order->restaurant->restaurant_sub()->decrement('max_order', 1);
            }
        }

        OrderLogic::update_unpaid_order_payment(order_id: $order->id, payment_method: $data->payment_method);
        //PlaceOrderMail
        try {
            Helpers::send_order_notification($order);


            $address = json_decode($order->delivery_address, true);

            $order_verification_mail_status = Helpers::get_mail_status('order_verification_mail_status_user');

            $notification_status = Helpers::getNotificationStatusData('customer', 'customer_delivery_verification');

            if ($notification_status?->mail_status == 'active' &&  config('mail.status') && config('order_delivery_verification') == 1 && $order_verification_mail_status == '1' && $order->is_guest == 0) {
                Mail::to($order->customer->email)->send(new OrderVerificationMail($order->otp, $order->customer->f_name));
            }

            if ($notification_status?->mail_status == 'active' && $order->is_guest == 1 && config('mail.status') && $order_verification_mail_status == '1' && isset($address['contact_person_email'])) {
                Mail::to($address['contact_person_email'])->send(new OrderVerificationMail($order->otp, $order->customer->f_name));
            }
        } catch (\Exception $exception) {
            info([$exception->getFile(), $exception->getLine(), $exception->getMessage()]);
        }
    }
}
if (! function_exists('order_failed')) {
    function order_failed($data)
    {
        $order = Order::find($data->attribute_id);
        $order->order_status = 'failed';
        if ($order->payment_method != 'partial_payment') {
            $order->payment_method = $data->payment_method;
        }
        $order->failed = now();
        $order->save();
    }
}

if (! function_exists('wallet_success')) {
    function wallet_success($data)
    {
        $order = WalletPayment::find($data->attribute_id);
        $order->payment_method = $data->payment_method;
        // $order->transaction_reference=$data->transaction_ref;
        $order->payment_status = 'success';
        $order->save();
        $wallet_transaction = CustomerLogic::create_wallet_transaction($data->payer_id, $data->payment_amount, 'add_fund', $data->payment_method);
        if ($wallet_transaction) {
            try {
                Helpers::add_fund_push_notification($data->payer_id,$data->payment_amount);
                $notification_status = Helpers::getNotificationStatusData('customer', 'customer_add_fund_to_wallet');

                if ($notification_status?->mail_status == 'active' && config('mail.status') && Helpers::get_mail_status('add_fund_mail_status_user') == '1') {
                    Mail::to($wallet_transaction->user->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction));
                }
            } catch (\Exception $exception) {
                info([$exception->getFile(), $exception->getLine(), $exception->getMessage()]);
            }
        }
    }
}
if (! function_exists('wallet_failed')) {
    function wallet_failed($data)
    {
        $order = WalletPayment::find($data->attribute_id);
        $order->payment_status = 'failed';
        $order->payment_method = $data->payment_method;
        $order->save();
    }
}


if (! function_exists('sub_success')) {
    function sub_success($data)
    {
        $type = 'renew';
        if ($data->attribute == 'restaurant_subscription_payment') {
            $type = 'new_plan';
        } elseif ($data->attribute == 'restaurant_subscription_new_join') {
            $type = 'new_join';
        }

        $pending_bill = SubscriptionBillingAndRefundHistory::where([
            'restaurant_id' => $data->payer_id,
            'transaction_type' => 'pending_bill',
            'is_success' => 0
        ])?->sum('amount') ?? 0;
        Helpers::subscription_plan_chosen(restaurant_id: $data->payer_id, package_id: $data->attribute_id, payment_method: $data->payment_method, discount: 0, pending_bill: $pending_bill, reference: $data->attribute, type: $type);
        if ($type !== 'new_join') {
            Toastr::success($type == 'renew' ?  translate('Subscription_Package_Renewed_Successfully.') : translate('Subscription_Package_Shifted_Successfully.'));
        }

        return true;
    }
}

if (! function_exists('sub_fail')) {
    function sub_fail($data)
    {
        return true;
    }
}


if (! function_exists('collect_cash_fail')) {
    function collect_cash_fail($data)
    {
        return 0;
    }
}
if (! function_exists('collect_cash_success')) {
    function collect_cash_success($data)
    {

        try {
            $account_transaction = new AccountTransaction();
            if ($data->attribute === 'restaurant_collect_cash_payments') {
                $restaurant = Restaurant::where('vendor_id', $data->attribute_id)->first();
                $restaurant->status = 1;
                $restaurant->save();
                $user_data = $restaurant?->vendor;
                $current_balance = $user_data?->wallet?->collected_cash ?? 0;
                $account_transaction->from_type = 'restaurant';
                $account_transaction->from_id = $restaurant?->vendor?->id;
                $account_transaction->created_by = 'restaurant';
            } elseif ($data->attribute === 'deliveryman_collect_cash_payments') {
                $user_data = DeliveryMan::findOrFail($data->attribute_id);
                $user_data->status = 1;
                $user_data->save();
                $current_balance = $user_data?->wallet?->collected_cash ?? 0;
                $account_transaction->from_type = 'deliveryman';
                $account_transaction->from_id = $user_data->id;
                $account_transaction->created_by = 'deliveryman';
            } else {
                return 0;
            }
            $account_transaction->method = $data->payment_method;
            $account_transaction->ref = $data->attribute;
            $account_transaction->amount = $data->payment_amount;
            $account_transaction->current_balance = $current_balance;

            DB::beginTransaction();
            $account_transaction->save();
            $user_data?->wallet?->decrement('collected_cash', $account_transaction->amount);
            AdminWallet::where('admin_id', Admin::where('role_id', 1)->first()->id)->increment('digital_received',  $account_transaction->amount);

            DB::commit();
        } catch (\Exception $exception) {
            info([$exception->getFile(), $exception->getLine(), $exception->getMessage()]);
            DB::rollBack();
        }
        try {

            $notification_status = Helpers::getNotificationStatusData('deliveryman', 'deliveryman_collect_cash');

            if ($data->attribute == 'deliveryman_collect_cash_payments' && config('mail.status') && Helpers::get_mail_status('cash_collect_mail_status_dm') == 1) {
                Mail::to($notification_status?->mail_status == 'active' && $user_data['email'])->send(new \App\Mail\CollectCashMail($account_transaction, $user_data['f_name']));
            }
        } catch (\Exception $exception) {
            info([$exception->getFile(), $exception->getLine(), $exception->getMessage()]);
        }
        return true;
    }
}

if (!function_exists('addon_published_status')) {
    function addon_published_status($module_name)
    {
        $is_published = 0;
        try {
            if(file_exists("Modules/{$module_name}/Addon/info.php")){
                $full_data = include("Modules/{$module_name}/Addon/info.php");
                $is_published = $full_data['is_published'] == 1 ? 1 : 0;
            }
            return $is_published;
        } catch (\Exception $exception) {
            info([$exception->getFile(), $exception->getLine(), $exception->getMessage()]);
            return 0;
        }
    }
}

if (!function_exists('dynamicAsset')) {
    function dynamicAsset(string $directory): string
    {
        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $result = str_replace('public/', '', $directory);
        } else {
            $result = $directory;
        }
        return asset($result);
    }
}
if (!function_exists('dynamicStorage')) {
    function dynamicStorage(string $directory): string
    {
        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $result = str_replace('storage/app/public', 'storage', $directory);
        } else {
            $result = $directory;
        }
        return asset($result);
    }
}

if (!function_exists('getWebConfig')) {
    function getWebConfig($name): string|object|array
    {
        $config = null;
        $check = ['currency_model', 'currency_symbol_position', 'system_default_currency', 'language', 'company_name', 'decimal_point_settings', 'product_brand', 'digital_product', 'company_email'];

        if (in_array($name, $check) && session()->has($name)) {
            $config = session($name);
        } else {
            $data =Helpers::getSettingsDataFromConfig(settings:$name);
            // $data = BusinessSetting::where(['key' => $name])->first();
            if (isset($data)) {
                $config = json_decode($data['value'], true);
                if (is_null($config)) {
                    $config = $data['value'];
                }
            }

            if (in_array($name, $check)) {
                session()->put($name, $config);
            }
        }

        return $config;
    }
}
