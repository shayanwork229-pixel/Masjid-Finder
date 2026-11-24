<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasjidImage extends Model
{
    protected $fillable = ['masjid_id', 'image_path', 'caption', 'sort_order'];

    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }
}
