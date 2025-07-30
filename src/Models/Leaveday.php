<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leaveday extends Model
{
    public $table = 'leavedays';
    protected $guarded = ['id'];
    public $timestamps = false;
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
