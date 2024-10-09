<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\AttendanceResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
