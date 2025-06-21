<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\CardResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\CardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCards extends ListRecords
{
    protected static string $resource = CardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
