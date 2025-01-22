<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Pastor extends Model
{
    public $table = 'pastors';
    protected $guarded = ['id'];

    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }

    public function pastoralnotes(): HasMany
    {
        return $this->hasMany(Pastoralnote::class);
    }

    public function individuals(): MorphToMany
    {
        return $this->morphedByMany(Individual::class, 'pastorable');
    }

    public function households(): MorphToMany
    {
        return $this->morphedByMany(Household::class, 'pastorable');
    }
}
