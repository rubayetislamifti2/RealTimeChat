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
    public $chat_room;
    public $userId;
    public $message;
    public function __construct($chat_room, $userId, $message)
    {
        $this->chat_room = $chat_room;
        $this->userId = $userId;
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
            new Channel("publicChat.{$this->chat_room}")
        ];
    }

    public function broadcastAs()
    {
        return 'private.message';
    }

    public function broadcastWith(): array
    {
        return [
            'chat_room' => $this->chat_room,
            'from' => $this->userId,
            'msg' => $this->message
        ];
    }
}
