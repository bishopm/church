<?php

namespace Bishopm\Church\Filament\Clusters;

use Filament\Clusters\Cluster;

class Resources extends Cluster
{
    protected static ?int $navigationSort = -6;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    public static function canAccess(): bool 
    { 
        $user=auth()->user();
        if (($user->can('view-any Book')) or (($user->can('view-any Devotional'))) or (($user->can('view-any Document')))){
            return true;
        } else {
            return false;
        }
    }
}
