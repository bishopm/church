<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\EmployeeResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
