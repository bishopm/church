<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agendaitem extends Model
{
    public $table = 'agendaitems';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function meetingtasks(): HasMany
    {
        return $this->hasMany(Meetingtask::class);
    }
}