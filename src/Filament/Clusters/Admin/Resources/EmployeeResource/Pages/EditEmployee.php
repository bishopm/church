<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\EmployeeResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
