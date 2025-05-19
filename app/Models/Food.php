<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use App\Scopes\ZoneScope;
use Illuminate\Support\Str;
use App\Scopes\RestaurantScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\ReportFilter;

class Food extends Model
{
    use HasFactory , ReportFilter;

    protected $casts = [
        'tax' => 'float',
        'price' => 'float',
        'status' => 'integer',
        'discount' => 'float',
        'avg_rating' => 'float',
        'set_menu' => 'integer',
        'category_id' => 'integer',
        'restaurant_id' => 'integer',
        'reviews_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'veg' => 'integer',
        'min' => 'integer',
        'max' => 'integer',
        'maximum_cart_quantity' => 'integer',
        'recommended' => 'integer',
        'order_count'=>'integer',
        'rating_count'=>'integer',
        'is_halal'=>'integer',
    ];

    protected $appends = ['image_full_url'];
    public function getImageFullUrlAttribute(){
        $value = $this->image;
        if (count($this->storage) > 0) {
            foreach ($this->storage as $storage) {
                if ($storage['key'] == 'image') {
                    return Helpers::get_full_url('product',$value,$storage['value']);
                }
            }
        }

        return Helpers::get_full_url('product',$value,'public');
    }

    public function logs()
    {
        return $this->hasMany(Log::class,'model_id')->where('model','Food');
    }
    public function newVariations()
    {
        return $this->hasMany(Variation::class,'food_id');
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class,'food_id');
    }
    public function newVariationOptions()
    {
        return $this->hasMany(VariationOption::class,'food_id');
    }

    public function scopeRecommended($query)
    {
        return $query->where('recommended',1);
    }

    public function carts()
    {
        return $this->morphMany(Cart::class, 'item');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }


    // public function scopeActive($query)
    // {
    //     return $query->where('status', 1)->whereHas('restaurant', function ($query) {
    //         return $query->where('status', 1);
    //     });
    // }



    public function scopeActive($query)
    {
        return $query->where('status', 1)
        ->whereHas('restaurant', function($query) {
            $query->where('status', 1)
                    ->where(function($query) {
                        $query->where('restaurant_model', 'commission')
                                ->orWhereHas('restaurant_sub', function($query) {
                                    $query->where(function($query) {
                                        $query->where('max_order', 'unlimited')->orWhere('max_order', '>', 0);
                                    });
                                });
                    });
            });
    }



    public function scopeAvailable($query,$time)
    {
        $query->where(function($q)use($time){
            $q->where('available_time_starts','<=',$time)->where('available_time_ends','>=',$time);
        });
    }

    public function scopePopular($query)
    {
        return $query->orderBy('order_count', 'desc');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function rating()
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, count(food_id) rating_count, food_id'))
            ->groupBy('food_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orders()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function storage()
    {
        return $this->morphMany(Storage::class, 'data');
    }

    protected static function booted()
    {
        // dd( app()->getLocale());
        if (auth('vendor')->check() || auth('vendor_employee')->check()) {
            static::addGlobalScope(new RestaurantScope);
        }

        static::addGlobalScope(new ZoneScope);

        // static::addGlobalScope('storage', function ($builder) {
        //     $builder->with('storage');
        // });

        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                return $query->where('locale', app()->getLocale());
            }]);
        });
    }


    public function scopeType($query, $type)
    {
        if ($type == 'veg') {
            return $query->where('veg', true);
        } else if ($type == 'non_veg') {
            return $query->where('veg', false);
        }

        return $query;
    }


    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function allergies()
    {
        return $this->belongsToMany(Allergy::class);
    }
    public function nutritions()
    {
        return $this->belongsToMany(Nutrition::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($food) {
            $food->slug = $food->generateSlug($food->name);
            $food->save();
        });

        static::retrieved(function ($food) {
            try {
                if($food?->orders?->count() != 0 && $food?->orders()->whereDay('created_at', now())->count() == 0 && $food->stock_type == 'daily'){
                        $food->sell_count = 0;
                        $food->save();
                        $food?->newVariationOptions()?->update([
                            'sell_count' => 0
                        ]);
                    }
                    unset($food->orders);
                } catch (\Exception $exception) {
                    info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
                }
            });
        static::saved(function ($model) {
            if($model->isDirty('image')){
                $value = Helpers::getDisk();

                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                    'key' => 'image',
                ], [
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
    private function generateSlug($name)
    {
        $slug = Str::slug($name);
        if ($max_slug = static::where('slug', 'like',"{$slug}%")->latest('id')->value('slug')) {

            if($max_slug == $slug) return "{$slug}-2";

            $max_slug = explode('-',$max_slug);
            $count = array_pop($max_slug);
            if (isset($count) && is_numeric($count)) {
                $max_slug[]= ++$count;
                return implode('-', $max_slug);
            }
        }
        return $slug;
    }


    public function getNameAttribute($value){
        if (count($this->translations) > 0) {
            // info(count($this->translations));
            foreach ($this->translations as $translation) {
                if ($translation['key'] == 'name') {
                    return $translation['value'];
                }
            }
        }

        return $value;
    }

    public function getDescriptionAttribute($value){
        if (count($this->translations) > 0) {
            foreach ($this->translations as $translation) {
                if ($translation['key'] == 'description') {
                    return $translation['value'];
                }
            }
        }

        return $value;
    }
    public function getItemStockAttribute($value){
        return $value - $this->sell_count > 0 ? $value - $this->sell_count : 0 ;
    }

    public function getVariationsAttribute($value){
        try {
            if(is_string($value) &&  (json_decode($value, true) == null || count(json_decode($value, true)) == 0) && count($this->newVariations) > 0 && count($this->newVariationOptions) > 0 ){
                foreach ($this->newVariations as $variation) {
                    $variationArray = [
                        "variation_id" => (int) $variation['id'],
                        "name" => $variation['name'],
                        "type" => $variation['type'],
                        "min" => (string) $variation['min'],
                        "max" => (string) $variation['max'],
                        "required" => (string) $variation['is_required'] == true ? "on" :'off',
                        "values" => []
                    ];

                    foreach ($this->newVariationOptions as $option) {
                        if ($option['variation_id'] == $variation['id']) {

                            $current_stock=  $option['stock_type'] == 'unlimited'  ? 'unlimited': $option['total_stock'] - $option['sell_count'] ;
                            $variationArray['values'][] = [
                                "label" => $option['option_name'],
                                "optionPrice" => $option['option_price'],
                                "total_stock" => (string) $option['total_stock'],
                                "stock_type" => $option['stock_type'],
                                "sell_count" =>(string) $option['sell_count'],
                                "option_id" => (int) $option['id'],
                                "current_stock" => (int)   ($current_stock == 'unlimited' ? 0 : ($current_stock > 0 ? $current_stock : 0)),
                            ];
                        }
                    }
                    $result[] = $variationArray;
                    unset($this->newVariations);
                    unset($this->newVariationOptions);
                }

                return json_encode($result);
            }
            else{
                return $value;
            }
        } catch (\Exception $exception) {
            info([$exception->getFile(),$exception->getLine(),$exception->getMessage()]);
            return $value;
        }

    }

}
