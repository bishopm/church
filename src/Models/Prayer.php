<?php

namespace Bishopm\Church\Models;

use Bishopm\Church\Traits\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Prayer extends Model
{
    use Taggable;
    
    public $table = 'prayers';
    protected $guarded = ['id'];

    public function setitem(): MorphMany
    {
        return $this->morphMany(Setitem::class,'setitemable');
    }
}
