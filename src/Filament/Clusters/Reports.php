<?php

namespace Bishopm\Church\Filament\Clusters;

use Filament\Clusters\Cluster;

class Reports extends Cluster
{
    protected static ?int $navigationSort = -7;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    public static function canAccess(): bool 
    { 
        $user=auth()->user();
        if ($user->can('view-any Individual')){
            return true;
        } else {
            return false;
        }
    }
}
