<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\AgendaitemResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\AgendaitemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgendaitems extends ListRecords
{
    protected static string $resource = AgendaitemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
