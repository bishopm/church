<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\EventResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\EventResource;
use Bishopm\Church\Models\Diaryentry;
use Filament\Resources\Pages\CreateRecord;
use Spatie\GoogleCalendar\Event;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function beforeCreate(): void
    {
        $day=substr($this->data['eventdate'],0,10);
        $time=substr($this->data['eventdate'],11);
        $event = new Event;
        $event->name = $this->data['event'];
        $event->description = "group_id:0";
        $event->startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i',$day . " " . $time)->setTimezone('UTC');
        $event->endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i',$day . " " . $this->data['endtime'])->setTimezone('UTC');
        $event->save();
    }

    protected function afterCreate(): void
    {
        Diaryentry::create([
            'diarisable_type' => 'tenant',
            'diarisable_id' => setting('admin.church_tenant'),
            'venue_id' => $this->record->venue_id,
            'details' => $this->record->event,
            'diarydatetime' => $this->record->eventdate,
            'endtime' => $this->record->endtime
        ]);
    }
}
