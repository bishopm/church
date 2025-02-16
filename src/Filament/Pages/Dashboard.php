<?php
 
namespace Bishopm\Church\Filament\Pages;

use Filament\Pages\Dashboard as PagesDashboard;

class Dashboard extends PagesDashboard
{
    protected static string $view = 'church::dashboard';

    protected static ?int $navigationSort = -11;

    protected static ?string $title = 'WMC';
}