<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rosteritem extends Model
{
    public $table = 'rosteritems';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function rostergroup(): BelongsTo
    {
        return $this->belongsTo(Rostergroup::class);
    }

    public function individuals(): BelongsToMany
    {
        return $this->belongsToMany(Individual::class,'individual_rosteritem');
    }

}
