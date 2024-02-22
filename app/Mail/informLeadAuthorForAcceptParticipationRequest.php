<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\BookParticipationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class informLeadAuthorForAcceptParticipationRequest extends Mailable
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
        $this->subject = 'Book Participation Request Accepted';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $with_email = "";
        $book_title = $this->book_request->book->title;
        $lead_author = $this->book_request->book->lead_author->name;
        $name = $this->book_request->name ?? $this->book_request->user->name;
        $slug = $this->book_request->book->slug;
        $user_id = $this->book_request->user->id ?? null ;
        if (!$this->book_request->email) {

            $route = route('web.author_books', ['author' => $this->book_request->user->id, 'type'=> 'all_books']);
            $user_link = " Mr.<a style='color: #ce7852; text-decoration: underline'
                href=\"$route\"><strong> $name </strong></a>";
        }else{
            $user_link = "Mr.<strong> $name </strong></a>";
            $with_email = "with email " . $this->book_request->email;
        }

        $book_link = route('web.view_book', ['slug' => $slug]);

        event(new TracingEmail( $user_id ,  $this->book_request->book->lead_author->id , $this->book_request->book->id , $this->subject));

        return $this->view('mail.inform_lead_author_for_accept_participation')->with(
            [
                'lead_author' => $lead_author,
                'book_title' => $book_title,
                'book_link' => $book_link,
                'user_link' => $user_link,
                'with_email' => $with_email,
            ]
        );
    }
}
