<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\RecurringtaskResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\RecurringtaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecurringtask extends EditRecord
{
    protected static string $resource = RecurringtaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
