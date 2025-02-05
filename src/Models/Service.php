<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Tags\HasTags;

class Service extends Model
{
    use HasTags;

    public $table = 'services';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function setitems(): HasMany
    {
        return $this->hasMany(Setitem::class);
    }
}
