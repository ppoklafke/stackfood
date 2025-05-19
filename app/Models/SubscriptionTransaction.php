<?php

namespace App\Models;

use App\Scopes\ZoneScope;
use App\Scopes\RestaurantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\ReportFilter;
class SubscriptionTransaction extends Model
{
    use HasFactory, ReportFilter;
    protected $guarded = ['id'];
    public $incrementing = false;

    protected $casts = [
        'package_details' => 'array',
        'id'=> 'string',
        'chat'=>'integer',
        'review'=>'integer',
        'package_id'=>'integer',
        'restaurant_id'=>'integer',
        'status'=>'integer',
        'self_delivery'=>'integer',
        'max_order'=>'string',
        'max_product'=>'string',
        'payment_method'=>'string',
        'paid_amount'=>'float',
        'validity'=>'integer',
        'is_trial'=>'integer',
        'restaurant_subscription_id'=>'integer',

    ];

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class,'id', 'restaurant_id');
    }
    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'package_id','id');
    }
    public function subscription()
    {
        return $this->belongsTo(RestaurantSubscription::class, 'restaurant_subscription_id');
    }
    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }

}
