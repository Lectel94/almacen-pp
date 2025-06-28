<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['description', 'color'];

    // relación con Order
    public function orders()
    {
        return $this->hasMany(Order::class, 'status_id');
    }
}
