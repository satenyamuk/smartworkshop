<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'order_id',
        'workshop_id',
        'ticket_code',
        'participant_type',
        'participant_name',
        'participant_id_number',
        'participant_email',
        'class_id',
        'status',
    ];

    // --- Relationships ---
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}