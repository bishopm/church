<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\EventResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\EventResource;
use Bishopm\Church\Models\Diaryentry;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

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
