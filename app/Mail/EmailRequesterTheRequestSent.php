<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\BookParticipationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailRequesterTheRequestSent extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(BookParticipationRequest $book_request)
    {
        $this->book_request = $book_request;
        $this->subject = "Participation Request Sent";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $book_name = $this->book_request->book->title;
        $slug = $this->book_request->book->slug;
        $lead_author = $this->book_request->book->lead_author->name;
        $lead_author_id = $this->book_request->book->lead_author->id;

        $name = $this->book_request->name ? $this->book_request->name : $this->book_request->user->name;

        $requester_id = $this->book_request->user->id ?? null ;

        event(new TracingEmail( $requester_id ,  $lead_author_id , $this->book_request->book->id, $this->subject));

        return $this->view('mail.inform_requster_request_sent')->with(
            [
                'book_name' => $book_name,
                'name'  => $name,
                'slug'  => $slug,
                'lead_author' => $lead_author,
                'lead_author_id' => $lead_author_id,

            ]
        );
    }
}
