<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',

    ];

    public function getProductStockCountAttribute()
    {
        return $this->products()->where('stock', '>', 0)->count();
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
