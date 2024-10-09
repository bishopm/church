<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\HouseholdResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\HouseholdResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHouseholds extends ListRecords
{
    protected static string $resource = HouseholdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
