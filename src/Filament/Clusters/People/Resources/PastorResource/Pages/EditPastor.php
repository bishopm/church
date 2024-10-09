<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastorResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\PastorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPastor extends EditRecord
{
    protected static string $resource = PastorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
