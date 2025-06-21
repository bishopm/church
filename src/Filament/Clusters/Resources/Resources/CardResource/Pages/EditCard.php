<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\CardResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\CardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCard extends EditRecord
{
    protected static string $resource = CardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
