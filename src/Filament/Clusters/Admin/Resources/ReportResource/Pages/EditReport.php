<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\ReportResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
