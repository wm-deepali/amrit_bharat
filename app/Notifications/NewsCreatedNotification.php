<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $title; 
    public function __construct($title)
    {
        //
        $this->title=$title;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        
        if(strlen($this->title) <= 40)
        {
            $title = $this->title;
        }
        else
        {
            $title = substr($this->title,0, 40) . '...';
        }
        
        $url = env('APP_URL').'storage/app/public/headersettings/logo.jpg';
        return [
            'image' => $url,
            'title' => 'PD News has posted new news " '.mb_convert_encoding($title, 'UTF-8', 'UTF-8').'"',
        ];
    }
}
