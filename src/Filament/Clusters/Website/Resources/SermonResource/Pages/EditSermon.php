<?php

namespace Bishopm\Church\Filament\Clusters\Website\Resources\SermonResource\Pages;

use Bishopm\Church\Filament\Clusters\Website\Resources\SermonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSermon extends EditRecord
{
    protected static string $resource = SermonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Sermon series')->url(fn (): string => route('filament.admin.website.resources.series.index')),
            Actions\DeleteAction::make(),
        ];
    }
}
