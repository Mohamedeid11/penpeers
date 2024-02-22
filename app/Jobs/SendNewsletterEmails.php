<?php

namespace App\Jobs;

use App\Events\TracingEmail;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    public $timeout = 7200;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscription = Subscription::get(['email' , 'name']);
        $content = $this->data['content'];
        $subject = $this->data['subject'];

        foreach($subscription as $subscribe){
            event(new TracingEmail( null ,  null , null , $subject));

            Mail::send('mail.newsletter-email', ['content' => $content , 'name' => $subscribe->name], function($message) use ($subscribe, $subject){
                $message->to($subscribe->email)
                    ->subject($subject);
            });

        }
    }
}
