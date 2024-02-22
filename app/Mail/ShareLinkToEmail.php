<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Http\Requests\Web\Share\ShareLinkToMailRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ShareLinkToEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $share_request;
    public $email_type;

    /**
     * Create a new message instance.
     *
     * @param User
     * @param void
     */
    public function __construct(ShareLinkToMailRequest $share_request, $email_type)
    {
        $this->share_request = $share_request;
        $this->email_type = $email_type;
        $this->subject = "Share $email_type Link";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (auth()->check()){

            $user = auth()->user();
            $sender_id = $user->id;

            $sender_name = "<a href='" . route('web.author_books', ['author'=> $user->id, 'type'=> 'all_books']) . "'
                style='color: #ce7852; text-decoration: underline'>" . $user->name . "</a>";
            $sender_email = "<strong>" . $user->email . "</strong>";

        } else {

            $sender_id = null;
            $sender_name = "<strong>" . $this->share_request->sender_name . "</strong>";
            $sender_email = "<strong>" . $this->share_request->sender_email . "</strong>";

        }

        $receiver_name = $this->share_request->receiver_name;

        $link = $this->share_request->link;

        $button = "<a href='$link' style='color: #ce7852; text-decoration: underline'>here</a>.";

        $text = "Mr.$sender_name with email $sender_email has shared a link with you, to view this $this->email_type please click ";

        event(new TracingEmail($sender_id , null , null , $this->subject));

        return $this->view('mail.share_link_to_email')->with(
            [
                'receiver_name' => $receiver_name,
                'button' => $button,
                'text' => $text,
            ]
        );
    }
}
