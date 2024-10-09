<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\RosterResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\RosterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRosters extends ListRecords
{
    protected static string $resource = RosterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
