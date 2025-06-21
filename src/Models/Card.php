<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    public $table = 'cards';
    protected $guarded = ['id'];
    public $timestamps = false;
    
    public function carditems(): HasMany
    {
        return $this->hasMany(Carditem::class);
    }
}
