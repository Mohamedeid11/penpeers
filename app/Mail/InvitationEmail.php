<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\BookParticipant;
use App\Models\EmailInvitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $bookAuthorName;
    private $book;
    private $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bookAuthorName, $book, $type, $email)
    {
        $this->bookAuthorName = $bookAuthorName;
        $this->book = $book;
        $this->type = $type;
        $this->email = $email;
        if($this->type == "byEmail"){

            $this->subject = 'Invitation to participate in a book with a FREE trial period on PenPeers Platform';

        } else {

            $this->subject = 'Invitation To Paticipate A Book';

        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $hashing_email = base64_encode($this->email);
        if($this->type == "byEmail"){
            //non registred user
            $record = EmailInvitation::where('email',$this->email)->firstOrFail();
            $name = $record->name;
            $receiver_id = null;
        }else{
            // registred user
            $user = User::where('email',$this->email)->firstOrFail();
            $name = $user->name;
            $receiver_id = $user->id;
            $record = BookParticipant::where(['user_id' => $user->id, 'book_id' => $this->book->id, 'book_role_id' => 2])->first();
        }
        $hashed_record_id = base64_encode($record ? $record->id : 0);

        $book_link = route('web.view_book', ['slug' => $this->book->slug ]);
        $author_link = route('web.author_books', ['author' => $this->book->lead_author->id, 'type'=> 'all_books']);

        event(new TracingEmail( $this->book->lead_author->id ,  $receiver_id , $this->book->id , $this->subject));

        return $this->markdown('mail/invitation-email')->with(
            [
                'bookAuthorName' => $this->bookAuthorName,
                'book' => $this->book,
                'type' => $this->type,
                'hashing_email' => $hashing_email,
                'name' => $name,
                'id' => $hashed_record_id,
                'book_link' => $book_link,
                'author_link' => $author_link,
            ]
        );
    }
}
