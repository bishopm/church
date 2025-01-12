<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recurringtask extends Model
{
    public $table = 'recurringtasks';
    protected $guarded = ['id'];

    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }
}
