<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    public $table = 'gifts';
    protected $guarded = ['id'];
}
