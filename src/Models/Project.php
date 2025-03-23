<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Bishopm\Church\Traits\Taggable;

class Project extends Model
{
    use Taggable;

    public $table = 'projects';
    protected $guarded = ['id'];
}
