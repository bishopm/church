<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\MeetingResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMeeting extends EditRecord
{
    protected static string $resource = MeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Minutes')->url(fn (): string => route('reports.minutes', ['id' => $this->record])),
            Actions\Action::make('A4 agenda')->url(fn (): string => route('reports.a4meeting', ['id' => $this->record])),
            Actions\Action::make('A5 agenda')->url(fn (): string => route('reports.a5meeting', ['id' => $this->record])),
            Actions\DeleteAction::make(),
        ];
    }
}
