<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\Book;
use App\Models\BookChapter;
use App\Models\BookEdition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformCoAuthorChapterNotCompleted extends Mailable
{
    use Queueable, SerializesModels;
    public $remarks_text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Book $book, BookEdition $edition, BookChapter $chapter, User $user, User $co_author ,$remarks_text)
    {
        $this->book = $book;
        $this->edition = $edition;
        $this->chapter = $chapter;
        $this->user = $user;
        $this->co_author  = $co_author ;
        $this->remarks_text  = $remarks_text ;
        $this->subject = 'Chapter Is Not Completed';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->co_author->name ;

        $book_title = $this->book->title;

        $chapter_name = $this->chapter->name;

        $lead_author_name =  $this->user->name;

        $remarks_text =  $this->remarks_text;

        $book_link = route('web.dashboard.books.editions.edition_settings', ['edition' => 1 , 'book' => $this->book->id]);
        $author_link = route('web.author_books', ['author' => $this->book->lead_author->id, 'type'=> 'all_books']);

        $chapter_link = route('web.dashboard.books.editions.chapters.index', [ 'book' => $this->book->id , 'edition' => 1 , 'chapter' => $this->chapter->id]);

        event(new TracingEmail( $this->user->id ,  $this->co_author->id , $this->book->id , $this->subject));

        return $this->view('mail.inform_co_author_chapter_not_completed')->with(
            [
                'name' => $name,
                'book_title' => $book_title,
                'chapter_name' => $chapter_name ,
                'lead_author_name' => $lead_author_name,
                'book_link' => $book_link,
                'author_link' => $author_link,
                'chapter_link' =>  $chapter_link,
                'remarks' =>  $remarks_text
            ]
        );
    }
}
