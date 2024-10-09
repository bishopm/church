<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\RosterResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\RosterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoster extends EditRecord
{
    protected static string $resource = RosterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
