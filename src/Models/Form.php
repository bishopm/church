<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    public $table = 'forms';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function formitems(): HasMany
    {
        return $this->hasMany(Formitem::class);
    }
}
