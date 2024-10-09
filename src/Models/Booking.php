<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\Event;


class Booking extends Model implements Eventable
{
    public $table = 'bookings';
    protected $guarded = ['id'];

    public function toEvent(): Event|array {
        if (isset($this->tenant->tenant)){
            $this->title = $this->tenant->tenant;
        } else {
            $this->title="Title";
        }
        return Event::make($this)
            ->title($this->title)
            ->start($this->bookingdate)
            ->end(date('Y-m-d',strtotime($this->bookingdate)) . " " . $this->endtime)
            ->extendedProp('notes', $this->notes);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
