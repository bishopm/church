<?php

namespace Bishopm\Church\Filament\Clusters\Reports\Resources\ReportResource\Pages;

use Bishopm\Church\Filament\Clusters\Reports\Resources\ReportResource;
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
