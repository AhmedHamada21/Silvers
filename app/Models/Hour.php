<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_hours',
        'offer_price',
        'discount_hours',
        'price_hours',
        'price_premium',
        'offer_price_premium',
        'car_type_id',
    ];

    public function car_type()
    {
        return $this->belongsTo(CarType::class,'car_type_id');
    }
}
