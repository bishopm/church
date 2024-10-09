<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\BookResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;
}
