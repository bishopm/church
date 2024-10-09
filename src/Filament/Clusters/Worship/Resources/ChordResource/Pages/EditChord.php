<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\ChordResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\ChordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChord extends EditRecord
{
    protected static string $resource = ChordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
