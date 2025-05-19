<?php

namespace App\Http\Controllers\Vendor;


use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Carbon;
use App\Models\BusinessSetting;
use App\Mail\SubscriptionCancel;
use App\Models\RestaurantWallet;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\RestaurantSubscription;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\Session;
use App\Exports\SubscriptionTransactionsExport;
use App\Models\SubscriptionBillingAndRefundHistory;

class SubscriptionController extends Controller
{
    public function subscriberDetail()
    {
        $restaurant = Restaurant::where('id', Helpers::get_restaurant_id())->with([
            'restaurant_sub_update_application.package',
            'vendor',
            'restaurant_sub_update_application.last_transcations'
        ])->withcount(['foods', 'restaurant_all_sub_trans'])
            ->first();
        $packages = SubscriptionPackage::where('status', 1)->latest()->get();
        $admin_commission = BusinessSetting::where('key', 'admin_commission')->first()?->value;
        $business_name = BusinessSetting::where('key', 'business_name')->first()?->value;
        try {
            $index =  $restaurant->restaurant_model == 'commission' ? 0 : 1 + array_search($restaurant?->restaurant_sub_update_application?->package_id ?? 1, array_column($packages->toArray(), 'id'));
        } catch (\Throwable $th) {
            $index = 2;
        }
        return view('vendor-views.subscription.subscriber.vendor-subscription', compact('restaurant', 'packages', 'business_name', 'admin_commission', 'index'));
    }




    public function cancelSubscription(Request $request, $id)
    {
        RestaurantSubscription::where(['restaurant_id' => Helpers::get_restaurant_id(), 'id' => $request->subscription_id])->update([
            'is_canceled' => 1,
            'canceled_by' => 'restaurant',
        ]);

        try {
            $restaurant = Restaurant::where('id', Helpers::get_restaurant_id())->first();

            $notification_status = Helpers::getNotificationStatusData('restaurant', 'restaurant_subscription_cancel');
            $reataurant_notification_status = Helpers::getRestaurantNotificationStatusData($restaurant?->id, 'restaurant_subscription_cancel');


            if ($notification_status?->push_notification_status  == 'active' && $reataurant_notification_status?->push_notification_status  == 'active'  &&  $restaurant?->vendor?->firebase_token) {
                $data = [
                    'title' => translate('subscription_canceled'),
                    'description' => translate('Your_subscription_has_been_canceled'),
                    'order_id' => '',
                    'image' => '',
                    'type' => 'subscription',
                    'order_status' => '',
                ];
                Helpers::send_push_notif_to_device($restaurant?->vendor?->firebase_token, $data);
                DB::table('user_notifications')->insert([
                    'data' => json_encode($data),
                    'vendor_id' => $restaurant?->vendor_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            if (config('mail.status') && Helpers::get_mail_status('subscription_cancel_mail_status_restaurant') == '1' &&  $notification_status?->mail_status  == 'active' && $reataurant_notification_status?->mail_status  == 'active') {
                Mail::to($restaurant->email)->send(new SubscriptionCancel($restaurant->name));
            }
        } catch (\Exception $ex) {
            info($ex->getMessage());
        }

        return response()->json(200);
    }





    public function switchToCommission($id)
    {
        $restaurant =  Restaurant::where('id', $id)->with('restaurant_sub')->first();

        $restaurant_subscription =  $restaurant->restaurant_sub;
        if ($restaurant->restaurant_model == 'subscription'  && $restaurant_subscription?->is_canceled === 0 && $restaurant_subscription?->is_trial === 0) {
            Helpers::calculateSubscriptionRefundAmount(restaurant: $restaurant);
        }

        $restaurant->restaurant_model = 'commission';
        $restaurant->save();

        RestaurantSubscription::where(['restaurant_id' => Helpers::get_restaurant_id()])->update([
            'status' => 0,
        ]);
        return response()->json(200);
    }



    public function packageView($id, $restaurant_id)
    {
        $restaurant_subscription = RestaurantSubscription::where('restaurant_id', $restaurant_id)->with(['package'])->latest()->first();
        $package = SubscriptionPackage::where('status', 1)->where('id', $id)->first();

        $restaurant = Restaurant::Where('id', $restaurant_id)->first();
        $pending_bill = SubscriptionBillingAndRefundHistory::where([
            'restaurant_id' => $restaurant->id,
            'transaction_type' => 'pending_bill',
            'is_success' => 0
        ])?->sum('amount') ?? 0;

        $balance = BusinessSetting::where('key', 'wallet_status')->first()?->value == 1 ? RestaurantWallet::where('vendor_id', $restaurant->vendor_id)->first()?->balance ?? 0 : 0;
        $payment_methods = Helpers::getActivePaymentGateways();
        $disable_item_count = null;
        if (data_get(Helpers::subscriptionConditionsCheck(restaurant_id: $restaurant->id, package_id: $package->id), 'disable_item_count') > 0 && (!$restaurant_subscription || $package->id != $restaurant_subscription->package_id)) {
            $disable_item_count = data_get(Helpers::subscriptionConditionsCheck(restaurant_id: $restaurant->id, package_id: $package->id), 'disable_item_count');
        }
        $restaurant_model = $restaurant->restaurant_model;
        $admin_commission = BusinessSetting::where('key', "admin_commission")->first()?->value ?? 0;

        $cash_backs = [];
        if ($restaurant->restaurant_model == 'subscription' &&  $restaurant_subscription->status == 1 && $restaurant_subscription->is_canceled == 0 && $restaurant_subscription->is_trial == 0  && $restaurant_subscription->package_id !=  $package->id) {
            $cash_backs = Helpers::calculateSubscriptionRefundAmount(restaurant: $restaurant, return_data: true);
        }

        return response()->json([
            'disable_item_count' => $disable_item_count,
            'view' => view('vendor-views.subscription.subscriber.partials._package_selected', compact('restaurant_subscription', 'package', 'restaurant_id', 'balance', 'payment_methods', 'pending_bill', 'restaurant_model', 'admin_commission', 'cash_backs'))->render()
        ]);
    }



    public function packageBuy(Request $request)
    {


        $request->validate([
            'package_id' => 'required',
            'restaurant_id' => 'required',
            'payment_gateway' => 'required'
        ]);
        $restaurant = Restaurant::Where('id', $request->restaurant_id)->first(['id', 'vendor_id']);
        $package = SubscriptionPackage::withoutGlobalScope('translate')->find($request->package_id);
        $pending_bill = SubscriptionBillingAndRefundHistory::where([
            'restaurant_id' => $restaurant->id,
            'transaction_type' => 'pending_bill',
            'is_success' => 0
        ])?->sum('amount') ?? 0;

        if (!in_array($request->payment_gateway, ['wallet'])) {
            $url = route('vendor.subscriptionackage.subscriberDetail', $restaurant->id);
            return redirect()->away(Helpers::subscriptionPayment(restaurant_id: $restaurant->id, package_id: $package->id, payment_gateway: $request->payment_gateway, payment_platform: 'web', url: $url, pending_bill: $pending_bill, type: $request?->type));
        }

        if ($request->payment_gateway == 'wallet') {
            $wallet = RestaurantWallet::firstOrNew(['vendor_id' => $restaurant->vendor_id]);
            $balance = BusinessSetting::where('key', 'wallet_status')->first()?->value == 1 ? $wallet?->balance ?? 0 : 0;

            if ($balance >= ($package?->price + $pending_bill)) {
                $reference = 'wallet_payment_by_vendor';
                $plan_data =   Helpers::subscription_plan_chosen(restaurant_id: $restaurant->id, package_id: $package->id, payment_method: $reference, discount: 0, pending_bill: $pending_bill, reference: $reference, type: $request?->type);
                if ($plan_data != false) {
                    $wallet->total_withdrawn = $wallet?->total_withdrawn + $package->price + $pending_bill;
                    $wallet?->save();
                }
            } else {
                Toastr::error(translate('messages.Insufficient_balance_in_wallet'));
                return to_route('vendor.subscriptionackage.subscriberDetail', $restaurant->id);
            }
        }

        $plan_data != false ?  Toastr::success($request?->type == 'renew' ?  translate('Subscription_Package_Renewed_Successfully.') : translate('Subscription_Package_Shifted_Successfully.')) : Toastr::error(translate('Something_went_wrong!.'));
        return to_route('vendor.subscriptionackage.subscriberDetail', $restaurant->id);
    }


    public function subscriberTransactions($id, Request $request)
    {
        $filter = $request['filter'];
        $plan_type = $request['plan_type'];
        $from = $request['start_date'] ?? Carbon::now()->format('Y-m-d');
        $to = $request['end_date'] ?? Carbon::now()->format('Y-m-d');
        $restaurant = Restaurant::where('id', Helpers::get_restaurant_id())->with([
            'restaurant_sub_update_application.package'
        ])
            ->first();

        $key = explode(' ', $request['search']);
        $transactions = SubscriptionTransaction::where('restaurant_id', Helpers::get_restaurant_id())
            ->when(isset($key), function ($query) use ($key) {
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('id', 'like', "%{$value}%");
                    }
                });
            })
            ->when($filter == 'this_year', function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            })
            ->when($filter == 'this_month', function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month);
            })
            ->when($filter == 'this_week', function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when($filter == 'custom', function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })

            ->when(in_array($plan_type, ['renew', 'new_plan', 'first_purchased', 'free_trial']), function ($query) use ($plan_type) {
                $query->where('plan_type', $plan_type);
            })

            ->latest()->paginate(config('default_pagination'));
        $subscription_deadline_warning_days = BusinessSetting::where('key', 'subscription_deadline_warning_days')->first()?->value ?? 7;
        return view('vendor-views.subscription.subscriber.transaction', compact('restaurant', 'transactions', 'id', 'filter', 'subscription_deadline_warning_days'));
    }



    public function invoice($id)
    {
        $BusinessData = ['admin_commission', 'business_name', 'address', 'phone', 'logo', 'email_address'];
        $transaction = SubscriptionTransaction::with(['restaurant.vendor', 'package:id,package_name,price'])->find($id);
        $BusinessData = BusinessSetting::whereIn('key', $BusinessData)->pluck('value', 'key');
        $logo = BusinessSetting::where('key', "logo")->first();

        $mpdf_view = View::make('subscription-invoice', compact('transaction', 'BusinessData', 'logo'));
        Helpers::gen_mpdf(view: $mpdf_view, file_prefix: 'Subscription', file_postfix: $id);
        return back();
    }


    public function subscriberTransactionExport(Request $request)
    {


        $filter = $request['filter'];
        $plan_type = $request['plan_type'];
        $from = $request['start_date'] ?? Carbon::now()->format('Y-m-d');
        $to = $request['end_date'] ?? Carbon::now()->format('Y-m-d');
        $restaurant = Restaurant::where('id', Helpers::get_restaurant_id())->first();

        $key = explode(' ', $request['search']);
        $transactions = SubscriptionTransaction::where('restaurant_id', $restaurant->id)
            ->when(isset($key), function ($query) use ($key) {
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('id', 'like', "%{$value}%");
                    }
                });
            })
            ->when($filter == 'this_year', function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            })
            ->when($filter == 'this_month', function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month);
            })
            ->when($filter == 'this_week', function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when($filter == 'custom', function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })

            ->when(in_array($plan_type, ['renew', 'new_plan', 'first_purchased', 'free_trial']), function ($query) use ($plan_type) {
                $query->where('plan_type', $plan_type);
            })

            ->latest()->get();

        $data = [
            'data' => $transactions,
            'plan_type' => $request['plan_type'] ?? 'all',
            'filter' => $request['filter'] ?? 'all',
            'search' => $request['search'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'restaurant' => $restaurant->name,
        ];
        if ($request->export_type == 'excel') {
            return Excel::download(new SubscriptionTransactionsExport($data), 'SubscriptionTransactionsExport.xlsx');
        }
        return Excel::download(new SubscriptionTransactionsExport($data), 'SubscriptionTransactionsExport.csv');
    }



    public function addToSession(Request $request)
    {
        Session::put($request->value, true);
        return response()->json(['success' => true]);
    }

    public function subscriberWalletTransactions(Request $request)
    {
        $restaurant = Restaurant::where('id', Helpers::get_restaurant_id())->first();
        $transactions = SubscriptionBillingAndRefundHistory::where('restaurant_id', $restaurant->id)->with('package')
            ->where('transaction_type', 'refund')
            ->latest()->paginate(config('default_pagination'));
        return view('vendor-views.subscription.subscriber.wallet-transaction', compact('transactions', 'restaurant'));
    }
}
