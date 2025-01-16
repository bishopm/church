<?php

namespace Bishopm\Church\Models;

use Guava\Calendar\Contracts\Resourceable;
use Guava\Calendar\ValueObjects\Resource;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model implements Resourceable
{
    public $table = 'venues';
    protected $guarded = ['id'];

    public function toResource(): Resource|array {
        return Resource::make($this)
            ->title($this->venue);
    }
}
