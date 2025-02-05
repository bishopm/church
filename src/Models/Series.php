<?php

namespace Bishopm\Church\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Series extends Model
{
    public $table = 'series';
    protected $guarded = ['id'];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
