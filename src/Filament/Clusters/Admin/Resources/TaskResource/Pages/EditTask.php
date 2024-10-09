<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
