<?php

namespace App\Providers;

use App\Events\DeleteBookFiles;
use App\Events\TracingEmail;
use App\Events\UserCreated;
use App\Events\GenerateBookPDFS;
use App\Listeners\CheckUserCreated;
use App\Listeners\DeleteBookPDFS;
use App\Listeners\SentEmailTracing;
use App\Listeners\DownloadBookPDFS;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserCreated::class => [
            CheckUserCreated::class
        ],
        TracingEmail::class => [
            SentEmailTracing::class
        ],
        GenerateBookPDFS::class => [
            DownloadBookPDFS::class,
        ],
        DeleteBookFiles::class => [
            DeleteBookPDFS::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
