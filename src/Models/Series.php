<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Series extends Model
{
    public $table = 'series';
    protected $guarded = ['id'];

    public function sermons(): HasMany
    {
        return $this->hasMany(Sermon::class);
    }
}
