<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OnetoOneMsg implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $userID;
    public $message;
    public function __construct($userID, $message)
    {
        $this->userID = $userID;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
//            new PrivateChannel("privateChat.{$this->userID}"),
            new Channel("publicChat.{$this->userID}")
        ];
    }

    public function broadcastAs()
    {
        return 'private.message'; // Event name clients will receive
    }

    public function broadcastWith(): array
    {
        return [
            'userID' => $this->userID,
            'msg' => $this->message
        ];
    }
}
