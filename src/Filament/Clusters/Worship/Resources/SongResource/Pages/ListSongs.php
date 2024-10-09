<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\SongResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\SongResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSongs extends ListRecords
{
    protected static string $resource = SongResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
