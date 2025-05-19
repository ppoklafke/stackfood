<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReference extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'order_id' => 'integer',
        'is_reviewed' => 'boolean',
        'is_review_canceled' => 'boolean',
    ];

    public function Order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
