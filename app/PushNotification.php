<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class PushNotification extends Model
{
    //
    protected $table= "push_notificatons";
    protected $fillable = ['is_all_user	', 'user_id', 'title', 'message', 'type'];

    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    
}