<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\ServiceResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
