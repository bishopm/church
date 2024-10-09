<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\TenantResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
