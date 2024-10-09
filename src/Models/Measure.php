<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    public $table = 'measures';
    protected $guarded = ['id'];
    public $timestamps = false;

}
