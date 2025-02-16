<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\CourseResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\CourseResource;
use Bishopm\Church\Models\Diaryentry;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

    protected function afterCreate(): void
    {
        Diaryentry::create([
            'diarisable_type' => 'tenant',
            'diarisable_id' => setting('admin.church_tenant'),
            'venue_id' => $this->record->venue_id,
            'details' => $this->record->course,
            'diarydatetime' => $this->record->coursedate,
            'endtime' => $this->record->endtime
        ]);
    }
}
