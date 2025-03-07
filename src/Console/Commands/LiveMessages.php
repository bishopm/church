<?php

namespace Bishopm\Church\Console\Commands;

use Bishopm\Church\Mail\ChurchHtmlMail;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LiveMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:livemessages';

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
        // Send to followup group
        $setting=intval(setting('worship.online_service_group'));
        $messages=Message::with('individual')->orderBy('messagetime','ASC')->get();
        if (count($messages)){
            $body="Here is a transcript of the chat from the service today: <table>";
            foreach ($messages as $message){
                if (isset($message->individual->firstname)){
                    $name = $message->individual->firstname . " " . $message->individual->surname;
                } else {
                    $name = setting('general.church_abbreviation');
                }
                $body.="<tr><td>" . $name . "</td><td>" . date('d M H:i',strtotime($message->messagetime)) . "</td><td>" . $message->message . "</td></tr>";
            }
            $churchname=setting('general.church_name');
            $churchemail=setting('email.church_email');
            $group=Group::with('individuals')->where('id',$setting)->first();
            foreach ($group->individuals as $recip) {
                $data=array();
                $data['firstname']=$recip->firstname;
                $data['subject']="Online service transcript: " . $churchname;
                $data['url']="https://westvillemethodist.co.za";
                $data['sender']=$churchemail;
                $data['body']=$body . "</table>";
                $data['email']=$recip->email;
                Mail::to($data['email'])->send(new ChurchHtmlMail($data));
                Log::info('Cleaning up live service messages as at: ' . date('Y-m-d H:i'));
                Message::truncate();
            }
        }
    }
}
