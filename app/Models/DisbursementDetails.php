<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ReportFilter;

class DisbursementDetails extends Model
{
    use HasFactory, ReportFilter;

    protected $casts = [
        'disbursement_id'=>'integer',
        'restaurant_id'=>'integer',
        'delivery_man_id'=>'integer',
        'payment_method'=>'integer',
        'disbursement_amount' => 'float',
        'is_default'=>'boolean',

    ];


    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function delivery_man()
    {
        return $this->belongsTo(DeliveryMan::class);
    }
    public function disbursement()
    {
        return $this->belongsTo(Disbursement::class);
    }

    public function withdraw_method()
    {
        return $this->belongsTo(DisbursementWithdrawalMethod::class,'payment_method','id');
    }
}
