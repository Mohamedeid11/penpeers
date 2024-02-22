<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class informLeadAuthorInvitationRejected extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Book $book , $user)
    {
        //
        $this->book = $book;
        $this->user = $user;
        $this->subject = "Invitation Rejected";

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if( $this->user['type'] == "registred" ) {

            $participant_link = route('web.author_books', ['author' => $this->user['data']->id, 'type'=> 'all_books']);
            $participant_id = $this->user['data']->id;
            $participant_name = $this->user['data']->name;
            $participant_email = $this->user['data']->email;

        } else {

            $participant_link = "#";
            $participant_id = null;
            $participant_name = $this->user['name'];
            $participant_email = $this->user['email'];

        }

        $name = $this->book->lead_author->name ;

        $book_title = $this->book->title;

        $book_link = route('web.dashboard.books.editions.edition_settings', ['edition' => 1 , 'book' => $this->book->id]);
        $author_link =  route('web.author_books', ['author' => $this->book->lead_author->id, 'type'=> 'all_books']);
        event(new TracingEmail( $participant_id ,   $this->book->lead_author->id , $this->book->id , $this->subject));

        return $this->view('mail.inform_lead_author_invitation_rejected')->with([
            'name' => $name,
            'book_title' => $book_title ,
            'book_link' => $book_link ,
            'author_link' => $author_link,
            'participant_link' => $participant_link,
            'participant_name' => $participant_name,
            'participant_email' => $participant_email
        ]);
    }
}
