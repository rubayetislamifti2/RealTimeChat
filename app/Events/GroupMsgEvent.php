<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupMsgEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $group;
    public $userID;
    public $msg;
    public function __construct($group, $userID, $msg)
    {
        $this->group = $group;
        $this->userID = $userID;
        $this->msg = $msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
//            new PrivateChannel('channel-name'),
        new Channel("chat.{$this->group}")
        ];
    }

    public function broadcastAs()
    {
        return 'group.message'; // Event name clients will receive
    }

    public function broadcastWith(): array
    {
        return [
            'group' => $this->group,
            'userID' => $this->userID,
            'msg' => $this->msg
        ];
    }
}
