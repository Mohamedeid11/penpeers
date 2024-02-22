<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\BookParticipationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookParticipationRequestAccepted extends Mailable
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
        $text = "";

        $book_title = $this->book_request->book->title;
        $lead_author = $this->book_request->book->lead_author->name;
        $name = $this->book_request->name ? $this->book_request->name : $this->book_request->user->name;
        $slug = $this->book_request->book->slug;

        if ($this->book_request->email) {
            $hashing_email = base64_encode($this->book_request->email);

            $route = route('web.get_register',['invitaion'=>'true','hashing'=>$hashing_email]);
            $text = "Please register first from <a style='color: #ce7852; text-decoration: underline'
                href=\"$route\">here</a>";
        }

        $book_link = route('web.view_book', ['slug' => $slug]);
        $author_link = route('web.author_books', ['author' => $this->book_request->book->lead_author->id, 'type'=> 'all_books']);

        event(new TracingEmail($this->book_request->book->lead_author->id, $this->book_request->user_id , $this->book_request->book->id , $this->subject));

        return $this->view('mail.book_participation_request_accepted')->with(
            [
                'lead_author' => $lead_author,
                'book_title' => $book_title,
                'co_author_name' => $name,
                'register_text'  => $text,
                'book_link' => $book_link,
                'author_link' => $author_link,
            ]
        );
    }
}
