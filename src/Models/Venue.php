<?php

namespace Bishopm\Church\Models;

use Guava\Calendar\Contracts\Resourceable;
use Guava\Calendar\ValueObjects\CalendarResource;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model implements Resourceable
{
    public $table = 'venues';
    protected $guarded = ['id'];

    public function toCalendarResource(): CalendarResource|array {
        return CalendarResource::make($this)
            ->title($this->venue);
    }
}
