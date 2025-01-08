<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $table = 'files';
    protected $guarded = ['id'];
}
