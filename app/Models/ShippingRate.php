<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;

class ShippingRate extends Model
{
    protected $fillable = [
        'city_code',
        'shipping_cost',
        'min_order_qty',
        'is_active',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }
}
