<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    public $table = 'services';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function setitems(): HasMany
    {
        return $this->hasMany(Setitem::class);
    }
}
