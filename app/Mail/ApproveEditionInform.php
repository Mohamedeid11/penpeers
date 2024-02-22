<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\Book;
use App\Models\BookEdition;
use App\Models\BookPublishRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveEditionInform extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $bookPublishRequest;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $book
     */
    public function __construct(BookPublishRequest $bookPublishRequest)
    {
        $this->bookPublishRequest = $bookPublishRequest;
        $this->subject = 'Edition Approved';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = User::find($this->bookPublishRequest->user_id);
        $name = $user ? $user->name : '';

        $book = Book::find($this->bookPublishRequest->book_id);
        $book_title = $book ? $book->title : '';

        $edtion = BookEdition::find($this->bookPublishRequest->book_edition_id);
        $edition_number = $edtion ? $edtion->edition_number : '';
        event(new TracingEmail(null, $user->id , $book->id , $this->subject));

        return $this->view('mail.approve_edition_inform')->with(
            [
                'name' => $name,
                'book_title' => $book_title,
                'edition_number' => ordinal($edition_number) . ' Edition'
            ]
        );
    }
}
