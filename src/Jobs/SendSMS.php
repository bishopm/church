<?php

namespace Bishopm\Church\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Bishopm\Church\Classes\BulksmsService;

class SendSMS implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $messages)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $smss = new BulksmsService(setting('services.bulksms_clientid'), setting('services.bulksms_api_secret'));
        $final=array();
        foreach ($this->messages as $no=>$msg) {
            $newno = "+27" . substr($no, 1);
            if ($smss->checkcell($no)) {
                $final[] = array('to' => $newno, 'body' => $msg);
            }
        }
        Log::info('Sending messages to ' . count($final) . ' recipients');
        $smss->send_message($final);
    }
}
