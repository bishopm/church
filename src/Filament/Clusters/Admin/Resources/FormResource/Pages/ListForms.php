<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListForms extends ListRecords
{
    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
