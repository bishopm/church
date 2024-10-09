<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PersonResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\PersonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePerson extends CreateRecord
{
    protected static string $resource = PersonResource::class;
}
