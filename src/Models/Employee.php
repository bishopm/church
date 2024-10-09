<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    public $table = 'employees';
    protected $guarded = ['id'];

    public function leavedays(): HasMany
    {
        return $this->hasMany(Leaveday::class);
    }
}
