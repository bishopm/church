<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Pastoralnote;
use Filament\Widgets\Widget;

class PastoralContact extends Widget
{
    protected static string $view = 'church::widgets.pastoral-contact';

    public ?array $pastoraldata;

    function mount() {
        $this->pastoraldata['notes']=Pastoralnote::with('pastor.individual')->where('pastoralnotable_type','household')->orderBy('pastoraldate','DESC')->take(5)->get();
    }

    public static function canView(): bool 
    { 
        $roles =auth()->user()->roles->toArray(); 
        $permitted = array('Office','Finance','Pastoral');
        foreach ($roles as $role){
            if ((in_array($role['name'],$permitted)) or (auth()->user()->isSuperAdmin())){
                return true;
            }
        }
        return false;
    }
}
