<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    public $table = 'meetings';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function agendaitems(): HasMany
    {
        return $this->hasMany(Agendaitem::class);
    }
}
