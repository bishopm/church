<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMeetings extends ListRecords
{
    protected static string $resource = MeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
