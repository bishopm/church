<?php

namespace Bishopm\Church\Console\Commands;

use Bishopm\Church\Mail\ChurchMail;
use Bishopm\Church\Models\Anniversary;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BirthdayEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:birthdayemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a weekly email listing birthdays and anniversaries';

    public function gethcell($id)
    {
        $indiv=Individual::find($id);
        if ($indiv) {
            return $indiv->firstname . " (" . $indiv->cellphone . ")";
        }
        return "Invalid individual";
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
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
            $msg=$msg . "*" . date("D d M", strtotime($thisyr . "-" . $bday->bd)) . "* **" . $bday->firstname . " " . $bday->surname . ":**";
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
        // Send to birthday group
        $setting=intval(setting('birthday_group'));
        $churchname=setting('general.church_name');
        $churchemail=setting('email.church_email');
        $group=Group::with('groupmembers.individual')->find($setting);
        foreach ($group->individuals as $recip) {
            $data=array();
            $data['recipient']=$recip->individual->firstname;
            $data['subject']="Birthdays / Anniversaries: " . $churchname;
            $data['sender']=$churchemail;
            $data['body']=$msg;
            $data['email']=$recip->individual->email;
            if ($data['email']=="michael@westvillemethodist.co.za"){
                Mail::to($data['email'])->queue(new ChurchMail($data));
            }
        }
    }
}
