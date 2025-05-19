<?php

namespace App\CentralLogics;

use Exception;
use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Incentive;
use App\Models\Restaurant;
use App\Models\AdminWallet;
use App\Models\DeliveryMan;
use App\Models\IncentiveLog;
use App\Models\OrderPayment;
use App\Models\Subscription;
use App\Models\BusinessSetting;
use App\Models\SubscriptionLog;
use App\Models\OrderTransaction;
use App\Models\RestaurantWallet;
use App\Models\DeliveryManWallet;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderLogic
{
    public static function create_transaction($order, $received_by=false, $status = null)
    {
        $comission = !isset($order?->restaurant?->comission)?\App\Models\BusinessSetting::where('key','admin_commission')->first()?->value:$order?->restaurant?->comission;

        $admin_subsidy = 0;
        $amount_admin = 0;
        $restaurant_d_amount = 0;
        $admin_coupon_discount_subsidy =0;
        $restaurant_subsidy =0;
        $restaurant_coupon_discount_subsidy =0;
        $restaurant_discount_amount=0;
        $restaurant= $order->restaurant;
        $rest_sub = $restaurant?->restaurant_sub;
        $ref_bonus_amount=0;

        // free delivery by admin
        if($order->free_delivery_by == 'admin')
        {
            $admin_subsidy = $order->original_delivery_charge;
            Helpers::expenseCreate( amount:$order->original_delivery_charge, type:'free_delivery',datetime:now(),order_id:  $order->id,created_by:  $order->free_delivery_by);
        }
        // free delivery by restaurant
        if($order->free_delivery_by == 'vendor')
        {
            $restaurant_subsidy = $order->original_delivery_charge;
            Helpers::expenseCreate( amount:$order->original_delivery_charge,type:'free_delivery',datetime:now(),order_id:  $order->id,created_by:  $order->free_delivery_by,restaurant_id:$order?->restaurant?->id);
        }
        // coupon discount by Admin
        if($order->coupon_created_by == 'admin')
        {
            $admin_coupon_discount_subsidy = $order->coupon_discount_amount;
            Helpers::expenseCreate( amount:$admin_coupon_discount_subsidy,type:'coupon_discount',datetime:now(),order_id:  $order->id,created_by:  $order->coupon_created_by);
        }
        // 1st order discount by Admin
        if($order->ref_bonus_amount > 0)
        {
            $ref_bonus_amount = $order->ref_bonus_amount;
            Helpers::expenseCreate(amount:$ref_bonus_amount,type:'referral_discount',datetime:now(),created_by:'admin',order_id:$order->id);
        }
        // coupon discount by restaurant
        if($order->coupon_created_by == 'vendor')
        {
            $restaurant_coupon_discount_subsidy = $order->coupon_discount_amount;
            Helpers::expenseCreate( amount:$restaurant_coupon_discount_subsidy,type:'coupon_discount',datetime:now(),order_id:  $order->id,created_by:  $order->coupon_created_by, restaurant_id:$order?->restaurant?->id);
        }

        if($order->restaurant_discount_amount > 0  && $order->discount_on_product_by == 'vendor')
        {
            if($restaurant->restaurant_model == 'subscription' && isset($rest_sub)){
                $restaurant_d_amount=  $order->restaurant_discount_amount;
                Helpers::expenseCreate( amount:$restaurant_d_amount,type:'discount_on_product',datetime:now(),order_id:  $order->id,created_by:  'vendor',restaurant_id:$order?->restaurant?->id);
            } else{
                $amount_admin = $comission?($order->restaurant_discount_amount/ 100) * $comission:0;
                $restaurant_d_amount=  $order->restaurant_discount_amount- $amount_admin;
                Helpers::expenseCreate( amount:$restaurant_d_amount,type:'discount_on_product',datetime:now(),order_id:  $order->id,created_by:  'vendor',restaurant_id:$order?->restaurant?->id);
                Helpers::expenseCreate( amount:$amount_admin,type:'discount_on_product',datetime:now(),order_id:  $order->id,created_by:  'admin');
            }
        }

        if($order->restaurant_discount_amount > 0  && $order->discount_on_product_by == 'admin')
        {
            $restaurant_discount_amount=$order->restaurant_discount_amount;
            Helpers::expenseCreate( amount:$restaurant_discount_amount,type:'discount_on_product',datetime:now(),order_id:  $order->id,created_by:  'admin');
        }


        if($order?->cashback_history){
            self::cashbackToWallet($order);
        }


        $order_amount = $order->order_amount - $order->additional_charge - $order->extra_packaging_amount - $order->delivery_charge - $order->total_tax_amount - $order->dm_tips + $order->coupon_discount_amount + $restaurant_discount_amount + $ref_bonus_amount;

        if($restaurant->restaurant_model == 'subscription' && isset($rest_sub)){
            $comission_amount =0;
            $subscription_mode= 1;
            $commission_percentage= 0;
        }
        else{
            $comission_amount = $comission?($order_amount/ 100) * $comission:0;
            $subscription_mode= 0;
            $commission_percentage= $comission;
        }

        if(($restaurant->restaurant_model == 'subscription' &&  $rest_sub?->self_delivery == 1) || ($restaurant->restaurant_model != 'subscription' && $restaurant->self_delivery_system)){
            $comission_on_delivery =0;
            $comission_on_actual_delivery_fee =0;
        }
        else{
            $delivery_charge_comission_percentage = BusinessSetting::where('key', 'delivery_charge_comission')->first()?->value ?? 0;

            $comission_on_delivery = $delivery_charge_comission_percentage * ( $order->original_delivery_charge / 100 );
            $comission_on_actual_delivery_fee = ($order->delivery_charge > 0) ? $comission_on_delivery : 0;
        }
        $restaurant_amount =$order_amount + $order->total_tax_amount + $order->extra_packaging_amount - $comission_amount - $restaurant_coupon_discount_subsidy ;
        try{
            OrderTransaction::insert([
                'vendor_id' =>$order->restaurant->vendor->id,
                'delivery_man_id'=>$order->delivery_man_id,
                'order_id' =>$order->id,
                'order_amount'=>$order->order_amount,
                'restaurant_amount'=>$restaurant_amount,
                'admin_commission'=>$comission_amount + $order->additional_charge -  $admin_subsidy - $admin_coupon_discount_subsidy - $ref_bonus_amount - $restaurant_discount_amount,
                //add a new column. add the comission here
                'delivery_charge'=>$order->delivery_charge - $comission_on_actual_delivery_fee,//minus here
                'original_delivery_charge'=>$order->original_delivery_charge - $comission_on_delivery,//calculate the comission with this. minus here
                'tax'=>$order->total_tax_amount,
                'received_by'=> $received_by?$received_by:'admin',
                'zone_id'=>$order->zone_id,
                'status'=> $status,
                'dm_tips'=> $order->dm_tips,
                'created_at' => now(),
                'updated_at' => now(),
                'delivery_fee_comission'=>$comission_on_actual_delivery_fee,
                'admin_expense'=>$admin_subsidy + $admin_coupon_discount_subsidy + $restaurant_discount_amount + $amount_admin + $ref_bonus_amount,
                'restaurant_expense'=>$restaurant_subsidy + $restaurant_coupon_discount_subsidy ,
                // for restaurant business model
                'is_subscribed'=> $subscription_mode,
                'commission_percentage'=> $commission_percentage,
                'discount_amount_by_restaurant' => $restaurant_coupon_discount_subsidy + $restaurant_d_amount + $restaurant_subsidy,
                // for subscription order
                'is_subscription' => isset($order->subscription_id) ?  1 : 0 ,
                'additional_charge' => $order->additional_charge,
                'extra_packaging_amount' => $order->extra_packaging_amount,
                'ref_bonus_amount' => $order->ref_bonus_amount,
            ]);
            $adminWallet = AdminWallet::firstOrNew(
                ['admin_id' => Admin::where('role_id', 1)->first()->id]
            );
            $vendorWallet = RestaurantWallet::firstOrNew(
                ['vendor_id' => $order->restaurant->vendor->id]
            );
            if($order->delivery_man &&
           (($restaurant->restaurant_model == 'subscription' &&  $rest_sub?->self_delivery == 0) || ($restaurant->restaurant_model != 'subscription' && $restaurant->self_delivery_system == 0))
            ){
                $dmWallet = DeliveryManWallet::firstOrNew(
                    ['delivery_man_id' => $order->delivery_man_id]
                );
                if (isset($order->delivery_man->earning) && $order->delivery_man->earning == 1) {
                    self::check_incentive($order->zone_id, $order->delivery_man_id, $order->delivery_man->todays_earning()->sum('original_delivery_charge'), $order->delivery_man->incentive);

                    $dmWallet->total_earning = $dmWallet->total_earning + $order->dm_tips + $order->original_delivery_charge - $comission_on_delivery;
                } else {
                    $adminWallet->total_commission_earning = $adminWallet->total_commission_earning + $order->dm_tips + $order->original_delivery_charge - $comission_on_delivery;
                }
            }

            $adminWallet->total_commission_earning = $adminWallet->total_commission_earning + $comission_amount + $comission_on_actual_delivery_fee - $admin_subsidy - $admin_coupon_discount_subsidy -$restaurant_discount_amount + $order->additional_charge  - $ref_bonus_amount;

            if(($restaurant->restaurant_model == 'subscription' &&  $rest_sub?->self_delivery == 1) || ($restaurant->restaurant_model != 'subscription' && $restaurant->self_delivery_system == 1))
            {
                $vendorWallet->total_earning = $vendorWallet->total_earning + $order->delivery_charge + $order->dm_tips;
            }
            else{
                $adminWallet->delivery_charge = $adminWallet->delivery_charge + $order->delivery_charge - $comission_on_actual_delivery_fee;
            }
            $vendorWallet->total_earning = $vendorWallet->total_earning + $restaurant_amount;
            try
            {
                DB::beginTransaction();
                $unpaid_payment = OrderPayment::where('payment_status','unpaid')->where('order_id',$order->id)->first()?->payment_method;
                $unpaid_pay_method = 'digital_payment';
                if($unpaid_payment){
                    $unpaid_pay_method = $unpaid_payment;
                }

                if($received_by=='admin')
                {
                    $adminWallet->digital_received = $adminWallet->digital_received + ($order->order_amount - $order->partially_paid_amount);
                }
                else if($received_by=='restaurant' && ($order->payment_method == "cash_on_delivery" || $unpaid_pay_method == 'cash_on_delivery'))
                {

                    $restaurant_over_flow =  true ;
                    $vendorWallet->collected_cash = $vendorWallet->collected_cash + ($order->order_amount - $order->partially_paid_amount);
                }
                else if($received_by==false)
                {
                    $adminWallet->manual_received = $adminWallet->manual_received + ($order->order_amount - $order->partially_paid_amount);
                }
                else if($received_by=='deliveryman' && $order->delivery_man->type == 'zone_wise' && ($order->payment_method == "cash_on_delivery" || $unpaid_pay_method == 'cash_on_delivery'))
                {
                    if(!isset($dmWallet)) {
                        $dmWallet = DeliveryManWallet::firstOrNew(
                            ['delivery_man_id' => $order->delivery_man_id]
                        );
                    }

                $dmWallet->collected_cash=$dmWallet->collected_cash + ($order->order_amount-$order->partially_paid_amount);
                $dm_over_flow =  true ;

            }
                if(isset($dmWallet)) {
                    $dmWallet->save();
                }
                $vendorWallet->save();
                $adminWallet->save();




                if(isset($restaurant_over_flow) ){
                    self::create_account_transaction_for_collect_cash(old_collected_cash:$vendorWallet->collected_cash , from_type:'restaurant' , from_id: $order->restaurant->vendor->id , amount: $order->order_amount - $order->partially_paid_amount ,order_id: $order->id);
                }
                if(isset($dm_over_flow)){
                    self::create_account_transaction_for_collect_cash(old_collected_cash:$dmWallet->collected_cash , from_type:'deliveryman' , from_id: $order->delivery_man_id , amount: $order->order_amount - $order->partially_paid_amount ,order_id: $order->id);
                }



                self::update_unpaid_order_payment(order_id:$order->id, payment_method:$order->payment_method);

                DB::commit();
                if($order->is_guest  == 0){
                    $ref_status = BusinessSetting::where('key','ref_earning_status')->first()?->value;
                    if(isset($order?->customer?->ref_by) && $order?->customer?->order_count == 0  && $ref_status == 1){
                        $ref_code_exchange_amt = BusinessSetting::where('key','ref_earning_exchange_rate')->first()?->value;
                        $referar_user=User::where('id',$order?->customer?->ref_by)->first();
                        $refer_wallet_transaction = CustomerLogic::create_wallet_transaction(user_id:$referar_user?->id, amount:$ref_code_exchange_amt, transaction_type:'referrer',referance:$order?->customer?->phone);
                        $customer_push_notification_status=Helpers::getNotificationStatusData('customer','customer_referral_bonus_earning');
                        $notification_data = [
                            'title' => translate('messages.Congratulation'),
                            'description' => translate('You_have_received').' '.Helpers::format_currency($ref_code_exchange_amt).' '.translate('in_your_wallet_as').' '.$order?->customer?->f_name.' '.$order?->customer?->l_name.' '.translate('you_referred_completed_thier_first_order') ,
                            'order_id' => '',
                            'image' => '',
                            'type' => 'referral_earn',
                        ];

                        if($customer_push_notification_status?->push_notification_status  == 'active' && $referar_user?->cm_firebase_token){
                            Helpers::send_push_notif_to_device($referar_user?->cm_firebase_token, $notification_data);
                            DB::table('user_notifications')->insert([
                                'data' => json_encode($notification_data),
                                'user_id' => $referar_user?->id,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                        try{
                            $notification_status= Helpers::getNotificationStatusData('customer','customer_add_fund_to_wallet');
                            Helpers::add_fund_push_notification($referar_user->id,$ref_code_exchange_amt);
                            if($notification_status?->mail_status == 'active' && config('mail.status') && $referar_user?->email && Helpers::get_mail_status('add_fund_mail_status_user') == '1') {
                                Mail::to($referar_user->email)->send(new \App\Mail\AddFundToWallet($refer_wallet_transaction));
                                }
                            } catch(\Exception $exception){
                                info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
                            }
                    }

                    if($order->user_id) CustomerLogic::create_loyalty_point_transaction(user_id:$order->user_id,referance: $order->id, amount:$order->order_amount,transaction_type: 'order_place');
                }
            }
            catch(\Exception $exception)
            {
                DB::rollBack();
                info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
                return false;
            }
        }
        catch(\Exception $exception){
            info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
            return false;
        }
        return true;
    }
    public static function refund_before_delivered($order){
        $adminWallet = AdminWallet::firstOrNew(
            ['admin_id' => Admin::where('role_id', 1)->first()->id]
        );
        if ($order->payment_method == 'cash_on_delivery') {
            return false;
        }
            if(($order->payment_status == "paid")){
                $adminWallet->digital_received = $adminWallet->digital_received - $order->order_amount;
                $adminWallet->save();
                if ($order->payment_status == "paid" && BusinessSetting::where('key', 'wallet_add_refund')->first()?->value == 1 && $order->is_guest  == 0) {
                    CustomerLogic::create_wallet_transaction(user_id:$order->user_id, amount:$order->order_amount, transaction_type:'order_refund', referance:$order->id);
                }
            }elseif(($order->payment_status == "partially_paid")){
                $adminWallet->digital_received = $adminWallet->digital_received - $order->partially_paid_amount;
                $adminWallet->save();
                if (BusinessSetting::where('key', 'wallet_add_refund')->first()?->value == 1 && $order->is_guest  == 0) {
                    CustomerLogic::create_wallet_transaction($order->user_id, $order->partially_paid_amount, 'order_refund', $order->id);
                }
            }
        return true;
    }


    public static function refund_order($order)
    {
        $order_transaction = $order->transaction;
        if($order_transaction == null || $order->restaurant == null)
        {
            return false;
        }
        $received_by = $order_transaction->received_by;

        $adminWallet = AdminWallet::firstOrNew(
            ['admin_id' => Admin::where('role_id', 1)->first()->id]
        );

        $vendorWallet = RestaurantWallet::firstOrNew(
            ['vendor_id' => $order->restaurant->vendor->id]
        );

        $adminWallet->total_commission_earning = $adminWallet->total_commission_earning - $order_transaction->admin_commission;

        $vendorWallet->total_earning = $vendorWallet->total_earning - $order_transaction->restaurant_amount;

        $refund_amount = $order->order_amount - $order->additional_charge - $order->extra_packaging_amount;

        $status = 'refunded_with_delivery_charge';
        if($order->order_status == 'delivered' || $order->order_status == 'refund_requested'|| $order->order_status == 'refund_request_canceled')
        {
            $refund_amount = $order->order_amount - $order->delivery_charge - $order->dm_tips - $order->additional_charge - $order->extra_packaging_amount;
            $status = 'refunded_without_delivery_charge';
        }
        else
        {
            $adminWallet->delivery_charge = $adminWallet->delivery_charge - $order_transaction->delivery_charge;
        }
        try
        {
            DB::beginTransaction();
            $partially_paid = OrderPayment::where('payment_method','cash_on_delivery')->where('order_id',$order->id)->exists() ?? false;

            if($partially_paid){
                $refund_amount = $refund_amount - $order->partially_paid_amount;
            }


            if($received_by=='admin')
            {
                if($order->delivery_man_id && $order->payment_method != "cash_on_delivery")
                {
                    $adminWallet->digital_received = $adminWallet->digital_received - $refund_amount;
                }
                else
                {
                    $adminWallet->manual_received = $adminWallet->manual_received - $refund_amount;
                }

            }
            else if($received_by=='restaurant')
            {
                $vendorWallet->collected_cash = $vendorWallet->collected_cash - $refund_amount;
            }

            else if($received_by=='deliveryman')
            {
                $dmWallet = DeliveryManWallet::firstOrNew(
                    ['delivery_man_id' => $order->delivery_man_id]
                );
                $dmWallet->collected_cash=$dmWallet->collected_cash - $refund_amount;
                $dmWallet->save();
            }
            $order_transaction->status = $status;
            $order_transaction->save();

            $adminWallet->save();
            $vendorWallet->save();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return false;
        }
        return true;

    }


    public static function check_incentive($zone_id, $delivery_man_id, $delivery_man_earning, $dm_incentive)
    {
        $incentive = Incentive::where('zone_id', $zone_id)->where('earning', '<=', $delivery_man_earning)->orderBy('earning', 'desc')->first();

        if ($dm_incentive) {
            if ($incentive && $dm_incentive->earning != $incentive->earning){
                $dm_incentive->earning = $incentive ? $incentive->earning : $dm_incentive->earning;
                $dm_incentive->incentive = $incentive ? $incentive->incentive : $dm_incentive->incentive;
            }
        } else {
            $dm_incentive = new IncentiveLog();
            $dm_incentive->earning = $incentive ? $incentive->earning : 0;
            $dm_incentive->incentive = $incentive ? $incentive->incentive : 0;
            $dm_incentive->delivery_man_id = $delivery_man_id;
            $dm_incentive->zone_id = $zone_id;
            $dm_incentive->date = now();
            $dm_incentive->status = 'pending';
        }
        $dm_incentive->today_earning = $delivery_man_earning;
        $dm_incentive->save();
        return true;
    }

    public static function create_subscription_log($id=null)
    {
        $order = Order::find($id);
        if(!isset($order)  || !isset($order?->subscription?->schedule) || !isset($order?->subscription?->schedule_today) || isset($order?->subscription_log ) || $order?->restaurant?->restaurant_model == 'unsubscribed'){
            return true;
        }

        $day = $order->subscription->schedule_today->day ??  $order->subscription->schedule->day ?? 0;
        $today = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][$day] ??'Sun';
        $nextdate = date('Y-m-d', strtotime('next ' . $today));

        $time= $order->subscription->schedule_today->time ?? $order->subscription->schedule->time;
        $schedule_at = $day != 0 ? $nextdate : now()->format('Y-m-d');
        $subscription_log = new SubscriptionLog();
        $subscription_log->subscription_id = $order->subscription_id;
        $subscription_log->order_id = $order->id;
        $subscription_log->order_status = 'pending';
        $subscription_log->schedule_at = $schedule_at.' '.$time;
        $subscription_log->updated_at = now();
        $subscription_log->created_at = now();
        $order->subscription_log()->save($subscription_log);
        $order->order_status = 'pending';
        $order->payment_status='unpaid';
        $order->schedule_at = $schedule_at.' '.$time ;
        $order->save();

        Helpers::send_order_notification($order);

        return true;
    }

    public static function update_subscription_log(Order $order):void
    {
        if(!isset($order?->subscription_log) || !isset($order->subscription_id)){
            return ;
        }
        $schedule_today = $order->subscription_log;
        $schedule_today->order_status = $order->order_status;
        $schedule_today->delivery_man_id = $order->delivery_man_id;
        if($order->order_status != 'pending')$schedule_today->{$order->order_status} = now();
        $schedule_today->save();

        if($order->order_status == 'delivered'){
            $subscription = $order->subscription;
            $subscription->billing_amount += $order->order_amount;
            $subscription->paid_amount += $order->order_amount;
            $subscription->save();

            $order->delivery_man_id = null;
            $order->save();
        }

        return ;
    }

    public static function check_subscription(User $user):void
    {
        $subscriptions = Subscription::where('user_id', $user->id)->expired()->get();
        try{
            DB::beginTransaction();
            foreach($subscriptions as $subscription){
                if($subscription->paid_amount > $subscription->billing_amount){
                    $extra = $subscription->paid_amount - $subscription->billing_amount;
                    CustomerLogic::create_wallet_transaction(user_id:$user->id,amount: $extra,transaction_type: 'add_fund',referance:"Subscription, Id:{$subscription->id}");
                }
                $subscription->status = 'expired';
                $subscription->save();
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            info(["line___{$e->getLine()}",$e->getMessage()]);
        }
    }

    public static function create_order_payment($order_id, $amount, $payment_status, $payment_method)
    {
        $payment = new OrderPayment();
        $payment->order_id = $order_id;
        $payment->amount = $amount;
        $payment->payment_status = $payment_status;
        $payment->payment_method = $payment_method;
        if($payment->save()){
            return true;
        }

        return false;

    }

    public static function update_unpaid_order_payment($order_id,$payment_method)
    {
        $payment = OrderPayment::where('payment_status','unpaid')->where('order_id',$order_id)->first();
        if($payment){
            $payment->payment_status = 'paid';
            if($payment_method != 'partial_payment'){
                $payment->payment_method = $payment_method;
            }
            if($payment->save()){
                return true;
            }

            return false;
        }
        return true;

    }

    public static function create_account_transaction_for_collect_cash($old_collected_cash, $from_type ,$from_id ,$amount, $order_id){
        $account_transaction = new AccountTransaction();
            $account_transaction->from_type =$from_type;
            $account_transaction->from_id = $from_id;
            $account_transaction->created_by = $from_type;
            $account_transaction->method = 'cash_collection';
            $account_transaction->ref = $order_id;
            $account_transaction->amount = $amount ?? 0;
            $account_transaction->current_balance = $old_collected_cash ?? 0;
            $account_transaction->type = 'cash_in';
            $account_transaction->save();


            if($from_type  ==  'restaurant'){
                $vendor= Vendor::find($from_id);

                $Payable_Balance = $vendor?->wallet?->collected_cash   > 0 ? 1: 0;
                $cash_in_hand_overflow= BusinessSetting::where('key' ,'cash_in_hand_overflow_restaurant')->first()?->value;
                $cash_in_hand_overflow_restaurant_amount = BusinessSetting::where('key' ,'cash_in_hand_overflow_restaurant_amount')->first()?->value;

                if ($Payable_Balance == 1 &&  $cash_in_hand_overflow && $vendor?->wallet?->balance < 0 &&  $cash_in_hand_overflow_restaurant_amount <= abs($vendor?->wallet?->collected_cash)){
                    $rest= Restaurant::where('vendor_id', $vendor->id)->first();
                    $rest->status = 0 ;
                    $rest->save();
                }

            } elseif($from_type  ==  'deliveryman' ){
                $cash_in_hand_overflow= BusinessSetting::where('key' ,'cash_in_hand_overflow_delivery_man')->first()?->value;
                $cash_in_hand_overflow_delivery_man = BusinessSetting::where('key' ,'dm_max_cash_in_hand')->first()?->value;

            $dm= DeliveryMan::find($from_id);

            $wallet_balance = round( $dm?->wallet?->total_earning - ($dm?->wallet?->total_withdrawn +$dm?->wallet?->pending_withdraw + $dm?->wallet?->collected_cash),8);
            $over_flow_balance = $dm?->wallet?->collected_cash;
            $Payable_Balance =  $over_flow_balance  > 0 ? 1: 0;
                if ($Payable_Balance == 1 &&  $cash_in_hand_overflow  && $wallet_balance<0 &&  $cash_in_hand_overflow_delivery_man < abs($over_flow_balance)){
                    $dm->status = 0 ;
                    $dm->save();
                }
            }

            return true;
    }


    public static function cashbackToWallet($order){

        $refer_wallet_transaction = CustomerLogic::create_wallet_transaction($order?->cashback_history?->user_id, $order?->cashback_history?->calculated_amount, 'CashBack',$order->id);
        if($refer_wallet_transaction != false){
            Helpers::expenseCreate(amount:$order?->cashback_history?->calculated_amount,type:'CashBack',datetime:now(),created_by:'admin', order_id:$order->id);
            $order?->cashback_history?->cashBack?->increment('total_used');

            $notification_data = [
                'title' => translate('messages.You’ve_Earned_Cahback!'),
                'description' => translate('You_have_received').' '.Helpers::format_currency($order?->cashback_history?->calculated_amount).' '.translate('in_your_wallet_as_CashBack'),
                'order_id' => $order->id,
                'image' => '',
                'type' => 'CashBack',
            ];
            $customer_push_notification_status=Helpers::getNotificationStatusData('customer','customer_cashback');
            if($customer_push_notification_status?->push_notification_status  == 'active' && $order->customer?->cm_firebase_token){
                Helpers::send_push_notif_to_device($order->customer?->cm_firebase_token, $notification_data);
                DB::table('user_notifications')->insert([
                    'data' => json_encode($notification_data),
                    'user_id' => $order->customer?->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

        }

        return true;
    }

}
