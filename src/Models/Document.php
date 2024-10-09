<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $table = 'documents';
    protected $guarded = ['id'];
}
