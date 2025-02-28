<?php

namespace Bishopm\Church\Console\Commands;

use Bishopm\Church\Mail\ChurchHtmlMail;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckinEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:checkinemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a weekly email listing people who have been absent without pastoral contact';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Preparing Check in email on ' . date('Y-m-d H:i'));
        $sixweeks=date('Y-m-d',strtotime('-6 weeks'));
        $data=array();
        $data['never']=array();
        $missings=Individual::with(['attendances'=> function($q) use ($sixweeks) { $q->where('attendancedate','<',$sixweeks)->orderBy('attendancedate');}])->orderBy('surname','ASC')->get();
        foreach ($missings as $missing){
            if (count($missing->attendances)>0){
                $data['attended'][]=$missing;
            } else {
                $data['never'][]=$missing;
            }
        }
        $message="The following people have never used a nametag at a service:<br>";
        foreach ($data['never'] as $never){
            $message.=$never->firstname . " " . $never->surname . ". Cellphone: " . $never->cellphone . "<br>";
        }
        $message.="<br>The following people have not used a nametag at a service since " . $sixweeks . ":<br>";
        foreach ($data['attended'] as $attended){
            $message.=$attended->firstname . " " . $attended->surname . ". Cellphone: " . $attended->cellphone . " (Last seen: " . $attended->lastseen . ")<br>";
        }
        dd($message);
        // Send to followup group
        $setting=intval(setting('automation.followup_group'));
        $churchname=setting('general.church_name');
        $churchemail=setting('email.church_email');
        $group=Group::with('individuals')->where('id',$setting)->first();
        foreach ($group->individuals as $recip) {
            $data=array();
            $data['firstname']=$recip->firstname;
            $data['subject']="Follow up email: " . $churchname;
            $data['url']="https://westvillemethodist.co.za";
            $data['sender']=$churchemail;
            $data['body']=$message;
            $data['email']=$recip->email;
            Mail::to($data['email'])->send(new ChurchHtmlMail($data));
        }
        Log::info('Check in email sent on ' . date('Y-m-d H:i'));
    }
}
