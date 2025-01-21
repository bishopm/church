<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Pastoralcase extends Model
{
    public $table = 'pastoralcases';
    protected $guarded = ['id'];

    public function pastor(): BelongsTo
    {
        return $this->belongsTo(Pastor::class);
    }

    public function pastorable(): MorphTo
    {
        return $this->morphTo();
    }
}
