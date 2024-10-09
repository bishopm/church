<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;

class Devotional extends Model
{
    public $table = 'devotionals';
    protected $guarded = ['id'];
}
