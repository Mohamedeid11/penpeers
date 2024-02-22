<?php

namespace App\Console\Commands;

use App\Mail\EmailAuthorsPendingRequests;
use App\Models\BookParticipationRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RemindAuthorsByTheRequestsPending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requests-pending:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind The Author With The Requests Pending';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $this->sendEmailToAuthor();
    }

    public function sendEmailToAuthor()
    {
        $requests = BookParticipationRequest::get()->where('days_diff' , '<=', 3);
        foreach($requests as $request)
        {
           $lead_author_email = $request->book->lead_author->email;
           Mail::to($lead_author_email)->send(new EmailAuthorsPendingRequests($request));
        }


    }
}
