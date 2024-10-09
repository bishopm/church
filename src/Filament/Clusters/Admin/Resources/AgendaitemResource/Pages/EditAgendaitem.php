<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\AgendaitemResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\AgendaitemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgendaitem extends EditRecord
{
    protected static string $resource = AgendaitemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
