<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\VenueResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\VenueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVenue extends EditRecord
{
    protected static string $resource = VenueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
