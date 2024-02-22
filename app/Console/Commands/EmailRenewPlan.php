<?php

namespace App\Console\Commands;

use App\Mail\EmailUserToRenewPlan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailRenewPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:RenewPlanUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email user before his plan expiry';

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
        // return Command::SUCCESS;
        $users = User::all();

        foreach ($users as $user) {
            $remaining_days = $user->plan ? $user->plan->remaining : 0;
            // echo  $user->username . " : " . $remaining_days . "-----";
            if ($user->can_renew && $remaining_days < 15) {
                // Enable to send Email
                if ($remaining_days == 14) {
                    Mail::to($user->email)->send(new EmailUserToRenewPlan($user));
                } else if ($remaining_days == 7) {
                    Mail::to($user->email)->send(new EmailUserToRenewPlan($user));
                } else if ($remaining_days == 1) {
                    Mail::to($user->email)->send(new EmailUserToRenewPlan($user));
                }
            }
        }

        $this->info('Email sent successfully');
    }
}
