<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\Book;
use App\Models\BookEdition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeleteEditionApprove extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $book;

    public $edition;

    public $user;


    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Book $book
     * @param BookEdition $edition
     */
    public function __construct(Book $book, BookEdition $edition, User $user)
    {
        $this->book = $book;
        $this->edition = $edition;
        $this->user = $user;
        $this->subject = 'Delete Edition';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name =  $this->user->name;

        $edition_number = ordinal($this->edition->edition_number);

        $book_name = $this->book->title;

        $text = "Please make sure you are logged-in, if you want to delete the $edition_number edition from $book_name book, just click";

        $link = route('web.dashboard.books.editions.delete_edition', ['book' => $this->book->id, 'edition' => $this->edition->id]);

        $button = "<a href='$link' style='color: #ce7852; text-decoration: underline'>here.</a>";

        event(new TracingEmail($this->user->id , null , $this->book->id , $this->subject));

        return $this->view('mail.delete_edition_approve')->with(
            [
                'name' => $name,
                'text' => $text,
                'button' => $button
            ]
        );
    }
}
