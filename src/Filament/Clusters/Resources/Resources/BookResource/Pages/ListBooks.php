<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\BookResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
