<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingtaskResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingtaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMeetingtasks extends ListRecords
{
    protected static string $resource = MeetingtaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
