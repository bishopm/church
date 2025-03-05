<?php

namespace Bishopm\Church\Events;

use Bishopm\Church\Models\Individual;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class NewLiveMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public string $action;

    /**
     * Create a new event instance.
     */
    public function __construct($id, $message="")
    {
        $individual = Individual::find($id);
        if ($message==""){
            // Check that this is not just a page refresh
            $recent=strtotime('-90 minutes');
            $this->action = "login";
            if ((is_null($individual->online)) or (strtotime($individual->online)<$recent)){
                $this->message = $individual->firstname . " " . $individual->surname . " has logged in. Welcome to the service, " . $individual->firstname . "!";
                DB::table('messages')->insert([
                    'message' => $this->message
                ]);
                $individual->online=date('Y-m-d H:i:s');
                $individual->save();
            } else {
                $this->message="";
            }
            $this->action="login";
        } else {
            $this->message=$message;
            $this->action="message";
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('church-messages'),
        ];
    }
}
