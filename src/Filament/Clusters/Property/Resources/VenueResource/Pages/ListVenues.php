<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\VenueResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\VenueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVenues extends ListRecords
{
    protected static string $resource = VenueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Venue users')->url(fn (): string => route('filament.admin.property.resources.tenants.index')),
            Actions\CreateAction::make()
        ];
    }

    protected function getFooterWidgets(): array {
        return [
            \Bishopm\Church\Filament\Widgets\ChurchVenuesWidget::class,
        ];
    }
}
