<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'content', 'priority', 'expiry_date', 'is_active'];

    protected $casts = [
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];
}
