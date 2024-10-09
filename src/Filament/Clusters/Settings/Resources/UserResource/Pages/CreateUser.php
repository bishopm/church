<?php

namespace Bishopm\Church\Filament\Clusters\Settings\Resources\UserResource\Pages;

use Bishopm\Church\Filament\Clusters\Settings\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
