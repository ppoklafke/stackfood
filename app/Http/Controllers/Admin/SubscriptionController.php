<?php

namespace App\Http\Controllers\Admin;

use App\Models\Zone;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Carbon;
use App\Models\BusinessSetting;
use App\Scopes\RestaurantScope;
use App\Mail\SubscriptionCancel;
use App\Models\RestaurantWallet;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use App\Mail\SubscriptionPlanUpdate;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\RestaurantSubscription;
use App\Models\SubscriptionTransaction;
use App\Exports\SubscriptionPackageExport;
use App\Exports\SubscriptionTransactionsExport;
use App\Exports\SubscriptionSubscriberListExport;
use App\Exports\SubsPackageWiseTransactionExport;
use App\Models\SubscriptionBillingAndRefundHistory;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request['statistics'];
        $package_sell_count= SubscriptionPackage::
            withSum([
                'transactions' => function ($query) use ($filter) {
                    $query->where('is_trial',0)
                    ->applyDateFilter($filter);
                },
            ], 'paid_amount')
            ->get();

        return view('admin-views.subscription.index', [
            'packages' => $this->allPackageData($request['search']),
            'package_sell_count' => $package_sell_count,
        ]);
    }

    public function create()
    {
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        $totla_count=SubscriptionPackage::count();
        return view('admin-views.subscription.create', compact('language','defaultLang','totla_count'));
    }

    public function edit($id)
    {
        $subscriptionackage = SubscriptionPackage::withoutGlobalScope('translate')->with('translations')->findOrFail($id);
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());


        return view('admin-views.subscription.edit', compact('language','defaultLang','subscriptionackage'));
    }

    //change to s t o r e
    public function store(Request $request)
    {
        $request->validate([
            'package_name' => 'max:191|unique:subscription_packages',
            'package_name.0' => 'required',
            'package_price' => 'required|numeric|between:0,999999999999.999',
            'package_validity' => 'required|integer|between:0,36160',
            'max_order' => 'nullable|integer|between:0,999999999',
            'max_product' => 'nullable|integer|between:0,999999999',
            'pos_system' => 'nullable|boolean',
            'mobile_app' => 'nullable|boolean',
            'self_delivery' => 'nullable|boolean',
            'chat' => 'nullable|boolean',
            'review' => 'nullable|boolean',
            'text' => 'nullable|max:1000',

            ], [
            'price.required' => translate('Must enter Price for the Package'),
            'package_validity.required' => translate('Must enter a validity period for the Package in days'),
            'package_validity.between' => translate('validity must be in 99 years'),
            'package_name.0.required'=>translate('default_package_name_is_required'),
        ]);

        $package = new SubscriptionPackage;
        $package->package_name = $request->package_name[array_search('default', $request->lang)];
        $package->text = $request->text[array_search('default', $request->lang)];
        $package->price = $request->package_price;
        $package->validity = $request->package_validity;
        $package->max_order = $request?->minimum_order_limit == 'on' ?   'unlimited' : $request->max_order;
        $package->max_product =   $request?->maximum_item_limit == 'on' ?   'unlimited' : $request->max_product;
        $package->pos = $request->pos_system ?? 0;
        $package->mobile_app = $request->mobile_app ?? 0;
        $package->self_delivery = $request->self_delivery ?? 0;
        $package->chat = $request->chat ?? 0;
        $package->review = $request->review ?? 0;
        $package->colour = $request?->colour;
        $package->save();

        Helpers::add_or_update_translations(request: $request, key_data:'package_name' , name_field:'package_name' , model_name: 'SubscriptionPackage' ,data_id: $package->id,data_value: $package->package_name);
        Helpers::add_or_update_translations(request: $request, key_data:'text' , name_field:'text' , model_name: 'SubscriptionPackage' ,data_id: $package->id,data_value: $package->text);

        Toastr::success(translate('Subscription Plan Added Successfully'));
        return redirect()->route('admin.subscription.package_list');
    }

    public function update(SubscriptionPackage $subscriptionackage, Request $request)
    {
        $request->validate([
            'package_name' => 'max:191|unique:subscription_packages,package_name,'.$subscriptionackage->id,
            'package_name.0' => 'required',
            'package_price' => 'required|numeric|between:0,999999999999.999',
            'package_validity' => 'required|integer|between:0,36160',
            'max_order' => 'nullable|integer|between:0,999999999',
            'max_product' => 'nullable|integer|between:0,999999999',
            'pos_system' => 'nullable|boolean',
            'mobile_app' => 'nullable|boolean',
            'self_delivery' => 'nullable|boolean',
            'chat' => 'nullable|boolean',
            'review' => 'nullable|boolean',
            'text' => 'nullable|max:1000',
        ], [
            'price.required' => translate('Must enter Price for the Package'),
            'package_validity.required' => translate('Must enter a validity period for the Package in days'),
            'package_validity.between' => translate('validity must be in 99 years'),
            'package_name.0.required'=>translate('default_package_name_is_required'),
        ]);


        $subscriptionackage->package_name = $request->package_name[array_search('default', $request->lang)];
        $subscriptionackage->text = $request->text[array_search('default', $request->lang)];
        $subscriptionackage->price = $request->package_price;
        $subscriptionackage->validity = $request->package_validity;
        $subscriptionackage->max_order = $request?->minimum_order_limit == 'on' ?   'unlimited' : $request->max_order;
        $subscriptionackage->max_product =   $request?->maximum_item_limit == 'on' ?   'unlimited' : $request->max_product;
        $subscriptionackage->pos = $request->pos_system ?? 0;
        $subscriptionackage->mobile_app = $request->mobile_app ?? 0;
        $subscriptionackage->self_delivery = $request->self_delivery ?? 0;
        $subscriptionackage->chat = $request->chat ?? 0;
        $subscriptionackage->review = $request->review ?? 0;
        $subscriptionackage->colour = $request?->colour;
        $subscriptionackage->save();
        Helpers::add_or_update_translations(request: $request, key_data:'package_name' , name_field:'package_name' , model_name: 'SubscriptionPackage' ,data_id: $subscriptionackage->id,data_value: $subscriptionackage->package_name);
        Helpers::add_or_update_translations(request: $request, key_data:'text' , name_field:'text' , model_name: 'SubscriptionPackage' ,data_id: $subscriptionackage->id,data_value: $subscriptionackage->text);


        Toastr::success(translate('Subscription Plan Updated Successfully'));

        try {
            $notification_status=Helpers::getNotificationStatusData('restaurant','restaurant_subscription_plan_update');

            $subscribers= RestaurantSubscription::with('restaurant.vendor')->has('restaurant')->where(['package_id' =>  $subscriptionackage->id,'status'=> 1])->get();

            foreach ($subscribers as $subscriber){
                $reataurant_notification_status=Helpers::getRestaurantNotificationStatusData($subscriber?->restaurant?->id,'restaurant_subscription_plan_update');
                if($notification_status?->push_notification_status  == 'active' && $reataurant_notification_status?->push_notification_status  == 'active' &&  $subscriber?->restaurant->vendor?->firebase_token){
                    $data = [
                        'title' => translate('subscription_plan_updated'),
                        'description' => translate('Your_subscription_plan_has_been_updated'),
                        'order_id' => '',
                        'image' => '',
                        'type' => 'subscription',
                        'order_status' => '',
                    ];
                        Helpers::send_push_notif_to_device($subscriber?->restaurant->vendor?->firebase_token, $data);
                        DB::table('user_notifications')->insert([
                            'data' => json_encode($data),
                            'vendor_id' => $subscriber?->restaurant->vendor_id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    if(config('mail.status') && Helpers::get_mail_status('subscription_plan_upadte_mail_status_restaurant') == '1' &&  $notification_status?->mail_status  == 'active'  && $reataurant_notification_status?->mail_status  == 'active' ){
                        Mail::to($subscriber?->restaurant->email)->send(new SubscriptionPlanUpdate($subscriber?->restaurant->name));
                    }
                }

        } catch (\Exception $ex) {
            info($ex->getMessage());
        }

        return redirect()->route('admin.subscription.package_details',[$subscriptionackage->id]);
        }




        public function overView(SubscriptionPackage $subscriptionackage, Request $request)
        {
            $over_view_data= $this->packageOverview($subscriptionackage,$request?->type);
                return response()->json([
                'view'=>view('admin-views.subscription.partials._over-view-data',compact('over_view_data'))->render(),

                ]);
        }







    public function show($id)
    {
        $subscriptionackage = SubscriptionPackage::withCount('transactions')->findOrFail($id);
        $packages= SubscriptionPackage::where('status',1)->get();
        $over_view_data= $this->packageOverview($subscriptionackage);
        return view('admin-views.subscription.view', compact('packages','over_view_data','subscriptionackage'));
    }

    private function packageOverview($subscriptionackage,$type ='all'){
        $data=[];
        $subscription_deadline_warning_days = BusinessSetting::where('key','subscription_deadline_warning_days')->first()?->value ?? 7;

        $totalSubscribersData = $subscriptionackage->subscribers()
        ->when($type == 'this_month' ,function($query){
            $query->whereMonth('renewed_at', Carbon::now()->month );
        })
        ->when($type == 'this_year' ,function($query){
            $query->whereYear('renewed_at', Carbon::now()->year );
        })
        ->when($type == 'this_week' ,function($query){
            $query->whereBetween('renewed_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()] );
        })
        ->selectRaw('COUNT(DISTINCT restaurant_id) AS total_subscribers,
                    COUNT(DISTINCT CASE WHEN status = 1 THEN restaurant_id END) AS active_subscriptions,
                    COUNT(DISTINCT CASE WHEN status = 0 THEN restaurant_id END) AS expired_subscriptions,
                    COUNT(DISTINCT CASE WHEN status = 1 AND expiry_date <= ? THEN restaurant_id END) AS expired_soon',
                    [Carbon::today()->addDays($subscription_deadline_warning_days)])
        ->first();

        $data['total_subscribed_user']= $totalSubscribersData['total_subscribers'];
        $data['active_subscription']= $totalSubscribersData['active_subscriptions'];
        $data['expired_subscription']= $totalSubscribersData['expired_subscriptions'];
        $data['expired_soon']= $totalSubscribersData['expired_soon'];

        $totals = $subscriptionackage->transactions()
        ->when($type == 'this_month' ,function($query){
            $query->whereMonth('created_at', Carbon::now()->month );
        })
        ->when($type == 'this_year' ,function($query){
            $query->whereYear('created_at', Carbon::now()->year );
        })
        ->when($type == 'this_week' ,function($query){
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()] );
        })
        ->selectRaw('COUNT(DISTINCT CASE WHEN is_trial = 1 THEN restaurant_id END) AS total_free_trials,
                    COUNT(DISTINCT CASE WHEN is_trial = 0 THEN restaurant_id END) AS total_renewed,
                    SUM(CASE WHEN is_trial = 0 THEN paid_amount ELSE 0 END) AS total_amount')
        ->first();

        $data['total_free_trials']= $totals['total_free_trials'];
        $data['total_renewed']= $totals['total_renewed'];
        $data['total_amount']= $totals['total_amount'];

        return $data;

    }


    public function statusChange(SubscriptionPackage $subscriptionackage, Request $request)
    {
        $subscriptionackage->status =!$subscriptionackage->status;
        $subscriptionackage->save();
        Toastr::success($subscriptionackage->status == 1 ? translate('messages.Package_Acitvated_successfully') : translate('Package_Deacitvated_successfully'));
        return back();
    }
    public function packageExport(Request $request)
    {
        try{
            $data = [
                'data'=>$this->allPackageData($request['search'],'export'),
                'search'=>request()->search ?? null,
            ];

            if($request->type == 'csv'){
                return Excel::download(new SubscriptionPackageExport($data), 'SubscriptionPackage.csv');
            }
            return Excel::download(new SubscriptionPackageExport($data), 'SubscriptionPackage.xlsx');
        } catch(\Exception $e) {
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }
    }

    private function allPackageData($search,$type = null){
        $key = explode(' ', $search);
        $packages = SubscriptionPackage::withcount('currentSubscribers')
            ->when(isset($key), function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('package_name', 'like', "%{$value}%");
                    }
                });
            })
            ->latest();
            if($type == 'export'){
                return $packages->get();
            }
            return $packages->paginate(config('default_pagination'));
    }



    public function transaction(Request $request, $id){
            $transactions=$this->allTransactionData($request,$id);
            $filter= $request['filter'];
            $subscription_deadline_warning_days = BusinessSetting::where('key','subscription_deadline_warning_days')->first()?->value ?? 7;
        return view('admin-views.subscription.subscription-transaction', compact('transactions','id','filter','subscription_deadline_warning_days'));
    }



    public function TransactionExport(Request $request){
        try{
            $request->validate([
                'id' => 'required',
            ]);
            $id= $request['id'];
            $data = [
                'data'=>  $this->allTransactionData($request,$id,'export'),
                'plan_type'=>$request['plan_type'] ?? 'all',
                'filter'=>$request['filter'] ?? 'all',
                'search'=>$request['search'],
                'start_date'=>$request['start_date'],
                'end_date'=>$request['end_date'],
                'package_name'=>SubscriptionPackage::where('id',$id)->first()?->package_name,
            ];
            if($request->type == 'csv'){
                return Excel::download(new SubsPackageWiseTransactionExport($data), 'SubscriptionTransaction.csv');
            }
            return Excel::download(new SubsPackageWiseTransactionExport($data), 'SubscriptionTransaction.xlsx');
        } catch(\Exception $e) {
            Toastr::error("line___{$e->getLine()}",$e->getMessage());
            info(["line___{$e->getLine()}",$e->getMessage()]);
            return back();
        }
    }

    private function allTransactionData($request, $id, $type = null){

        $filter= $request['filter'];
        $plan_type= $request['plan_type'];
        $from =$request['start_date'] ?? Carbon::now()->format('Y-m-d');
        $to =$request['end_date'] ?? Carbon::now()->format('Y-m-d');

        $key = explode(' ', $request['search']);

        $transactions= SubscriptionTransaction::where('package_id',$id)
        ->when(isset($key), function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('id', 'like', "%{$value}%");
                }
                $q->orWhereHas('restaurant' , function ($q) use ($key) {
                    foreach ($key as $value) {
                    $q->where('name', 'like', "%{$value}%");
                }
                });
            });
        })
        ->applyDateFilter($filter,$from,$to)
        ->when( in_array( $plan_type,['renew','new_plan','first_purchased','free_trial'])  , function($query) use($plan_type){
            $query->where('plan_type', $plan_type );
        })

            ->latest();
            if($type == 'export'){
                return $transactions->get();
            }
            return $transactions->paginate(config('default_pagination'));
    }

    public function invoice($id){
        $BusinessData= ['admin_commission' ,'business_name','address','phone','logo','email_address'];
        $transaction= SubscriptionTransaction::with(['restaurant.vendor','package:id,package_name,price'])->find($id);
        $BusinessData=BusinessSetting::whereIn('key', $BusinessData)->pluck('value' ,'key') ;
        $logo=BusinessSetting::where('key', "logo")->first() ;


        $mpdf_view = View::make('subscription-invoice', compact('transaction','BusinessData','logo'));
        Helpers::gen_mpdf(view: $mpdf_view,file_prefix: 'Subscription',file_postfix: $id);
        return back();
    }

    private function allSubscriberListData($request,$type = null){

        $key = explode(' ', $request['search']);
        $subscribers= Restaurant::has('restaurant_sub_update_application')->whereHas('vendor',function($query){
            $query->where('status', 1);
        })
        ->whereIn('restaurant_model' ,['subscription','unsubscribed'])->with([
            'restaurant_sub_update_application.package'
        ])->withCount('restaurant_all_sub_trans')

        ->when(isset($key), function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
                $q->orWhereHas('restaurant_sub_update_application.package' , function ($q) use ($key) {
                    foreach ($key as $value) {
                    $q->where('package_name', 'like', "%{$value}%");
                }
                });
            });
        })
        ->when(isset($request->zone_id) && is_numeric($request->zone_id), function ($query) use ($request) {
            return $query->where('zone_id', $request->zone_id);
        })


        ->when(isset($request->subscription_type) && $request->subscription_type == 'active', function ($query)  {
            return $query->whereHas('restaurant_sub_update_application', function ($q)  {
                return $q->where('status',1);
            });
        })
        ->when(isset($request->subscription_type) && $request->subscription_type == 'expired', function ($query)  {
            return $query->whereHas('restaurant_sub_update_application', function ($q)  {
                return $q->where('status',0);
            });
        })
        ->when(isset($request->subscription_type) && $request->subscription_type == 'cancaled', function ($query)  {
            return $query->whereHas('restaurant_sub_update_application', function ($q)  {
                return $q->where('is_canceled',1);
            });
        })
        ->when(isset($request->subscription_type) && $request->subscription_type == 'free_trial', function ($query)  {
            return $query->whereHas('restaurant_sub_update_application', function ($q)  {
                return $q->where('is_trial',1);
            });
        })

            ->latest();
            if($type == 'export'){
                return $subscribers->get();
            }
            return $subscribers->paginate(config('default_pagination'));
    }
    public function subscriberList(Request $request)
    {
        $subscribers= $this->allSubscriberListData($request);
        $data=[];
        $subscription_deadline_warning_days = BusinessSetting::where('key','subscription_deadline_warning_days')->first()?->value ?? 7;

        $totalSubscribersData= RestaurantSubscription::whereHas('restaurant',function ($query)use($request){
            $query->whereIn('restaurant_model' ,['subscription','unsubscribed'])
            ->when(isset($request->zone_id) && is_numeric($request->zone_id), function ($query) use ($request) {
                return $query->where('zone_id', $request->zone_id);

            });
        })
        ->whereHas('restaurant.vendor',function($query){
            $query->where('status', 1);
        })
        ->selectRaw('COUNT(DISTINCT restaurant_id) AS total_subscribers,
        COUNT(DISTINCT CASE WHEN status = 1 THEN restaurant_id END) AS active_subscriptions,
        COUNT(DISTINCT CASE WHEN status = 1 AND expiry_date <= ? THEN restaurant_id END) AS expired_soon',
        [Carbon::today()->addDays($subscription_deadline_warning_days)])
        ->first();

        $data['total_subscribed_user']= $totalSubscribersData['total_subscribers'];
            $data['active_subscription']= $totalSubscribersData['active_subscriptions'];
            $data['expired_soon']= $totalSubscribersData['expired_soon'];


            $total_inactive_subscription = Restaurant::has('restaurant_sub_update_application')
            ->whereIn('restaurant_model' ,['unsubscribed'])
            ->whereHas('vendor',function($query){
                $query->where('status', 1);
            })
            ->when(is_numeric($request->zone_id), function ($query) use ($request) {
                return $query->where('zone_id', $request->zone_id);
                })
            ->count();

        $data['expired_subscription']= $total_inactive_subscription;



            $totals= SubscriptionTransaction::whereHas('restaurant.vendor',function($query){
                $query->where('status', 1);
            })->where('is_trial',0)
            ->when(isset($request->zone_id) && is_numeric($request->zone_id), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($q) use ($request) {
                    return $q->where('zone_id', $request->zone_id);
                });
            })
            ->selectRaw('  COUNT(*) as total_transactions,
                SUM(paid_amount) as total_paid_amount,
                SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN paid_amount ELSE 0 END) as current_month_paid_amount ', [Carbon::now()->month, Carbon::now()->year])
            ->first();

            $data['total_transactions']= $totals['total_transactions'];
            $data['total_paid_amount']= $totals['total_paid_amount'];
            $data['current_month_paid_amount']= $totals['current_month_paid_amount'];


        return view('admin-views.subscription.list',compact('subscribers','data'));
    }

    public function subscriberDetail($id){
        $restaurant= Restaurant::where('id',$id)->with([
            'restaurant_sub_update_application.package','vendor','restaurant_sub_update_application.last_transcations'
        ])->withcount('foods')
        ->first();
        $packages = SubscriptionPackage::where('status',1)->latest()->get();
        $admin_commission=BusinessSetting::where('key', 'admin_commission')->first()?->value ;
        $business_name=BusinessSetting::where('key', 'business_name')->first()?->value ;
        try {
            $index=  $restaurant->restaurant_model == 'commission' ? 0 : 1+ array_search($restaurant?->restaurant_sub_update_application?->package_id??1 ,array_column($packages->toArray() ,'id') );
        } catch (\Throwable $th) {
            $index= 2;
        }

        return view('admin-views.subscription.vendor-subscription',compact('restaurant','packages','business_name','admin_commission','index'));
    }

    public function subscriberListExport(Request $request){

        $data = [
            'data'=>$this->allSubscriberListData($request,'export'),
            'zone'=>Zone::where('id' ,$request->zone_id)->first()?->name ?? 'all',
            'filter'=>$request->subscription_type ?? 'all',
            'search'=>$request['search'],
        ];
        if ($request->export_type == 'excel') {
            return Excel::download(new SubscriptionSubscriberListExport($data), 'SubscriptionSubscriberListExport.xlsx');
        }
        return Excel::download(new SubscriptionSubscriberListExport($data), 'SubscriptionSubscriberListExport.csv');
    }


    private function allSubscriberTransactionsData($request, $id, $type = null){

            $filter= $request['filter'];
            $plan_type= $request['plan_type'];
            $from =$request['start_date'] ?? Carbon::now()->format('Y-m-d');
            $to =$request['end_date'] ?? Carbon::now()->format('Y-m-d');

            $key = explode(' ', $request['search']);

            $transactions= SubscriptionTransaction::where('restaurant_id',$id)
            ->when(isset($key), function($query) use($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('id', 'like', "%{$value}%");
                    }
                });
            })
            ->applyDateFilter($filter,$from,$to)
            ->when( in_array( $plan_type,['renew','new_plan','first_purchased','free_trial'])  , function($query) use($plan_type){
                $query->where('plan_type', $plan_type );
            })

            ->latest();
            if($type == 'export'){
                return $transactions->get();
            }
            return $transactions->paginate(config('default_pagination'));
    }

    public function subscriberTransactions($id,Request $request){

        $restaurant= Restaurant::where('id',$id)->with([
            'restaurant_sub_update_application.package'
            ])
            ->first();
            $transactions=$this->allSubscriberTransactionsData($request,$id);
            $filter= $request['filter'];
            $subscription_deadline_warning_days = BusinessSetting::where('key','subscription_deadline_warning_days')->first()?->value ?? 7;
        return view('admin-views.subscription.transaction',compact('restaurant','transactions','id','filter','subscription_deadline_warning_days'));

    }

    public function subscriberTransactionExport(Request $request){
        $request->validate([
            'id' => 'required',
        ]);
        $id= $request['id'];

        $restaurant= Restaurant::where('id',$id)->first();

        $data = [
            'data'=>$this->allSubscriberTransactionsData($request,$id,'export'),
            'plan_type'=>$request['plan_type'] ?? 'all',
            'filter'=>$request['filter'] ?? 'all',
            'search'=>$request['search'],
            'start_date'=>$request['start_date'],
            'end_date'=>$request['end_date'],
            'restaurant'=>$restaurant->name,
        ];
        if ($request->export_type == 'excel') {
            return Excel::download(new SubscriptionTransactionsExport($data), 'SubscriptionTransactionsExport.xlsx');
        }
        return Excel::download(new SubscriptionTransactionsExport($data), 'SubscriptionTransactionsExport.csv');
    }
    public function switchPlan(Request $request){
        $request->validate([
            'package_id' => 'required',
        ]);

        SubscriptionPackage::where('id',$request->turn_off_package_id)->update([
            'status' => 0
        ]);

        $restaurants=  RestaurantSubscription::where('package_id',$request->turn_off_package_id)->where('status',1)->where('is_canceled',0)->where('is_trial',0)->get(['restaurant_id']);

        if($request->package_id == 'commission'){
            RestaurantSubscription::where('package_id',$request->turn_off_package_id)->update([
                'status' => 0
            ]);
        }

        foreach($restaurants as $restaurant){

            if($request->package_id == 'commission'){
                Restaurant::where('id', $restaurant->restaurant_id)->update([
                    'restaurant_model'=>'commission',
                    'food_section'=> 1
                ]);
            } else{
                $pending_bill=0;
                $pending_bill= SubscriptionBillingAndRefundHistory::where(['restaurant_id'=>$restaurant->restaurant_id,
                'transaction_type'=>'pending_bill', 'is_success' =>0])?->sum('amount')?? 0;
                    $reference= 'plan_shift_by_admin';
                    Helpers::subscription_plan_chosen(restaurant_id:$restaurant->restaurant_id,package_id:$request->package_id,payment_method:$reference,discount:0,pending_bill:$pending_bill,reference:$reference);
            }

        }
        Toastr::success( translate('messages.Plan_Switch_Successful'));
        return back();
    }


    public function cancelSubscription(Request $request, $id){

        RestaurantSubscription::where(['restaurant_id' => $id, 'id'=>$request->subscription_id])->update([
            'is_canceled' => 1,
            'canceled_by' => 'admin',
        ]);

        try {
            $restaurant=Restaurant::where('id',$id)->first();

            $notification_status=Helpers::getNotificationStatusData('restaurant','restaurant_subscription_cancel');
            $reataurant_notification_status=Helpers::getRestaurantNotificationStatusData($restaurant?->id,'restaurant_subscription_cancel');


            if(  $notification_status?->push_notification_status  == 'active' && $reataurant_notification_status?->push_notification_status  == 'active' &&   $restaurant?->vendor?->firebase_token){
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


            if (config('mail.status') && Helpers::get_mail_status('subscription_cancel_mail_status_restaurant') == '1' && $notification_status?->mail_status  == 'active' && $reataurant_notification_status?->mail_status  == 'active') {
                Mail::to($restaurant->email)->send(new SubscriptionCancel($restaurant->name));
            }
        } catch (\Exception $ex) {
            info($ex->getMessage());
        }
        return response()->json(200);

    }

    public function settings(){
        Helpers::CheckOldSubscriptionSettings();
        $key=['subscription_deadline_warning_days','subscription_deadline_warning_message','subscription_free_trial_days','subscription_free_trial_type','subscription_free_trial_status','subscription_usage_max_time'];
        $settings=BusinessSetting::whereIn('key', $key)->pluck('value','key');
        return view('admin-views.subscription.settings',compact('settings'));
    }


    public function trialStatus(){
        $status = BusinessSetting::firstOrNew([
            'key' => 'subscription_free_trial_status'
        ]);
        $status->value =  $status->value != 1 ?  1 : 0;
        $status->save();
        Toastr::success($status->value == 1 ? translate('messages.Free_Trial_Activated_Successfully') : translate('messages.Free_Trial_Disabled_Successfully'));
        return back();
    }


    public function settings_update(Request $request){
        $key=['subscription_deadline_warning_days','subscription_deadline_warning_message','subscription_free_trial_days','subscription_free_trial_type','subscription_free_trial_status','subscription_usage_max_time'];
            foreach ($request->all() as $k => $value) {

                if(in_array($k, $key) ){
                    $status = BusinessSetting::firstOrNew([
                        'key' => $k
                    ]);
                    if( $k == 'subscription_free_trial_days'){
                        if($request->subscription_free_trial_type == 'year'){
                            $value = $value * 365;
                        } else if($request->subscription_free_trial_type == 'month'){
                            $value = $value * 30;
                        } else{
                            $value = $value;
                        }
                    }

                    $status->value =  $value;
                    $status->save();
                }
            }

        Toastr::success( translate('messages.Settings_Saved_Successfully'));
        return back();
    }


    public function subscriberWalletTransactions($id,Request $request){
        $restaurant= Restaurant::where('id',$id)->first();
        $transactions= SubscriptionBillingAndRefundHistory::where('restaurant_id', $id)->with('package')
        ->where('transaction_type','refund')
        ->latest()->paginate(config('default_pagination'));

        return view('admin-views.subscription.wallet-transaction',compact('transactions','restaurant'));

    }

    public function switchToCommission($id){

        $restaurant=  Restaurant::where('id',$id)->with('restaurant_sub')->first();

        $restaurant_subscription=  $restaurant->restaurant_sub;
        if($restaurant->restaurant_model == 'subscription'  && $restaurant_subscription?->is_canceled === 0 && $restaurant_subscription?->is_trial === 0){
            Helpers::calculateSubscriptionRefundAmount(restaurant:$restaurant);
        }

        $restaurant->restaurant_model = 'commission';
        $restaurant->save();

        RestaurantSubscription::where(['restaurant_id' => $id])->update([
            'status' => 0,
        ]);
        return response()->json(200);

    }
    public function packageView($id,$restaurant_id){
        $restaurant_subscription= RestaurantSubscription::where('restaurant_id', $restaurant_id)->with(['package'])->latest()->first();
        $package = SubscriptionPackage::where('status',1)->where('id',$id)->first();
        $restaurant= Restaurant::Where('id',$restaurant_id)->first();
        $pending_bill= SubscriptionBillingAndRefundHistory::where(['restaurant_id'=>$restaurant->id,
                            'transaction_type'=>'pending_bill', 'is_success' =>0])->sum('amount') ;

        $balance = BusinessSetting::where('key', 'wallet_status')->first()?->value == 1 ? RestaurantWallet::where('vendor_id',$restaurant->vendor_id)->first()?->balance ?? 0 : 0;
        $payment_methods = Helpers::getActivePaymentGateways();
        $disable_item_count=null;
        if(data_get(Helpers::subscriptionConditionsCheck(restaurant_id:$restaurant->id,package_id:$package->id) , 'disable_item_count') > 0 && ( !$restaurant_subscription || $package->id != $restaurant_subscription->package_id)){
            $disable_item_count=data_get(Helpers::subscriptionConditionsCheck(restaurant_id:$restaurant->id,package_id:$package->id) , 'disable_item_count');
        }
        $restaurant_model=$restaurant->restaurant_model;
        $admin_commission=BusinessSetting::where('key', "admin_commission")->first()?->value ?? 0 ;
        $cash_backs=[];
        if($restaurant->restaurant_model == 'subscription' &&  $restaurant_subscription->status == 1 && $restaurant_subscription->is_canceled == 0 && $restaurant_subscription->is_trial == 0  && $restaurant_subscription->package_id !=  $package->id){
            $cash_backs= Helpers::calculateSubscriptionRefundAmount(restaurant:$restaurant, return_data:true);
        }

        return response()->json([
            'disable_item_count'=> $disable_item_count,
            'view' => view('admin-views.subscription.partials._package_selected', compact('restaurant_subscription','package','restaurant_id','balance','payment_methods','pending_bill','restaurant_model','admin_commission','cash_backs'))->render()
        ]);

    }
    public function packageBuy(Request $request){

        $request->validate([
            'package_id' => 'required',
            'restaurant_id' => 'required',
            'payment_gateway' => 'required'
        ]);
        $restaurant= Restaurant::Where('id',$request->restaurant_id)->first(['id','vendor_id']);
        $package = SubscriptionPackage::withoutGlobalScope('translate')->find($request->package_id);


        $pending_bill= SubscriptionBillingAndRefundHistory::where(['restaurant_id'=>$restaurant->id,
                            'transaction_type'=>'pending_bill', 'is_success' =>0])?->sum('amount')?? 0;

        if(!in_array($request->payment_gateway,['wallet','manual_payment_by_admin'])){
            $url= route('admin.business-settings.subscriptionackage.subscriberDetail',$restaurant->id);
            return redirect()->away(Helpers::subscriptionPayment(restaurant_id:$restaurant->id,package_id:$package->id,payment_gateway:$request->payment_gateway,payment_platform:'web',url:$url,pending_bill:$pending_bill,type: $request?->type));
        }

        if($request->payment_gateway == 'wallet'){
        $wallet= RestaurantWallet::firstOrNew(['vendor_id'=> $restaurant->vendor_id]);
        $balance = BusinessSetting::where('key', 'wallet_status')->first()?->value == 1 ? $wallet?->balance ?? 0 : 0;

            if($balance >= ($package?->price + $pending_bill)){
                $reference= 'wallet_payment_by_admin';
                $plan_data=   Helpers::subscription_plan_chosen(restaurant_id:$restaurant->id,package_id:$package->id,payment_method:$reference,discount:0,pending_bill:$pending_bill,reference:$reference,type: $request?->type);
                if($plan_data != false){
                    $wallet->total_withdrawn= $wallet?->total_withdrawn + $package->price + $pending_bill;
                    $wallet?->save();
                }

            }
            else{
                Toastr::error( translate('messages.Insufficient_balance_in_wallet'));
                return back();
            }
        } elseif($request->payment_gateway == 'manual_payment_by_admin'){
            $reference= 'manual_payment_by_admin';
            $plan_data=   Helpers::subscription_plan_chosen(restaurant_id:$restaurant->id,package_id:$package->id,payment_method:$reference,discount:0,pending_bill:$pending_bill,reference:$reference,type: $request?->type);
        }

        $plan_data != false ?  Toastr::success(  $request?->type == 'renew' ?  translate('Subscription_Package_Renewed_Successfully.'): translate('Subscription_Package_Shifted_Successfully.') ) : Toastr::error( translate('Something_went_wrong!.'));
        return back();

    }


}
