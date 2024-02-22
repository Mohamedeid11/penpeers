<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\Book;
use App\Models\BookEdition;
use App\Models\BookSpecialChapter;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformLeadAuthorSpecialChapterCompleted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Book $book, BookEdition $edition, BookSpecialChapter $specialChapter, User $user, User $lead_author)
    {
        $this->book = $book;
        $this->edition = $edition;
        $this->specialChapter = $specialChapter;
        $this->user = $user;
        $this->lead_author  = $lead_author ;
        $this->subject = 'Special Chapter Completed';
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

        $chapter_name = $this->specialChapter->special_chapter->name;

        $co_author_name =  $this->user->name;


        $book_link = route('web.dashboard.books.editions.edition_settings', ['edition' => 1 , 'book' => $this->book->id]);
        $co_author_link = route('web.author_books', ['author' => $this->user->id, 'type'=> 'all_books']);

        $chapter_link = route('web.dashboard.books.editions.special_chapters.index', ['book' => $this->book->id , 'edition' => 1 , 'special_chapter' => $this->specialChapter->special_chapter->id]);

        event(new TracingEmail( $this->user->id ,  $this->lead_author->id , $this->book->id , $this->subject));

        return $this->view('mail.inform_lead_author_special_chapter_completed')->with(
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
