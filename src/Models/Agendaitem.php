<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;

class Agendaitem extends Model
{
    public $table = 'agendaitems';
    protected $guarded = ['id'];
    public $timestamps = false;
}