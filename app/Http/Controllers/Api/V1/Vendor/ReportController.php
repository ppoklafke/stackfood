<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Models\Food;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\OrderTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\DisbursementDetails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function expense_report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

        $key = explode(' ', $request['search']);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limite']??25;
        $offset = $request['offset']??1;
        $from = $request->from;
        $to = $request->to;
        $restaurant_id = $request?->vendor?->restaurants[0]?->id;

        $expense = Expense::where('created_by','vendor')->where('restaurant_id',$restaurant_id)
            ->when(isset($from) &&  isset($to) ,function($query) use($from,$to){
                $query->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:29']);
            })->when(isset($key), function($query) use($key) {
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('order_id', 'like', "%{$value}%");
                    }
                });
            })
            ->paginate($limit, ['*'], 'page', $offset);
            $data = [
                'total_size' => $expense->total(),
                'limit' => $limit,
                'offset' => $offset,
                'expense' => $expense->items()
            ];
            return response()->json($data,200);
    }


    public function day_wise_report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limite']??25;
        $offset = $request['offset']??1;

        $key = explode(' ', $request['search']);
        $from = null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $restaurant_id = $request?->vendor?->restaurants[0]?->id;


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
            ->paginate($limit, ['*'], 'page', $offset);




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


        $data = [
            'total_size' => $order_transactions->total(),
            'limit' => $limit,
            'offset' => $offset,
            'on_hold' =>(int) $on_hold,
            'canceled' =>(int) $canceled,
            'completed_transactions' =>(int) $delivered,
            'order_transactions' => $this->transaction_report_formatter( $order_transactions->items())
        ];
        return response()->json($data,200);

    }


    public function order_report(Request $request){
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limite']??25;
        $offset = $request['offset']??1;

        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $restaurant_id = $request?->vendor?->restaurants[0]?->id;

        $restaurant= Restaurant::where('id' , $restaurant_id)->first();
        $data =0;
        if (($restaurant->restaurant_model == 'subscription' && isset($restaurant->restaurant_sub) && $restaurant->restaurant_sub->self_delivery == 1)  || ($restaurant->restaurant_model == 'commission' && $restaurant->self_delivery_system == 1) ){
            $data =1;
        }

        $key = explode(' ', $request['search']);
        $orders = Order::with(['restaurant','details','transaction'])->where('restaurant_id',$restaurant_id)
            ->Notpos()
            ->NotDigitalOrder()
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
            ->orderBy('schedule_at', 'desc')
            ->paginate($limit, ['*'], 'page', $offset);
        // order card values calculation
            $orders_list = Order::where('restaurant_id',$restaurant_id)->Notpos()
            ->NotDigitalOrder()
            ->whereNotIn('order_status',(config('order_confirmation_model') == 'restaurant'|| $data)?['failed','canceled', 'refund_requested']:['pending','failed','canceled', 'refund_requested'])
            ->HasSubscriptionToday()->OrderScheduledIn(30)
            ->applyDateFilterSchedule($filter, $from, $to)
            ->orderBy('schedule_at', 'desc')->get();

            $other_data=[
                'total_canceled_count' => $orders_list->where('order_status', 'canceled')->count(),
                    'total_delivered_count' => $orders_list->where('order_status', 'delivered')->where('order_type', '<>' , 'pos')->count(),
                    'total_progress_count' => $orders_list->whereIn('order_status', ['accepted','confirmed','processing','handover'])->count(),
                    'total_failed_count' => $orders_list->where('order_status', 'failed')->count(),
                    'total_refunded_count' => $orders_list->where('order_status', 'refunded')->count(),
                    'total_on_the_way_count' => $orders_list->whereIn('order_status', ['picked_up'])->count(),
                    'total_accepted_count' => $orders_list->where('order_status', 'accepted')->count(),
                    'total_pending_count' => $orders_list->where('order_status', 'pending')->count(),
                    'total_scheduled_count' => $orders_list->where('scheduled', 1)->count(),
            ];

        $data = [
            'total_size' => $orders->total(),
            'limit' => $limit,
            'offset' => $offset,
            'other_data' => $other_data,
            'orders' => $this->order_report_formatter($orders->items())
        ];
        return response()->json($data,200);

    }

    public function food_wise_report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limite']??25;
        $offset = $request['offset']??1;

        $months = [
            translate('Jan'),
            translate('Feb'),
            translate('Mar'),
            translate('Apr'),
            translate('May'),
            translate('Jun'),
            translate('Jul'),
            translate('Aug'),
            translate('Sep'),
            translate('Oct'),
            translate('Nov'),
            translate('Dec')
        ];

        $from =  null;
        $to = null;
        $type = $request->query('type', 'all');
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $restaurant_id = $request?->vendor?->restaurants[0]?->id;
        $category_id = $request->query('category_id', 'all');
        $category = is_numeric($category_id) ? Category::findOrFail($category_id) : null;
        $foods = Food::where('restaurant_id' ,$restaurant_id)
            ->withCount([
                'orders' => function ($query) use ($from, $to, $filter ,$restaurant_id) {
                    $query->whereHas('order', function($query) use($restaurant_id){
                        return $query->where('restaurant_id',$restaurant_id)->whereIn('order_status',['delivered','refund_requested','refund_request_canceled']);
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
                            return $query->whereBetween('order_details.created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
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
                            return $query->whereBetween('order_details.created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]); 
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
            ->with(['restaurant','translations'])
            ->having('orders_count', '>' ,0)
            ->orderBy('orders_count', 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        $monthly_order = [];
        $data = [];
        $data_avg = [];
        $discount_on_food = [];
        $label = [];


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
                $data[] = (int) $monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'];
                $discount_on_food[] = (int) $monthly_order[$i]['discount_on_food'];
                $data_avg[] = (int) ($monthly_order[$i]['order_count'] > 0 ? ($monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'] )/ $monthly_order[$i]['quantity'] : 0 );
            }
            $label = $months;
        }
        elseif($filter == 'this_week' ){
            $days = [
                translate('Sun'),
                translate('Mon'),
                translate('Tue'),
                translate('Wed'),
                translate('Thu'),
                translate('Fri'),
                translate('Sat')
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
                $data[] = (int) $monthly_order[$i]['earning'] -$monthly_order[$i]['discount_on_food'];
                $discount_on_food[] = (int) $monthly_order[$i]['discount_on_food'];
                $data_avg[] = (int) ($monthly_order[$i]['order_count'] > 0 ? ($monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'] )/ $monthly_order[$i]['quantity'] : 0 );
            }
            $data = $data;
            $label = $days;
        }
        elseif($filter == 'this_month'){
            $start = now()->startOfMonth();
            $end = now()->startOfMonth()->addDays(7);
            $total_day = now()->daysInMonth;
            $weeks = [
                translate('Day 1-7'),
                translate('Day 8-14'),
                translate('Day 15-21'),
                translate('Day 22-') . $total_day ,
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
                $data[] = (int) $monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'];
                $discount_on_food[] = (int) $monthly_order[$i]['discount_on_food'];
                $data_avg[] = (int) ($monthly_order[$i]['order_count'] > 0 ? ($monthly_order[$i]['earning'] - $monthly_order[$i]['discount_on_food'] )/ $monthly_order[$i]['quantity'] : 0 );
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
                return  (int) $order['earning'] - ($order['discount_on_food']);
            }, $monthly_order);
            $data_avg = array_map(function ($order) {
                return (int) ($order['order_count'] > 0 ? ($order['earning'] - $order['discount_on_food'] )/ $order['quantity'] : 0) ;
            }, $monthly_order);
            $discount_on_food = array_map(function ($order) {
                return (int) ($order['discount_on_food']) ;
            }, $monthly_order);
        }


        if (isset($filter) &&  in_array($filter, ['this_year','previous_year','custom'])){
            $avg_type=  translate('Average_Monthly_Sales_Value') ;
            $earning_avg= (array_sum($data)  )  / 12 ;
        }
        elseif(isset($filter) &&  in_array($filter, ['this_week'])){
            $avg_type =translate('Average_Daily_Sales_Value') ;
            $earning_avg =(array_sum($data)   / 7) ;
        }

        elseif(isset($filter) &&  in_array($filter, ['this_month'])){
            $avg_type =translate('Average_Monthly_Sales_Value') ;
            $earning_avg =  ((array_sum($data)  )  / 4) ;
        }

        elseif(!$filter ||  $filter== 'all_time'){
            $avg_type=translate('Average_Yearly_Sales_Value') ;
           $earning_avg = (array_sum($data)  )  / (count($data)> 0 ? count($data) : 1 ) ;
        }




        $items = [
            'total_size' => $foods->total(),
            'limit' => $limit,
            'offset' => $offset,
            'label' => $label,
            'earning' => $data,
            'earning_avg' => $earning_avg ?? 0,
            'avg_type' => $avg_type ??  translate('Average_Yearly_Sales_Value'),

            'foods' => $this->food_report_formatter($foods->items())
        ];
        return response()->json($items,200);
    }
    public function campaign_order_report(Request $request) {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limite']??25;
        $offset = $request['offset']??1;

        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $restaurant_id = $request?->vendor?->restaurants[0]?->id;
        $key = explode(' ', $request['search']);

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
            ->paginate($limit, ['*'], 'page', $offset);

        $orders_list = Order::where('restaurant_id',$restaurant_id)
            ->whereHas('details',function ($query){
                $query->whereNotNull('item_campaign_id');
            })
            ->when(is_numeric($campaign_id), function ($query) use ($campaign_id) {
                return $query->whereHas('details',function ($query) use ($campaign_id){
                    $query->where('item_campaign_id',$campaign_id);
                });
            })
            ->applyDateFilterSchedule($filter, $from, $to)
            ->orderBy('schedule_at', 'desc')
            ->get();

        $other_data=[
            'total_canceled_count' => $orders_list->where('order_status', 'canceled')->count(),
            'total_delivered_count' => $orders_list->where('order_status', 'delivered')->where('order_type', '<>' , 'pos')->count(),
            'total_progress_count' => $orders_list->whereIn('order_status', ['accepted','confirmed','processing','handover'])->count(),
            'total_failed_count' => $orders_list->where('order_status', 'failed')->count(),
            'total_refunded_count' => $orders_list->where('order_status', 'refunded')->count(),
            'total_on_the_way_count' => $orders_list->whereIn('order_status', ['picked_up'])->count(),
            'total_orders' => $orders_list->count(),
        ];

        $data = [
            'total_size' => $orders->total(),
            'limit' => $limit,
            'offset' => $offset,
            'other_data' => $other_data,
            'orders' => $this->order_report_formatter($orders->items())
        ];
        return response()->json($data,200);

  }

    private function order_report_formatter($data): array
    {
        $storage = [];
        foreach ($data as $item) {
               $storage[] = [
                'order_id' => (int)  $item->id,
                'total_item_amount' => (float)  ($item->order_amount- $item->additional_charge - $item->dm_tips-$item->total_tax_amount-$item->delivery_charge - $item->order?->extra_packaging_amount +$item->coupon_discount_amount + $item->restaurant_discount_amount + $item?->order?->ref_bonus_amount),
                'item_discount' => (float)  $item->details()->sum(DB::raw('discount_on_food * quantity')),
                'coupon_discount' => (float)  $item->coupon_discount_amount,
                'referral_discount' => (float)  $item->ref_bonus_amount,
                'discounted_amount' => (float)  ($item->coupon_discount_amount + $item->restaurant_discount_amount + $item->order?->ref_bonus_amount),
                'tax' => (float)  $item->total_tax_amount,
                'delivery_charge' => (float)  $item->delivery_charge,
                'additional_charge' => (float)  $item->additional_charge,
                'order_amount' => (float)  $item->order_amount,
                'extra_packaging_amount' => (float)  $item->extra_packaging_amount,
                'amount_received_by' =>   isset($item->transaction) ? translate(str_replace('_', ' ', $item->transaction->received_by))  : translate('messages.not_received_yet'),
                'payment_method' =>  translate(str_replace('_', ' ', $item->payment_method)),
                'order_status' =>   $item->order_status,
                'payment_status' =>  $item->payment_status,
                'dm_tips' => (float)  $item->dm_tips,
            ];
        }
            return $storage;
    }

    private function transaction_report_formatter($data): array
    {
        $storage = [];
        foreach ($data as $item) {
            if ($item->order->customer){
                $customer_name =  $item->order->customer['f_name'] . ' ' . $item->order->customer['l_name'];
            }
            elseif($item->order->is_guest){
                $customer_details = json_decode($item->order['delivery_address'],true);
                $customer_name =$customer_details['contact_person_name'];
            }
            $discount_by_admin = 0;
            if($item->order->discount_on_product_by == 'admin'){
                $discount_by_admin = $item->order['restaurant_discount_amount'];
            };

            if ($item->received_by == 'admin'){
                $amount_received_by = translate('messages.admin') ;
            } elseif($item->received_by == 'restaurant'){
                $amount_received_by = translate('messages.restaurant') ;
            }else{
                if (isset($item->order->delivery_man) && $item->order->delivery_man->earning == 1){
                    $amount_received_by = translate('messages.delivery_man').' ('.translate('messages.freelance').')' ;
                } elseif (isset($item->order->delivery_man) && $item->order->delivery_man->earning == 0 && $item->order->delivery_man->type == 'restaurant_wise'){
                    $amount_received_by =  translate('messages.delivery_man').' ('.translate('messages.restaurant').')' ;
                } elseif (isset($item->order->delivery_man) && $item->order->delivery_man->earning == 0 && $item->order->delivery_man->type == 'zone_wise'){
                    $amount_received_by =  translate('messages.delivery_man').' ('.translate('messages.admin').')' ;
                } else{
                    $amount_received_by = translate('messages.delivery_man') ;
                }
            }

            $storage[] = [
                'order_id' => (int)  $item->order_id,
                'restaurant' =>  $item?->order?->restaurant ? $item->order->restaurant->name : translate('messages.Not_found') ,
                'customer_name' => $customer_name ?? translate('messages.Not_found') ,

                'total_item_amount' =>(float)  ($item->order['order_amount'] - $item->additional_charge  -  $item->order['dm_tips']-$item->order['delivery_charge'] - $item['tax'] - $item->order['extra_packaging_amount'] + $item->order['coupon_discount_amount'] + $item->order['restaurant_discount_amount'] + $item->order['ref_bonus_amount']),
                'item_discount' =>(float)  $item->order->details()->sum(DB::raw('discount_on_food * quantity')),
                'coupon_discount' => (float) $item->order['coupon_discount_amount'],
                'referral_discount' => (float) $item->order['ref_bonus_amount'],
                'discounted_amount' => (float) ($item->order['coupon_discount_amount'] + $item->order['restaurant_discount_amount'] + $item->order['ref_bonus_amount']),
                'vat' => (float) $item->tax,
                'delivery_charge' =>(float)  ($item->delivery_charge + $item->delivery_fee_comission),
                'order_amount' => (float) $item->order_amount,
                'admin_discount' =>(float)  $item->admin_expense,
                'restaurant_discount' => (float) $item->discount_amount_by_restaurant,
                'admin_commission' =>(float) ( $item->admin_commission  - $item->additional_charge ),
                'additional_charge' => (float) $item->additional_charge,
                'extra_packaging_amount' => (float) $item->extra_packaging_amount,
                'commission_on_delivery_charge' => (float) $item->delivery_fee_comission,
                'admin_net_income' =>(float)  ($item->admin_commission + $item->delivery_fee_comission) ,
                'restaurant_net_income' => (float) ($item->restaurant_amount - $item->tax),
                'amount_received_by' => $amount_received_by ??  translate('messages.admin') ,
                'payment_method' => translate(str_replace('_', ' ', $item->order['payment_method'])),
                'payment_status' =>$item->status ?  translate('messages.refunded') : translate('messages.completed') ,

            ];
        }
        return $storage;
    }

    private function food_report_formatter($data): array
    {
        $storage = [];
        foreach ($data as $item) {
            $storage[] = [
                'image' => $item->image,
                'name' =>  $item->name,
                'total_order_count' => (int)  $item->orders_sum_quantity,
                'unit_price' => (float)  $item->price,
                'total_amount_sold' => (int)  $item->orders_sum_price,
                'total_discount_given' => (float)  $item->total_discount,
                'average_sale_value' => (float) ($item->orders_count>0? ($item->orders_sum_price-$item->total_discount)/($item->orders_sum_quantity ?? 0):0 ),
                'total_ratings_given' => (int)  $item->rating_count,
                'average_ratings' => (int)  round($item->avg_rating,1),
            ];
        }
        return $storage;
    }

    public function disbursement_report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limit']??25;
        $offset = $request['offset']??1;

        $restaurant_id = $request?->vendor?->restaurants[0]?->id;

        $total_disbursements=DisbursementDetails::where('restaurant_id',$restaurant_id)->orderBy('created_at', 'desc')->get();
        $paginator=DisbursementDetails::where('restaurant_id',$restaurant_id)->latest()->paginate($limit, ['*'], 'page', $offset);

        $paginator->each(function ($data) {
            $data->withdraw_method?->method_fields ?  $data->withdraw_method->method_fields = json_decode($data->withdraw_method?->method_fields, true) : '';
        });

        $data = [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'pending' =>(float) $total_disbursements->where('status','pending')->sum('disbursement_amount'),
            'completed' =>(float) $total_disbursements->where('status','completed')->sum('disbursement_amount'),
            'canceled' =>(float) $total_disbursements->where('status','canceled')->sum('disbursement_amount'),
            'complete_day' =>(int) BusinessSetting::where(['key'=>'restaurant_disbursement_waiting_time'])->first()?->value,
            'disbursements' => $paginator->items()
        ];
        return response()->json($data,200);

    }

    public function generate_transaction_statement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $vendor_id = $request?->vendor?->id;
        $key =['phone','email_address','footer_text','business_name','logo'];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        $company_phone =$settings['phone'] ?? null;
        $company_email =$settings['email_address'] ?? null;
        $company_name =$settings['business_name'] ?? null;
        $company_web_logo =$settings['logo'] ?? null;
        $footer_text = $settings['footer_text'] ?? null;

        $order_transaction = OrderTransaction::with('order','order.details','order.customer','order.restaurant')->where('vendor_id',$vendor_id)->where('order_id', $request->order_id)->first();
        if (!$order_transaction) {
            return response()->json(['error' => translate('Order transaction not found')], 404);
        }
        $data["email"] = $order_transaction->order->customer !=null?$order_transaction->order->customer["email"]: translate('email_not_found');
        $data["client_name"] = $order_transaction->order->customer !=null? $order_transaction->order->customer["f_name"] . ' ' . $order_transaction->order->customer["l_name"]: translate('customer_not_found');
        $data["order_transaction"] = $order_transaction;

        $mpdf_view = View::make('admin-views.report.order-transaction-statement', compact('order_transaction', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'footer_text'));

        $file_name = Helpers::down_mpdf(view: $mpdf_view, file_prefix: 'order_trans_statement', file_postfix: $order_transaction->id);

        if (!$file_name) {
            return response()->json(['error' => 'Failed to generate statement'], 500);
        }

        $file_url = dynamicStorage('storage/app/public/pdfs') .'/'. $file_name;

        return response()->json(['file_url' => $file_url], 200);
    }
}
