<?php

namespace Bishopm\Church\Console\Commands;

use Bishopm\Church\Mail\ChurchMail;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Pastor;
use Bishopm\Church\Models\Pastorable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class PastorsEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:pastorsemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a fortnightly email to pastoral carers outlining their cases and recent contact';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Fornightly email to pastors and weekly email to co-ordinators
        $data=array();
        $pastors = Pastor::with('individual','individuals.pastoralnotes','households.pastoralnotes')->where('active',1)->get();
        $casemessage="Thank you for serving on our pastoral care co-ordination team! Here is the latest update on pastoral care cases:<br><br>";
        $casemessage.="<table class=\"table table-bordered\">";
        $casemessage.="<tr><th>Name</th><th>Details</th><th>Pastor</th><th>Most recent contact</th></tr>";
        foreach ($pastors as $pastor){
            $message="Thank you so much for serving on our pastoral care team! Here is your fortnightly update on your pastoral care cases (please let us know via the office if any details need to be corrected):<br><br>";
            $message.="<table class=\"table table-bordered\">";
            $message.="<tr><th>Name</th><th>Most recent contact</th></tr>";
            $pastoremail=$pastor->individual->email;
            foreach ($pastor->individuals as $individual){
                $lastcontact=null;
                if ($individual->pastoralnotes->count()>0){
                    $lastnote=$individual->pastoralnotes->sortByDesc('pastoraldate')->first();
                    $lastcontact=$lastnote->details . " (" . $lastnote->created_at->toDateString() . " - " . $lastnote->pastor->individual->firstname . ")";
                }
                $message.= "<tr><td>" . $individual->firstname . " " . $individual->surname . "</td><td>" . $lastcontact . "</td></tr>";
                $details=Pastorable::where('pastor_id',$pastor->id)->where('pastorable_type','individual')->where('pastorable_id',$individual->id)->first();
                if (($details) and ($details->active)){
                    $casemessage.="<tr><td>" . $individual->firstname . " " . $individual->surname . "</td><td>" . $details->details . "</td><td>" . $pastor->individual->firstname . " " . $pastor->individual->surname . "</td><td>" . $lastcontact . "</td></tr>";
                }
            }
            foreach ($pastor->households as $household){
                $lastcontact=null;
                if ($household->pastoralnotes->count()>0){
                    $lastnote=$household->pastoralnotes->sortByDesc('pastoraldate')->first();
                    $lastcontact=$lastnote->details . " (" . $lastnote->created_at->toDateString() . " - " . $lastnote->pastor->individual->firstname . ")";
                }
                $message.= "<tr><td>" . $household->addressee . "</td><td>" . $lastcontact . "</td></tr>";
                $details=Pastorable::where('pastor_id',$pastor->id)->where('pastorable_type','household')->where('pastorable_id',$household->id)->first();
                if (($details) and ($details->active)){
                    $casemessage.="<tr><td>" . $household->addressee . "</td><td>" . $details->details . "</td><td>" . $pastor->individual->firstname . " " . $pastor->individual->surname . "</td><td>" . $lastcontact . "</td></tr>";
                }
            }
            $message.="</table>";
            $data['firstname']=$pastor->individual->firstname;
            $data['body']=$message;
            $data['subject']="Pastoral Care Update";
            if (date('W') % 2){
                Mail::to($pastoremail)->queue(new ChurchMail($data));
            }
        }
        $casemessage.="</table>";
        $setting=intval(setting('automation.pastoral_coordinators_group'));
        $churchname=setting('general.church_name');
        $churchemail=setting('email.church_email');
        $group=Group::with('individuals')->where('id',$setting)->first();
        foreach ($group->individuals as $recip) {
            $data=array();
            $data['firstname']=$recip->firstname;
            $data['subject']="Pastoral Care co-ordination: " . $churchname;
            $data['url']="https://westvillemethodist.co.za";
            $data['body']=$casemessage;
            $data['email']=$recip->email;
            Mail::to($data['email'])->queue(new ChurchMail($data));
        }
    }
}
