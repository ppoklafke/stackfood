<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AllergyFood extends Pivot
{
    use HasFactory;

    protected $casts = [
        'id'=>'integer',
        'food_id'=>'integer',
        'allergy_id'=>'integer'
    ];
}
