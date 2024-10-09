<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Group extends Model
{
    protected $dates = ['deleted_at'];
    public $table = 'groups';
    protected $guarded = ['id'];

    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }

    public function individuals(): BelongsToMany
    {
        return $this->belongsToMany(Individual::class);
    }

    public function diaryentries(): MorphMany
    {
        return $this->morphMany(Diaryentry::class,'diarisable');
    }

    public function rostergroups(): HasMany
    {
        return $this->hasMany(Rostergroup::class);
    }
}
