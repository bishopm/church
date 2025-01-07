<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Individual;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today=date('m-d');
        $birthdays = Individual::where(DB::raw("SUBSTR(birthdate, 6, 5)"),$today)->get();
        $bdays = array();
        foreach ($birthdays as $ind){
            $bdays[]=$ind->firstname . " " . $ind->surname;
        }
        $bd=implode(", ",$bdays);
        return [
            Stat::make('Birthdays today', $bd)->label('')
                ->color('success')
                ->description('Upcoming birthdays')
                ->descriptionIcon('heroicon-o-cake')
        ];
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
