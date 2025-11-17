<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\User;

class NewsLikeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $title; 
    public $user_id; 
    public function __construct($user_id, $title)
    {
        //
        $this->user_id=$user_id;
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
        return [GroupedDbChannel::class,'broadcast']; 
        //return ['database'];
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

    public function toDatabase($notifiable): array
    {
        return $this->toArray($notifiable);
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
        $notifyUser = Auth::user();
        $user = User::findOrFail($this->user_id);
        if($user->id != $notifyUser->id)
        {
            if($notifyUser->image !='')
            {
                $url = env('APP_URL').'storage/app/public/'.$notifyUser->image;
            }
            else{
                $url = env('APP_URL').'storage/app/public/users/no-img.jpg';
            }
            
            return [
                'image' => $url,
                'title' => ucfirst($notifyUser->name).' likes your news " '.mb_convert_encoding($title, 'UTF-8', 'UTF-8').'"',
            ];
        }
    }
}
