<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rostergroup extends Model
{
    public $table = 'rostergroups';
    protected $guarded = ['id'];
    protected $casts = [
        'editable' => 'boolean',
        'extrainfo' => 'boolean'
    ];

    public function roster(): BelongsTo
    {
        return $this->belongsTo(Roster::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function rosteritems(): HasMany
    {
        return $this->hasMany(Rosteritem::class);
    }
}
