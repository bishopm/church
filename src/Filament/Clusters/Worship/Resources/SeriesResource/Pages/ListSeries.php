<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\SeriesResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\SeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeries extends ListRecords
{
    protected static string $resource = SeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Sermon series plan')->url(fn (): string => route('reports.seriesplan')),
            Actions\CreateAction::make(),
        ];
    }
}
