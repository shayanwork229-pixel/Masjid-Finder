<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationCampaign extends Model
{
    protected $fillable = [
        'masjid_id',
        'title',
        'description',
        'image_path',
        'target_amount',
        'collected_amount',
        'start_date',
        'end_date',
        'status',
        'is_featured',
        'is_emergency',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
        'is_emergency' => 'boolean',
    ];

    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->target_amount - $this->collected_amount);
    }

    public function getPercentageCompletedAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(100, round(($this->collected_amount / $this->target_amount) * 100, 2));
    }

    public function getProgressLabelAttribute(): string
    {
        return number_format($this->percentage_completed, 2) . '%';
    }
}
