<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\BookParticipant;
use App\Models\EmailInvitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckUserCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $invitations = EmailInvitation::where(['email' => $event->email, 'status' => 0])->get();
        if($invitations->count()){
            foreach($invitations as $invitation){
                $invitation->update(['status' => 1]);

                BookParticipant::create([
                    'user_id' => $event->user_id,
                    'status' => false,
                    'book_id' => $invitation->book_id,
                    'book_role_id' => $invitation->book_role_id
                ]);
            }       
        }
    }
}
