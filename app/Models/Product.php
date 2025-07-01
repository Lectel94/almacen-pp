<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'image_url',
        'sku',
        'barcode',
        'stock',
        'list_price',
        'cost_unit',
        'total_value',
        'potencial_revenue',
        'potencial_profit',
        'profit_margin',
        'markup',
        'warehouse_id',
        'category_id',
        'variant_id',
        'vendor_id',

    ];

    public function getPrecioPorRolAttribute()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->list_price;
        }

        if ($user->hasRole('Category1')) {
            return $this->list_price;
        }

        if ($user->hasRole('Category2')) {
            return $this->list_price / 0.9;
        }

        if ($user->hasRole('Category3')) {
            return $this->list_price / 0.85;
        }
        if ($user->hasRole('Category4')) {
            return $this->list_price / 0.8;
        }

        return $this->list_price ;
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }


}
