<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;
use App\Models\DeliveryMan;

class AccountTransaction extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'float',
        'current_balance' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getRestaurantAttribute()
    {
        if($this->from_type == 'restaurant'){
            return Restaurant::setEagerLoads([])->where('vendor_id', $this->from_id)->first();
        }
        return null;
    }

    public function getDeliverymanAttribute()
    {
        if($this->from_type == 'deliveryman'){
            return DeliveryMan::setEagerLoads([])->find($this->from_id);
        }
        return null;
    }

}
