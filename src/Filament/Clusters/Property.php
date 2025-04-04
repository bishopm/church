<?php

namespace Bishopm\Church\Filament\Clusters;

use Filament\Clusters\Cluster;

class Property extends Cluster
{
    protected static ?int $navigationSort = -8;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    public static function canAccess(): bool 
    { 
        $user=auth()->user();
        if (($user->can('view-any Maintenancetask')) or (($user->can('view-any Tenant'))) or (($user->can('view-any Venue')))){
            return true;
        } else {
            return false;
        }
    }
}
