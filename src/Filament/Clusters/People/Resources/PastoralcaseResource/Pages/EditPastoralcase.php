<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastoralcaseResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\PastoralcaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPastoralcase extends EditRecord
{
    protected static string $resource = PastoralcaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
