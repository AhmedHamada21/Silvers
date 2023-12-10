<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InviteFriend extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'captain_id',
        'type',
        'code_invite',
        'data',
    ];
}
