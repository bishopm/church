<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Anniversary;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class Birthdays extends Widget
{
    protected static string $view = 'church::widgets.birthdays';

    public ?array $memberdata;

    function mount() {
        $todaynum=date('w');
        $thisyr=date("Y");
        $mon=strval(date('m-d', strtotime("next Monday")));
        $tue=strval(date('m-d', strtotime("next Monday")+86400));
        $wed=strval(date('m-d', strtotime("next Monday")+172800));
        $thu=strval(date('m-d', strtotime("next Monday")+259200));
        $fri=strval(date('m-d', strtotime("next Monday")+345600));
        $sat=strval(date('m-d', strtotime("next Monday")+432000));
        $sun=strval(date('m-d', strtotime("next Monday")+518400));
        $msg="<b>Birthdays for next week: (starting " . $thisyr . "-" . $mon . ")</b><br>";
        $days=array($mon,$tue,$wed,$thu,$fri,$sat,$sun);
        $this->memberdata['today']=Individual::join('households', 'households.id', '=', 'individuals.household_id')->wherein(DB::raw('substr(birthdate, 6, 5)'), [date('m-d')])->whereNull('individuals.deleted_at')->select('individuals.firstname', 'individuals.surname', 'individuals.cellphone', 'households.homephone', 'households.householdcell', DB::raw('substr(birthdate, 6, 5) as bd'))->orderByRaw('bd')->get();
        $birthdays=Individual::join('households', 'households.id', '=', 'individuals.household_id')->wherein(DB::raw('substr(birthdate, 6, 5)'), $days)->whereNull('individuals.deleted_at')->select('individuals.firstname', 'individuals.surname', 'individuals.cellphone', 'households.homephone', 'households.householdcell', DB::raw('substr(birthdate, 6, 5) as bd'))->orderByRaw('bd')->get();
        $olddate="";
        foreach ($birthdays as $bday) {
            if ($olddate<>date("D d M", strtotime($thisyr . "-" . $bday->bd))){
                $msg=$msg . "<br><b>" . date("D d M", strtotime($thisyr . "-" . $bday->bd)) . "</b> " . $bday->firstname . " " . $bday->surname;
                $olddate=date("D d M", strtotime($thisyr . "-" . $bday->bd));
            } else {
                $msg=$msg . ", " . $bday->firstname . " " . $bday->surname;
            }
        }
        $this->memberdata['msg']=$msg;
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

    public function gethcell($id)
    {
        $indiv=Individual::find($id);
        if ($indiv) {
            return $indiv->firstname . " (" . $indiv->cellphone . ")";
        }
        return "Invalid individual";
    }
}
