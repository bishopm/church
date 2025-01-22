<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Household extends Model
{
    protected $dates = ['deleted_at'];
    public $table = 'households';
    protected $guarded = ['id'];

    public function individuals(): HasMany
    {
        return $this->hasMany(Individual::class);
    }

    public function anniversaries(): HasMany
    {
        return $this->hasMany(Anniversary::class);
    }

    public function pastoralnotes(): MorphMany
    {
        return $this->morphMany(Pastoralnote::class,'pastoralnotable');
    }

    public function pastors(): MorphToMany
    {
        return $this->morphToMany(Pastor::class,'pastorable');
    }

    public function getNameAttribute()
    {
        return $this->addressee;
    }
}
