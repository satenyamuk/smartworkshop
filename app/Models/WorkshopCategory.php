<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function workshops()
    {
        return $this->hasMany(Workshop::class, 'category_id');
    }
}