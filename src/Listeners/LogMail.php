<?php
 
namespace Bishopm\Church\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;

class LogMail
{
    public function __construct() {}
 
    public function handle(MessageSending $event): void
    {
        Log::info('MJB: ' . $event);
    }
}