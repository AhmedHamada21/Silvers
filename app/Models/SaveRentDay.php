<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveRentDay extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'trip_type_id',
        'order_code',
        'total_price',
        'lat_user',
        'long_user',
        'address_now',
        'status',
        'payments',
        'chat_id',
        'start_day',
        'end_day',
        'number_day',
        'start_time',
        'commit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function trip_type()
    {
        return $this->belongsTo(TripType::class, 'trip_type_id');
    }
}
