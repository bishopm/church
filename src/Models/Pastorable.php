<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Pastorable extends Model
{
    public $table = 'pastorables';
    protected $guarded = ['id'];

    public function individuals(): MorphToMany
    {
        return $this->morphedByMany(Individual::class, 'pastorable');
    }

    public function households(): MorphToMany
    {
        return $this->morphedByMany(Household::class, 'pastorable');
    }
}
