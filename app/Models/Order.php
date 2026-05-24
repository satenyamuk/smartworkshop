<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'workshop_id',
        'quantity',
        'total_amount',
        'receipt_path',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // --- Relationships ---
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}