<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\GiftResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\GiftResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGift extends EditRecord
{
    protected static string $resource = GiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
