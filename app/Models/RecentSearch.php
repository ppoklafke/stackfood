<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentSearch extends Model
{
    use HasFactory;

    protected $table = 'recent_searches';

    protected $fillable = [
        'user_id',
        'user_type',
        'route_name',
        'route_uri',
        'route_full_url',
    ];
}
