<?php

namespace Bishopm\Church\Models;

use Bishopm\Church\Traits\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use Taggable;
    
    public $table = 'videos';
    protected $guarded = ['id'];
    protected $casts = [
        'videos' => 'array'
    ];
}
