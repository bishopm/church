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
        $msg="<b>Birthdays for the week: (starting " . $thisyr . "-" . $mon . ")</b><br><br>";
        $days=array($mon,$tue,$wed,$thu,$fri,$sat,$sun);
        $birthdays=Individual::join('households', 'households.id', '=', 'individuals.household_id')->wherein(DB::raw('substr(birthdate, 6, 5)'), $days)->whereNull('individuals.deleted_at')->select('individuals.firstname', 'individuals.surname', 'individuals.cellphone', 'households.homephone', 'households.householdcell', DB::raw('substr(birthdate, 6, 5) as bd'))->orderByRaw('bd')->get();
        foreach ($birthdays as $bday) {
            $msg=$msg . "<b>" . date("D d M", strtotime($thisyr . "-" . $bday->bd)) . "</b> " . $bday->firstname . " " . $bday->surname . ":";
            if ($bday->cellphone) {
                $msg=$msg . " Cellphone: " . $bday->cellphone;
            }
            if ($bday->homephone) {
                $msg=$msg . " Homephone: " . $bday->homephone;
            }
            if (($bday->householdcell) and ($bday->householdcell<>$bday->id)) {
                $msg=$msg . " Household cellphone: " . self::gethcell($bday->householdcell);
            }
            $msg=$msg . "<br>";
        }
        $anniversaries=Anniversary::join('households', 'households.id', '=', 'anniversaries.household_id')->select('homephone', 'householdcell', 'addressee', 'household_id', 'anniversarytype', 'details', DB::raw('substr(anniversarydate, 6, 5) as ad'))->wherein(DB::raw('substr(anniversarydate, 6, 5)'), $days)->orderBy(DB::raw('substr(anniversarydate, 6, 5)'))->get();
        $msg = $msg . "<br>" . "<b>Anniversaries</b>" . "<br><br>";
        foreach ($anniversaries as $ann) {
            $msg=$msg . date("D d M", strtotime($thisyr . "-" . $ann->ad)) . " (" . $ann->addressee . ". " . ucfirst($ann->anntype) . ": " . $ann->details. ")";
            if ($ann->homephone) {
                $msg=$msg . " Homephone: " . $ann->homephone;
            }
            if ($ann->householdcell) {
                $msg=$msg . " Household cellphone: " . self::gethcell($ann->householdcell);
            }
            $msg=$msg. "<br>";
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
