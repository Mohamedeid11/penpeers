<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\BookBuyRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailLeadAuthorForBuyingBook extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(BookBuyRequest $buy_request)
    {
        $this->buy_request = $buy_request;
        $this->subject = "Buying request";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $book_name = $this->buy_request->book->title;
        $slug = $this->buy_request->book->slug;
        $lead_author = $this->buy_request->book->lead_author->name;
        $lead_author_id = $this->buy_request->book->lead_author->id;
        $button = route('web.dashboard.buying_requests' );

        $sender_name = $this->buy_request->name ? $this->buy_request->name : $this->buy_request->user->name;
        $sender_id = auth()->check() ?  auth()->user()->id : null;
        if(auth()->check() )
        {

            $sender_link = route('web.author_books', ['author' => $sender_id , 'type' => 'all_books']);
            $sender = "<a href='$sender_link' style='color: #ce7852; text-decoration: underline'>$sender_name</a>";
        }else{
            $sender = "<strong>$sender_name</strong>";
        }
        event(new TracingEmail($sender_id , $lead_author_id , $this->buy_request->book->id , $this->subject));

        return $this->view('mail.inform_lead_author_for_requesting_to_buy_book')->with(
            [
                'book_name' => $book_name,
                'sender'  => $sender,
                'slug'  => $slug,
                'lead_author_name' => $lead_author,
                'lead_author_id' => $lead_author_id,
                'button' => $button,
            ]
        );

    }
}
