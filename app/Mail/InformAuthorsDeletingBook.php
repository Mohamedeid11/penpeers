<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\Book;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class InformAuthorsDeletingBook extends Mailable
{
    use Queueable, SerializesModels;

    public $book;
    public $user;
    public $type;


    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Book $book
     */
    public function __construct(Book $book,  User $user , $type = null)
    {
        $this->book = $book;
        $this->user = $user;
        $this->type = $type;
        $this->subject = ($type == 'cancel_deleting_book') ? 'Restore the book' : 'Countdown to delete book';

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
        $type = $this->type;

        $book_link = route('web.dashboard.books.editions.edition_settings', ['edition' => 1 , 'book' => $this->book->id]);
        if($type == 'cancel_deleting_book' && $this->book->lead_author->name == $name )
        {
            $text = "You have restored the book <a style='color: #ce7852; text-decoration: underline' href=$book_link> <strong> $book_name </strong> </a>.";

        } elseif ($type == 'cancel_deleting_book'){

            $text = "Mr. " . $this->book->lead_author->name . " has restored the book <a style='color: #ce7852; text-decoration: underline' href=$book_link><strong>$book_name</strong></a>.";

        } else {

            $text = "Mr. " . $this->book->lead_author->name . " has set the book \"<a style='color: #ce7852; text-decoration: underline' href=$book_link><strong>$book_name</strong></a>\"
            to be deleted on " . Carbon::parse($this->book->deleted_at)->toFormattedDateString();
        }

        event(new TracingEmail($this->book->lead_author->id , $this->user->id , $this->book->id , $this->subject));

        return $this->view('mail.inform_authors_for_deleting_book')->with(
            [
                'name' => $name,
                'text' => $text,
            ]
        );
    }
}
