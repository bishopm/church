<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\ChordResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\ChordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChords extends ListRecords
{
    protected static string $resource = ChordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
