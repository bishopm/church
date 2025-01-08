<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\IndividualResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\IndividualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndividuals extends ListRecords
{
    protected static string $resource = IndividualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('New member barcodes')->url(fn (): string => route('reports.barcodes',['newonly'=>'new'])),
            Actions\Action::make('All member barcodes')->url(fn (): string => route('reports.barcodes')),
            Actions\CreateAction::make(),
        ];
    }
}
