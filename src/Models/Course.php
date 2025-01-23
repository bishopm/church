<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Course extends Model
{
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
}
