<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_hours',
        'discount_hours',
        'price_hours',
        'price_premium',
        'offer_price_premium',
    ];
}
