<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastoralnoteResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\PastoralnoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPastoralnotes extends ListRecords
{
    protected static string $resource = PastoralnoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
