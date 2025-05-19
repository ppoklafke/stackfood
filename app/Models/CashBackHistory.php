<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBackHistory extends Model
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



    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function cashBack()
    {
        return $this->belongsTo(CashBack::class,'cash_back_id');
    }
}
