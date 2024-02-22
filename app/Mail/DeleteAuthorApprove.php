<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\Book;
use App\Models\BookParticipant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeleteAuthorApprove extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Book $book, User $user, BookParticipant $author)
    {
        $this->user = $user;

        $this->book = $book;

        $this->author = $author;

        $this->subject = 'Deleting Author Request';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name =  $this->user->name;

        $author_name =  $this->author->user->name;

        $author_id = $this->author->user->id;

        $author_link = route('web.author_books', ['author' => $author_id, 'type'=> 'all_books']);

        $link = route('web.dashboard.books.authors.delete_author', ['book' => $this->book->id, 'author' => $author_id]);

        $button = "<a href='$link' style='color: #ce7852; text-decoration: underline'>here</a>";

        $text = "Please make sure you are logged-in, if you want to delete <a
            style='color: #ce7852; text-decoration: underline' href=$author_link><strong>$author_name</strong></a>, just
        click ${button}. <br>Note that deleting co-author will delete his/her chapters.";

        event(new TracingEmail($this->user->id , null ,  $this->book->id , $this->subject));

        return $this->view('mail.delete_author_approve')->with(
            [
                'name' => $name,
                'text' => $text,
                'button' => $button
            ]
        );
    }
}
