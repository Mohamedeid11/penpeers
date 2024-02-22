<?php

namespace App\Mail;

use App\Events\TracingEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class EmailUserToRenewPlan extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $user;
    public $name;
    public $remaining;
    public $remaining_text = "days";
    public $subject = "Renew Plan";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->name = $user->name;
        $this->user = $user;
        $this->remaining = $user->plan->remaining;
        $this->remaining_text = ($this->remaining) > 1 ? "days" : "day";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        event(new TracingEmail(null , $this->user->id , null , $this->subject));

        return $this->view('mail.renew_plan')->with([
                'name' => $this->name,
                'remaining' => $this->remaining,
                'remaining_text' => $this->remaining_text
            ]);
    }
}
