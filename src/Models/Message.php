<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    public $table = 'messages';
    protected $guarded = ['id'];
    public $timestamps = false;
 
    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }

}
