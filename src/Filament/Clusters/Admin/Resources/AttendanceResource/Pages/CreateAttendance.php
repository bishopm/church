<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\AttendanceResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    protected static bool $canCreateAnother = false;
}
