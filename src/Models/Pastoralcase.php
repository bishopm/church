<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pastoralcase extends Model
{
    public $table = 'pastors';
    protected $guarded = ['id'];

    public function pastor(): BelongsTo
    {
        return $this->belongsTo(Pastor::class);
    }
}
