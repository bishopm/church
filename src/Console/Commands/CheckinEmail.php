<?php

namespace Bishopm\Church\Console\Commands;

use Bishopm\Church\Mail\ChurchMail;
use Bishopm\Church\Models\Attendance;
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
        $data=array();
        $data = [
            'never'  => [],
            'over_six_months' => [],
            'over_six_weeks'  => [],
        ];
        $sixWeeks  = now()->subWeeks(6);
        $sixMonths = now()->subMonths(6);
        $individuals = Individual::where(function ($q) { $q->whereNull('nametag_exclude')->orWhere('nametag_exclude', '!=', 1); })
            ->select('individuals.*')
            ->addSelect([
                'last_attended' => Attendance::selectRaw('MAX(attendancedate)')
                    ->whereColumn('individual_id', 'individuals.id')
            ])
            ->orderBy('surname', 'ASC')
            ->get();
        foreach ($individuals as $individual) {
            $last = $individual->last_attended;

            if (is_null($last)) {
                // 1. Never attended
                $data['never'][] = $individual;
            } elseif ($last < $sixMonths) {
                // 2. Attended, but not for over 6 months
                $data['over_six_months'][] = $individual;
            } elseif ($last < $sixWeeks) {
                // 3. Attended, but not in the last 6 weeks (but within 6 months)
                $data['over_six_weeks'][] = $individual;
            }
        }
        $message="The following people have never used a nametag at a service:\n\n";
        foreach ($data['never'] as $never){
            $message.=$never->firstname . " " . $never->surname . ". Cellphone: " . $never->cellphone . "<br>";
        }
        $message.="\n\n" . "The following people have not used a nametag at a service for the last six months:\n\n";
        foreach ($data['over_six_months'] as $attended){
            $message.=$attended->firstname . " " . $attended->surname . ". Cellphone: " . $attended->cellphone . " (Last seen: " . $attended->lastseen . ")<br>";
        }
        $message.="\n\n" . "The following people have used a nametag in the last six months, but not in the last six weeks :\n\n";
        foreach ($data['over_six_weeks'] as $away){
            $message.=$away->firstname . " " . $away->surname . ". Cellphone: " . $away->cellphone . " (Last seen: " . $away->lastseen . ")<br>";
        }
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
            $data['body']=$message;
            $data['email']=$recip->email;
            if ($data['email']=='michael@westvillemethodist.co.za') {
                //Mail::to($data['email'])->queue(new ChurchMail($data));
                Mail::to($data['email'])->send(new ChurchMail($data));
            }
        }
        Log::info('Check in email sent on ' . date('Y-m-d H:i'));
    }
}
