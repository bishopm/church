<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\EmployeeResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
