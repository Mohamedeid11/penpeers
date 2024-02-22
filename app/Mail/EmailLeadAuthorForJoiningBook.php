<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\BookParticipationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailLeadAuthorForJoiningBook extends Mailable
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
        $this->subject = "Participation Request";
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
        $button = route('web.dashboard.books.requests' , ['book' => $this->book_request->book->id]);

        $sender_name = $this->book_request->name ?? $this->book_request->user->name;
        $sender_id = $this->book_request->user_id ;
        if(auth()->check() )
        {
            $sender_link = route('web.author_books', ['author' => $sender_id , 'type' => 'all_books']);
            $sender = "<a href='$sender_link' style='color: #ce7852; text-decoration: underline'>$sender_name</a>";
        }else{
            $sender = "<strong>$sender_name</strong>";
        }

        event(new TracingEmail( $sender_id ,  $lead_author_id , $this->book_request->book_id , $this->subject));

        return $this->view('mail.inform_lead_author_for_requesting_to_join_book')->with(
            [
                'book_name' => $book_name,
                'sender'  => $sender,
                'slug'  => $slug,
                'lead_author_name' => $lead_author,
                'lead_author_id' => $lead_author_id,
                'button' => $button,
            ]
        );
    }
}
