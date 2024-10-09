<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Roster extends Model
{
    public $table = 'rosters';
    protected $guarded = ['id'];

    public function rostergroups(): HasMany
    {
        return $this->hasMany(Rostergroup::class);
    }
}
