<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\AttendanceResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\AttendanceResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    protected static bool $canCreateAnother = false;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()->extraAttributes(['type' => 'button', 'wire:click' => 'create']);
    }
}
