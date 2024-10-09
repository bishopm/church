<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    public $table = 'persons';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->surname;
    }

    public function sermons(): HasMany
    {
        return $this->hasMany(Sermon::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
