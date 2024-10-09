<?php

namespace Bishopm\Church\Filament\Clusters;

use Filament\Clusters\Cluster;

class Website extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-c-globe-alt';

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    public static function canAccess(): bool 
    { 
        $user=auth()->user();
        if (($user->can('view-any Post')) or (($user->can('view-any Series'))) or (($user->can('view-any Sermon')))){
            return true;
        } else {
            return false;
        }
    }
}
