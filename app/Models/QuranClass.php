<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuranClass extends Model
{
    protected $fillable = ['title', 'description', 'teacher_name', 'schedule', 'target_audience', 'is_online', 'location', 'is_active'];

    protected $casts = [
        'is_online' => 'boolean',
        'is_active' => 'boolean',
    ];
}
