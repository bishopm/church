<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Tags\HasTags;

class Prayer extends Model
{
    use HasTags;
    
    public $table = 'prayers';
    protected $guarded = ['id'];

    public function setitem(): MorphMany
    {
        return $this->morphMany(Setitem::class,'setitemable');
    }
}
