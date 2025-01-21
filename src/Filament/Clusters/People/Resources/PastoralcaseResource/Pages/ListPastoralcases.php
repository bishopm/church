<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastoralcaseResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\PastoralcaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPastoralcases extends ListRecords
{
    protected static string $resource = PastoralcaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
