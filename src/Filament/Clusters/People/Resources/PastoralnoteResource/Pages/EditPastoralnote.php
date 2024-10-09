<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastoralnoteResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\PastoralnoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPastoralnote extends EditRecord
{
    protected static string $resource = PastoralnoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
