<?php

namespace Bishopm\Church\Console\Commands;

use Bishopm\Church\Mail\ChurchHtmlMail;
use Illuminate\Console\Command;
use Bishopm\Church\Models\Maintenancetask;
use Bishopm\Church\Models\Group;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MaintenanceEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:maintenanceemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a weekly maintenance email';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('Preparing maintenance email on ' . date('Y-m-d H:i'));
        $lastweek = date('Y-m-d',strtotime('7 days ago'));
        $donetasks = Maintenancetask::with('individual','venue')->where('completed_at','>',$lastweek)->orderBy('created_at','ASC')->get();
        $tasks = Maintenancetask::with('individual','venue')->orderBy('created_at','ASC')->whereNull('completed_at')->get();
        $message = "The following jobs were completed over the last week:<br>";
        foreach ($donetasks as $dtask){
            $message.="<br>" . substr($dtask->created_at,0,10) . " " . $dtask->details;
            if ($dtask->venue){
                $message.= " (" . $dtask->venue->venue . ")";
            }
            if ($dtask->individual){
                $message.= " (" . $dtask->individual->firstname . " " . $dtask->individual->surname . ")";
            }
        }
        $message .= "<br><br>Here is the current list of outstanding maintenance jobs:<br>";
        foreach ($tasks as $task){
            $message.="<br>" . substr($task->created_at,0,10) . " " . $task->details;
            if ($task->venue){
                $message.= " (" . $task->venue->venue . ")";
            }
            if ($task->individual){
                $message.= " (" . $task->individual->firstname . " " . $task->individual->surname . ")";
            }
        }
        $message.="<br><br>Thank you!";

        // Send to maintenance group
        $setting=intval(setting('automation.maintenance_group'));
        $churchname=setting('general.church_name');
        $churchemail=setting('general.church_email');
        $group=Group::with('individuals')->find($setting);
        foreach ($group->individuals as $recip) {
            $data=array();
            $data['firstname']=$recip->firstname;
            $data['subject']="Maintenance: " . $churchname . " (as at " . date('d M Y') . ")";
            $data['sender']=$churchemail;
            $data['url']="https://westvillemethodist.co.za";
            $data['body']=$message;
            $data['email']=$recip->email;
            Mail::to($data['email'])->queue(new ChurchHtmlMail($data));
        }
        Log::info('Maintenance email sent on ' . date('Y-m-d H:i'));
    }
}