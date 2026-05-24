<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    protected $fillable = [
        'instructor_id',
        'category_id',
        'title',
        'description',
        'banner_image',
        'start_at',
        'end_at',
        'location',
        'capacity',
        'tickets_sold',
        'price',
        'audience',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'price'    => 'decimal:2',
    ];

    // --- Helpers ---
    public function isFree(): bool
    {
        return $this->price == 0;
    }

    public function remainingSlots(): int
    {
        return $this->capacity - $this->tickets_sold;
    }

    public function isFull(): bool
    {
        return $this->remainingSlots() <= 0;
    }

    // --- Relationships ---
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(WorkshopCategory::class, 'category_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}