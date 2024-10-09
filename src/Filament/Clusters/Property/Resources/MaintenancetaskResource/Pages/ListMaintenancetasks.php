<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\MaintenancetaskResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\MaintenancetaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaintenancetasks extends ListRecords
{
    protected static string $resource = MaintenancetaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
