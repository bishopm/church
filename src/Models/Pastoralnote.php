<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Pastoralnote extends Model
{
    public $table = 'pastoralnotes';
    protected $guarded = ['id'];

    public function pastoralnotable(): MorphTo
    {
        return $this->morphTo();
    }

    public function pastor(): BelongsTo
    {
        return $this->belongsTo(Pastor::class);
    }

    public function getNameAttribute()
    {
        if ($this->pastoralnotable_type=="individual"){
            return $this->pastoralnotable->surname;
        } elseif ($this->pastoralnotable_type=="household"){
            return $this->pastoralnotable->addressee;
        } else {
            return "Pastoral case";
        }
    }
}
