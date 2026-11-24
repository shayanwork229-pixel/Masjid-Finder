<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'event_date', 'event_time', 'category', 'sect', 'masjid_id', 'is_featured'];

    protected $casts = [
        'event_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }
}
