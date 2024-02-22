<?php

namespace App\Listeners;

use App\Models\TracingEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SentEmailTracing
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        TracingEmail::create([
            'subject' => $event->subject,
            'book_id' => $event->book_id,
            'sender_id' => $event->sender_id,
            'receiver_id' => $event->receiver_id,
        ]);
    }
}
