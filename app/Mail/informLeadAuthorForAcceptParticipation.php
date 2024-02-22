<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\BookParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class informLeadAuthorForAcceptParticipation extends Mailable
{
    use Queueable, SerializesModels;
    private $bookParticipant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bookParticipant)
    {
        $this->bookParticipant = $bookParticipant;
        $this->subject = 'Book Participation Accepted';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $with_email = "";

        $book_title = $this->bookParticipant->book->title;
        $lead_author = $this->bookParticipant->book->lead_author->name;
        $name = $this->bookParticipant->name ?? $this->bookParticipant->user->name;
        $slug = $this->bookParticipant->book->slug;

        $route = route('web.author_books', ['author' => $this->bookParticipant->user->id, 'type'=> 'all_books']);
        $user_link = "Mr. <a style='color: #ce7852; text-decoration: underline'
            href=\"$route\"><strong> $name </strong></a>";


        $book_link = route('web.view_book', ['slug' => $slug]);

        event(new TracingEmail( $this->bookParticipant->user->id, $this->bookParticipant->book->lead_author->id ,$this->bookParticipant->book->id , $this->subject));

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
