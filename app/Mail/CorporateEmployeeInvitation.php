<?php

namespace App\Mail;

use App\Events\TracingEmail;
use App\Models\CorporateEmployee;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class CorporateEmployeeInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $user;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $email
     */
    public function __construct(User $user, string $email)
    {
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = rawurldecode(route('web.get_register') . "?company=" . $this->user->id . "&hash=" . sha1($this->user->email) . "&email=" . $this->email);
        $company_username = $this->user->username;
        $company_name = $this->user->name;
        $sent_user_name = CorporateEmployee::where('email', $this->email)->first();
        $sent_user_name = $sent_user_name ? $sent_user_name->name : "";

        event(new TracingEmail($this->user->id, $sent_user_name->id , null , $this->subject));

        return $this->view('mail.corporate_email_invitation')->text("mail.corporate_email_invitation")->with(
            [
                'link' => $link,
                'company_name' => $company_name,
                'company_username' => $company_username,
                'name'  => $sent_user_name
            ]
        );
    }
}
