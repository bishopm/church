<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Project extends Model
{
    use HasTags;

    public $table = 'projects';
    protected $guarded = ['id'];
}
