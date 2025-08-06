<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingtaskResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingtaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMeetingtask extends EditRecord
{
    protected static string $resource = MeetingtaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
