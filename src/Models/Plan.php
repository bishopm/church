<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{
    public $table = 'plans';
    protected $guarded = ['id'];
    protected $connection = 'methodist';

    public function person(): BelongsTo
    {
        return $this->belongsTo(Preacher::class);
    }
}
