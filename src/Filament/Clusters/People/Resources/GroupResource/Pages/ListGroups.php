<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\GroupResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\GroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGroups extends ListRecords
{
    protected static string $resource = GroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
