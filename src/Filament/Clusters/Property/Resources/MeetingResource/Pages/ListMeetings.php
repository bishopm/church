<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\MeetingResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMeetings extends ListRecords
{
    protected static string $resource = MeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Calendar report')->url(fn (): string => route('reports.calendar')),
            Actions\CreateAction::make(),
        ];
    }
}
