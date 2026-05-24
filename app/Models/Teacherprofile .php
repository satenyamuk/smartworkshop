<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    protected $fillable = [
        'user_id',
        'teacher_id',
        'employee_email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}