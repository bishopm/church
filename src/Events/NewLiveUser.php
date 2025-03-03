<?php

namespace Bishopm\Church\Events;

use Bishopm\Church\Models\Individual;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLiveUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;

    /**
     * Create a new event instance.
     */
    public function __construct($id)
    {
        $individual = Individual::find($id);
        $this->message = $individual->firstname . " " . $individual->surname . " has logged in. Welcome to the service, " . $individual->firstname . "!";
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
