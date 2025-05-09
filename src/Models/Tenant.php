<?php

namespace Bishopm\Church\Models;

use Bishopm\Church\Traits\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tenant extends Model
{
    use Taggable;

    public $table = 'tenants';
    protected $guarded = ['id'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function diaryentries(): MorphMany
    {
        return $this->morphMany(Diaryentry::class,'diarisable');
    }

    public function getContactAttribute()
    {
        return $this->contact_firstname . ' ' . $this->contact_surname;
    }
}
