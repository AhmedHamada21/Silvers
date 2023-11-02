<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCaption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'type',
    ];
}
