<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\StatisticResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\StatisticResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListStatistics extends ListRecords
{
    protected static string $resource = StatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Scan individual attendances')
            ->url(fn (): string => route('filament.admin.admin.resources.attendances.index')),
            Actions\CreateAction::make()->label('Add service attendance record')
        ];
    }
}
