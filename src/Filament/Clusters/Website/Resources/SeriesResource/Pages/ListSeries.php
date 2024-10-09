<?php

namespace Bishopm\Church\Filament\Clusters\Website\Resources\SeriesResource\Pages;

use Bishopm\Church\Filament\Clusters\Website\Resources\SeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeries extends ListRecords
{
    protected static string $resource = SeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
