<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RealTimeNotification extends Notification
{
    use Queueable;
    public  $message;
    private  $url;
    private  $url_type;
//    private  $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message , $url, $url_type)
    {
        $this->message = $message;
        $this->url = $url;
        $this->url_type = $url_type;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\BroadcastMessage
     */
//    public function toBroadcast($notifiable): BroadcastMessage
//    {
//        return new BroadcastMessage([
//            'message' => "$this->message",
//            'url' => "$this->url",
//        ]);
//    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "$this->message",
            'url' => "$this->url",
            'url_type' => "$this->url_type",
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
