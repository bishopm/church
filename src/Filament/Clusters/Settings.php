<?php

namespace Bishopm\Church\Filament\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?int $navigationSort = -5;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

}
