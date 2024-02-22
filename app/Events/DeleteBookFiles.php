<?php

namespace App\Events;

use App\Models\Book;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeleteBookFiles
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $book;
    public $type;
    public $chapter;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Book $book , $type, $chapter = null)
    {
        $this->book = $book;
        $this->type = $type;
        $this->chapter = $chapter;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('general');
    }
}
