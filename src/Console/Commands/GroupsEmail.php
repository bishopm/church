<?php

namespace Bishopm\Church\Console\Commands;

use Bishopm\Church\Mail\ChurchMail;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GroupsEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:groupsemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a monthly email to home group leaders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $groups=Group::withWhereHas('individual')->where('grouptype','fellowship')->where('publish',1)->get();
        $days = [0 => 'Sunday',1 => 'Monday',2 => 'Tuesday',3 => 'Wednesday',4 => 'Thursday',5 => 'Friday',6 => 'Saturday'];
        foreach ($groups as $group){
            $data['firstname']=$group->individual->firstname;
            $data['subject']=setting('general.church_abbreviation') . ": " . $group->groupname . " - monthly email";
            $data['url']="https://westvillemethodist.co.za";
            $data['email']=$group->individual->email;
            $message="This is a monthly email from the church to say how much we appreciate your willingness to serve and invest in others through leading a small group at " . setting('general.church_abbreviation');
            $message.=". We believe in small groups and their power to change the world and we're grateful for the significant role that you are playing in your group - thank you!<br><br>";
            $message.="For your info, your group is currently listed on our website and in our app, with the following details:<br><br>";
            $message.="<b>Group name: </b>" . $group->groupname . "<br>";
            $message.="<b>Description: </b>" . $group->description . "<br>";
            $message.="<b>Group leader: </b>" . $group->individual->name . "<br>";
            $message.="<b>Meeting day: </b>";
            if ($group->meetingday){
                $message.=$days[$group->meetingday];
            }
            $message.="<br>";
            $message.="<b>Meeting time: </b>";
            if ($group->meetingtime){
                $message.=$group->meetingtime;
            }
            $message.="<br>";
            $message.="<b>Image: </b>";
            if ($group->image){
                $message.="Click <a href=\"" . url('/storage/' . $group->image) . "\">here</a> to view your current group image";
            } else {
                $message.="We don't have an image on file - please send us one if you can<br>";
            }
            $message.="<h4>Group members</h4><table class=\"table table-bordered\">";
            $message.="<tr><th>First name</th><th>Surname</th><th>Cellphone</th><th>Birthday</th>";
            foreach ($group->individuals->sortBy('firstname') as $indiv){
                $message.="<tr><td class=\"px-2\">" . $indiv->firstname . "</td><td class=\"px-2\">" . $indiv->surname . "</td><td class=\"px-2\">" . $indiv->cellphone . "</td>";
                if ($indiv->birthdate){
                    $message.="<td class=\"px-2\">" . date('j M',strtotime($indiv->birthdate)) . "</td></tr>";
                } else {
                    $message.="<td></td></tr>";
                }
            }
            $message.="</table><br>Please let us know if any names need to be added or removed or any group details updated. May God continue to bless and use you in this ministry!<br><br>";
            $data['body']=$message;
            Mail::to($data['email'])->queue(new ChurchMail($data));
        }
        Log::info('Groups email sent on ' . date('Y-m-d H:i'));
    }
}
