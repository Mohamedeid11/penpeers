<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\Book;
use App\Models\BookChapter;
use App\Models\BookEdition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformLeadAuthorChapterCompleted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Book $book, BookEdition $edition, BookChapter $chapter, User $user, User $lead_author )
    {
        $this->book = $book;
        $this->edition = $edition;
        $this->chapter = $chapter;
        $this->user = $user;
        $this->lead_author  = $lead_author ;
        $this->subject = 'Chapter Completed';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->lead_author->name ;

        $book_title = $this->book->title;

        $chapter_name = $this->chapter->name;

        $co_author_name =  $this->user->name;

        $book_link = route('web.dashboard.books.editions.edition_settings', ['edition' => 1 , 'book' => $this->book->id]);
        // $book_link = route('web.view_book', ['slug' => $this->book->slug ]);
        // $author_link = route('web.author_books', ['author' => $this->book->lead_author->id ]);
        $co_author_link = route('web.author_books', ['author' => $this->user->id, 'type'=> 'all_books']);

        $chapter_link = route('web.dashboard.books.editions.chapters.index', ['book' => $this->book->id , 'edition' => 1 , 'chapter' => $this->chapter->id]);

        event(new TracingEmail( $this->user->id ,  $this->lead_author->id , $this->book->id , $this->subject));

        return $this->view('mail.inform_lead_author_chapter_completed')->with(
            [
                'name' => $name,
                'book_title' => $book_title,
                'chapter_name' => $chapter_name ,
                'co_author_name' => $co_author_name,
                'book_link' => $book_link,
                'co_author_link' => $co_author_link,
                'chapter_link' =>  $chapter_link
            ]
        );
    }
}
