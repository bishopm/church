<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\DevotionalResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\DevotionalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDevotionals extends ListRecords
{
    protected static string $resource = DevotionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
