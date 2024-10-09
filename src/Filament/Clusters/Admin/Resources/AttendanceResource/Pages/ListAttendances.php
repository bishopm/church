<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\AttendanceResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
