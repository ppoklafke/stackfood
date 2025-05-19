<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'data_type',
        'data_id',
        'value'
    ];

    public function data()
    {
        return $this->morphTo();
    }
}
