<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CashBack extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'same_user_limit' => 'integer',
        'total_used' => 'integer',
        'cashback_amount' => 'float',
        'min_purchase' => 'float',
        'max_discount' => 'float',
        'status' => 'boolean',
    ];


    // cash_back_id = cash back history id
    public function orders()
    {
        return $this->hasMany(Order::class,'cash_back_id')->where('is_guest',0);
    }
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }


    public function getTitleAttribute($value): string
    {
        if (count($this->translations) > 0) {
            foreach ($this->translations as $translation) {
                if ($translation['key'] == 'title') {
                    return $translation['value'];
                }
            }
        }

        return $value;
    }

       /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query): mixed
    {
        return $query->where('status', 1);
    }
    public function scopeCashBackType($query,$value): mixed
    {
        if($value == 'amount'){
            return $query->where('cashback_type', 'amount');
        }
        else if($value == 'percentage'){
            return $query->where('cashback_type', 'percentage');
        }
        return $query;
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeRunning($query): mixed
    {
        return $query->where(function($q){
                $q->whereDate('end_date', '>=', date('Y-m-d'))->orWhereNull('end_date');
            })->where(function($q){
                $q->whereDate('start_date', '<=', date('Y-m-d'))->orWhereNull('start_date');
            });
    }

    protected static function booted()
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                return $query->where('locale', app()->getLocale());
            }]);
        });
    }
}
