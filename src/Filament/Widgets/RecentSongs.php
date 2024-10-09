<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Household;
use Bishopm\Church\Models\Song;
use Filament\Widgets\Widget;

class RecentSongs extends Widget
{
    protected static string $view = 'church::widgets.recent-songs';

    public ?array $widgetdata;

    function mount() {
        $this->widgetdata['songs']=Song::orderBy('created_at','DESC')->take(5)->get();
    }

    public static function canView(): bool 
    { 
        $roles =auth()->user()->roles->toArray(); 
        $permitted = array('Office','Finance','Worship');
        foreach ($roles as $role){
            if ((in_array($role['name'],$permitted)) or (auth()->user()->isSuperAdmin())){
                return true;
            }
        }
        return false;
    }
}
