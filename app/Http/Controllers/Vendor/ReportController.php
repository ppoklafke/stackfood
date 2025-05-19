<?php

namespace App\Http\Controllers\Vendor;

use App\Exports\DisbursementVendorReportExport;
use App\Models\DisbursementDetails;
use App\Models\Food;
use App\Models\OrderDetail;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Category;
use App\Models\ItemCampaign;
use App\Models\WithdrawalMethod;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\OrderTransaction;
use App\Exports\FoodReportExport;
use App\Exports\ExpenseReportExport;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CampaignReportExport;
use App\Exports\VendorOrderReportExport;
use App\Exports\VendorTransactionReportExport;

class ReportController extends Controller
{

    public function set_date(Request $request)
    {
        session()->put('from_date', date('Y-m-d', strtotime($request['from'])));
        session()->put('to_date', date('Y-m-d', strtotime($request['to'])));
        return back();
    }

    public function expense_report(Request $request)
    {
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $expense = Expense::with('order')->where('created_by','vendor')->where('restaurant_id',Helpers::get_restaurant_id())
        ->when(isset($from) && isset($to) && $filter == 'custom', function ($query) use ($from, $to) {
            return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
        })
        ->when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('created_at', now()->format('Y'));
        })
        ->when(isset($filter) && $filter == 'this_month', function ($query) {
            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
        })
        ->when(isset($filter) && $filter == 'this_month', function ($query) {
            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
        })
        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
            return $query->whereYear('created_at', date('Y') - 1);
        })
        ->when(isset($filter) && $filter == 'this_week', function ($query) {
            return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
        })
        ->when( isset($key), function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('type', 'like', "%{$value}%")->orWhere('order_id', 'like', "%{$value}%");
                }
            });
        })
        ->with('order.customer')

        ->orderBy('created_at', 'desc')
        ->paginate(config('default_pagination'))->withQueryString();
        return view('vendor-views.report.expense-report', compact('expense','from','to','filter'));
    }




    public function expense_export(Request $request)
    {
        try{
            $from = null;
            $to = null;
            $filter = $request->query('filter', 'all_time');
            if($filter == 'custom'){
                $from = $request->from ?? null;
                $to = $request->to ?? null;
            }
            $key = explode(' ', $request['search']);
            $expense = Expense::with('order')->where('created_by','vendor')->where('restaurant_id',Helpers::get_restaurant_id())
            ->when(isset($from) && isset($to) && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when( isset($key), function($query) use($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('type', 'like', "%{$value}%")->orWhere('order_id', 'like', "%{$value}%");
                    }
                });
            })
            ->with('order.customer')
            ->orderBy('created_at', 'desc')
            ->get();

            $data = [
                'expenses'=>$expense,
                'search'=>$request->search??null,
                'from'=>(($filter == 'custom') && $from)?$from:null,
                'to'=>(($filter == 'custom') && $to)?$to:null,
                'restaurant'=>Helpers::get_restaurant_name(Helpers::get_restaurant_id()),
                'filter'=>$filter,
            ];

            if ($request->type == 'excel') {
                return Excel::download(new ExpenseReportExport($data), 'ExpenseReport.xlsx');
            } else if ($request->type == 'csv') {
                return Excel::download(new ExpenseReportExport($data), 'ExpenseReport.csv');
            }

        } catch(\Exception $e) {
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }
    }



    public function day_wise_report(Request $request)
    {
        $key = explode(' ', $request['search']);
        $from = null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $restaurant_id = Helpers::get_restaurant_id();


        $order_transactions = OrderTransaction::with('order','order.details:id,order_id,discount_on_food','order.customer:id,f_name,l_name','order.restaurant:id,name','order.delivery_man:id,earning,type')
            ->whereHas('order', function($q) use ($restaurant_id){
                $q->where('restaurant_id', $restaurant_id);
                })
                ->applyDateFilter($filter, $from, $to)
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('order_id', 'like', "%{$value}%");
                        }
                    });
                })
            ->orderBy('created_at', 'desc')
            ->paginate(config('default_pagination'))->withQueryString();

                $order_data = Order::where('restaurant_id',$restaurant_id)
                    ->applyDateFilter($filter, $from, $to)
                    ->when(isset($request['search']), function ($query) use($key){
                        $query->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('id', 'like', "%{$value}%");
                            }
                        });
                    })
                    ->Notpos()
                    ->selectRaw('SUM(CASE WHEN order_status = "refunded" THEN order_amount - delivery_charge - dm_tips ELSE 0 END) as canceled,
                        SUM(CASE WHEN order_status NOT IN ("delivered", "failed","refund_requested", "refund_request_canceled","refunded","canceled") THEN order_amount ELSE 0 END) as on_hold,
                        SUM(CASE WHEN order_status IN ("delivered", "refund_requested", "refund_request_canceled") THEN order_amount ELSE 0 END) as delivered')
                    ->first();

                    $canceled=$order_data->canceled;
                    $delivered=$order_data->delivered;
                    $on_hold=$order_data->on_hold;

        return view('vendor-views.report.day-wise-report', compact('order_transactions','from', 'to','filter','delivered' ,'on_hold','canceled'));
    }

    public function day_wise_report_export(Request $request){
        try{
            $key = explode(' ', $request['search']);
            $from = null;
            $to = null;
            $filter = $request->query('filter', 'all_time');
            if($filter == 'custom'){
                $from = $request->from ?? null;
                $to = $request->to ?? null;
            }

            $restaurant_id = Helpers::get_restaurant_id();

            $order_transactions = OrderTransaction::with('order','order.details:id,order_id,discount_on_food','order.customer:id,f_name,l_name','order.restaurant:id,name','order.delivery_man:id,earning,type')
            ->whereHas('order', function($q) use ($restaurant_id){
                $q->where('restaurant_id', $restaurant_id);
                })
                ->applyDateFilter($filter, $from, $to)
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('order_id', 'like', "%{$value}%");
                        }
                    });
                })
                    ->orderBy('created_at', 'desc')
                    ->get();



                $order_data = Order::where('restaurant_id',$restaurant_id)
                ->applyDateFilter($filter, $from, $to)
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('id', 'like', "%{$value}%");
                        }
                    });
                })
                ->Notpos()
                ->selectRaw('SUM(CASE WHEN order_status = "refunded" THEN order_amount - delivery_charge - dm_tips ELSE 0 END) as canceled,
                    SUM(CASE WHEN order_status NOT IN ("delivered", "failed","refund_requested", "refund_request_canceled","refunded","canceled") THEN order_amount ELSE 0 END) as on_hold,
                    SUM(CASE WHEN order_status IN ("delivered", "refund_requested", "refund_request_canceled") THEN order_amount ELSE 0 END) as delivered')
                ->first();


            $data = [
                'order_transactions'=>$order_transactions,
                'search'=>$request->search??null,
                'from'=>(($filter == 'custom') && $from)?$from:null,
                'to'=>(($filter == 'custom') && $to)?$to:null,
                'zone'=>null,
                'restaurant'=>is_numeric($restaurant_id)?Helpers::get_restaurant_name($restaurant_id):null,

                'delivered'=>$order_data->delivered,
                'canceled'=>$order_data->canceled,
                'on_hold'=>$order_data->on_hold,
                'filter'=>$filter,
            ];

            if ($request->type == 'excel') {
                return Excel::download(new VendorTransactionReportExport($data), 'TransactionReport.xlsx');
            } else if ($request->type == 'csv') {
                return Excel::download(new VendorTransactionReportExport($data), 'TransactionReport.csv');
            }
        }  catch(\Exception $e)
        {
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }
    }
    public function generate_statement($id)
    {
        $key =['phone','email_address','footer_text','business_name','logo'];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        $company_phone =$settings['phone'] ?? null;
        $company_email =$settings['email_address'] ?? null;
        $company_name =$settings['business_name'] ?? null;
        $company_web_logo =$settings['logo'] ?? null;
        $footer_text = $settings['footer_text'] ?? null;

        $order_transaction = OrderTransaction::with('order','order.details','order.customer','order.restaurant')->where('id', $id)->first();
        $data["email"] = $order_transaction->order->customer !=null?$order_transaction->order->customer["email"]: translate('email_not_found');
        $data["client_name"] = $order_transaction->order->customer !=null? $order_transaction->order->customer["f_name"] . ' ' . $order_transaction->order->customer["l_name"]: translate('customer_not_found');
        $data["order_transaction"] = $order_transaction;
        $mpdf_view = View::make('admin-views.report.order-transaction-statement',
            compact('order_transaction', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'footer_text')
        );
        Helpers::gen_mpdf(view: $mpdf_view,file_prefix: 'order_trans_statement',file_postfix: $order_transaction->id);
    }




    public function order_report(Request $request){
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);

        $restaurant_id = Helpers::get_restaurant_id();
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $restaurant= Helpers::get_restaurant_data();
        $data =0;
        if (($restaurant->restaurant_model == 'subscription' && isset($restaurant->restaurant_sub) && $restaurant->restaurant_sub->self_delivery == 1)  || ($restaurant->restaurant_model == 'commission' && $restaurant->self_delivery_system == 1) ){
            $data =1;
        }
        $orders = Order::with(['customer', 'restaurant','details','transaction'])->where('restaurant_id',$restaurant_id)
            ->Notpos()
            ->NotDigitalOrder()
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->applyDateFilterSchedule($filter, $from, $to)
            ->when(isset($key), function($query) use($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })
            ->whereNotIn('order_status',(config('order_confirmation_model') == 'restaurant'|| $data)?['failed','canceled', 'refund_requested']:['pending','failed','canceled', 'refund_requested'])
            ->HasSubscriptionToday()->OrderScheduledIn(30)
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')->paginate(config('default_pagination'))->withQueryString();

        // order card values calculation
        $orders_list = Order::where('restaurant_id',$restaurant_id)
            ->Notpos()
            ->NotDigitalOrder()
        ->when(isset($customer), function ($query) use ($customer) {
            return $query->where('user_id', $customer->id);
        })
        ->whereNotIn('order_status',(config('order_confirmation_model') == 'restaurant'|| $data)?['failed','canceled', 'refund_requested']:['pending','failed','canceled', 'refund_requested'])
        ->HasSubscriptionToday()->OrderScheduledIn(30)
        ->applyDateFilterSchedule($filter, $from, $to)
        ->orderBy('schedule_at', 'desc')->get();

        $total_canceled_count = $orders_list->where('order_status', 'canceled')->count();
        $total_delivered_count = $orders_list->where('order_status', 'delivered')->where('order_type', '<>' , 'pos')->count();
        $total_progress_count = $orders_list->whereIn('order_status', ['accepted','confirmed','processing','handover'])->count();
        $total_failed_count = $orders_list->where('order_status', 'failed')->count();
        $total_refunded_count = $orders_list->where('order_status', 'refunded')->count();
        $total_on_the_way_count = $orders_list->whereIn('order_status', ['picked_up'])->count();
        $total_accepted_count = $orders_list->where('order_status', 'accepted')->count();
        $total_pending_count = $orders_list->where('order_status', 'pending')->count();
        $total_scheduled_count = $orders_list->where('scheduled', 1)->count();

        return view('vendor-views.report.order-report', compact('orders','orders_list','from','to','total_accepted_count','total_pending_count','total_scheduled_count',
        'filter','customer','total_on_the_way_count','total_refunded_count','total_failed_count','total_progress_count','total_canceled_count','total_delivered_count'));
    }

    public function order_report_export(Request $request)
    {
        try{
            $key = isset($request['search']) ? explode(' ', $request['search']) : [];

            $from =  null;
            $to = null;
            $filter = $request->query('filter', 'all_time');
            if($filter == 'custom'){
                $from = $request->from ?? null;
                $to = $request->to ?? null;
            }

            $restaurant_id = Helpers::get_restaurant_id();


            $restaurant= Helpers::get_restaurant_data();
            $data =0;
            if (($restaurant->restaurant_model == 'subscription' && isset($restaurant->restaurant_sub) && $restaurant->restaurant_sub->self_delivery == 1)  || ($restaurant->restaurant_model == 'commission' && $restaurant->self_delivery_system == 1) ){
                $data =1;
            }

            $customer_id = $request->query('customer_id', 'all');
            $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
            $filter = $request->query('filter', 'all_time');

            $orders = Order::with(['customer', 'restaurant'])->where('restaurant_id',$restaurant_id)
                ->Notpos()
                ->NotDigitalOrder()
                ->whereNotIn('order_status',(config('order_confirmation_model') == 'restaurant'|| $data)?['failed','canceled', 'refund_requested']:['pending','failed','canceled', 'refund_requested'])
                ->HasSubscriptionToday()->OrderScheduledIn(30)
                ->when(isset($customer), function ($query) use ($customer) {
                    return $query->where('user_id', $customer->id);
                })
                ->applyDateFilterSchedule($filter, $from, $to)
                ->when(isset($key), function($query) use($key){
                    $query->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('id', 'like', "%{$value}%");
                        }
                    });
                })
                ->withSum('transaction', 'admin_commission')
                ->withSum('transaction', 'admin_expense')
                ->withSum('transaction', 'delivery_fee_comission')
                ->orderBy('schedule_at', 'desc')->get();

            $data = [
                'orders'=>$orders,
                'search'=>$request->search??null,
                'from'=>(($filter == 'custom') && $from)?$from:null,
                'to'=>(($filter == 'custom') && $to)?$to:null,
                'restaurant'=>Helpers::get_restaurant_name($restaurant_id),
                'customer'=>is_numeric($customer_id)?Helpers::get_customer_name($customer_id):null,
                'filter'=>$filter,
            ];

            if ($request->type == 'excel') {
                return Excel::download(new VendorOrderReportExport($data), 'OrderReport.xlsx');
            } else if ($request->type == 'csv') {
                return Excel::download(new VendorOrderReportExport($data), 'OrderReport.csv');
            }
        } catch(\Exception $e) {
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }

    }


    public function campaign_order_report(Request $request){
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $restaurant_id = Helpers::get_restaurant_id();

        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $campaign_id = $request->query('campaign_id', 'all');
        $key = explode(' ', $request['search']);

        $orders = Order::with(['customer', 'restaurant','details','transaction'])->where('restaurant_id',$restaurant_id)
            ->whereHas('details',function ($query){
                $query->whereNotNull('item_campaign_id');
            })
            ->when(is_numeric($campaign_id), function ($query) use ($campaign_id) {
                return $query->whereHas('details',function ($query) use ($campaign_id){
                    $query->where('item_campaign_id',$campaign_id);
                });
            })
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->applyDateFilterSchedule($filter, $from, $to)
            ->when(isset($request['search']), function ($query) use($key){
                $query->where(function ($qu)use ($key){
                    foreach ($key as $value) {
                        $qu->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')
            ->paginate(config('default_pagination'))
            ->withQueryString();

        $orders_list = Order::where('restaurant_id',$restaurant_id)
        ->whereHas('details',function ($query){
            $query->whereNotNull('item_campaign_id');
        })
        ->when(is_numeric($campaign_id), function ($query) use ($campaign_id) {
        return $query->whereHas('details',function ($query) use ($campaign_id){
            $query->where('item_campaign_id',$campaign_id);
                });
        })
        ->when(isset($customer), function ($query) use ($customer) {
            return $query->where('user_id', $customer->id);
        })
        ->applyDateFilterSchedule($filter, $from, $to)
        ->orderBy('schedule_at', 'desc')
        ->get();

        $total_order_amount = $orders_list->sum('order_amount');
        $total_coupon_discount = $orders_list->sum('coupon_discount_amount');
        $total_product_discount = $orders_list->sum('restaurant_discount_amount');

        $total_canceled_count = $orders_list->where('order_status', 'canceled')->count();
        $total_delivered_count = $orders_list->where('order_status', 'delivered')->where('order_type', '<>' , 'pos')->count();
        $total_progress_count = $orders_list->whereIn('order_status', ['accepted','confirmed','processing','handover'])->count();
        $total_failed_count = $orders_list->where('order_status', 'failed')->count();
        $total_refunded_count = $orders_list->where('order_status', 'refunded')->count();
        $total_on_the_way_count = $orders_list->whereIn('order_status', ['picked_up'])->count();
        $total_orders = $orders_list->count();
        return view('vendor-views.report.campaign_order-report', compact('orders','orders_list', 'campaign_id','from','to','total_orders','filter','customer','total_on_the_way_count','total_refunded_count','total_failed_count','total_progress_count','total_canceled_count','total_delivered_count','total_order_amount' ));
    }

    public function campaign_report_export(Request $request)
    {
        try{
            $key = isset($request['search']) ? explode(' ', $request['search']) : [];
            $filter = $request->query('filter', 'all_time');
            $from =  null;
            $to = null;
            $filter = $request->query('filter', 'all_time');
            if($filter == 'custom'){
                $from = $request->from ?? null;
                $to = $request->to ?? null;
            }
            $restaurant_id = Helpers::get_restaurant_id();

            $customer_id = $request->query('customer_id', 'all');
            $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
            $campaign_id = $request->query('campaign_id', 'all');

            $orders = Order::with(['customer', 'restaurant','details'])->where('restaurant_id',$restaurant_id)
            ->whereHas('details',function ($query){
                $query->whereNotNull('item_campaign_id');
            })
                ->when(is_numeric($campaign_id), function ($query) use ($campaign_id) {
                    return $query->whereHas('details',function ($query) use ($campaign_id){
                        $query->where('item_campaign_id',$campaign_id);
                    });
                })
                ->when(isset($customer), function ($query) use ($customer) {
                    return $query->where('user_id', $customer->id);
                })
                ->applyDateFilterSchedule($filter, $from, $to)
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($qu)use ($key){
                        foreach ($key as $value) {
                            $qu->orWhere('id', 'like', "%{$value}%");
                        }
                    });
                })
                ->orderBy('schedule_at', 'desc')
                ->get();

            $data = [
                'orders'=>$orders,
                'search'=>$request->search??null,
                'from'=>(($filter == 'custom') && $from)?$from:null,
                'to'=>(($filter == 'custom') && $to)?$to:null,
                'campaign' => is_numeric($campaign_id)?ItemCampaign::where('id' ,$campaign_id)->first()?->title:null,
                'restaurant'=>Helpers::get_restaurant_name($restaurant_id),
                'customer'=>is_numeric($customer_id)?Helpers::get_customer_name($customer_id):null,
                'filter'=>$filter,
            ];

            if ($request->type == 'excel') {
                return Excel::download(new CampaignReportExport($data), 'CampaignOrderReport.xlsx');
            } else if ($request->type == 'csv') {
                return Excel::download(new CampaignReportExport($data), 'CampaignOrderReport.csv');
            }
        } catch(\Exception $e) {
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }

    }



    private static function get_food_data($request){
        $from =  null;
        $to = null;
        $type = $request->query('type', 'all');
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $restaurant_id = Helpers::get_restaurant_id();
            $category_id = $request->query('category_id', 'all');
            $category = is_numeric($category_id) ? Category::findOrFail($category_id) : null;


        $foods = Food::where('restaurant_id' ,$restaurant_id)
        ->withCount([
            'orders' => function ($query) use ($from, $to, $filter) {
                    $query->whereHas('order', function($query){
                        return $query->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                    })->applyDateFilter($filter, $from, $to);
            },
        ])
        ->withSum([
            'orders' => function ($query) use ($from, $to, $filter) {
                $query->whereHas('order', function ($query) {
                    return $query->whereIn('order_status', ['delivered', 'refund_requested', 'refund_request_canceled']);
                })->applyDateFilter($filter, $from, $to);
            },
        ], 'quantity')

        ->addSelect([
            'total_discount' => function ($query) use ($from, $to, $filter) {
                $query->selectRaw('SUM(order_details.discount_on_food * order_details.quantity)')
                    ->from('order_details')
                    ->join('orders', 'orders.id', '=', 'order_details.order_id')
                    ->whereColumn('order_details.food_id', 'food.id')
                    ->whereIn('orders.order_status', ['delivered', 'refund_requested', 'refund_request_canceled'])
                    ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('order_details.created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                        return $query->whereYear('order_details.created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                        return $query->whereMonth('order_details.created_at', now()->format('m'))
                                    ->whereYear('order_details.created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                        return $query->whereYear('order_details.created_at', date('Y') - 1);
                    })
                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                        return $query->whereBetween('order_details.created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]); // Filter by this week
                    });
            },
            'orders_sum_price' => function ($query) use ($from, $to, $filter) {
                $query->selectRaw('SUM(order_details.price * order_details.quantity)')
                    ->from('order_details')
                    ->join('orders', 'orders.id', '=', 'order_details.order_id')
                    ->whereColumn('order_details.food_id', 'food.id')
                    ->whereIn('orders.order_status', ['delivered', 'refund_requested', 'refund_request_canceled'])
                    ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('order_details.created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                        return $query->whereYear('order_details.created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                        return $query->whereMonth('order_details.created_at', now()->format('m'))
                                    ->whereYear('order_details.created_at', now()->format('Y'));
                    })
                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                        return $query->whereYear('order_details.created_at', date('Y') - 1);
                    })
                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                        return $query->whereBetween('order_details.created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]); // Filter by this week
                    });
            },
        ])

        ->when(isset($request['search']), function ($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
        });
        })
        ->when(isset($category), function ($query) use ($category) {
            return $query->whereHas('category',function($q) use($category){
                return $q->whereId($category->id)->orWhere('parent_id', $category->id);
            });
        })
        ->when(isset($type) && $type =='veg', function ($query)  {
            return $query->where('veg', 1);
        })
        ->when(isset($type) && $type =='non_veg', function ($query)  {
            return $query->where('veg', 0);
        })
        ->having('orders_count', '>' ,0)
        ->orderBy('orders_count', 'desc');

        return $foods;

    }
    public function food_wise_report(Request $request)
    {
        $months = [
            '"' . translate('Jan') . '"',
            '"' . translate('Feb') . '"',
            '"' . translate('Mar') . '"',
            '"' . translate('Apr') . '"',
            '"' . translate('May') . '"',
            '"' . translate('Jun') . '"',
            '"' . translate('Jul') . '"',
            '"' . translate('Aug') . '"',
            '"' . translate('Sep') . '"',
            '"' . translate('Oct') . '"',
            '"' . translate('Nov') . '"',
            '"' . translate('Dec') . '"'
        ];


        $categories = Category::where(['position' => 0])->get();
        $from =  null;
        $to = null;
        $type = $request->query('type', 'all');
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $restaurant_id = Helpers::get_restaurant_id();
            $category_id = $request->query('category_id', 'all');
            $category = is_numeric($category_id) ? Category::findOrFail($category_id) : null;


            $foods = $this->get_food_data($request);
            $foods =  $foods->paginate(config('default_pagination'))->withQueryString();

                $monthly_order = [];
                $data = [];
                $data_avg = [];
                $label = [];
                $discount_on_food = [];

                if( in_array($filter, ['this_year','previous_year','custom'])){
                    for ($i = 1; $i <= 12; $i++) {
                        $monthly_order[$i] = OrderDetail::with('order')->whereHas('order',function($query) use($restaurant_id) {
                            $query->where('restaurant_id' ,$restaurant_id)->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                        })->select(
                            DB::raw('IFNULL(sum(price * quantity),0) as earning'),
                            DB::raw('IFNULL(sum(quantity),0) as quantity'),
                            DB::raw('IFNULL(avg(price * quantity ),0) as avg_commission'),
                            DB::raw('IFNULL(sum(discount_on_food * quantity),0) as discount_on_food'),
                            DB::raw('IFNULL(count(id),0) as order_count'),
                        )
                        ->when(isset($category), function ($query) use ($category) {
                            return $query->whereHas('food.category',function($q) use($category){
                                    return $q->whereId($category->id)->orWhere('parent_id', $category->id);
                            });
                        })
                        ->when(isset($type) && $type =='veg', function ($query){
                            return $query->whereHas('food',function($q) {
                                $q->where('veg', 1);
                            });
                        })
                        ->when(isset($type) && $type =='non_veg', function ($query){
                            return $query->whereHas('food',function($q) {
                                $q->where('veg', 0);
                            });
                        })
                        ->whereMonth('created_at', $i)
                        ->when($filter == 'this_year', function($q){
                            $q->whereYear('created_at', now()->format('Y'));
                        })
                        ->when($filter == 'previous_year', function($q){
                            $q->whereYear('created_at', date('Y') - 1);
                        })
                        ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })

                        ->first();
                        $discount_on_food[$i] = $monthly_order[$i]['discount_on_food'];
                        $data[$i] = $monthly_order[$i]['earning'] -$monthly_order[$i]['discount_on_food'];
                        $data_avg[$i] = $monthly_order[$i]['order_count'] > 0 ? ($monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'] )/ $monthly_order[$i]['quantity'] : 0 ;
                    }
                    $label = $months;
                }
                elseif($filter == 'this_week' ){
                    $days = [
                        '"' . translate('Sun') . '"',
                        '"' . translate('Mon') . '"',
                        '"' . translate('Tue') . '"',
                        '"' . translate('Wed') . '"',
                        '"' . translate('Thu') . '"',
                        '"' . translate('Fri') . '"',
                        '"' . translate('Sat') . '"'
                    ];

                    $weekStartDate = now()->startOfWeek();
                    for ($i = 1; $i <= 7; $i++) {
                        $monthly_order[$i] = OrderDetail::with('order')->whereHas('order',function($query) use($restaurant_id) {
                            $query->where('restaurant_id' ,$restaurant_id)->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                        })->select(
                            DB::raw('IFNULL(sum(price * quantity),0) as earning'),
                            DB::raw('IFNULL(sum(quantity),0) as quantity'),
                            DB::raw('IFNULL(avg(price *quantity ),0) as avg_commission'),

                            DB::raw('IFNULL(sum(discount_on_food *quantity),0) as discount_on_food'),
                            DB::raw('IFNULL(count(id),0) as order_count'),
                        )
                        ->when(isset($category), function ($query) use ($category) {
                            return $query->whereHas('food.category',function($q) use($category){
                                    return $q->whereId($category->id)->orWhere('parent_id', $category->id);
                            });
                        })
                        ->when(isset($type) && $type =='veg', function ($query){
                            return $query->whereHas('food',function($q) {
                                $q->where('veg', 1);
                            });
                        })
                        ->when(isset($type) && $type =='non_veg', function ($query){
                            return $query->whereHas('food',function($q) {
                                $q->where('veg', 0);
                            });
                        })
                        ->whereBetween('created_at', [now()->startOfweek()->addDays($i-1)->format('Y-m-d H:i:s'), now()->endOfWeek()->subDays(7-$i)->format('Y-m-d H:i:s')])

                        ->first();
                        $discount_on_food[$i] = $monthly_order[$i]['discount_on_food'];
                        $data[$i] = $monthly_order[$i]['earning'] -$monthly_order[$i]['discount_on_food'];
                        $data_avg[$i] = $monthly_order[$i]['order_count'] > 0 ? ($monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'] )/ $monthly_order[$i]['quantity'] : 0 ;

                    }
                    $label = $days;
                }
                elseif($filter == 'this_month'){
                    $start = now()->startOfMonth();
                    $end = now()->startOfMonth()->addDays(7);
                    $total_day = now()->daysInMonth;
                    $remaining_days = now()->daysInMonth - 28;
                    $weeks = [
                        '"' . translate('Day 1-7') . '"',
                        '"' . translate('Day 8-14') . '"',
                        '"' . translate('Day 15-21') . '"',
                        '"' . translate('Day 22-' . $total_day) . '"'
                    ];
                    for ($i = 1; $i <= 4; $i++) {
                        $monthly_order[$i] = OrderDetail::with('order')->whereHas('order',function($query) use($restaurant_id) {
                            $query->where('restaurant_id' ,$restaurant_id)->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                        })->select(
                            DB::raw('IFNULL(sum(price * quantity),0) as earning'),
                            DB::raw('IFNULL(sum(quantity),0) as quantity'),
                            DB::raw('IFNULL(avg(price * quantity),0) as avg_commission'),
                            DB::raw('IFNULL(sum(discount_on_food * quantity),0) as discount_on_food'),
                            DB::raw('IFNULL(count(id),0) as order_count'),
                        )
                        ->when(isset($category), function ($query) use ($category) {
                            return $query->whereHas('food.category',function($q) use($category){
                                    return $q->whereId($category->id)->orWhere('parent_id', $category->id);
                            });
                        })
                        ->when(isset($type) && $type =='veg', function ($query){
                            return $query->whereHas('food',function($q) {
                                $q->where('veg', 1);
                            });
                        })
                        ->when(isset($type) && $type =='non_veg', function ($query){
                            return $query->whereHas('food',function($q) {
                                $q->where('veg', 0);
                            });
                        })
                        ->whereBetween('created_at', [now()->startOfMonth()->addWeeks($i-1)->format('Y-m-d H:i:s'), now()->endOfMonth()->subWeeks(4-$i)->format('Y-m-d H:i:s')])
                        ->first();
                        $discount_on_food[$i] = $monthly_order[$i]['discount_on_food'];
                        $data[$i] = $monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'];
                        $data_avg[$i] = $monthly_order[$i]['order_count'] > 0 ? ($monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'] )/ $monthly_order[$i]['quantity'] : 0 ;
                    }
                    $label = $weeks;
                }
                else{
                    $monthly_order = OrderDetail::with('order')->whereHas('order',function($query) use($restaurant_id) {
                        $query->where('restaurant_id' ,$restaurant_id)->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                    })->select(
                        DB::raw('IFNULL(sum(price * quantity),0) as earning'),
                        DB::raw('IFNULL(sum(quantity),0) as quantity'),
                        DB::raw('IFNULL(avg(price* quantity ),0) as avg_commission'),

                        DB::raw('IFNULL(sum(discount_on_food * quantity),0) as discount_on_food'),
                        DB::raw('IFNULL(count(id),0) as order_count'),
                        DB::raw("(DATE_FORMAT(created_at, '%Y')) as year")

                    )->applyDateFilter($filter, $from, $to)
                    ->when(isset($category), function ($query) use ($category) {
                        return $query->whereHas('food.category',function($q) use($category){
                                return $q->whereId($category->id)->orWhere('parent_id', $category->id);
                        });
                    })
                    ->when(isset($type) && $type =='veg', function ($query){
                        return $query->whereHas('food',function($q) {
                            $q->where('veg', 1);
                        });
                    })
                    ->when(isset($type) && $type =='non_veg', function ($query){
                        return $query->whereHas('food',function($q) {
                            $q->where('veg', 0);
                        });
                    })
                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))
                    ->get()->toArray();


                    $label = array_map(function ($order) {
                    return $order['year'];
                    }, $monthly_order);
                    $data = array_map(function ($order) {
                        return $order['earning'] - $order['discount_on_food'];
                    }, $monthly_order);
                    $data_avg = array_map(function ($order) {
                        return  $order['order_count'] > 0 ? ($order['earning'] - $order['discount_on_food'] )/ $order['quantity'] : 0 ;
                    }, $monthly_order);
                    $discount_on_food = array_map(function ($order) {
                        return  $order['discount_on_food'];
                    }, $monthly_order);
                }

            return view('vendor-views.report.food-wise-report', compact('category_id', 'categories','foods','from', 'to','filter','label', 'data','data_avg' , 'discount_on_food'));
        }

    public function food_wise_report_export(Request $request){
        try{
            $from =  null;
            $to = null;
            $filter = $request->query('filter', 'all_time');
            if($filter == 'custom'){
                $from = $request->from ?? null;
                $to = $request->to ?? null;
            }
                $category_id = $request->query('category_id', 'all');
                $restaurant_id = Helpers::get_restaurant_id();
                $foods = $this->get_food_data($request);
                $foods =  $foods->get();

            $data = [
                'foods'=>$foods,
                'search'=>$request->search??null,
                'from'=>(($filter == 'custom') && $from)?$from:null,
                'to'=>(($filter == 'custom') && $to)?$to:null,
                'zone'=>Helpers::get_zones_name(Helpers::get_restaurant_data()->zone_id),
                'restaurant'=>Helpers::get_restaurant_name($restaurant_id),
                'category'=>is_numeric($category_id)?Helpers::get_category_name($category_id):null,
                'filter'=>$filter,
            ];

            if ($request->export_type == 'excel') {
                return Excel::download(new FoodReportExport($data), 'FoodReport.xlsx');
            } else if ($request->export_type == 'csv') {
                return Excel::download(new FoodReportExport($data), 'FoodReport.csv');
            }
        } catch(\Exception $e) {
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }
    }

    public function disbursement_report(Request $request)
    {
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $restaurant_id = Helpers::get_restaurant_id();
        $withdrawal_methods = WithdrawalMethod::ofStatus(1)->get();
        $status = $request->query('status', 'all');
        $payment_method_id = $request->query('payment_method_id', 'all');

        $dis = DisbursementDetails::where('restaurant_id',$restaurant_id)
            ->when((isset($payment_method_id) && ($payment_method_id != 'all')), function ($query) use ($payment_method_id) {
                return $query->whereHas('withdraw_method',function($q)use ($payment_method_id){
                    $q->where('withdrawal_method_id', $payment_method_id);
                });
            })
            ->when((isset($status) && ($status != 'all')), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(isset($filter) , function ($query) use ($filter,$from, $to) {
                return $query->applyDateFilter($filter, $from, $to);
            })
            ->when(isset($key), function ($q) use ($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('disbursement_id', 'like', "%{$value}%")
                            ->orWhere('status', 'like', "%{$value}%");
                    }
                });
            })
            ->latest();

        $total_disbursements= $dis->get();

        $disbursements= $dis->paginate(config('default_pagination'))->withQueryString();

        $pending =(float) $total_disbursements->where('status','pending')->sum('disbursement_amount');
        $completed =(float) $total_disbursements->where('status','completed')->sum('disbursement_amount');
        $canceled =(float) $total_disbursements->where('status','canceled')->sum('disbursement_amount');

        return view('vendor-views.report.disbursement-report', compact('disbursements','pending', 'completed','canceled','filter','from','to','withdrawal_methods','status','payment_method_id'));

    }

    public function disbursement_report_export(Request $request,$type)
    {
        $from = null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $restaurant_id = Helpers::get_restaurant_id();
        $withdrawal_methods = WithdrawalMethod::ofStatus(1)->get();
        $status = $request->query('status', 'all');
        $payment_method_id = $request->query('payment_method_id', 'all');

        $disbursements = DisbursementDetails::where('restaurant_id',$restaurant_id)
            ->when((isset($payment_method_id) && ($payment_method_id != 'all')), function ($query) use ($payment_method_id) {
                return $query->whereHas('withdraw_method',function($q)use ($payment_method_id){
                    $q->where('withdrawal_method_id', $payment_method_id);
                });
            })
            ->when((isset($status) && ($status != 'all')), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(isset($filter) , function ($query) use ($filter,$from, $to) {
                return $query->applyDateFilter($filter, $from, $to);
            })
            ->when(isset($key), function ($q) use ($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('disbursement_id', 'like', "%{$value}%")
                            ->orWhere('status', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->get();

        $data=[
            'disbursements' =>$disbursements,
            'search'=>$request->search??null,
            'status'=>$status,
            'filter'=>$filter,
            'from'=>(($filter == 'custom') && $from)?$from:null,
            'to'=>(($filter == 'custom') && $to)?$to:null,
            'pending' =>(float) $disbursements->where('status','pending')->sum('disbursement_amount'),
            'completed' =>(float) $disbursements->where('status','completed')->sum('disbursement_amount'),
            'canceled' =>(float) $disbursements->where('status','canceled')->sum('disbursement_amount'),
        ];
        if($type == 'csv'){
            return Excel::download(new DisbursementVendorReportExport($data), 'DisbursementReport.csv');
        }
        return Excel::download(new DisbursementVendorReportExport($data), 'DisbursementReport.xlsx');

    }

}
