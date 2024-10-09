<?php

namespace Bishopm\Church\Filament\Clusters;

use Filament\Clusters\Cluster;

class Worship extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-c-musical-note';

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    public static function canAccess(): bool 
    { 
        $user=auth()->user();
        if (($user->can('view-any Song')) or (($user->can('view-any Service'))) or (($user->can('view-any Prayer'))) or (($user->can('view-any Chord')))){
            return true;
        } else {
            return false;
        }
    }
}
