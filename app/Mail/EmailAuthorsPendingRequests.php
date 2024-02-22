<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\BookParticipationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailAuthorsPendingRequests extends Mailable
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
        $this->subject = "Reminder For Pending Join Requests";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $book = $this->book_request->book;
        $name = $this->book_request->name ? $this->book_request->name : $this->book_request->user->name;
        $book_author_name = $this->book_request->book->lead_author->name;

        $book_link = route('web.dashboard.books.editions.edition_settings', ['edition' => 1 , 'book' => $book->id]);
        $author_link = route('web.author_books', ['author' => $book->lead_author->id, 'type'=> 'all_books' ]);

        event(new TracingEmail(null , $this->book_request->book->lead_author->id , $book->id, $this->subject));

        return $this->view('mail.remind-author-pending-requests')->with(
            [
                'book_author_name' => $book_author_name,
                'book_name' => $book->title,
                'book_id' => $book->id,
                'name'  => $name,
                'book_link' => $book_link,
                'author_link' => $author_link,
            ]
        );
    }
}
