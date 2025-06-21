<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Carditem extends Model
{
    public $table = 'carditems';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'properties' => 'json'
    ];
    
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
