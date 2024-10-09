<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\TenantResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
