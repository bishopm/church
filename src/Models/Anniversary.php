<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anniversary extends Model
{
    public $table = 'anniversaries';
    protected $guarded = ['id'];

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }
}
