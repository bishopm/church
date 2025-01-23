<?php

namespace Bishopm\Church\Filament\Clusters\Website\Resources\PageResource\Pages;

use Bishopm\Church\Filament\Clusters\Website\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
