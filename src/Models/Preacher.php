<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Preacher extends Model
{
    public $table = 'persons';
    protected $guarded = ['id'];
    protected $connection = 'methodist';

    public function planns(): HasMany
    {
        return $this->hasMany(Plan::class);
    }
}
