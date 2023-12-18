<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    use HasFactory;
    protected $table = "tickets";

    protected $fillable = ['title', 'subject', 'priority', 'assign_to_admin', 'assign_to_callcenter'];

    public function assignedToAdmin()
    {
        return $this->belongsTo(Admin::class, 'assign_to_admin');
    }

    public function assignedToCallCenter()
    {
        return $this->belongsTo(CallCenter::class, 'assign_to_callcenter');
    }
}
