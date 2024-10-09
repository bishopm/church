<?php

namespace Bishopm\Church\Filament\Clusters\Website\Resources\PostResource\Pages;

use Bishopm\Church\Filament\Clusters\Website\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
