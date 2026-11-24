<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DonationCampaign;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Masjid extends Model
{
    protected $fillable = [
        'name','address','area','city','sect','latitude','longitude','fajr','zuhr','asr','maghrib','isha','juma_time','eid_time','phone','description','is_featured',
        'parking', 'wheelchair', 'ladies_prayer', 'construction_status'
    ];

    protected $casts = [
        'parking' => 'boolean',
        'wheelchair' => 'boolean',
        'ladies_prayer' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'facility_masjid');
    }

    public function images(): HasMany
    {
        return $this->hasMany(MasjidImage::class)->orderBy('sort_order');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(DonationCampaign::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($q2) use ($search) {
                $q2->where('name', 'like', '%'.$search.'%')
                   ->orWhere('address', 'like', '%'.$search.'%')
                   ->orWhere('area', 'like', '%'.$search.'%');
            });
        });
    }

    public function scopeSect($query, $sect)
    {
        return $query->when($sect, function ($q) use ($sect) {
            if (in_array($sect, ['Sunni', 'Shia'])) {
                $q->where('sect', $sect);
            }
        });
    }

    public function scopeArea($query, $area)
    {
        return $query->when($area, function ($q) use ($area) {
            $q->where('area', 'like', '%'.$area.'%');
        });
    }

    public function scopeWithFacility($query, $facilityId)
    {
        return $query->when($facilityId, function ($q) use ($facilityId) {
            $q->whereHas('facilities', function ($q2) use ($facilityId) {
                $q2->where('facilities.id', $facilityId);
            });
        });
    }

    public function scopeJumaAvailable($query, $available)
    {
        return $query->when($available, function ($q) {
            $q->whereNotNull('juma_time');
        });
    }

    public function scopePrayerAvailable($query, $available)
    {
        return $query->when($available, function ($q) {
            $q->whereNotNull('fajr')->whereNotNull('zuhr')->whereNotNull('asr')->whereNotNull('maghrib')->whereNotNull('isha');
        });
    }
}
