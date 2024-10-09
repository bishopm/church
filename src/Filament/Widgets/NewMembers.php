<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Individual;
use Filament\Widgets\Widget;

class NewMembers extends Widget
{
    protected static string $view = 'church::widgets.new-members';

    public ?array $memberdata;

    function mount() {
        $this->memberdata['individuals']=Individual::orderBy('created_at','DESC')->take(5)->get();
    }

    public static function canView(): bool 
    { 
        $roles =auth()->user()->roles->toArray(); 
        $permitted = array('Office','Finance');
        foreach ($roles as $role){
            if ((in_array($role['name'],$permitted)) or (auth()->user()->isSuperAdmin())){
                return true;
            }
        }
        return false;
    }
}
