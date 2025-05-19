<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }
    public function getMessageAttribute($value){
        if (count($this->translations) > 0) {
            foreach ($this->translations as $translation) {
                if ($translation['key'] ==  $this->key) {
                    return $translation['value'];
                }
            }
        }

        return $value;
    }

}
