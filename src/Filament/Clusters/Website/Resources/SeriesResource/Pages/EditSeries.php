<?php

namespace Bishopm\Church\Filament\Clusters\Website\Resources\SeriesResource\Pages;

use Bishopm\Church\Filament\Clusters\Website\Resources\SeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeries extends EditRecord
{
    protected static string $resource = SeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
