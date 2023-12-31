<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpMessages extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'code',
        'date',
        'phone',
    ];
}
