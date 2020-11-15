<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Vote;

class VoteOpen implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Vote $vote;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Vote $vote)
    {
        $this->vote = $vote;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('open');
    }
}
