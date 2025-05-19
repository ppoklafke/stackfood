<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class BusinessSetting extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = [
        'key',
        'value'
    ];

    public function storage()
    {
        return $this->morphMany(Storage::class, 'data');
    }

    protected static function booted(): void
    {
        // static::addGlobalScope('storage', function ($builder) {
        //     $builder->with('storage');
        // });

    }
    protected static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            $value = Helpers::getDisk();

            DB::table('storages')->updateOrInsert([
                'data_type' => get_class($model),
                'data_id' => $model->id,
            ], [
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
