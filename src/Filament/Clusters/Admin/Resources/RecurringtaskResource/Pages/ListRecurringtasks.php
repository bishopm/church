<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\RecurringtaskResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\RecurringtaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecurringtasks extends ListRecords
{
    protected static string $resource = RecurringtaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
