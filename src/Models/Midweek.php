<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Midweek extends Model
{
    public $table = 'midweeks';
    protected $guarded = ['id'];
    protected $connection = 'methodist';
    public $timestamps = false;

}
