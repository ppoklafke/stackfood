<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantNotificationSetting extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
