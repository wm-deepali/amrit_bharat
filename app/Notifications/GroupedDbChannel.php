<?php
namespace App\Notifications;


use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\User;
use DateTimeInterface;

class GroupedDbChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toDatabase($notifiable);
        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,
            'user_id' => Auth::user()->id,
            'profile_pic' => $data['image'], 
            'msg_text' => $data['title'],
            'notifiable_type'=> Auth::user()->id,
            'type' => get_class($notification),
            'data' => $data,
            'read_at' => null,
        ]);
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}