<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nutrition extends Model
{
    use HasFactory;
    protected $table = 'nutritions';

    protected $fillable = ['nutrition'];

    public function foods()
    {
        return $this->belongsToMany(Food::class)->using('App\Models\FoodNutrition');
    }
}
