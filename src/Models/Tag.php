<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $table = 'tags';
    protected $guarded = ['id'];

    public static function unslug($slug){
        return ucwords(str_replace('-', ' ', $slug));
    }
}
