<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DisbursementReportExport;
use App\Models\DeliveryMan;
use App\Models\DisbursementDetails;
use App\Models\Food;
use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Models\WithdrawalMethod;
use App\Models\Zone;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Category;
use App\Scopes\ZoneScope;
use App\Models\Restaurant;
use App\Models\OrderDetail;
use App\Models\ItemCampaign;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Scopes\RestaurantScope;
use App\Models\OrderTransaction;
use App\Exports\FoodReportExport;
use App\Exports\OrderReportExport;
use Illuminate\Support\Facades\DB;
use App\Exports\ExpenseReportExport;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CampaignReportExport;
use App\Models\SubscriptionTransaction;
use App\Exports\TransactionReportExport;
use App\Exports\SubscriptionReportExport;
use App\Exports\RestaurantSummaryReportExport;

class ReportController extends Controller
{
    public function __construct()
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
    }

    public function order_index()
    {
        if(session()->has('from_date') == false)
        {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        return view('admin-views.report.order-index');
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
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;

        $order_transactions = OrderTransaction::with('order','order.details:id,order_id,discount_on_food','order.customer:id,f_name,l_name','order.restaurant:id,name','order.delivery_man:id,earning,type')
                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->where('zone_id', $zone->id);
                })
                ->when(isset($restaurant), function ($query) use ($restaurant){
                        return $query->whereHas('order', function($q) use ($restaurant){
                            $q->where('restaurant_id', $restaurant->id);
                    });
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

                $order_trans_data = OrderTransaction::
                    when(isset($zone), function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                        })
                    ->when(isset($restaurant), function ($query) use ($restaurant){
                            return $query->whereHas('order', function($q) use ($restaurant){
                                $q->where('restaurant_id', $restaurant->id);
                        });
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
                    // ->notRefunded()
                    ->selectRaw('SUM(original_delivery_charge + dm_tips) as deliveryman_earned,
                    SUM(CASE WHEN status NOT IN ("refunded_with_delivery_charge", "refunded_without_delivery_charge") OR status IS NULL THEN restaurant_amount - tax ELSE 0 END) as restaurant_earned,
                    SUM(CASE WHEN status NOT IN ("refunded_with_delivery_charge", "refunded_without_delivery_charge") OR status IS NULL THEN admin_commission + delivery_fee_comission ELSE 0 END) as admin_earned')
                    ->first();

                    $admin_earned= $order_trans_data->admin_earned;
                    $restaurant_earned= $order_trans_data->restaurant_earned;
                    $deliveryman_earned= $order_trans_data->deliveryman_earned;


                $order_data = Order::when(isset($zone), function ($query) use ($zone) {
                            return $query->where('zone_id', $zone->id);
                        })
                    ->when(isset($restaurant), function ($query) use ($restaurant) {
                        return $query->where('restaurant_id', $restaurant->id);
                    })
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
                        SUM(CASE WHEN order_status IN ("delivered", "refund_requested", "refund_request_canceled") THEN order_amount ELSE 0 END) as delivered')
                    ->first();

                    $canceled=$order_data->canceled;
                    $delivered=$order_data->delivered;

        return view('admin-views.report.day-wise-report', compact('order_transactions', 'zone', 'from', 'to',
        'restaurant','filter','admin_earned','restaurant_earned','deliveryman_earned' , 'delivered' , 'canceled'));
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
            $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
            $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
            $restaurant_id = $request->query('restaurant_id', 'all');
            $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;

            $order_transactions = OrderTransaction::with('order','order.details','order.customer','order.restaurant','order.delivery_man')
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                    })
                    ->when(isset($restaurant), function ($query) use ($restaurant){
                            return $query->whereHas('order', function($q) use ($restaurant){
                                $q->where('restaurant_id', $restaurant->id);
                        });
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


                    $order_trans_data = OrderTransaction::with('order')
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                        })
                    ->when(isset($restaurant), function ($query) use ($restaurant){
                            return $query->whereHas('order', function($q) use ($restaurant){
                                $q->where('restaurant_id', $restaurant->id);
                        });
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
                    // ->notRefunded()
                    ->selectRaw('SUM(original_delivery_charge + dm_tips) as deliveryman_earned,
                    SUM(CASE WHEN status NOT IN ("refunded_with_delivery_charge", "refunded_without_delivery_charge") OR status IS NULL THEN restaurant_amount - tax ELSE 0 END) as restaurant_earned,
                    SUM(CASE WHEN status NOT IN ("refunded_with_delivery_charge", "refunded_without_delivery_charge") OR status IS NULL THEN admin_commission + delivery_fee_comission ELSE 0 END) as admin_earned')
                    ->first();

                $order_data = Order::when(isset($zone), function ($query) use ($zone) {
                            return $query->where('zone_id', $zone->id);
                        })
                    ->when(isset($restaurant), function ($query) use ($restaurant) {
                        return $query->where('restaurant_id', $restaurant->id);
                    })
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
                        SUM(CASE WHEN order_status IN ("delivered", "refund_requested", "refund_request_canceled") THEN order_amount ELSE 0 END) as delivered')
                    ->first();

            $data = [
                'order_transactions'=>$order_transactions,
                'search'=>$request->search??null,
                'from'=>(($filter == 'custom') && $from)?$from:null,
                'to'=>(($filter == 'custom') && $to)?$to:null,
                'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id):null,
                'restaurant'=>is_numeric($restaurant_id)?Helpers::get_restaurant_name($restaurant_id):null,
                'admin_earned'=> $order_trans_data->admin_earned,
                'restaurant_earned'=>$order_trans_data->restaurant_earned,
                'deliveryman_earned'=>$order_trans_data->deliveryman_earned,
                'delivered'=>$order_data->delivered,
                'canceled'=>$order_data->canceled,
                'filter'=>$filter,
            ];

            if ($request->type == 'excel') {
                return Excel::download(new TransactionReportExport($data), 'TransactionReport.xlsx');
            } else if ($request->type == 'csv') {
                return Excel::download(new TransactionReportExport($data), 'TransactionReport.csv');
            }
        }  catch(\Exception $e)
        {
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }
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
            $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
            $restaurant_id = $request->query('restaurant_id', 'all');
            $category_id = $request->query('category_id', 'all');
            $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
            $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
            $category = is_numeric($category_id) ? Category::findOrFail($category_id) : null;

            $foods = $this->get_food_data($request);
            $foods =  $foods->paginate(config('default_pagination'))->withQueryString();

                $monthly_order = [];
                $data = [];
                $data_avg = [];
                $discount_on_food = [];
                $label = [];

                if( in_array($filter, ['this_year','previous_year','custom'])){
                    for ($i = 1; $i <= 12; $i++) {
                        $monthly_order[$i] = OrderDetail::with('order')->whereHas('order',function($query) use($zone ,$restaurant) {
                            $query->when(isset($zone), function ($query) use ($zone) {
                                $query->where('zone_id',$zone->id);
                            })
                            ->when(isset($restaurant), function ($query) use ($restaurant) {
                                    $query->where('restaurant_id',$restaurant->id);
                            })
                            ->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
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
                        $data[$i] = $monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'];
                        $discount_on_food[$i] = $monthly_order[$i]['discount_on_food'];
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
                        $monthly_order[$i] = OrderDetail::with('order')->whereHas('order',function($query) use($zone ,$restaurant) {
                            $query->when(isset($zone), function ($query) use ($zone) {
                                $query->where('zone_id',$zone->id);
                            })
                            ->when(isset($restaurant), function ($query) use ($restaurant) {
                                    $query->where('restaurant_id',$restaurant->id);
                            })
                            ->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
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
                        $data[$i] = $monthly_order[$i]['earning']-$monthly_order[$i]['discount_on_food'];
                        $discount_on_food[$i] = $monthly_order[$i]['discount_on_food'];
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
                        $monthly_order[$i] = OrderDetail::with('order')
                        ->whereHas('order',function($query) use($zone ,$restaurant) {
                            $query
                            ->when(isset($zone), function ($query) use ($zone) {
                                $query->where('zone_id',$zone->id);
                            })
                            ->when(isset($restaurant), function ($query) use ($restaurant) {
                                    $query->where('restaurant_id',$restaurant->id);
                            })
                            ->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
                        })
                        ->select(
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
                        $data[$i] = $monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'];
                        $discount_on_food[$i] = $monthly_order[$i]['discount_on_food'];
                        $data_avg[$i] = $monthly_order[$i]['order_count'] > 0 ? ($monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'] )/ $monthly_order[$i]['quantity'] : 0 ;
                    }
                    $label = $weeks;
                }
                else{
                    $monthly_order = OrderDetail::with('order')->whereHas('order',function($query) use($zone ,$restaurant) {
                        $query->when(isset($zone), function ($query) use ($zone) {
                            $query->where('zone_id',$zone->id);
                        })
                        ->when(isset($restaurant), function ($query) use ($restaurant) {
                                $query->where('restaurant_id',$restaurant->id);
                        })
                        ->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
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
                        return $order['earning']-$order['discount_on_food'];
                    }, $monthly_order);
                    $data_avg = array_map(function ($order) {
                        return  $order['order_count'] > 0 ? ($order['earning'] - $order['discount_on_food'] )/ $order['quantity'] : 0 ;
                    }, $monthly_order);
                    $discount_on_food = array_map(function ($order) {
                        return  $order['discount_on_food'];
                    }, $monthly_order);

                }

            return view('admin-views.report.food-wise-report', compact('zone',
            'restaurant', 'category_id', 'categories','foods','from', 'to','filter' , 'label', 'data','data_avg','discount_on_food'));
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
            $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
            $restaurant_id = $request->query('restaurant_id', 'all');
            $category_id = $request->query('category_id', 'all');

            $foods = $this->get_food_data($request);
            $foods =  $foods->get();

            $data = [
                'foods'=>$foods,
                'search'=>$request->search??null,
                'from'=>(($filter == 'custom') && $from)?$from:null,
                'to'=>(($filter == 'custom') && $to)?$to:null,
                'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id):null,
                'restaurant'=>is_numeric($restaurant_id)?Helpers::get_restaurant_name($restaurant_id):null,
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
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $restaurant_id = $request->query('restaurant_id', 'all');
        $category_id = $request->query('category_id', 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $category = is_numeric($category_id) ? Category::findOrFail($category_id) : null;

        $foods = Food::withoutGlobalScopes([ZoneScope::class, RestaurantScope::class])
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
        ->when(isset($zone), function ($query) use ($zone) {
            return $query->whereHas('orders.order', function($query) use($zone){
                $query->where('zone_id',$zone->id);
            });
        })
        ->when(isset($restaurant), function ($query) use ($restaurant) {
            return $query->where('restaurant_id', $restaurant->id);
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
        ->with(['restaurant','translations'])
        ->having('orders_count', '>' ,0)
        ->orderBy('orders_count', 'desc');

        return $foods;

    }

    public function order_transaction()
    {
        $order_transactions = OrderTransaction::latest()->paginate(config('default_pagination'));
        return view('admin-views.report.order-transactions', compact('order_transactions'));
    }


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
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $type = $request->query('expense_type', 'all');

        $expense = Expense::with('order','order.customer:id,f_name,l_name','user')->where('created_by','admin')
            ->when(isset($zone) || isset($restaurant) || isset($customer), function ($query) use ($zone,$restaurant,$customer) {
                return $query->whereHas('order', function($query) use ($zone,$restaurant,$customer) {
                    $query->when($zone, function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                    });
                    $query->when($restaurant, function ($query) use ($restaurant) {
                        return $query->where('restaurant_id', $restaurant->id);
                    });
                    $query->when($customer, function ($query) use ($customer) {
                        return $query->where('user_id', $customer->id);
                    });
                })->when($customer, function ($query) use ($customer) {
                    $query->orWhere(function ($q) use ($customer){
                        return $q->where('user_id', $customer->id)->where('created_by','admin');
                    });
                });
            })
            ->when(isset($type) &&  $type != 'all', function ($query) use ($type) {
                return $query->where('type',$type);
            })

            ->applyDateFilter($filter, $from, $to)
            ->when( isset($key), function($query) use($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('type', 'like', "%{$value}%")->orWhere('order_id', 'like', "%{$value}%");
                    }
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(config('default_pagination'))->withQueryString();

//        dd($expense);

        return view('admin-views.report.expense-report', compact('expense','zone', 'restaurant','filter','customer','from','to' ,'type'));
    }


    public function expense_export(Request $request)
    {
        try {
            $from =  null;
            $to = null;
            $filter = $request->query('filter', 'all_time');
            if($filter == 'custom'){
                $from = $request->from ?? null;
                $to = $request->to ?? null;
            }
            $key = explode(' ', $request['search']);

            $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
            $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
            $restaurant_id = $request->query('restaurant_id', 'all');
            $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
            $customer_id = $request->query('customer_id', 'all');
            $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;

            $expense = Expense::with('order','order.customer:id,f_name,l_name','user')->where('created_by','admin')
                ->when(isset($zone) || isset($restaurant) || isset($customer), function ($query) use ($zone,$restaurant,$customer) {
                    return $query->whereHas('order', function($query) use ($zone,$restaurant,$customer) {
                        $query->when($zone, function ($query) use ($zone) {
                            return $query->where('zone_id', $zone->id);
                        });
                        $query->when($restaurant, function ($query) use ($restaurant) {
                            return $query->where('restaurant_id', $restaurant->id);
                        });
                        $query->when($customer, function ($query) use ($customer) {
                            return $query->where('user_id', $customer->id);
                        });
                    });
                })
                ->applyDateFilter($filter, $from, $to)
                ->when( isset($key), function($query) use($key){
                    $query->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('type', 'like', "%{$value}%")->orWhere('order_id', 'like', "%{$value}%");
                        }
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $data = [
                'expenses'=>$expense,
                'search'=>$request->search??null,
                'from'=>(($filter == 'custom') && $from)?$from:null,
                'to'=>(($filter == 'custom') && $to)?$to:null,
                'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id): translate('messages.All'),
                'restaurant'=>is_numeric($restaurant_id)?Helpers::get_restaurant_name($restaurant_id):null,
                'customer'=>is_numeric($customer_id)?Helpers::get_customer_name($customer_id): translate('messages.All'),
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

    public function subscription_report(Request $request)
    {
        $from =  null;
        $to = null;

        $restaurant_id = $request->query('restaurant_id', 'all');
        $package_id = $request->query('package_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $package = is_numeric($package_id) ? SubscriptionPackage::findOrFail($package_id) : null;
        $filter = $request->query('filter', 'all_time');

        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $payment_type = $request->query('payment_type', 'all');

        $subscriptions = SubscriptionTransaction::with(['restaurant','package'])
                ->when(isset($restaurant), function ($query) use ($restaurant){
                    return $query->where('restaurant_id', $restaurant->id);
                })
                ->when(isset($package), function ($query) use ($package){
                    return $query->where('package_id', $package->id);
                })
                ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                    return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                ->when(isset($payment_type) && $payment_type == 'wallet_payment', function ($query) {
                    return $query->where('payment_method', 'wallet');
                })
                ->when(isset($payment_type) && $payment_type == 'manual_payment', function ($query) {
                    return $query->whereIn('payment_method',[ 'manual_payment_by_restaurant','manual_payment_admin']);
                })
                ->when(isset($payment_type) && $payment_type == 'digital_payment', function ($query) {
                    return $query->where(function($query){
                        $query->where('payment_method', 'digital_payment')->orWhereNotIn('payment_method',['manual_payment_by_restaurant','manual_payment_admin','wallet','free_trial']);
                    });
                })
                ->when(isset($payment_type) && $payment_type == 'free_trial', function ($query) {
                    return $query->where('payment_method', 'free_trial');
                })
                ->applyDateFilter($filter, $from, $to)
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($qu) use ($key){
                            $qu->where(function ($q) use ($key) {
                                foreach ($key as $value) {
                                    $q->orWhere('id', 'like', "%{$value}%")
                                        ->orWhere('paid_amount', 'like', "%{$value}%")
                                        ->orWhere('reference', 'like', "%{$value}%");
                                }
                            })->orwhereHas('restaurant',function($query)use($key){
                                foreach ($key as $v) {
                                    $query->where('name', 'like', "%{$v}%")
                                            ->orWhere('email', 'like', "%{$v}%");
                                }
                            });
                    });
                })

                ->orderBy('created_at', 'desc')
                ->paginate(config('default_pagination'))->withQueryString();

        return view('admin-views.report.subscription-report', compact('subscriptions','restaurant','package','filter','payment_type','to','from'));
    }

    public function subscription_export(Request $request)
    {
        try{
            $from =  null;
            $to = null;
            $restaurant_id = $request->query('restaurant_id', 'all');
            $package_id = $request->query('package_id', 'all');
            $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
            $package = is_numeric($package_id) ? SubscriptionPackage::findOrFail($package_id) : null;
            $filter = $request->query('filter', 'all_time');
            if($filter == 'custom'){
                $from = $request->from ?? null;
                $to = $request->to ?? null;
            }
            $payment_type = $request->query('payment_type', 'all');
            $key = explode(' ', $request['search']);

            $subscriptions = SubscriptionTransaction::with(['restaurant','package'])
                    ->when(isset($restaurant), function ($query) use ($restaurant){
                        return $query->where('restaurant_id', $restaurant->id);
                    })
                    ->when(isset($package), function ($query) use ($package){
                    return $query->where('package_id', $package->id);
                    })
                    ->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->when(isset($payment_type) && $payment_type == 'wallet_payment', function ($query) {
                        return $query->where('payment_method', 'wallet');
                    })
                    ->when(isset($payment_type) && $payment_type == 'manual_payment', function ($query) {
                        return $query->whereIn('payment_method',[ 'manual_payment_by_restaurant','manual_payment_admin']);
                    })
                    ->when(isset($payment_type) && $payment_type == 'digital_payment', function ($query) {
                        return $query->where('payment_method', 'digital_payment')->orWhereNotIn('payment_method',['manual_payment_by_restaurant','manual_payment_admin','wallet','free_trial']);
                    })
                    ->when(isset($payment_type) && $payment_type == 'free_trial', function ($query) {
                        return $query->where('payment_method', 'free_trial');
                    })
                    ->applyDateFilter($filter, $from, $to)
                    ->when(isset($request['search']), function ($query) use($key){
                        $query->where(function ($qu) use ($key){
                            $qu->where(function ($q) use ($key) {
                                foreach ($key as $value) {
                                    $q->orWhere('id', 'like', "%{$value}%")
                                        ->orWhere('paid_amount', 'like', "%{$value}%")
                                        ->orWhere('reference', 'like', "%{$value}%");
                                }
                            })->orwhereHas('restaurant',function($query)use($key){
                                foreach ($key as $v) {
                                    $query->where('name', 'like', "%{$v}%")
                                            ->orWhere('email', 'like', "%{$v}%");
                                }
                            });
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();


            $data = [
                'data'=>$subscriptions,
                'search'=>$request->search??null,
                'from'=>(($filter == 'custom') && $from)?$from:null,
                'to'=>(($filter == 'custom') && $to)?$to:null,
                'restaurant'=>is_numeric($restaurant_id)?Helpers::get_restaurant_name($restaurant_id):null,
                'package'=>is_numeric($package_id)?$package->package_name:null,
                'filter'=>$filter,
            ];

            if ($request->type == 'excel') {
                return Excel::download(new SubscriptionReportExport($data), 'SubscriptionReportExport.xlsx');
            } else if ($request->type == 'csv') {
                return Excel::download(new SubscriptionReportExport($data), 'SubscriptionReportExport.csv');
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
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $campaign_id = $request->query('campaign_id', 'all');
        $key = explode(' ', $request['search']);

        $orders = Order::with(['customer', 'restaurant','details','transaction'])
            ->whereHas('details',function ($query){
                $query->whereNotNull('item_campaign_id');
            })
            ->when(is_numeric($campaign_id), function ($query) use ($campaign_id) {
                return $query->whereHas('details',function ($query) use ($campaign_id){
                    $query->where('item_campaign_id',$campaign_id);
                });
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($restaurant), function ($query) use ($restaurant) {
                return $query->where('restaurant_id', $restaurant->id);
            })
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->applyDateFilterSchedule($filter, $from, $to)
            ->when(isset($request['search']), function ($query) use($key){
                $query->where(function ($qu)use ($key){
                    $qu->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('id', 'like', "%{$value}%");
                        }
                    })->orwhereHas('restaurant',function($query)use($key){
                        foreach ($key as $v) {
                            $query->where('name', 'like', "%{$v}%")
                                    ->orWhere('email', 'like', "%{$v}%");
                        }
                    });
                });
            })
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')
            ->paginate(config('default_pagination'))
            ->withQueryString();

        $orders_list = Order::
        whereHas('details',function ($query){
            $query->whereNotNull('item_campaign_id');
        })
        ->when(is_numeric($campaign_id), function ($query) use ($campaign_id) {
        return $query->whereHas('details',function ($query) use ($campaign_id){
            $query->where('item_campaign_id',$campaign_id);
                });
        })
        ->when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
        ->when(isset($restaurant), function ($query) use ($restaurant) {
            return $query->where('restaurant_id', $restaurant->id);
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
        return view('admin-views.report.campaign_order-report', compact('orders','orders_list','zone', 'campaign_id','from','to','total_orders',
        'restaurant','filter','customer','total_on_the_way_count','total_refunded_count','total_failed_count','total_progress_count','total_canceled_count','total_delivered_count'));
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
            $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
            $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
            $restaurant_id = $request->query('restaurant_id', 'all');
            $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
            $customer_id = $request->query('customer_id', 'all');
            $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
            $campaign_id = $request->query('campaign_id', 'all');

            $orders = Order::with(['customer', 'restaurant','details'])
            ->whereHas('details',function ($query){
                $query->whereNotNull('item_campaign_id');
            })
                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->where('zone_id', $zone->id);
                })
                ->when(is_numeric($campaign_id), function ($query) use ($campaign_id) {
                    return $query->whereHas('details',function ($query) use ($campaign_id){
                        $query->where('item_campaign_id',$campaign_id);
                    });
                })
                ->when(isset($restaurant), function ($query) use ($restaurant) {
                    return $query->where('restaurant_id', $restaurant->id);
                })
                ->when(isset($customer), function ($query) use ($customer) {
                    return $query->where('user_id', $customer->id);
                })
                ->applyDateFilterSchedule($filter, $from, $to)
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($qu)use ($key){
                        $qu->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('id', 'like', "%{$value}%");
                            }
                        })->orwhereHas('restaurant',function($query)use($key){
                            foreach ($key as $v) {
                                $query->where('name', 'like', "%{$v}%")
                                        ->orWhere('email', 'like', "%{$v}%");
                            }
                        });
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
                'restaurant'=>is_numeric($restaurant_id)?Helpers::get_restaurant_name($restaurant_id):null,
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

    public function order_report(Request $request){
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;

        $orders = Order::with(['customer', 'restaurant','details','transaction'])
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($restaurant), function ($query) use ($restaurant) {
                return $query->where('restaurant_id', $restaurant->id);
            })
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
            ->orderBy('schedule_at', 'desc')->paginate(config('default_pagination'))->withQueryString();

        // order card values calculation
        $orders_list = Order::
        when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
        ->when(isset($restaurant), function ($query) use ($restaurant) {
            return $query->where('restaurant_id', $restaurant->id);
        })
        ->when(isset($customer), function ($query) use ($customer) {
            return $query->where('user_id', $customer->id);
        })
        ->applyDateFilterSchedule($filter, $from, $to)
        ->HasSubscriptionToday()
        ->orderBy('schedule_at', 'desc')->get();


        $total_canceled_count = $orders_list->where('order_status', 'canceled')->count();
        $total_delivered_count = $orders_list->where('order_status', 'delivered')->where('order_type', '<>' , 'pos')->count();
        $total_progress_count = $orders_list->whereIn('order_status', ['confirmed','processing','handover'])->count();
        $total_failed_count = $orders_list->where('order_status', 'failed')->count();
        $total_refunded_count = $orders_list->where('order_status', 'refunded')->count();
        $total_on_the_way_count = $orders_list->whereIn('order_status', ['picked_up'])->count();
        $total_accepted_count = $orders_list->where('order_status', 'accepted')->count();
        $total_pending_count = $orders_list->where('order_status', 'pending')->count();
        $total_scheduled_count = $orders_list->where('scheduled', 1)->count();

        return view('admin-views.report.order-report', compact('orders','orders_list','zone', 'restaurant','from','to','total_accepted_count','total_pending_count','total_scheduled_count',
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
            $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
            $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
            $restaurant_id = $request->query('restaurant_id', 'all');
            $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
            $customer_id = $request->query('customer_id', 'all');
            $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
            $filter = $request->query('filter', 'all_time');

            $orders = Order::with(['customer', 'restaurant'])
                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->where('zone_id', $zone->id);
                })
                ->when(isset($restaurant), function ($query) use ($restaurant) {
                    return $query->where('restaurant_id', $restaurant->id);
                })
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
                'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id):null,
                'restaurant'=>is_numeric($restaurant_id)?Helpers::get_restaurant_name($restaurant_id):null,
                'customer'=>is_numeric($customer_id)?Helpers::get_customer_name($customer_id):null,
                'filter'=>$filter,
            ];

            if ($request->type == 'excel') {
                return Excel::download(new OrderReportExport($data), 'OrderReport.xlsx');
            } else if ($request->type == 'csv') {
                return Excel::download(new OrderReportExport($data), 'OrderReport.csv');
            }
        } catch(\Exception $e) {
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }

    }

    public function restaurant_report(Request $request)
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

        $days = [
            '"' . translate('Sun') . '"',
            '"' . translate('Mon') . '"',
            '"' . translate('Tue') . '"',
            '"' . translate('Wed') . '"',
            '"' . translate('Thu') . '"',
            '"' . translate('Fri') . '"',
            '"' . translate('Sat') . '"'
        ];

        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $restaurant_model = $request->query('restaurant_model', 'all');
        $type = $request->query('type', 'all');
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurants = Restaurant::with('reviews','vendor')
                ->whereHas('vendor',function($query){
                    return $query->where('status',1);
                })
                ->withSum('reviews' , 'rating')
                ->withCount(['reviews','foods'=> function ($query)use ($from, $to, $filter) {
                    $query->withoutGlobalScopes()
                    ->applyDateFilter($filter, $from, $to);
                    },
                    'transaction as without_refund_total_orders_count' => function ($query)use ($from, $to, $filter) {
                                $query->NotRefunded()
                                ->applyDateFilter($filter, $from, $to);
                    },])
                ->withSum([
                    'transaction' => function ($query) use ($from, $to, $filter) {
                                $query->NotRefunded()
                                ->applyDateFilter($filter, $from, $to);
                            },
                        ], 'order_amount')
                ->withSum([ 'transaction' => function ($query) use ($from, $to, $filter) {
                                $query->NotRefunded()
                                ->applyDateFilter($filter, $from, $to);
                            },
                        ], 'tax')
                ->withSum([
                    'transaction as transaction_sum_restaurant_expense'  => function ($query) use ($from, $to, $filter) {
                        $query->NotRefunded()
                        ->applyDateFilter($filter, $from, $to);
                    },
                    ], 'discount_amount_by_restaurant')
                ->withSum([
                        'transaction' => function ($query) use ($from, $to, $filter) {
                            $query->NotRefunded()
                            ->applyDateFilter($filter, $from, $to);
                        },
                    ], 'admin_commission')

                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->where('zone_id', $zone->id);
                })
                ->when(isset($filter) , function ($query) use ($filter,$from, $to) {
                    return $query->whereHas('transaction', function($q) use ($filter,$from, $to){
                        return $q->applyDateFilter($filter, $from, $to);
                    });
                })
                ->when(isset($restaurant_model), function ($query) use($restaurant_model) {
                    return $query->RestaurantModel($restaurant_model);
                })
                ->when(isset($type), function ($query) use($type) {
                    return $query->Type($type);
                })
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($qu) use ($key){
                            foreach ($key as $value) {
                                $qu->orWhere('name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%");
                            }
                    });
                })
                ->orderBy('created_at', 'asc')
                ->paginate(config('default_pagination'))
                ->withQueryString();

        $monthly_order = [];
        $data = [];
        $data_avg = [];

        switch ($filter) {
            case "all_time":
                $monthly_order = OrderTransaction::NotRefunded()
                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->whereHas('restaurant',function($q)use ($zone){
                        $q->where('zone_id', $zone->id);
                    });
                })
                ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                    return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                        $q->RestaurantModel($restaurant_model);
                    });
                })
                ->when(isset($type), function ($query) use ($type) {
                    return $query->whereHas('restaurant',function($q)use ($type){
                        $q->Type($type);
                    });
                })
                ->select(
                    DB::raw("(sum(order_amount)) as order_amount"),
                    DB::raw("(avg(order_amount)) as order_amount_avg"),
                    DB::raw("(DATE_FORMAT(created_at, '%Y')) as year")
                )
                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))
                    ->get()->toArray();
                $label = array_map(function ($order) {
                    return $order['year'];
                }, $monthly_order);
                $data = array_map(function ($order) {
                    return $order['order_amount'];
                }, $monthly_order);
                $data_avg = array_map(function ($order) {
                    return $order['order_amount_avg'];
                }, $monthly_order);

            break;
            case "this_year":
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_order[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', now()->format('Y'))
                    ->sum('order_amount');
                    $monthly_order_avg[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', now()->format('Y'))
                    ->avg('order_amount');
                }
                $label = $months;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;

            break;
            case "custom":
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_order[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)
                    ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->sum('order_amount');
                    $monthly_order_avg[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', now()->format('Y'))
                    ->avg('order_amount');
                }
                $label = $months;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;
            break;
            case "previous_year":
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_order[$i] =OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', date('Y') - 1)
                    ->sum('order_amount');
                    $monthly_order_avg[$i] =OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', date('Y') - 1)
                    ->avg('order_amount');
                }
                $label = $months;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;
            break;
            case "this_week":
                $weekStartDate = now()->startOfWeek();
                for ($i = 1; $i <= 7; $i++) {
                    $monthly_order[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereDay('created_at', $weekStartDate->format('d'))->whereMonth('created_at', now()->format('m'))
                    ->sum('order_amount');
                    $weekStartDate = $weekStartDate->addDays(1);

                    $monthly_order_avg[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereDay('created_at', $weekStartDate->format('d'))->whereMonth('created_at', now()->format('m'))
                    ->avg('order_amount');
                    $weekStartDate = $weekStartDate->addDays(1);
                }
                $label = $days;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;
            break;
            case "this_month":
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
                    $monthly_order[$i] =  OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                        ->whereBetween('created_at', ["{$start->format('Y-m-d')} 00:00:00", "{$end->format('Y-m-d')} 23:59:59"])
                        ->sum('order_amount');

                    $monthly_order_avg[$i] =  OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                        ->whereBetween('created_at', ["{$start->format('Y-m-d')} 00:00:00", "{$end->format('Y-m-d')} 23:59:59"])
                        ->avg('order_amount');
                    $start = $start->addDays(7);
                    $end = $i == 3 ? $end->addDays(7 + $remaining_days) : $end->addDays(7);
                }
                $label = $weeks;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;
            break;
            default:
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_order[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)->whereYear('created_at', now()->format('Y'))
                    ->sum('order_amount');
                    $monthly_order_avg[$i] = OrderTransaction::NotRefunded()
                    ->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereHas('restaurant',function($q)use ($zone){
                            $q->where('zone_id', $zone->id);
                        });
                    })
                    ->when(isset($restaurant_model), function ($query) use ($restaurant_model) {
                        return $query->whereHas('restaurant',function($q)use ($restaurant_model){
                            $q->RestaurantModel($restaurant_model);
                        });
                    })
                    ->when(isset($type), function ($query) use ($type) {
                        return $query->whereHas('restaurant',function($q)use ($type){
                            $q->Type($type);
                        });
                    })
                    ->whereMonth('created_at', $i)->whereYear('created_at', now()->format('Y'))
                    ->avg('order_amount');
                }
                $label = $months;
                $data = $monthly_order;
                $data_avg = $monthly_order_avg;
            }
        return view('admin-views.report.restaurant_report', compact('restaurants','filter','zone','to','from',  'monthly_order', 'label', 'data','data_avg'
                    ));
    }
    public function restaurant_export(Request $request)
    {
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $restaurant_model = $request->query('restaurant_model', 'all');
        $type = $request->query('type', 'all');
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurants = Restaurant::with('reviews','vendor')
            ->whereHas('vendor',function($query){
                return $query->where('status',1);
            })
            ->withSum('reviews' , 'rating')
            ->withCount(['reviews','orders','foods'=> function ($query)use ($from, $to, $filter) {
                $query->withoutGlobalScopes()
                ->applyDateFilter($filter, $from, $to);
            },
                'transaction as without_refund_total_orders_count' => function ($query)use ($from, $to, $filter) {
                            $query->NotRefunded()
                      ->applyDateFilter($filter, $from, $to);
                },
                'orders as canceled_orders'=> function ($query)use ($from, $to, $filter) {
                            $query->whereIn('order_status', ['failed', 'canceled'])
                            ->applyDateFilterSchedule($filter, $from, $to);
                        },
                'orders as on_going_orders'=> function ($query)use ($from, $to, $filter) {
                            $query->whereNotIn('order_status', ['failed', 'canceled', 'delivered'])
                            ->applyDateFilterSchedule($filter, $from, $to);
                        },

                ])
            ->withSum([
                'orders as wallet_payment' => function ($query) use ($from, $to, $filter) {
                                $query->where('payment_method' ,'wallet')->has('transaction')
                                ->applyDateFilterSchedule($filter, $from, $to);
                            },
                        ], 'order_amount')
            ->withSum([
                'orders as cash_on_delivery' => function ($query) use ($from, $to, $filter) {
                                $query->where('payment_method' ,'cash_on_delivery')->has('transaction')
                                ->applyDateFilterSchedule($filter, $from, $to);
                            },
                        ], 'order_amount')
            ->withSum([
                'orders as digital_payment' => function ($query) use ($from, $to, $filter) {
                                $query->whereNotIn('payment_method' ,['cash_on_delivery', 'wallet'])->has('transaction')
                                ->applyDateFilterSchedule($filter, $from, $to);
                            },
                        ], 'order_amount')
            ->withSum([
                'transaction' => function ($query) use ($from, $to, $filter) {
                                $query->NotRefunded()
                                ->applyDateFilter($filter, $from, $to);
                            },
                        ], 'order_amount')
            ->withSum([ 'transaction' => function ($query) use ($from, $to, $filter) {
                            $query->NotRefunded()
                            ->applyDateFilter($filter, $from, $to);
                            },
                    ], 'tax')
            ->withSum([
                    'transaction as transaction_sum_restaurant_expense'  => function ($query) use ($from, $to, $filter) {
                        $query->NotRefunded()
                        ->applyDateFilter($filter, $from, $to);
                    },
                    ], 'discount_amount_by_restaurant')
            ->withSum([
                    'transaction' => function ($query) use ($from, $to, $filter) {
                        $query->NotRefunded()
                        ->applyDateFilter($filter, $from, $to);
                    },
                    ], 'admin_commission')

                ->when(isset($zone), function ($query) use ($zone) {
                    return $query->where('zone_id', $zone->id);
                })
                ->when(isset($filter) , function ($query) use ($filter,$from, $to) {
                    return $query->whereHas('transaction', function($q) use ($filter,$from, $to){
                        return $q->applyDateFilter($filter, $from, $to);
                    });
                })
                ->when(isset($restaurant_model), function ($query) use($restaurant_model) {
                    return $query->RestaurantModel($restaurant_model);
                })
                ->when(isset($type), function ($query) use($type) {
                    return $query->Type($type);
                })
                ->when(isset($request['search']), function ($query) use($key){
                    $query->where(function ($qu) use ($key){
                            foreach ($key as $value) {
                                $qu->orWhere('name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%");
                            }
                    });
                })
                ->orderBy('created_at', 'asc')
                ->get();


            $data = [
                'restaurants'=>$restaurants,
                'search'=>$request->search??null,
                'total_restaurants'=>$restaurants->count(),
                'orders'=>$restaurants->sum('orders_count'),
                'total_ongoing'=>$restaurants->sum('on_going_orders'),
                'total_canceled'=>$restaurants->sum('canceled_orders'),
                'cash_payments'=>Helpers::number_format_short( $restaurants->sum('cash_on_delivery')),
                'digital_payments'=>Helpers::number_format_short( $restaurants->sum('digital_payment')),
                'wallet_payments'=>Helpers::number_format_short( $restaurants->sum('wallet_payment')),
                'zone'=>isset($zone)?$zone->name:null,
                'restaurant_model'=>isset($restaurant_model)? $restaurant_model:null,

                'filter'=>$filter,
            ];
        if ($request->export_type == 'csv') {
            return Excel::download(new RestaurantSummaryReportExport($data), 'RestaurantReport.csv');
        }
        return Excel::download(new RestaurantSummaryReportExport($data), 'RestaurantReport.xlsx');


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
    public function subscription_generate_statement($id)
    {
        $key =['phone','email_address','footer_text','business_name','logo'];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        $company_phone =$settings['phone'] ?? null;
        $company_email =$settings['email_address'] ?? null;
        $company_name =$settings['business_name'] ?? null;
        $company_web_logo =$settings['logo'] ?? null;
        $footer_text = $settings['footer_text'] ?? null;

        $transaction = SubscriptionTransaction::with(['restaurant','package'])->where('id', $id)->first();
        $data["email"] = $transaction->restaurant !=null?$transaction->restaurant->email: translate('email_not_found');
        $data["client_name"] = $transaction->restaurant !=null? $transaction->restaurant->name: translate('customer_not_found');
        $data["transaction"] = $transaction;
        $mpdf_view = View::make('admin-views.report.subs-transaction-statement',
            compact('transaction', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'footer_text')
        );
        // return view('admin-views.report.subs-transaction-statement',
        //     compact('transaction', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'footer_text'));
        Helpers::gen_mpdf(view: $mpdf_view,file_prefix: 'transaction',file_postfix: $transaction->id);
    }

    public function disbursement_report(Request $request,$tab = 'restaurant')
    {
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $delivery_man_id = $request->query('delivery_man_id', 'all');
        $delivery_man = is_numeric($delivery_man_id) ? DeliveryMan::findOrFail($delivery_man_id) : null;
        $withdrawal_methods = WithdrawalMethod::ofStatus(1)->get();
        $status = $request->query('status', 'all');
        $payment_method_id = $request->query('payment_method_id', 'all');

        $dis = DisbursementDetails::
        when((isset($tab) && ($tab == 'restaurant')), function ($query) {
            return $query->whereNotNull('restaurant_id');
        })
            ->when((isset($tab) && ($tab == 'delivery_man')), function ($query) {
                return $query->whereNotNull('delivery_man_id');
            })
            ->when((isset($zone) && ($tab == 'restaurant')), function ($query) use ($zone) {
                return $query->whereHas('restaurant',function($q)use ($zone){
                    $q->where('zone_id', $zone->id);
                });
            })
            ->when((isset($restaurant) && ($tab == 'restaurant')), function ($query) use ($restaurant) {
                return $query->where('restaurant_id', $restaurant->id);
            })
            ->when((isset($zone) && ($tab == 'delivery_man')), function ($query) use ($zone) {
                return $query->whereHas('restaurant',function($q)use ($zone){
                    $q->where('zone_id', $zone->id);
                });
            })
            ->when((isset($delivery_man) && ($tab == 'delivery_man')), function ($query) use ($delivery_man) {
                return $query->where('delivery_man_id', $delivery_man->id);
            })
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

        return view('admin-views.report.disbursement-report', compact('disbursements','pending', 'completed','canceled','zone', 'restaurant','filter','from','to','withdrawal_methods','status','payment_method_id','tab'));

    }
    public function disbursement_report_export(Request $request,$type,$tab = 'restaurant')
    {
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $restaurant_id = $request->query('restaurant_id', 'all');
        $restaurant = is_numeric($restaurant_id) ? Restaurant::findOrFail($restaurant_id) : null;
        $delivery_man_id = $request->query('delivery_man_id', 'all');
        $delivery_man = is_numeric($delivery_man_id) ? DeliveryMan::findOrFail($delivery_man_id) : null;
        $withdrawal_methods = WithdrawalMethod::ofStatus(1)->get();
        $status = $request->query('status', 'all');
        $payment_method_id = $request->query('payment_method_id', 'all');

        $disbursements = DisbursementDetails::
        when((isset($tab) && ($tab == 'restaurant')), function ($query) {
            return $query->whereNotNull('restaurant_id');
        })
            ->when((isset($tab) && ($tab == 'delivery_man')), function ($query) {
                return $query->whereNotNull('delivery_man_id');
            })
            ->when((isset($zone) && ($tab == 'restaurant')), function ($query) use ($zone) {
                return $query->whereHas('restaurant',function($q)use ($zone){
                    $q->where('zone_id', $zone->id);
                });
            })
            ->when((isset($restaurant) && ($tab == 'restaurant')), function ($query) use ($restaurant) {
                return $query->where('restaurant_id', $restaurant->id);
            })
            ->when((isset($zone) && ($tab == 'delivery_man')), function ($query) use ($zone) {
                return $query->whereHas('restaurant',function($q)use ($zone){
                    $q->where('zone_id', $zone->id);
                });
            })
            ->when((isset($delivery_man) && ($tab == 'delivery_man')), function ($query) use ($delivery_man) {
                return $query->where('delivery_man_id', $delivery_man->id);
            })
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
            'type'=>$tab,
            'disbursements' =>$disbursements,
            'restaurant'=>isset($restaurant)?$restaurant->name:null,
            'delivery_man'=>isset($delivery_man)?$delivery_man->f_name.''.$delivery_man->f_name:null,
            'search'=>$request->search??null,
            'status'=>$status,
            'zone'=>isset($zone)?$zone->name:null,
            'filter'=>$filter,
            'from'=>(($filter == 'custom') && $from)?$from:null,
            'to'=>(($filter == 'custom') && $to)?$to:null,
            'pending' =>(float) $disbursements->where('status','pending')->sum('disbursement_amount'),
            'completed' =>(float) $disbursements->where('status','completed')->sum('disbursement_amount'),
            'canceled' =>(float) $disbursements->where('status','canceled')->sum('disbursement_amount'),
        ];
        if($type == 'csv'){
            return Excel::download(new DisbursementReportExport($data), 'DisbursementReport.csv');
        }
        return Excel::download(new DisbursementReportExport($data), 'DisbursementReport.xlsx');

    }
}
