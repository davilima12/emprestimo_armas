<?php

declare(strict_types=1);
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TypingEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('typing-event');
    }

    public function broadcastAs()
    {
        return 'typing-event';
    }
}
