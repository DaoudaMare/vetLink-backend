<?php

namespace App\Events;
namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Message;

class NewMessageSent implements ShouldBroadcast
{
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load(['sender', 'product']);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->message->conversation_id);
    }

    public function broadcastAs()
    {
        return 'message.new';
    }
}
