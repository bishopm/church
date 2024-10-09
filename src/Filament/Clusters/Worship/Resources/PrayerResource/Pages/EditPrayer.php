<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\PrayerResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\PrayerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrayer extends EditRecord
{
    protected static string $resource = PrayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
