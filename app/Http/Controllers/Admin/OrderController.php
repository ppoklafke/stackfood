<?php

namespace App\Http\Controllers\Admin;

use App\Models\Food;
use App\Models\Zone;
use App\Models\AddOn;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Refund;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\DeliveryMan;
use App\Models\OrderDetail;
use App\Models\Translation;
use App\Exports\OrderExport;
use App\Mail\RefundRejected;
use App\Models\ItemCampaign;
use App\Models\OrderPayment;
use App\Models\RefundReason;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\VariationOption;
use App\Scopes\RestaurantScope;
use App\CentralLogics\OrderLogic;
use App\Models\OrderCancelReason;
use App\CentralLogics\CouponLogic;
use App\Exports\OrderRefundExport;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\CustomerLogic;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RestaurantOrderlistExport;
use MatanYadaev\EloquentSpatial\Objects\Point;

class OrderController extends Controller
{
    public function list($status, Request $request)
    {
        $key = explode(' ', $request['search']);

        if (session()->has('zone_filter') == false) {
            session()->put('zone_filter', 0);
        }

        if (session()->has('order_filter') && $status != 'dine_in') {
            $request = json_decode(session('order_filter'));
        }

        Order::where(['checked' => 0])->update(['checked' => 1]);

        $orders = Order::with(['customer', 'restaurant'])
            ->when(isset($request->zone), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($q) use ($request) {
                    return $q->whereIn('zone_id', $request->zone);
                });
            })
            ->when($status == 'scheduled', function ($query) {
                return $query->whereRaw('created_at <> schedule_at');
            })
            ->when($status == 'searching_for_deliverymen', function ($query) {
                return $query->SearchingForDeliveryman();
            })
            ->when($status == 'pending', function ($query) {
                return $query->Pending();
            })
            ->when($status == 'accepted', function ($query) {
                return $query->AccepteByDeliveryman();
            })
            ->when($status == 'processing', function ($query) {
                return $query->Preparing();
            })
            ->when($status == 'food_on_the_way', function ($query) {
                return $query->FoodOnTheWay();
            })
            ->when($status == 'delivered', function ($query) {
                return $query->Delivered();
            })
            ->when($status == 'canceled', function ($query) {
                return $query->Canceled();
            })
            ->when($status == 'failed', function ($query) {
                return $query->failed();
            })
            ->when($status == 'requested', function ($query) {
                return $query->Refund_requested();
            })
            ->when($status == 'rejected', function ($query) {
                return $query->Refund_request_canceled();
            })
            ->when($status == 'refunded', function ($query) {
                return $query->Refunded();
            })
            ->when($status == 'scheduled', function ($query) {
                return $query->Scheduled();
            })
            ->when($status == 'on_going', function ($query) {
                return $query->Ongoing();
            })
            ->when($status == 'dine_in', function ($query) {
                return $query->where('order_type','dine_in');
            })
            ->when( !in_array($status,['all','scheduled','canceled','refund_requested','refunded','delivered','failed','dine_in'])  , function ($query) {
                return $query->OrderScheduledIn(30);
            })
            ->when(isset($request->vendor), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($query) use ($request) {
                    return $query->whereIn('id', $request->vendor);
                });
            })
            ->when(isset($request->orderStatus) && $status == 'all', function ($query) use ($request) {
                return $query->whereIn('order_status', $request->orderStatus);
            })
            ->when(isset($request->scheduled) && $status == 'all', function ($query) {
                return $query->scheduled();
            })
            ->when(isset($request->order_type), function ($query) use ($request) {
                return $query->where('order_type', $request->order_type);
            })
            ->when($request?->from_date != null && $request?->to_date != null, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
            })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_reference', 'like', "%{$value}%");
                    }
                });
            })
            ->Notpos()
            ->hasSubscriptionToday()
            ->orderBy('schedule_at', 'desc')
            ->paginate(config('default_pagination'));

        $orderstatus = $request?->orderStatus ?? [];
        $scheduled =  $request?->scheduled ?? 0;
        $vendor_ids =  $request?->vendor ?? [];
        $zone_ids = $request?->zone ?? [];
        $from_date =  $request?->from_date ?? null;
        $to_date = $request?->to_date ?? null;
        $order_type =  $request?->order_type ?? null;
        $total = $orders->total();

        return view('admin-views.order.list', compact('orders', 'status', 'orderstatus', 'scheduled', 'vendor_ids', 'zone_ids', 'from_date', 'to_date', 'total', 'order_type'));
    }

    public function export_orders($status, $type, Request $request)
    {

        try{
            $key = explode(' ', $request['search']);

            if (session()->has('zone_filter') == false) {
                session()->put('zone_filter', 0);
            }

            if (session()->has('order_filter')) {
                $request = json_decode(session('order_filter'));
            }

            $orders = Order::with(['customer', 'restaurant','refund'])
                ->when(isset($request->zone), function ($query) use ($request) {
                    return $query->whereHas('restaurant', function ($q) use ($request) {
                        return $q->whereIn('zone_id', $request->zone);
                    });
                })
                ->when($status == 'scheduled', function ($query) {
                    return $query->whereRaw('created_at <> schedule_at');
                })
                ->when($status == 'searching_for_deliverymen', function ($query) {
                    return $query->SearchingForDeliveryman();
                })
                ->when($status == 'pending', function ($query) {
                    return $query->Pending();
                })
                ->when($status == 'accepted', function ($query) {
                    return $query->AccepteByDeliveryman();
                })
                ->when($status == 'processing', function ($query) {
                    return $query->Preparing();
                })
                ->when($status == 'food_on_the_way', function ($query) {
                    return $query->FoodOnTheWay();
                })
                ->when($status == 'delivered', function ($query) {
                    return $query->Delivered();
                })
                ->when($status == 'canceled', function ($query) {
                    return $query->Canceled();
                })
                ->when($status == 'failed', function ($query) {
                    return $query->failed();
                })
                ->when($status == 'requested', function ($query) {
                    return $query->Refund_requested();
                })
                ->when($status == 'rejected', function ($query) {
                    return $query->Refund_request_canceled();
                })
                ->when($status == 'refunded', function ($query) {
                    return $query->Refunded();
                })
                ->when($status == 'requested', function ($query) {
                    return $query->Refund_requested();
                })
                ->when($status == 'rejected', function ($query) {
                    return $query->Refund_request_canceled();
                })
                ->when($status == 'scheduled', function ($query) {
                    return $query->Scheduled();
                })
                ->when($status == 'on_going', function ($query) {
                    return $query->Ongoing();
                })
                ->when($status == 'dine_in', function ($query) {
                    return $query->where('order_type','dine_in');
                })
                ->when( !in_array($status,['all','scheduled','canceled','refund_requested','refunded','delivered','failed','dine_in'])  , function ($query) {
                    return $query->OrderScheduledIn(30);
                })
                ->when(isset($request->vendor), function ($query) use ($request) {
                    return $query->whereHas('restaurant', function ($query) use ($request) {
                        return $query->whereIn('id', $request->vendor);
                    });
                })
                ->when(isset($request->orderStatus) && $status == 'all', function ($query) use ($request) {
                    return $query->whereIn('order_status', $request->orderStatus);
                })
                ->when(isset($request->scheduled) && $status == 'all', function ($query) {
                    return $query->scheduled();
                })
                ->when(isset($request->order_type), function ($query) use ($request) {
                    return $query->where('order_type', $request->order_type);
                })
                ->when($request?->from_date != null && $request?->to_date != null, function ($query) use ($request) {
                    return $query->whereBetween('created_at', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
                })
                ->when(isset($key), function ($query) use ($key) {
                    return $query->where(function ($q) use ($key) {
                        foreach ($key as $k => $value) {
                            $q->orWhere('id', 'like', "%{$value}%")
                                ->orWhere('order_status', 'like', "%{$value}%")
                                ->orWhere('transaction_reference', 'like', "%{$value}%");
                                $search[$k]= $value;
                        }
                    });
                })
                ->Notpos()
                ->orderBy('schedule_at', 'desc')
                ->hasSubscriptionToday()


                ->when($status == 'offline_payments' && $request?->payment_status == 'pending' , function ($query) {
                    return $query->whereHas('offline_payments', function ($query) {
                        return $query->where('status', 'pending');
                    });
                })
                ->when($status == 'offline_payments' && $request?->payment_status == 'all' , function ($query) {
                    return $query->has('offline_payments');
                })
                ->when($status == 'offline_payments' && $request?->payment_status == 'denied', function ($query) {
                    return $query->whereHas('offline_payments', function ($query) {
                        return $query->where('status', 'denied');
                    });
                })
                ->when($status == 'offline_payments' && $request?->payment_status == 'verified' , function ($query) {
                    return $query->whereHas('offline_payments', function ($query) {
                        return $query->where('status', 'verified');
                    });
                })
                ->get();

                if (in_array($status, ['requested','rejected','refunded']))
                {
                    $data = [
                        'orders'=>$orders,
                        'type'=>$request->order_type ?? translate('messages.all'),
                        'status'=>$status,
                        'order_status'=>isset($request->orderStatus)?implode(', ', $request->orderStatus):null,
                        'search'=>$request->search ?? $key[0] ??null,
                        'from'=>$request->from_date??null,
                        'to'=>$request->to_date??null,
                        'zones'=>isset($request->zone)?Helpers::get_zones_name($request->zone):null,
                        'restaurant'=>isset($request->vendor)?Helpers::get_restaurant_name($request->vendor):null,
                    ];

                    if ($type == 'excel') {
                        return Excel::download(new OrderRefundExport($data), 'RefundOrders.xlsx');
                    } else if ($type == 'csv') {
                        return Excel::download(new OrderRefundExport($data), 'RefundOrders.csv');
                    }
                }


            $data = [
                'orders'=>$orders,
                'type'=>$request->order_type ?? translate('messages.all'),
                'status'=>$status,
                'order_status'=>isset($request->orderStatus)?implode(', ', $request->orderStatus):null,
                'search'=>$request->search ?? $key[0] ??null,
                'from'=>$request->from_date??null,
                'to'=>$request->to_date??null,
                'zones'=>isset($request->zone)?Helpers::get_zones_name($request->zone):null,
                'restaurant'=>isset($request->vendor)?Helpers::get_restaurant_name($request->vendor):null,
            ];

            if ($type == 'excel') {
                return Excel::download(new OrderExport($data), 'Orders.xlsx');
            } else if ($type == 'csv') {
                return Excel::download(new OrderExport($data), 'Orders.csv');
            }

        } catch(\Exception $e) {
            dd($e);
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }
    }

    public function dispatch_list($status, Request $request)
    {

        $key = explode(' ', $request?->search);
        if (session()->has('order_filter')) {
            $request = json_decode(session('order_filter'));
            $zone_ids =  $request?->zone ?? 0;
        }

        Order::where(['checked' => 0])->update(['checked' => 1]);

        $orders = Order::with(['customer', 'restaurant'])
            ->when(isset($request->zone), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($query) use ($request) {
                    return $query->whereIn('zone_id', $request->zone);
                });
            })
            ->when($status == 'searching_for_deliverymen', function ($query) {
                return $query->SearchingForDeliveryman();
            })
            ->when($status == 'on_going', function ($query) {
                return $query->Ongoing();
            })
            ->when(isset($request->vendor), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($query) use ($request) {
                    return $query->whereIn('id', $request->vendor);
                });
            })
            ->when(isset($key),function($query) use($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_reference', 'like', "%{$value}%");
                    }
                });
            })
            ->when($request?->from_date != null && $request?->to_date != null, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
            })

            ->Notpos()
            ->OrderScheduledIn(30)
            ->hasSubscriptionToday()
            ->orderBy('schedule_at', 'desc')
            ->paginate(config('default_pagination'));

        $orderstatus = $request?->orderStatus ?? [];
        $scheduled =   $request?->scheduled ?? 0;
        $vendor_ids =  $request?->vendor ?? [];
        $zone_ids =   $request?->zone ?? [];
        $from_date =   $request?->from_date ?? null;
        $to_date =   $request?->to_date ?? null;
        $total = $orders->total();

        return view('admin-views.order.distaptch_list', compact('orders', 'status', 'orderstatus', 'scheduled', 'vendor_ids', 'zone_ids', 'from_date', 'to_date', 'total'));
    }

    public function refund_settings()
    {
        $refund_active_status = BusinessSetting::where(['key'=>'refund_active_status'])->first();
        $reasons=RefundReason::orderBy('id', 'desc')
        ->paginate(config('default_pagination'));

        return view('admin-views.refund.index', compact('refund_active_status','reasons'));
    }

    public function refund_reason(Request $request)
    {
        $request->validate([
            'reason' => 'required|max:191',
        ]);

        if($request->reason[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_reason_is_required'));
            return back();
            }


        $reason = new RefundReason();
        $reason->reason = $request->reason[array_search('default', $request->lang)];
        $reason->save();
        $data = [];
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->reason[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\RefundReason',
                        'translationable_id' => $reason->id,
                        'locale' => $key,
                        'key' => 'reason',
                        'value' => $reason->reason,
                    ));
                }
            }else{
                if ($request->reason[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\RefundReason',
                        'translationable_id' => $reason->id,
                        'locale' => $key,
                        'key' => 'reason',
                        'value' => $request->reason[$index],
                    ));
                }
            }
        }
        Translation::insert($data);
        Toastr::success(translate('Refund Reason Added Successfully'));
        return back();
    }
    public function reason_edit(Request $request)
    {
        $request->validate([
            'reason' => 'required|max:100',
        ]);
        if($request->reason[array_search('default', $request->lang1)] == '' ){
            Toastr::error(translate('default_reason_is_required'));
            return back();
            }
        $refund_reason = RefundReason::findOrFail($request->reason_id);
        $refund_reason->reason = $request->reason[array_search('default', $request->lang1)];
        $refund_reason->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang1 as $index => $key) {
            if($default_lang == $key && !($request->reason[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\RefundReason',
                            'translationable_id' => $refund_reason->id,
                            'locale' => $key,
                            'key' => 'reason'
                        ],
                        ['value' => $refund_reason->reason]
                    );
                }
            }else{
                if ($request->reason[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\RefundReason',
                            'translationable_id' => $refund_reason->id,
                            'locale' => $key,
                            'key' => 'reason'
                        ],
                        ['value' => $request->reason[$index]]
                    );
                }
            }
        }


        Toastr::success(translate('Refund Reason Updated Successfully'));
        return back();
    }
    public function reason_status(Request $request)
    {
        $refund_reason = RefundReason::findOrFail($request->id);
        $refund_reason->status = $request->status;
        $refund_reason->save();
        Toastr::success(translate('messages.status_updated'));
        return back();
    }
    public function reason_delete(Request $request)
    {
        $refund_reason = RefundReason::findOrFail($request->id);
        $refund_reason?->translations()?->delete();
        $refund_reason->delete();
        Toastr::success(translate('Refund Reason Deleted Successfully'));
        return back();
    }

    public function order_refund_rejection(Request $request){

        $request->validate([
            'order_id' => 'required',
            'admin_note' => 'nullable|string|max:65535',
        ]);
            Refund::where('order_id', $request->order_id)->update([
                'order_status' => 'refund_request_canceled',
                'admin_note'=>$request?->admin_note ?? null,
                'refund_status'=>'rejected',
                'refund_method'=>'canceled',
            ]);

            $order = Order::Notpos()->find($request->order_id);
            $order->order_status = 'refund_request_canceled';
            $order->refund_request_canceled = now();
            $order->save();

            try {
                $notification_status= Helpers::getNotificationStatusData('customer','customer_refund_request_rejaction');

                if($notification_status?->mail_status == 'active' && config('mail.status') && $order?->customer?->email && Helpers::get_mail_status('refund_request_deny_mail_status_user') == '1'){
                    Mail::to($order->customer->email)->send(new RefundRejected($order->id));
                }
            } catch (\Throwable $th) {
                info($th->getMessage());
                Toastr::error(translate('messages.Failed_to_send_mail'));
            }

            Toastr::success(translate('Refund Rejection Successfully'));

            if (!Helpers::send_order_notification($order)) {
                Toastr::warning(translate('messages.push_notification_faild'));
            }
            return back();

    }


    public function refund_mode()
    {
        $refund_mode = BusinessSetting::where('key', 'refund_active_status')->first();
            Helpers::businessUpdateOrInsert(
            ['key' => 'refund_active_status'],
            [ 'value' => $refund_mode?->value == 1 ? 0 : 1, ]
        );

        if ( $refund_mode?->value){
            Toastr::success(translate('messages.Refund_is_off'));
            return back();
        }
        Toastr::success(translate('messages.Refund_is_on'));
        return back();
    }



    public function details(Request $request, $id)
    {
        $order = Order::with(['offline_payments','payments','subscription','subscription.schedule_today','details', 'refund','restaurant' => function ($query) {
            return $query->withCount('orders');
        }, 'customer' => function ($query) {
            return $query->withCount('orders');
        }, 'delivery_man' => function ($query) {
            return $query->withCount('orders');
        }, 'details.food' => function ($query) {
            return $query->withoutGlobalScope(RestaurantScope::class);
        }, 'details.campaign' => function ($query) {
            return $query->withoutGlobalScope(RestaurantScope::class);
        }])->where(['id' => $id])->Notpos()->first();
        if (isset($order)) {
            if (($order?->restaurant?->self_delivery_system && $order?->restaurant?->restaurant_model == 'commission') ||
            ($order?->restaurant?->restaurant_model == 'subscription' &&   $order?->restaurant?->restaurant_sub?->self_delivery == 1)  ) {
                $deliveryMen = DeliveryMan::with('last_location')->where('restaurant_id', $order->restaurant_id)->available()->active()->get();

            } else {
                if($order->restaurant !== null){
                    $deliveryMen = DeliveryMan::with('last_location')->where('zone_id', $order->restaurant->zone_id)->where(function($query)use($order){
                            $query->where('vehicle_id',$order->vehicle_id)->orWhereNull('vehicle_id');
                    })
                    ->available()->active()->get();
                } else{
                    $deliveryMen = DeliveryMan::with(['last_location', 'wallet'])->where('zone_id', '=', NULL)->where('vehicle_id',$order->vehicle_id)->active()->get();
                }
            }

            $category = $request->query('category_id', 0);
            // $sub_category = $request->query('sub_category', 0);
            $categories = Category::active()->get();
            $keyword = $request->query('keyword', false);
            $key = explode(' ', $keyword);
            $products = Food::withoutGlobalScope(RestaurantScope::class)->where('restaurant_id', $order->restaurant_id)
                ->when($category, function ($query) use ($category) {
                    $query->whereHas('category', function ($q) use ($category) {
                        return $q->whereId($category)->orWhere('parent_id', $category);
                    });
                })
                ->when($keyword, function ($query) use ($key) {
                    return $query->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('name', 'like', "%{$value}%");
                        }
                    });
                })
                ->latest()->paginate(10);
            $editing = false;
            if ($request->session()->has('order_cart')) {
                $cart = session()->get('order_cart');
                if (count($cart) > 0 && $cart[0]->order_id == $order->id) {
                    $editing = true;
                } else {
                    session()->forget('order_cart');
                }
            }

            $deliveryMen = Helpers::deliverymen_list_formatting(data:$deliveryMen, restaurant_lat: $order?->restaurant?->latitude, restaurant_lng: $order?->restaurant?->longitude);


            $selected_delivery_man = DeliveryMan::with('last_location')->where('id',$order->delivery_man_id)->first() ?? [];
            if($order->delivery_man){
                $selected_delivery_man = Helpers::deliverymen_list_formatting(data:$selected_delivery_man, restaurant_lat: $order?->restaurant?->latitude, restaurant_lng: $order?->restaurant?->longitude , single_data:true);
            }

            return view('admin-views.order.order-view', compact('order', 'deliveryMen', 'categories', 'products', 'category', 'keyword', 'editing', 'selected_delivery_man'));
        } else {
            Toastr::info(translate('messages.no_more_orders'));
            return back();
        }
    }

    public function search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $orders = Order::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('order_status', 'like', "%{$value}%")
                    ->orWhere('transaction_reference', 'like', "%{$value}%");
            }
        })->Notpos()->limit(50)->get();
        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render()
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'reason'=>'required_if:order_status,canceled'
        ]);
        $order = Order::Notpos()->with(['subscription_logs','details'])->find($request->id);

        if (in_array($order->order_status, ['refunded', 'failed'])) {
            Toastr::warning(translate('messages.you_can_not_change_the_status_of_a_completed_order'));
            return back();
        }


        if (in_array($order->order_status, ['refund_requested']) && BusinessSetting::where(['key'=>'refund_active_status'])->first()?->value == false)  {
            Toastr::warning(translate('Refund Option is not active. Please active it from Refund Settings'));
            return back();
        }

        if ($order['delivery_man_id'] == null && $request->order_status == 'out_for_delivery') {
            Toastr::warning(translate('messages.please_assign_deliveryman_first'));
            return back();
        }

        if ($request->order_status == 'delivered' && $order['transaction_reference'] == null && !in_array($order['payment_method'],['cash_on_delivery','wallet'])) {
            Toastr::warning(translate('messages.add_your_paymen_ref_first'));
            return back();
        }

        if ($request->order_status == 'delivered') {

            if(isset($order->subscription_id) && count($order->subscription_logs) == 0 ){
                Toastr::warning(translate('messages.You_Can_Not_Delivered_This_Subscription_order_Before_Schedule'));
                return back();
            }


            if ($order->transaction  == null || isset($order->subscription_id)) {
                $unpaid_payment = OrderPayment::where('payment_status','unpaid')->where('order_id',$order->id)->first()?->payment_method;
                $unpaid_pay_method = 'digital_payment';
                if($unpaid_payment){
                    $unpaid_pay_method = $unpaid_payment;
                }
                if ($order->payment_method == "cash_on_delivery" || $unpaid_pay_method == 'cash_on_delivery') {
                    if (in_array($order['order_type'],['dine_in','take_away'])) {
                        $ol = OrderLogic::create_transaction(order: $order, received_by: 'restaurant', status: null);
                    } else if ($order->delivery_man_id) {
                        $ol =  OrderLogic::create_transaction(order: $order, received_by: 'deliveryman', status: null);
                    } else if ($order->user_id) {
                        $ol =  OrderLogic::create_transaction(order: $order, received_by: false, status: null);
                    }
                } else {
                    $ol = OrderLogic::create_transaction(order: $order, received_by: 'admin', status: null);
                }
                if (!$ol) {
                    Toastr::warning(translate('messages.faield_to_create_order_transaction'));
                    return back();
                }
            } else if ($order->delivery_man_id) {
                $order->transaction->update(['delivery_man_id' => $order->delivery_man_id]);
            }

            $order->payment_status = 'paid';
            if ($order->delivery_man) {
                $dm = $order->delivery_man;
                $dm->increment('order_count');
                $dm->current_orders = $dm->current_orders > 1 ? $dm->current_orders - 1 : 0;
                $dm->save();
            }
            $order->details->each(function ($item, $key) {
                if ($item->food) {
                    $item->food->increment('order_count');
                }
            });
            $order->customer ?  $order->customer->increment('order_count') : '';
            $order->restaurant->increment('order_count');

            OrderLogic::update_unpaid_order_payment(order_id:$order->id, payment_method:$order->payment_method);
            }


            else if ($request->order_status == 'refunded' && BusinessSetting::where('key', 'refund_active_status')->first()?->value == 1) {
                    if ($order->payment_status == "unpaid") {
                        Toastr::warning(translate('messages.you_can_not_refund_a_cod_order'));
                        return back();
                    }
                    if (isset($order->delivered)) {
                        $rt = OrderLogic::refund_order($order);

                        if (!$rt) {
                            Toastr::warning(translate('messages.faield_to_create_order_transaction'));
                            return back();
                        }
                    }
                    $refund_method= $request->refund_method  ?? 'manual';
                    $wallet_status= BusinessSetting::where('key','wallet_status')->first()?->value;
                $refund_to_wallet= BusinessSetting::where('key', 'wallet_add_refund')->first()?->value;

                if ($order->payment_status == "paid" && $wallet_status == 1 && $refund_to_wallet==1) {
                    $refund_amount = round($order->order_amount - $order->delivery_charge - $order->dm_tips, config('round_up_to_digit'));
                    CustomerLogic::create_wallet_transaction(user_id:$order->user_id, amount:$refund_amount,transaction_type: 'order_refund',referance: $order->id);
                    Toastr::info(translate('Refunded amount added to customer wallet'));
                    $refund_method='wallet';
                }else{
                        Toastr::warning(translate('Customer Wallet Refund is not active.Plase Manage the Refund Amount Manually'));
                        $refund_method=$request->refund_method  ?? 'manual';
                    }

                    Refund::where('order_id', $order->id)->update([
                        'order_status' => 'refunded',
                        'admin_note'=>$request->admin_note ?? null,
                        'refund_status'=>'approved',
                        'refund_method'=>$refund_method,
                    ]);

                    Helpers::increment_order_count($order->restaurant);

                    if ($order->delivery_man) {
                        $dm = $order->delivery_man;
                        $dm->current_orders = $dm->current_orders > 1 ? $dm->current_orders - 1 : 0;
                        $dm->save();
                    }
                    try {
                        $notification_status= Helpers::getNotificationStatusData('customer','customer_refund_request_approval');

                        if($notification_status?->push_notification_status  == 'active' && $order?->customer?->cm_firebase_token){
                            $data = [
                                'title' => translate('messages.order_refunded'),
                                'description' => translate('messages.Your_refund_request_has_been_approved'),
                                'order_id' => $order->id,
                                'image' => '',
                                'type' => 'order_status',
                                'order_status' => $order->order_status,
                            ];
                            Helpers::send_push_notif_to_device($order?->customer?->cm_firebase_token, $data);
                            DB::table('user_notifications')->insert([
                                'data' => json_encode($data),
                                'user_id' => $order->user_id,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        if($notification_status?->mail_status == 'active' && config('mail.status') && $order?->customer?->email && Helpers::get_mail_status('refund_order_mail_status_user') == '1'){
                            Mail::to($order->customer->email)->send(new \App\Mail\RefundedOrderMail($order->id));
                        }
                    } catch (\Throwable $th) {
                        info($th->getMessage());
                        Toastr::error(translate('messages.Failed_to_send_mail'));
                    }
            }

        else if ($request->order_status == 'canceled') {
            if (in_array($order->order_status, ['delivered', 'canceled', 'refund_requested', 'refunded', 'failed'])) {
                Toastr::warning(translate('messages.you_can_not_cancel_a_completed_order'));
                return back();
            }
            if(isset($order->subscription_id)){
                $order->subscription()->update(['status' => 'canceled']);
                if($order?->subscription->log){
                    $order->subscription->log->update([
                        'order_status' => $request->order_status,
                        'canceled' => now(),
                        ]);
                }
            }
            $order->cancellation_reason = $request->reason;
            $order->canceled_by = 'admin';

            if ($order->delivery_man) {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders > 1 ? $dm->current_orders - 1 : 0;
                $dm->save();
            }
            Helpers::decreaseSellCount(order_details:$order->details);

            Helpers::increment_order_count($order->restaurant);
            OrderLogic::refund_before_delivered($order);
        }
        $order->order_status = $request->order_status;
        if($request->order_status == 'processing') {
            $order->processing_time = ($request?->processing_time) ? $request->processing_time : explode('-', $order['restaurant']['delivery_time'])[0];
        }
        $order[$request->order_status] = now();
        $order->save();

        OrderLogic::update_subscription_log($order);
        if (!Helpers::send_order_notification($order)) {
            Toastr::warning(translate('messages.push_notification_faild'));
        }
        Toastr::success(translate('messages.order') . translate('messages.status_updated'));
        return back();
    }

    public function add_delivery_man($order_id, $delivery_man_id)
    {
        if ($delivery_man_id == 0) {
            return response()->json(['message' => translate('messages.deliveryman_not_found')], 404);
        }
        $order = Order::Notpos()->with(['subscription.schedule_today'])->find($order_id);
        $deliveryman = DeliveryMan::where('id', $delivery_man_id)->available()->active()->first();
        if ($order->delivery_man_id == $delivery_man_id) {
            return response()->json(['message' => translate('messages.order_already_assign_to_this_deliveryman')], 400);
        }
        if ($deliveryman) {
            if ($deliveryman->current_orders >= config('dm_maximum_orders')) {
                return response()->json(['message' => translate('messages.dm_maximum_order_exceed_warning')], 400);
            }
            $cash_in_hand = $deliveryman?->wallet?->collected_cash ?? 0;
            $dm_max_cash=BusinessSetting::where('key','dm_max_cash_in_hand')->first();
            $value=  $dm_max_cash?->value ?? 0;
            if($order->payment_method == "cash_on_delivery" && (($cash_in_hand+$order->order_amount) >= $value)){
                return response()->json(['message'=>translate('delivery man max cash in hand exceeds')], 400);
            }
            if ($order->delivery_man) {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders > 1 ? $dm->current_orders - 1 : 0;
                // $dm->decrement('assigned_order_count');
                $dm->save();


                $deliveryman_push_notification_status=Helpers::getNotificationStatusData('deliveryman','deliveryman_order_assign_unassign');

                if($deliveryman_push_notification_status?->push_notification_status  == 'active' && $dm->fcm_token ){
                    $data = [
                        'title' => translate('messages.order_push_title'),
                        'description' => translate('messages.you_are_unassigned_from_a_order'),
                        'order_id' => '',
                        'image' => '',
                        'type' => 'unassign'
                    ];
                    Helpers::send_push_notif_to_device($dm->fcm_token, $data);

                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'delivery_man_id' => $dm->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                }
            }
            $order->delivery_man_id = $delivery_man_id;
            $order->order_status = in_array($order->order_status, ['pending', 'confirmed']) ? 'accepted' : $order->order_status;
            $order->accepted = now();
            $order->save();
            OrderLogic::update_subscription_log($order);
            $deliveryman->current_orders = $deliveryman->current_orders + 1;
            $deliveryman->save();
            $deliveryman->increment('assigned_order_count');

            $value = Helpers::text_variable_data_format(value:Helpers::order_status_update_message('accepted',$order->customer?$order?->customer?->current_language_key:'en'),
            restaurant_name:$order->restaurant?->name,
            order_id:$order->id,
            user_name:"{$order?->customer?->f_name} {$order?->customer?->l_name}",
            delivery_man_name:"{$order?->delivery_man?->f_name} {$order?->delivery_man?->l_name}");


            try {
                $notification_status= Helpers::getNotificationStatusData('customer','customer_order_notification');

                $fcm_token= ($order->is_guest == 0 ? $order?->customer?->cm_firebase_token : $order?->guest?->fcm_token )?? null;
                if ($notification_status?->push_notification_status  == 'active' && $value && $fcm_token) {
                    $data = [
                        'title' => translate('messages.Order_notification'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                        'type' => 'order_status',
                        'order_status' => $order->order_status,
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);

                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'user_id' => $order?->customer?->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                $deliveryman_push_notification_status=Helpers::getNotificationStatusData('deliveryman','deliveryman_order_assign_unassign');
                if($deliveryman_push_notification_status?->push_notification_status  == 'active' && $deliveryman->fcm_token ){

                    $data = [
                        'title' => translate('messages.order_push_title'),
                        'description' => translate('messages.you_are_assigned_to_a_order'),
                        'order_id' => $order['id'],
                        'image' => '',
                        'type' => 'assign'
                    ];
                    Helpers::send_push_notif_to_device($deliveryman->fcm_token, $data);
                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'delivery_man_id' => $deliveryman->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                }



            } catch (\Exception $e) {
                info($e->getMessage());
                Toastr::warning(translate('messages.push_notification_faild'));
            }
            return response()->json([], 200);
        }
        return response()->json(['message' => translate('Deliveryman not available!')], 400);
    }
    public function update_shipping(Request $request, Order $order)
    {

        $request->validate([
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
        ]);
        if ($request->latitude && $request->longitude) {
            $zone = Zone::where('id', $order->restaurant->zone_id)->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))->first();
            if (!$zone) {
                Toastr::error(translate('messages.out_of_coverage'));
                return back();
            }
        }
        $address = [
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'floor' => $request->floor,
            'house' => $request->house,
            'road' => $request->road,
        ];

        $order->delivery_address = json_encode($address);
        $order->save();
        Toastr::success(translate('messages.delivery_address_updated'));
        return back();
    }

    public function generate_invoice($id)
    {
        $order = Order::Notpos()->where('id', $id)->with(['payments'])->first();
        return view('admin-views.order.invoice', compact('order'));
    }

    public function add_payment_ref_code(Request $request, $id)
    {
        $request->validate([
            'transaction_reference' => 'max:30'
        ]);
        Order::Notpos()->where(['id' => $id])->update([
            'transaction_reference' => $request['transaction_reference']
        ]);

        Toastr::success(translate('messages.payment_reference_code_is_added'));
        return back();
    }

    public function restaurnt_filter($id)
    {
        session()->put('restaurnt_filter', $id);
        return back();
    }

    public function filter(Request $request)
    {
        $request->validate([
            'from_date' => 'required_if:to_date,true',
            'to_date' => 'required_if:from_date,true',
        ]);
        session()->put('order_filter', json_encode($request->all()));
        return back();
    }
    public function filter_reset(Request $request)
    {
        session()->forget('order_filter');
        return back();
    }

    public function add_to_cart(Request $request)
    {

        $old_selected_addons=[];
        $old_selected_variations=[];
        $old_selected_without_variation = $request?->old_selected_without_variation ?? 0;

        if($request?->old_selected_variations){
            $old_selected_variations= json_decode($request->old_selected_variations,true)?? [];
        }
        if($request?->old_selected_addons){
            $old_selected_addons= json_decode($request->old_selected_addons,true)?? [];
        }

        if ($request->item_type == 'food') {
            $product = Food::withOutGlobalScope(RestaurantScope::class)->find($request->id);
        } else {
            $product = ItemCampaign::withOutGlobalScope(RestaurantScope::class)->find($request->id);
        }

        $data = new OrderDetail();
        if ($request->order_details_id) {
            $data['id'] = $request->order_details_id;
        }

        $data['food_id'] = $request->item_type == 'food' ? $product->id : null;
        $data['item_campaign_id'] = $request->item_type == 'campaign' ? $product->id : null;
        $data['food'] = $request->item_type == 'food' ? $product : null;
        $data['item_campaign'] = $request->item_type == 'campaign' ? $product : null;
        $data['order_id'] = $request->order_id;
        $variations = [];
        $price = 0;
        $addon_price = 0;
        $variation_price=0;

        $product_variations = json_decode($product->variations, true);
        if ($request->variations && count($product_variations)) {
            foreach($request->variations  as $key=> $value ){

                if($value['required'] == 'on' &&  isset($value['values']) == false){
                    return response()->json([
                        'data' => 'variation_error',
                        'message' => translate('Please select items from') . ' ' . $value['name'],
                    ]);
                }
                if(isset($value['values'])  && $value['min'] != 0 && $value['min'] > count($value['values']['label'])){
                    return response()->json([
                        'data' => 'variation_error',
                        'message' => translate('Please select minimum ').$value['min'].translate(' For ').$value['name'].'.',
                    ]);
                }
                if(isset($value['values']) && $value['max'] != 0 && $value['max'] < count($value['values']['label'])){
                    return response()->json([
                        'data' => 'variation_error',
                        'message' => translate('Please select maximum ').$value['max'].translate(' For ').$value['name'].'.',
                    ]);
                }
            }
            $variation_data = Helpers::get_varient($product_variations, $request->variations);
            $variation_price = $variation_data['price'];
            $variations = $variation_data['variations'];

        }
        $price = $product->price + $variation_price;
        $data['variation'] = json_encode($variations);
        $data['variant'] = '';
        // $data['variation_price'] = $variation_price;
        $data['quantity'] = $request['quantity'];
        $data['price'] = $price;
        $data['status'] = true;
        $data['discount_on_food'] = Helpers::product_discount_calculate(product:$product, price:$price,restaurant: $product->restaurant);
        $data["discount_type"] = "discount_on_product";
        $data["tax_amount"] = Helpers::tax_calculate(food:$product,price:$price);
        $add_ons = [];
        $add_on_qtys = [];
        $add_on_ids=[];
        if ($request['addon_id']) {
            foreach ($request['addon_id'] as $id) {
                $add_on_ids[]= $id;
                $addon_price += $request['addon-price' . $id] * $request['addon-quantity' . $id];
                $add_on_qtys[] = $request['addon-quantity' . $id];
            }
            $add_ons = $request['addon_id'];
        }

        if ($request->item_type == 'food') {
                $addonAndVariationStock= Helpers::addonAndVariationStockCheck(product:$product,  quantity: $request->quantity,add_on_qtys:$add_on_qtys, variation_options:explode(',',$request?->option_ids),add_on_ids:$add_on_ids, old_selected_variations:$old_selected_variations ,old_selected_without_variation:$old_selected_without_variation ,old_selected_addons:$old_selected_addons );
                if(data_get($addonAndVariationStock, 'out_of_stock') != null) {
                    return response()->json([
                    'data' => 'stock_out',
                    'message' => data_get($addonAndVariationStock, 'out_of_stock'),
                    'current_stock' => data_get($addonAndVariationStock, 'current_stock'),
                    'id'=> data_get($addonAndVariationStock, 'id'),
                    'type'=> data_get($addonAndVariationStock, 'type'),
                ],203);
            }
        }


        $addon_data = Helpers::calculate_addon_price(addons:\App\Models\AddOn::withOutGlobalScope(RestaurantScope::class)->whereIn('id', $add_ons)->get(), add_on_qtys:$add_on_qtys ,old_selected_addons:$old_selected_addons);

        $data['add_ons'] = json_encode($addon_data['addons']);
        $data['total_add_on_price'] = $addon_data['total_add_on_price'];
        $cart = $request->session()->get('order_cart', collect([]));

        if (isset($request->cart_item_key)) {
            $cart[$request->cart_item_key] = $data;
            return response()->json([
                'data' => 2
            ]);
        } else {
            $cart->push($data);
        }

        return response()->json([
            'data' => 0
        ]);
    }

    public function remove_from_cart(Request $request)
    {
        $cart = $request->session()->get('order_cart', collect([]));
        $cart[$request->key]->status = false;
        $request->session()->put('order_cart', $cart);
        return response()->json([], 200);
    }

    public function edit(Request $request, Order $order)
    {
        $order = Order::withoutGlobalScope(RestaurantScope::class)->with(['details', 'restaurant' => function ($query) {
            return $query->withCount('orders');
        }, 'customer' => function ($query) {
            return $query->withCount('orders');
        }, 'delivery_man' => function ($query) {
            return $query->withCount('orders');
        }, 'details' => function ($query) {
            return $query->with(['food'=> function ($q) {
                return $q->withoutGlobalScope(RestaurantScope::class);
            },'campaign' => function ($q) {
                return $q->withoutGlobalScope(RestaurantScope::class);
            }]);
        }])->where(['id' => $order->id])->Notpos()->first();
        if ($request->cancle) {
            if ($request->session()->has(['order_cart'])) {
                session()->forget(['order_cart']);
            }
            return back();
        }
        $cart = collect([]);
        foreach ($order->details as $details) {
            unset($details['food_details']);
            $details['status'] = true;
            $cart->push($details);
        }
        if ($request->session()->has('order_cart')) {
            session()->forget('order_cart');
        } else {
            $request->session()->put('order_cart', $cart);
        }
        return back();
    }

    public function update(Request $request, Order $order)
    {
        $order = Order::with(['details', 'restaurant' => function ($query) {
            return $query->withCount('orders');
        }, 'customer' => function ($query) {
            return $query->withCount('orders');
        }, 'delivery_man' => function ($query) {
            return $query->withCount('orders');
        }, 'details.food' => function ($query) {
            return $query->withoutGlobalScope(RestaurantScope::class);
        }, 'details.campaign' => function ($query) {
            return $query->withoutGlobalScope(RestaurantScope::class);
        }])->where(['id' => $order->id])->Notpos()->first();

        if (!$request->session()->has('order_cart')) {
            Toastr::error(translate('messages.order_data_not_found'));
            return back();
        }
        $cart = $request->session()->get('order_cart', collect([]));
        $restaurant = $order?->restaurant;
        $coupon = null;
        $total_addon_price = 0;
        $product_price = 0;
        $restaurant_discount_amount = 0;
        if ($order->coupon_code) {
            $coupon = Coupon::where(['code' => $order->coupon_code])->first();
        }

        try {
            DB::beginTransaction();
        foreach ($cart as $c) {

            if ($c['status'] == true) {
                unset($c['food']);
                unset($c['status']);
                unset($c['item_campaign']);
                if ($c['item_campaign_id'] != null) {
                    $product = ItemCampaign::find($c['item_campaign_id']);
                    if ($product) {

                        $price = $c['price'];

                        $product = Helpers::product_data_formatting($product);

                        $c->food_details = json_encode($product);
                        $c->updated_at = now();
                        // $variation_data = Helpers::get_varient($product->variations, $c['variations']);
                        // $variations = $variation_data['variations'];
                        if (isset($c->id)) {
                            OrderDetail::where('id', $c->id)->update(
                                [
                                    'food_id' => $c->food_id,
                                    'item_campaign_id' => $c->item_campaign_id,
                                    'food_details' => $c->food_details,
                                    'quantity' => $c->quantity,
                                    'price' => $c->price,
                                    'tax_amount' => $c->tax_amount,
                                    'discount_on_food' => $c->discount_on_food,
                                    'discount_type' => $c->discount_type,
                                    'variant' => $c->variant,
                                    'variation' => $c->variation,
                                    'add_ons' => $c->add_ons,
                                    'total_add_on_price' => $c->total_add_on_price,
                                    'updated_at' => $c->updated_at
                                ]
                            );
                        } else {
                            $c->save();
                        }

                        $total_addon_price += $c['total_add_on_price'];
                        $product_price += $price * $c['quantity'];
                        $restaurant_discount_amount += $c['discount_on_food'] * $c['quantity'];
                    } else {
                        Toastr::error(translate('messages.food_not_found'));
                        return back();
                    }
                } else {
                    $product = Food::find($c['food_id']);
                    if ($product) {

                        $price = $c['price'];

                        $product = Helpers::product_data_formatting($product);

                        $c->food_details = json_encode($product);
                        $c->updated_at = now();
                        if (isset($c->id)) {

                        $OrderDetail=  OrderDetail::where('id', $c->id)->first();
                        $OrderDetail->food_id = $c->food_id;
                        $OrderDetail->item_campaign_id = $c->item_campaign_id;
                        $OrderDetail->food_details = $c->food_details;

                        $old_quantity= $OrderDetail->quantity;

                        if(gettype($c->variation) == 'string'){
                            $variations = json_decode($c->variation, true) ?? [];
                        } else if(gettype($c->variation) == 'array' ){
                            $variations = $c->variation ?? [];
                        }

                        $optionIds=[];
                        $oldOptionIds=[];
                        if($OrderDetail){
                            if($OrderDetail->variation != '[]'){
                                foreach (json_decode($OrderDetail->variation, true) ?? [] as $value) {
                                    foreach (data_get($value,'values' ,[]) as $item) {
                                        if(data_get($item, 'option_id', null ) != null){
                                            $oldOptionIds[] = data_get($item, 'option_id', null );
                                        }
                                    }
                                }
                                VariationOption::whereIn('id', $oldOptionIds)->where('sell_count','>=' ,$old_quantity )->decrement('sell_count' ,$old_quantity);

                            } else{
                                $product->sell_count >= $old_quantity ?   $product->decrement('sell_count' ,$old_quantity) : '';
                            }

                            foreach (json_decode($OrderDetail->add_ons, true) as $old_add_ons) {
                                if(data_get($old_add_ons, 'id', null ) != null){
                                AddOn::where('id',data_get($old_add_ons, 'id', null ))->where('sell_count','>=' ,$old_quantity )->decrement('sell_count' ,data_get($old_add_ons, 'quantity', 1 ));
                                }
                            }
                        }


                        if(count($variations) > 0){
                            foreach ($variations as $value) {
                                foreach (data_get($value,'values' ,[]) as $item) {
                                    if(data_get($item, 'option_id', null ) != null){
                                        $optionIds[] = data_get($item, 'option_id', null );
                                    }
                                }
                            }

                        }

                        $add_on_qtys=[];
                        $add_on_ids=[];
                        foreach (json_decode($c->add_ons, true) as $add_ons) {
                            if(data_get($add_ons, 'id', null ) != null){
                                $add_on_ids[]=data_get($add_ons, 'id', null );
                                $add_on_qtys[]=data_get($add_ons, 'quantity', 1 );
                            }
                        }


                        $addonAndVariationStock= Helpers::addonAndVariationStockCheck(product:$product,quantity: $c->quantity,add_on_qtys:$add_on_qtys, variation_options:$optionIds,add_on_ids:$add_on_ids ,incrementCount:true);
                        if(data_get($addonAndVariationStock, 'out_of_stock') != null) {
                            Toastr::error(data_get($addonAndVariationStock, 'out_of_stock'));
                            return back();
                        }


                        $OrderDetail->quantity = $c->quantity;
                        $OrderDetail->price = $c->price;
                        $OrderDetail->tax_amount = $c->tax_amount;
                        $OrderDetail->discount_on_food = $c->discount_on_food;
                        $OrderDetail->discount_type = $c->discount_type;
                        $OrderDetail->variant = $c->variant;
                        $OrderDetail->variation = $c->variation;
                        $OrderDetail->add_ons = $c->add_ons;
                        $OrderDetail->total_add_on_price = $c->total_add_on_price;
                        $OrderDetail->updated_at = $c->updated_at;
                        $OrderDetail->save();

                        } else {
                            $c->save();
                        }
                        $total_addon_price += $c['total_add_on_price'];
                        $product_price += $price * $c['quantity'];
                        $restaurant_discount_amount += $c['discount_on_food'] * $c['quantity'];
                    } else {
                        Toastr::error(translate('messages.food_not_found'));
                        return back();
                    }
                }
            } else {
                $c->delete();
            }
        }

        $restaurant_discount = Helpers::get_restaurant_discount($restaurant);
        if (isset($restaurant_discount)) {
            if ($product_price + $total_addon_price < $restaurant_discount['min_purchase']) {
                $restaurant_discount_amount = 0;
            }

            if ($restaurant_discount_amount > $restaurant_discount['max_discount']) {
                $restaurant_discount_amount = $restaurant_discount['max_discount'];
            }
        }
        $order->delivery_charge = $order->original_delivery_charge;
        if ($coupon) {
            if ($coupon->coupon_type == 'free_delivery') {
                $order->delivery_charge = 0;
                $coupon = null;
            }
        }

        if ($order->restaurant->free_delivery) {
            $order->delivery_charge = 0;
        }
        if(in_array($order->free_delivery_by,['admin'])){
            $order->delivery_charge = 0;
        }

        $coupon_discount_amount = $coupon ? CouponLogic::get_discount(coupon:$coupon, order_amount: $product_price + $total_addon_price - $restaurant_discount_amount) : 0;
        $total_price = $product_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount;

        $tax = $restaurant->tax;
        $total_tax_amount = ($tax > 0) ? (($total_price * $tax) / 100) : 0;
        $tax_a=$order->tax_status =='included'?0:$total_tax_amount;


        if ($restaurant->minimum_order > $product_price + $total_addon_price) {
            Toastr::error(translate('messages.you_need_to_order_at_least', ['amount' => $restaurant->minimum_order . ' ' . Helpers::currency_code()]));
            return back();
        }

        $free_delivery_over = BusinessSetting::where('key', 'free_delivery_over')->first()?->value;
        if (isset($free_delivery_over)) {
            if ($free_delivery_over <= $product_price + $total_addon_price - $coupon_discount_amount - $restaurant_discount_amount) {
                $order->delivery_charge = 0;
            }
        }
        $total_order_ammount = $total_price + $tax_a + $order->delivery_charge + $order->extra_packaging_amount + $order->additional_charge ;
        $adjustment = $order->order_amount - $total_order_ammount;

        $order->coupon_discount_amount = $coupon_discount_amount;
        $order->restaurant_discount_amount = $restaurant_discount_amount;
        $order->total_tax_amount = $total_tax_amount;
        $order->order_amount = $total_order_ammount;
        $order->adjusment = $adjustment;
        $order->edited = true;
        $order->save();
        DB::commit();
        session()->forget('order_cart');

        Toastr::success(translate('messages.order_updated_successfully'));

        } catch (\Exception $exception) {
            DB::rollBack();
            info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
        }

        return back();
    }

    public function quick_view(Request $request)
    {
        $product = $product = Food::withOutGlobalScope(RestaurantScope::class)->findOrFail($request->product_id);
        $item_type = 'food';
        $order_id = $request->order_id;

        return response()->json([
            'success' => 1,
            'view' => view('admin-views.order.partials._quick-view', compact('product', 'order_id', 'item_type'))->render(),
        ]);
    }

    public function quick_view_cart_item(Request $request)
    {
        $cart_item = session('order_cart')[$request->key];
        $order_id = $request->order_id;
        $item_key = $request->key;
        $product = $cart_item->food ? $cart_item->food : $cart_item->campaign;
        $item_type = $cart_item->food ? 'food' : 'campaign';
        $item_count=session('order_cart')->where('status',true)->count();
        return response()->json([
            'success' => 1,
            'view' => view('admin-views.order.partials._quick-view-cart-item', compact('order_id', 'product', 'cart_item', 'item_key', 'item_type' ,'item_count'))->render(),
        ]);
    }

    public function orders_export(Request $request, $type, $restaurant_id){
        try{
            $key = explode(' ', $request['search']);

            $orders=Order::where('restaurant_id', $restaurant_id)->with('customer')
            ->when(isset($key ), function ($q) use ($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->Notpos()->get();

            $restaurant= Restaurant::where('id', $restaurant_id)->select(['id','zone_id','name'])->first();
            $data = [
                'data'=>$orders,
                'search'=>request()->search ?? null,
                'zone'=>Helpers::get_zones_name($restaurant->zone_id) ,
                'restaurant'=>  $restaurant->name,
            ];

            if($type == 'csv'){
                return Excel::download(new RestaurantOrderlistExport($data), 'OrderList.csv');
            }
            return Excel::download(new RestaurantOrderlistExport($data), 'OrderList.xlsx');
        } catch(\Exception $e) {
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }
    }

    public function restaurant_order_search(Request $request){
        $key = explode(' ', $request['search']);
        $orders = Order::where(['restaurant_id'=>$request->restaurant_id])
                        ->where(function($q) use($key){
                            foreach ($key as $value){
                                $q->orWhere('id', 'like', "%{$value}%");

                            }
                        })
                        ->whereHas('customer', function($q) use($key){
                            foreach($key as $value){
                                $q->orWhere('f_name', 'like', "%{$value}%")
                                ->orWhere('l_name', 'like', "%{$value}%");
                            }
                        })->get();

        return response()->json([
            'view'=> view('admin-views.vendor.view.partials._orderTable', compact('orders'))->render()
        ]);
    }

    public function add_order_proof(Request $request, $id)
    {
        $order = Order::find($id);
        $img_names = $order->order_proof?json_decode($order->order_proof):[];
        $images = [];
        $total_file =  (is_array($request->order_proof) ? count($request->order_proof)  : 0) + count($img_names);
        if(!$img_names){
            $request->validate([
                'order_proof' => 'required|array|max:5',
            ]);
        }

        if ($total_file>5) {
            Toastr::error(translate('messages.order_proof_must_not_have_more_than_5_item'));
            return back();
        }

        if (!empty($request->file('order_proof'))) {
            foreach ($request->order_proof as $img) {
                $image_name = Helpers::upload('order/', 'png', $img);
                array_push($img_names, ['img'=>$image_name, 'storage'=> Helpers::getDisk()]);
            }
            $images = $img_names;
        }

        $order->order_proof = json_encode($images);
        $order->save();

        Toastr::success(translate('messages.order_proof_added'));
        return back();
    }
    public function remove_proof_image(Request $request)
    {
        $order = Order::find($request['id']);
        $array = [];
        $proof = isset($order->order_proof) ? json_decode($order->order_proof, true) : [];
        if (count($proof) < 2) {
            Toastr::warning(translate('all_image_delete_warning'));
            return back();
        }
        Helpers::check_and_delete('order/' , $request['name']);
        foreach ($proof as $image) {
            $image = is_array($image)?$image:(is_object($image) && get_class($image) == 'stdClass' ? json_decode(json_encode($image), true):['img' => $image, 'storage' => 'public']);
            if ($image['img'] != $request['name']) {
                array_push($array, $image);
            }
        }
        Order::where('id', $request['id'])->update([
            'order_proof' => json_encode($array),
        ]);
        Toastr::success(translate('order_proof_image_removed_successfully'));
        return back();
    }

        public function offline_payment(Request $request){
            $order=  Order::findOrFail($request->id);
            if($request->verify == 'yes'){
                $order->payment_status = 'paid';
                $order->confirmed = now();
                $order->order_status = 'confirmed';
                $order->save();
                $order->offline_payments()->update([
                    'status'=> 'verified'
                ]);

                $payment_method_name = data_get(json_decode($order->offline_payments->payment_info, true) ,'method_name' ,$order->payment_method )  ;
                if($order->payment_method == 'partial_payment'){
                    $order->payments()->where('payment_status','unpaid')->update([
                        'payment_method'=>  $payment_method_name,
                        'payment_status'=> 'paid',
                    ]);
                }

                // $order->payment_method = $payment_method_name;
                // $order->save();
                $this->sent_notification_on_offline_payment('approved', $order->id);
            }

            elseif($request->verify == 'switched_to_cod'){
                $order->offline_payments()->update([
                    'status'=> 'verified'
                ]);
                if($order->payment_method == 'partial_payment'){
                    $order->payments()->where('payment_status','unpaid')->update([
                        'payment_method'=> 'cash_on_delivery',
                    ]);
                } else{

                    $order->payment_method = 'cash_on_delivery';
                    $order->save();
                }

                if( $order->restaurant->restaurant_model == 'subscription' && isset($order->restaurant->restaurant_sub)){
                    if ($order->restaurant->restaurant_sub->max_order != "unlimited" && $order->restaurant->restaurant_sub->max_order > 0 ) {
                        $order->restaurant->restaurant_sub()->decrement('max_order' , 1);
                    }
                }
                Helpers::send_order_notification($order);
            }

            else{
                $order->offline_payments()->update([
                    'status'=> 'denied',
                    'note'=> $request->note ?? null
                ]);

                $this->sent_notification_on_offline_payment('denied', $order->id);
            }

            Toastr::success(translate('Payment_status_updated'));
            return back();
    }






    private function sent_notification_on_offline_payment($status,$id){
        $order = Order::findOrfail($id);
        if($status == 'approved'){
            Helpers::send_order_notification($order);

            $notification_text= 'offline_verified' ;
            $notification_title= translate('messages.Your_Offline_payment_was_approved') ;
            $mail_sattus = Helpers::get_mail_status('offline_payment_approve_mail_status_user');
            $mail_sattus_type =  'approved';
            $notification_status= Helpers::getNotificationStatusData('customer','customer_offline_payment_approve');


            if( $order->restaurant->restaurant_model == 'subscription' && isset($order->restaurant->restaurant_sub)){
                if ($order->restaurant->restaurant_sub->max_order != "unlimited" && $order->restaurant->restaurant_sub->max_order > 0 ) {
                    $order->restaurant->restaurant_sub()->decrement('max_order' , 1);
                }
            }
        }
        else{
            $notification_text= 'offline_denied' ;
            $notification_title= translate('messages.Your_Offline_payment_was_rejected') ;
            $mail_sattus_type =  'denied';
            $mail_sattus = Helpers::get_mail_status('offline_payment_deny_mail_status_user');
            $notification_status= Helpers::getNotificationStatusData('customer','customer_offline_payment_deny');

        }
        try {
        $value = Helpers::text_variable_data_format(value:Helpers::order_status_update_message($notification_text,$order->customer?$order->customer->current_language_key:'en'),restaurant_name:$order->restaurant?->name,order_id:$order->id,user_name:"{$order?->customer?->f_name} {$order?->customer?->l_name}",delivery_man_name:"{$order?->delivery_man?->f_name} {$order?->delivery_man?->l_name}");

            $data = [
                'title' => $notification_title,
                'description' => $value ?? '' ,
                'order_id' => $order->id,
                'image' => '',
                'type' => 'order_status',
                'order_status' => $order->order_status,
            ];

            $fcm= ($order->is_guest == 0 ? $order?->customer?->cm_firebase_token : $order?->guest?->fcm_token) ?? null ;
            if($notification_status?->push_notification_status  == 'active' && $fcm && $value ){
                Helpers::send_push_notif_to_device($fcm, $data);
                DB::table('user_notifications')->insert([
                    'data' => json_encode($data),
                    'user_id' => $order?->user_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }



            if($order?->customer?->email && config('mail.status') && $mail_sattus == '1' && $notification_status?->mail_status == 'active'){
                Mail::to($order?->customer?->email)->send(new \App\Mail\UserOfflinePaymentMail($order?->customer?->f_name .' '.$order?->customer?->l_name,$mail_sattus_type));
            }
        }
            catch(\Exception $e)
            {
                Toastr::error(translate('Failed_to_Send_Email'));
                info($e->getMessage());
                return true;
            }
        return true ;
    }




    public function offline_verification_list(Request $request, $status)
    {
        $reasons=OrderCancelReason::where('status', 1)->where('user_type' ,'admin' )->get();

        $key = explode(' ', $request['search']);
        $orders = Order::with(['customer', 'restaurant'])->has('offline_payments')

            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_reference', 'like', "%{$value}%");
                    }
                });
            })
            ->when($status == 'pending' , function ($query) {
                return $query->whereHas('offline_payments', function ($query) {
                    return $query->where('status', 'pending');
                });
            })
            ->when($status == 'denied' , function ($query) {
                return $query->whereHas('offline_payments', function ($query) {
                    return $query->where('status', 'denied');
                });
            })
            ->when($status == 'verified' , function ($query) {
                return $query->whereHas('offline_payments', function ($query) {
                    return $query->where('status', 'verified');
                });
            })
            ->orderBy('schedule_at', 'desc')
            ->paginate(config('default_pagination'));

        return view('admin-views.order.offline_verification_list', compact('orders', 'status', 'reasons'));
    }
    public function add_dine_in_table_number(Order $order , Request $request)
    {
        $request->validate([
            'table_number' => 'nullable|max:255|required_without_all:token_number',
            'token_number' => 'nullable|max:255|required_without_all:table_number',
        ], [
            'table_number.required_without_all' => translate('you_must_set_a_table_or_token_number'),
            'token_number.required_without_all' => translate('you_must_set_a_table_or_token_number'),
        ]);

        if($order?->order_type  == 'dine_in' ){
            $order->OrderReference()->update([
                'token_number' => $request->token_number ?? null,
                'table_number' =>$request->table_number ?? null
            ]);
        }
        $data = [
            'title' =>  $request->table_number &&  $request->token_number ?  translate('Here_is_your_Table_and_Token_Number') :($request->table_number  ? translate('Here_is_your_Table_Number') : translate('Here_is_your_Token_Number')) ,
            'description' => $request->table_number &&  $request->token_number ?  translate('Table No -') .' '.$request->table_number  .' '. translate('&_Token No -') .' '.$request->token_number : ($request->table_number  ? translate('Table No -') .' '.$request->table_number  :  translate('Token No -') .' '.$request->token_number ),
            'order_id' => $order->id,
            'image' => '',
            'type' => 'order_status',
            'order_status' => $order->order_status,
        ];
        $notification_status= Helpers::getNotificationStatusData('customer','customer_dine_in_table_or_token');
        $fcm= ($order->is_guest == 0 ? $order?->customer?->cm_firebase_token : $order?->guest?->fcm_token) ?? null ;
        if($notification_status?->push_notification_status  == 'active' && $fcm ){
            Helpers::send_push_notif_to_device($fcm, $data);
            DB::table('user_notifications')->insert([
                'data' => json_encode($data),
                'user_id' => $order?->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        Toastr::success($request->table_number ? translate('table_number_updated_successfully') : translate('token_number_updated_successfully')  );
        return back();
    }

}
