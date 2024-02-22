<?php

namespace App\Mail;


namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\Book;
use App\Models\BookEdition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class DeleteMyAccountApprove extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $book;


    public $user;


    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct( User $user)
    {
        $this->user = $user;
        $this->subject = 'Deleting My Account Request';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->user->name;

        $user = $this->user;

        $hashed_user_id = base64_encode($user ? $this->user->id : 0);

        $account_link = route('web.dashboard.account.edit.show');

        $link = route('web.dashboard.account.deleteAccount' , ['hashing_id'=> $hashed_user_id]);

        $button = "<a href='$link' style='color: #ce7852; text-decoration: underline'>here</a>.";

        $text = "Please make sure you are logged-in ,</br> if you want to delete <a
            style='color: #ce7852; text-decoration: underline' href=$account_link><strong>$name</strong></a> <br> just
        click";

        event(new TracingEmail($this->user->id , null , null, $this->subject));

        return $this->view('mail/delete_account_approve')->with(
            [
                'name' => $name,
                'button' => $button,
                'text' => $text,
            ]
        );
    }
}
