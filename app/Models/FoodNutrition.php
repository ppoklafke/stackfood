<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FoodNutrition extends Pivot
{
    use HasFactory;

    protected $casts = [
        'id'=>'integer',
        'food_id'=>'integer',
        'nutrition_id'=>'integer'
    ];
}
