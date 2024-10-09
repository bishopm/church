<?php

namespace Bishopm\Church\Filament\Clusters\Website\Resources\ProjectResource\Pages;

use Bishopm\Church\Filament\Clusters\Website\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
