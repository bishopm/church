<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\DevotionalResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\DevotionalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDevotional extends EditRecord
{
    protected static string $resource = DevotionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
