<?php

namespace Bishopm\Church\Models;

use Bishopm\Church\Traits\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Course extends Model
{
    use Taggable;

    public $table = 'courses';
    protected $guarded = ['id'];

    public function diaryentries(): MorphMany
    {
        return $this->morphMany(Diaryentry::class,'diarisable');
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function coursesessions(): HasMany
    {
        return $this->hasMany(Coursesession::class);
    }
}
