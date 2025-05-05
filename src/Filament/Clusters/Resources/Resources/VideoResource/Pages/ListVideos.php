<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\VideoResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideos extends ListRecords
{
    protected static string $resource = VideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
