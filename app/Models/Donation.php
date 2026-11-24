<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'donation_campaign_id',
        'name',
        'phone',
        'email',
        'amount',
        'category',
        'payment_method',
        'message',
        'is_anonymous',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(DonationCampaign::class, 'donation_campaign_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Anonymous Donor' : $this->name;
    }
}
