<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationOption extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'id' => 'integer',
        'food_id' => 'integer',
        'variation_id' => 'integer',
        'option_price' => 'float',
        'total_stock' => 'integer',
        'sell_count' => 'integer',
    ];
    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }
}
