<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\FileResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\FileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFile extends EditRecord
{
    protected static string $resource = FileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
