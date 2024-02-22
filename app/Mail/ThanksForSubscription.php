<?php

namespace App\Mail;

use App\Events\TracingEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ThanksForSubscription extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->subject = 'Subscribed Successfully';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        event(new TracingEmail(null , null , null ,"Success Payment"));

        return $this->markdown('mail.thanks-for-subscription')->with(['name' => $this->name]);
    }
}
