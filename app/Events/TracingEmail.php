<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TracingEmail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $sender_id;
    public $receiver_id;
    public $book_id;
    public $subject;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sender_id , $receiver_id , $book_id , $subject)
    {
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->book_id = $book_id;
        $this->subject = $subject;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
