<?php

namespace Bishopm\Church\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Queueable;

    public $email, $mailable;
    public $tries = 5;
    public $timeout = 90;

    /**
     * Create a new job instance.
     */
    public function __construct($email,$mailable)
    {
        $this->email = $email;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->email)->send($this->mailable);
        } catch (\Exception $e) {
            Log::error('Mail sending failed on attempt: ' . ($this->attempts() + 1), [
                'exception' => $e->getMessage(),
                'job' => $this,
            ]);
            throw $e; 
        }
    }
}
