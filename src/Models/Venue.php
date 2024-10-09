<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    public $table = 'venues';
    protected $guarded = ['id'];
}
