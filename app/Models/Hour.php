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
        'category_car_id',
    ];


    public function category_car()
    {
        return $this->belongsTo(CategoryCar::class,'category_car_id');
    }

    public function hour_car_type() {
        return $this->belongsToMany(Hour::class, 'hour_car_type');
    }
}
