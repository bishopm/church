<?php

namespace Bishopm\Church\Models;

use Bishopm\Church\Traits\Taggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Song extends Model
{
    use Taggable;

    public $table = 'songs';
    protected $guarded = ['id'];

    public function setitem(): MorphMany
    {
        return $this->morphMany(Setitem::class,'setitemable');
    }

    public function getLastusedAttribute() {
        $id=$this->id;
        $today=date('y-m-d');
        $set=Service::whereHas('setitems', function($q) use ($id) { $q->where('setitemable_id',$id)->where('setitemable_type','song'); })->where('servicedate','<',$today)->orderBy('servicedate','DESC')->first();
        if ($set){
            return Carbon::createFromTimeStamp(strtotime($set->servicedate))->diffForHumans();
        } else {
            return "";
        }
    }
}
