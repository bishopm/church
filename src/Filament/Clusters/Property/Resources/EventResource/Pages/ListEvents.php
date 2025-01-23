<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\EventResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Calendar report')->url(fn (): string => route('reports.calendar')),
            Actions\CreateAction::make(),
        ];
    }
}
