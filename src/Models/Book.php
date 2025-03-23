<?php

namespace Bishopm\Church\Models;

use Bishopm\Church\Traits\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Book extends Model
{
    use Taggable;

    public $table = 'books';
    protected $guarded = ['id'];
    protected $casts = ['authors'=>'array'];
    
    public function getAllauthorsAttribute() {
        $authornames="";
        if ($this->authors){
            foreach ($this->authors as $arr){
                $authornames.=$arr['name'].", ";
            }
            return substr($authornames,0,-2);
        }
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function getStatusAttribute() {
        $status=Loan::with('individual')->where('book_id',$this->id)->orderBy('duedate','DESC')->first();
        if ($status){
            return "On loan to " . $status->individual->fullname . " until " . $status->duedate;
        } else {
            return "Available";
        }
    }
}
