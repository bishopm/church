<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Bishopm\Church\Traits\Taggable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use Taggable;

    public $table = 'projects';
    protected $guarded = ['id'];

    public function diaryentries(): MorphMany
    {
        return $this->morphMany(Diaryentry::class,'diarisable');
    }
}
