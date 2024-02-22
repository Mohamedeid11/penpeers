<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\Book;
use App\Models\BookChapter;
use App\Models\BookEdition;
use App\Models\BookSpecialChapter;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformCoAuthorSpecialChapterNotCompleted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Book $book, BookEdition $edition, BookSpecialChapter $chapter, User $user, User $co_author )
    {
        $this->book = $book;
        $this->edition = $edition;
        $this->chapter = $chapter;
        $this->user = $user;
        $this->co_author  = $co_author ;
        $this->subject = 'Special Chapter Is Not Completed';
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

        $chapter_name = $this->chapter->special_chapter->name;

        $lead_author_name =  $this->user->name;

        $book_link = route('web.dashboard.books.editions.edition_settings', ['edition' => 1 , 'book' => $this->book->id]);
        $author_link = route('web.author_books', ['author' => $this->book->lead_author->id, 'type'=> 'all_books']);

        $chapter_link = route('web.dashboard.books.editions.special_chapters.index', ['book' => $this->book->id ,'edition' => 1 , 'special_chapter' => $this->chapter->special_chapter->id]);

        event(new TracingEmail( $this->user->id ,  $this->co_author->id , $this->book->id , $this->subject));

        return $this->view('mail.inform_co_author_special_chapter_not_completed')->with(
            [
                'name' => $name,
                'book_title' => $book_title,
                'chapter_name' => $chapter_name ,
                'lead_author_name' => $lead_author_name,
                'book_link' => $book_link,
                'author_link' => $author_link,
                'chapter_link' =>  $chapter_link
            ]
        );
    }
}
