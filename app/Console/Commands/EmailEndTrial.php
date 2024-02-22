<?php

namespace App\Console\Commands;

use App\Mail\EmailUserEndTrial;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class EmailEndTrial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:EndTrial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email the user before the trial days end';

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
        $users = User::all();


        foreach ($users as $user) {

            $now = Carbon::now();
            $diff = $user->created_at ? $user->created_at->diffInDays($now) : 0;
            $trial_period = Setting::where(['name' => 'trial_days'])->first()->value;
            $trial = $trial_period - $diff ;


            if ($user->isInTrial() || $trial == -1) {

                if ($trial == 7 || $trial == 1 || $trial == -1) {
//                    echo $user->name . "-" . $trial . "       ";

                    Mail::to($user->email)->send(new EmailUserEndTrial($user));
                    $this->info('Email sent successfully');

                }
            }

        }


    }
}
