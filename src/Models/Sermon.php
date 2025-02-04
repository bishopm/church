<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Tags\HasTags;

class Sermon extends Model
{
    use HasTags;

    public $table = 'sermons';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }


}
