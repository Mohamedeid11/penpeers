<?php

namespace App\Mail;

use App\Events\TracingEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class EmailUserEndTrial extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $user;
    public $name;
    public $remaining;
    public $remaining_text = "days";
    public $subject = "Create a Plan";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->remaining =  $user->isInTrial(True);

        $this->remaining_text = ($this->remaining) > 1 ? "days" : "day";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        event(new TracingEmail(null, $this->user->id , null , $this->subject));

        return $this->view('mail.email_user_for_end_trial')->with(
            [
                'name' => $this->name,
                'remaining' => $this->remaining,
                'remaining_text' => $this->remaining_text
            ]
        );
    }
}
