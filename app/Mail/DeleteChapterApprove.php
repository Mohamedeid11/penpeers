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

class DeleteChapterApprove extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Book $book, User $user, BookChapter $bookChapter, BookEdition $edition)
    {
        $this->user = $user;

        $this->book = $book;

        $this->bookChapter = $bookChapter;

        $this->edition = $edition;

        $this->subject = 'Deleting Chapter Request';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name =  $this->user->name;

        $chapter_name = $this->bookChapter->name;

        $chapter_link = route('web.dashboard.books.editions.chapters.index', ['book' => $this->book->id ,'edition' => 1 , 'chapter' => $this->bookChapter->id]);

        $text = "Please make sure you're logged-in, if you want to delete <a
            style='color: #ce7852; text-decoration: underline' href=$chapter_link><strong>$chapter_name</strong></a>
            chapter,
        just click";

        $link = route('web.dashboard.books.editions.chapters.delete_chapter', ['book' => $this->book->id, 'edition' => 1, 'chapter' => $this->bookChapter->id]);

        $button = "<a href='$link' style='color: #ce7852; text-decoration: underline'>here</a> .";

        event(new TracingEmail($this->user->id , null , $this->book->id , $this->subject));

        return $this->view('mail.delete_chapter_approve')->with(
            [
                'name' => $name,
                'text' => $text,
                'button' => $button
            ]
        );
    }
}
