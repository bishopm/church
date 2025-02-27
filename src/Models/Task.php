<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Tags\HasTags;

class Task extends Model
{
    use HasTags;

    public $table = 'tasks';
    protected $guarded = ['id'];

    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }
}
