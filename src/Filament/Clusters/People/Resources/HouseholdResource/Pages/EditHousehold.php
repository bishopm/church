<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\HouseholdResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\HouseholdResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHousehold extends EditRecord
{
    protected static string $resource = HouseholdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
