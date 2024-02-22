<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailAuthorsBeforeDeadline extends Mailable
{
    use Queueable, SerializesModels;
    public $book_name;
    public $chapter_name;
    public $author_name;
    public $days_diff;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($book_name, $chapter_name, $author_name, $days_diff)
    {
        $this->book_name = $book_name;
        $this->chapter_name = $chapter_name;
        $this->author_name = $author_name;
        $this->days_diff = $days_diff;
        $this->subject = "Chapter Deadline Reminder";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $book_link = route('web.dashboard.books.editions.edition_settings', ['edition' => 1 , 'book' => $this->book_request->book->id]);
        // $book_link = route('web.view_book', ['slug' => $this->book_request->book->slug ]);
        // $author_link = route('web.author_books', ['author' => $this->book_request->book->lead_author->id, 'type'=> 'all_books' ]);
        $book_link ="";
        $author_link="";

        return $this->view('mail.remind-authors-of-chapter-deadline')->with(
            [
                'book_name' => $this->book_name,
                'chapter_name' => $this->chapter_name,
                'author_name' => $this->author_name,
                'days_diff' => $this->days_diff,
                'book_link' => $book_link,
                'author_link' => $author_link,
            ]
        );
    }
}
