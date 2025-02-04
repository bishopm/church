<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\SermonResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\SermonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSermon extends EditRecord
{
    protected static string $resource = SermonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
