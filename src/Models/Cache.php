<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    public $table = 'caches';
    protected $guarded = ['id'];
}
