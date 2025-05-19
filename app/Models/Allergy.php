<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    use HasFactory;

    protected $fillable = ['allergy'];

    public function foods()
    {
        return $this->belongsToMany(Food::class)->using('App\Models\AllergyFood');
    }
}
