<?php

namespace App\Models;

use App\Scopes\RestaurantScope;
use App\Scopes\ZoneScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\CentralLogics\Helpers;
use Razorpay\Api\Addon as ApiAddon;

class AddOn extends Model
{
    protected $casts = [
        'price' => 'float',
        'restaurant_id' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'quantity' => 'integer',

    ];

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }



    public function getNameAttribute($value){
        if (count($this->translations) > 0) {
            foreach ($this->translations as $translation) {
                if ($translation['key'] == 'name') {
                    return $translation['value'];
                }
            }
        }

        return $value;
    }
    public function getAddonStockAttribute($value){
        return $value - $this->sell_count > 0 ? $value - $this->sell_count : 0 ;
    }

    protected static function booted()
    {
        if(auth('vendor')->check() || auth('vendor_employee')->check())
        {
            static::addGlobalScope(new RestaurantScope);
        }
        static::addGlobalScope(new ZoneScope);

        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function($query){
                return $query->where('locale', app()->getLocale());
            }]);
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::retrieved(function ($addon) {
            $current_date = date('Y-m-d');
            $check_daily_stock_on= BusinessSetting::where('key', 'check_daily_stock_on')->first();
            if(!$check_daily_stock_on){
                Helpers::insert_business_settings_key('check_daily_stock_on', $current_date);
                $check_daily_stock_on= BusinessSetting::where('key', 'check_daily_stock_on')->first();
            }

            if($check_daily_stock_on && $check_daily_stock_on?->value != $current_date){
                AddOn::where('stock_type','daily')->update([
                    'sell_count' => 0,
                ]);
                $check_daily_stock_on->value = $current_date;
                $check_daily_stock_on->save();
            }
        });
    }

}
