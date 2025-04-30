<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Groupmember extends Model
{
    public $table = 'group_individual';
    protected $guarded = ['id'];
    protected $casts = [
        'categories' => 'json'
    ];

    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }

    public function group(): BelongsTo
    {
        return $this->BelongsTo(Group::class);
    }

}
