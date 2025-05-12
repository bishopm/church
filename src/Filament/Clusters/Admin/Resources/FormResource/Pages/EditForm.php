<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditForm extends EditRecord
{
    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
