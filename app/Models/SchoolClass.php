<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'grade_level',
    ];

    // --- Relationships ---
    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class, 'class_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'class_id');
    }
}