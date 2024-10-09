<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\PrayerResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\PrayerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrayers extends ListRecords
{
    protected static string $resource = PrayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
