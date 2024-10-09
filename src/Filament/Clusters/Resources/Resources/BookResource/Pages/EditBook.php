<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\BookResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBook extends EditRecord
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
