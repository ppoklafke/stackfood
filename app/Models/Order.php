<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Scopes\ZoneScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\ReportFilter;

class Order extends Model
{
    use ReportFilter,HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'order_amount' => 'float',
        'coupon_discount_amount' => 'float',
        'total_tax_amount' => 'float',
        'restaurant_discount_amount' => 'float',
        'delivery_address_id' => 'integer',
        'delivery_man_id' => 'integer',
        'delivery_charge' => 'float',
        'original_delivery_charge'=>'float',
        'user_id' => 'integer',
        'scheduled' => 'integer',
        'restaurant_id' => 'integer',
        'details_count' => 'integer',
        'processing_time' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'dm_tips'=>'float',
        'vehicle_id' => 'integer',
        'quantity' => 'integer',
        'distance'=>'float',
        'subscription_id'=>'integer',
        'cutlery'=>'boolean',
        'is_guest'=>'boolean',
        'additional_charge' => 'float',
        'ref_bonus_amount' => 'float',
        'extra_packaging_amount' => 'float',
        'bring_change_amount'=>'integer',
    ];
    protected $appends = ['order_proof_full_url'];

    public function getOrderProofFullUrlAttribute(){
        $images = [];
        $value = is_array($this->order_proof)
            ? $this->order_proof
            : ($this->order_proof && is_string($this->order_proof) && $this->isValidJson($this->order_proof)
                ? json_decode($this->order_proof, true)
                : []);
        if ($value){
            foreach ($value as $item){
                $item = is_array($item)?$item:(is_object($item) && get_class($item) == 'stdClass' ? json_decode(json_encode($item), true):['img' => $item, 'storage' => 'public']);
                $images[] = Helpers::get_full_url('order',$item['img'],$item['storage']);
            }
        }

        return $images;
    }

    private function isValidJson($string)
    {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    public function cashback_history()
    {
        return $this->hasOne(CashBackHistory::class, 'order_id');
    }

    public function OrderReference()
    {
        return $this->hasOne(OrderReference::class, 'order_id');
    }


    public function guest()
    {
        return $this->belongsTo(Guest::class, 'user_id','id');
    }

    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
    }
    public function offline_payments(){
        return $this->belongsTo(OfflinePayments::class,'id','order_id' );
    }

    public function logs()
    {
        return $this->hasMany(Log::class,'model_id')->where('model','Order');
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function subscription_logs()
    {
        return $this->hasMany(SubscriptionLog::class);
    }

    public function subscription_log()
    {
        return $this->hasOne(SubscriptionLog::class)->where(function($q){
            $q->whereDate('schedule_at', now()->format('Y-m-d'))->orWhereNotIn('order_status',['delivered','failed','canceled', 'refund_requested','refund_request_canceled', 'refunded']);
        });
        // ->whereIn('order_status', ['pending', 'accepted','confirmed','processing','handover','picked_up']);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class,'subscription_id');
    }

    public function setDeliveryChargeAttribute($value)
    {
        $this->attributes['delivery_charge'] = round($value, 3);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class, 'order_id');
    }

    public function delivery_man()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function delivery_history()
    {
        return $this->hasMany(DeliveryHistory::class, 'order_id');
    }

    public function dm_last_location()
    {
        // return $this->hasOne(DeliveryHistory::class, 'order_id')->latest();
        return $this->delivery_man->last_location();
    }

    public function transaction()
    {
        return $this->hasOne(OrderTransaction::class);
    }

    public function scopeAccepteByDeliveryman($query)
    {
        return $query->where('order_status', 'accepted');
    }

    public function scopePreparing($query)
    {
        return $query->whereIn('order_status', ['confirmed','processing','handover']);
    }


    //check from here
    public function scopeOngoing($query)
    {
        return $query->whereIn('order_status', ['accepted','confirmed','processing','handover','picked_up']);
    }

    public function scopeFoodOnTheWay($query)
    {
        return $query->where('order_status','picked_up');
    }

    public function scopePending($query)
    {
        return $query->where('order_status','pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('order_status','failed');
    }

    public function scopeCanceled($query)
    {
        return $query->where('order_status','canceled');
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status','delivered');
    }

    public function scopeRefunded($query)
    {
        return $query->where('order_status','refunded');
    }

    public function scopeRefund_requested($query)
    {
        return $query->where('order_status','refund_requested');
    }

    public function scopeRefund_request_canceled($query)
    {
        return $query->where('order_status','refund_request_canceled');
    }


    public function scopeSearchingForDeliveryman($query)
    {
        return $query->whereNull('delivery_man_id')->where('order_type', '=' , 'delivery')->whereNotIn('order_status',['delivered','failed','canceled', 'refund_requested','refund_request_canceled', 'refunded']);
    }

    public function scopeDelivery($query)
    {
        return $query->where('order_type', '=' , 'delivery');
    }

    public function scopeScheduled($query)
    {
        return $query->whereRaw('created_at <> schedule_at')->where('scheduled', '1');
    }

    public function scopeOrderScheduledIn($query, $interval)
    {
        return $query->where(function($query)use($interval){
            $query->whereRaw('created_at <> schedule_at')->where(function($q) use ($interval) {
            $q->whereBetween('schedule_at', [Carbon::now()->toDateTimeString(),Carbon::now()->addMinutes($interval)->toDateTimeString()]);
            })->orWhere('schedule_at','<',Carbon::now()->toDateTimeString());
        })->orWhereRaw('created_at = schedule_at');

    }

    public function scopePos($query)
    {
        return $query->where('order_type', '=' , 'pos');
    }

    public function scopeNotpos($query)
    {
        return $query->where('order_type', '<>' , 'pos');
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s',strtotime($value));
    }

    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }

    public function scopeNotDigitalOrder($query)
    {
        return $query->where(function ($q){
            $q->whereNotIn('payment_method', ['digital_payment','offline_payment'])->orwhereNot('order_status' , 'pending');
        });

    }

    public function scopeNotRefunded($query)
    {
        return $query->where(function($query){
            $query->whereNotIn('order_status', ['refunded']);
        });
    }

    public function scopeRestaurantOrder($query)
    {
        return $query->whereIn('order_type',['dine_in','take_away','delivery']);
    }

    public function scopeHasSubscriptionToday($query)
    {
        return $query->where(function($query){
            $query->where(function($query){
                $query->whereHas('subscription', function($query){
                    $query->where('status','active')->whereDate('start_at', '<=', now()->format('Y-m-d'))->whereDate('end_at','>=', now()->format('Y-m-d'))

                    ->whereHas('schedules', function($query){
                        $query->where(function($query){
                            $query->where('type', 'weekly')->where('day', (int)now()->format('w'));
                        })->orWhere(function($query){
                            $query->where('type', 'monthly')->where('day', (int)now()->format('d'));
                        })->orWhere('type', 'daily');
                    })->whereDoesntHave('pause', function($query){
                        $query->whereDate('from', '<=', now()->format('Y-m-d'))->whereDate('to','>=', now()->format('Y-m-d'));
                    });
                })
                ->whereDoesntHave('subscription_logs', function($query){
                    $query->whereDate('schedule_at', now()->format('Y-m-d'))->orWhereIn('order_status',['delivered','failed','canceled', 'refund_requested','refund_request_canceled', 'refunded']);
                });
            })->orWhereNull('subscription_id');
        });
    }

    public function scopeHasSubscriptionInStatus($query, array $status_list)
    {
        return $query->orWhereHas('subscription_logs', function($query)use($status_list){
            $query->whereIn('order_status',$status_list);
        });
    }

    public function scopeHasSubscriptionTodayGet($query)
    {
        return $query->where(function($query){
            $query->where(function($query){
                $query->whereHas('subscription', function($query){
                    $query->where('status','active')->whereDate('start_at', '<=', now()->format('Y-m-d'))->whereDate('end_at','>=', now()->format('Y-m-d'))
                    ->whereHas('schedules', function($query){
                        $query->where(function($query){
                            $query->where('type', 'weekly')->where('day', (int)now()->format('w'));
                        })->orWhere(function($query){
                            $query->where('type', 'monthly')->where('day', (int)now()->format('d'));
                        })->orWhere('type', 'daily');
                    })->whereDoesntHave('pause', function($query){
                        $query->whereDate('from', '<=', now()->format('Y-m-d'))->whereDate('to','>=', now()->format('Y-m-d'));
                    });
                });
            });
        });
    }



    public static function scopeApplyDateFilterSchedule($query, $filter, $from = null, $to = null)
    {
        return $query->when(isset($from) && isset($to)  && $filter == 'custom', function ($query) use ($from, $to) {
            return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
        })
        ->when($filter == 'this_year', function ($query) {
            return $query->whereYear('schedule_at', now()->format('Y'));
        })
        ->when($filter == 'this_month', function ($query) {
            return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
        })
        ->when($filter == 'previous_year', function ($query) {
            return $query->whereYear('schedule_at', date('Y') - 1);
        })
        ->when($filter == 'this_week', function ($query) {
            return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
        });
        return $query;
    }
}
