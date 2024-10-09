<?php

namespace Bishopm\Church\Filament\Clusters;

use Filament\Clusters\Cluster;

class People extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    public static function canAccess(): bool 
    { 
        $user=auth()->user();
        if (($user->can('view-any Group')) or (($user->can('view-any Household'))) or (($user->can('view-any Individual'))) or (($user->can('view-any Pastoralnote'))) or (($user->can('view-any Pastor'))) or (($user->can('view-any Person'))) or (($user->can('view-any Roster')))){
            return true;
        } else {
            return false;
        }
    }

}
