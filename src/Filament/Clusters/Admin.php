<?php

namespace Bishopm\Church\Filament\Clusters;

use Filament\Clusters\Cluster;

class Admin extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    public static function canAccess(): bool 
    { 
        $user=auth()->user();
        if (($user->can('view-any Statistic')) or (($user->can('view-any Gift'))) or (($user->can('view-any Meeting'))) or (($user->can('view-any Task'))) or (($user->can('view-any Employee')))){
            return true;
        } else {
            return false;
        }
    }
}
