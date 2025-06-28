<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'number','first_name', 'user_id','last_name', 'company_name', 'address', 'city',
        'country', 'postcode', 'mobile', 'email', 'notes', 'total', 'status_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
