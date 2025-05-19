<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    use HasFactory;


    protected $casts = [
        'id'=>'integer',
        'total_amount' => 'float',
        
    ];



    public function details()
    {
        return $this->hasMany(DisbursementDetails::class);
    }
}
