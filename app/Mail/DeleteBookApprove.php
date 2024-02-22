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

class DeleteBookApprove extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $book;



    public $user;


    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Book $book
     * @param BookEdition $edition
     */
    public function __construct(Book $book,  User $user)
    {
        $this->book = $book;
        $this->user = $user;
        $this->subject = 'Deleting Book Request';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name =  $this->user->name;

        $book_name = $this->book->title;

        $book_link = route('web.dashboard.books.editions.edition_settings', ['edition' => 1 , 'book' => $this->book->id]);

        $link = route('web.dashboard.books.delete_book', ['book' => $this->book->id]);

        $button = "<a href='$link' style='color: #ce7852; text-decoration: underline'>here</a>.";

        $text = "Please make sure you are logged-in, if you want to delete <a
            style='color: #ce7852; text-decoration: underline' href=$book_link><strong>$book_name</strong></a>, just
            click";

        event(new TracingEmail($this->user->id , null , $this->book->id , $this->subject));

        return $this->view('mail.delete_book_approve')->with(
            [
                'name' => $name,
                'text' => $text,
                'button' => $button
            ]
        );
    }
}
