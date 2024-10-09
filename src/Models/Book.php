<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Book extends Model
{
    use HasTags;

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
}
