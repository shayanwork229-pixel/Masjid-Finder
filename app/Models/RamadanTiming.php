<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RamadanTiming extends Model
{
    protected $fillable = ['city', 'year', 'day', 'sehri_time', 'iftar_time', 'notes', 'is_active'];
}
