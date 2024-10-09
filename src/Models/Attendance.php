<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    public $table = 'attendances';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }

}