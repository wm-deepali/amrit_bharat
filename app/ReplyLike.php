<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ReplyLike extends Model
{
    //
    protected $table= "reply_likes";
    protected $fillable = ['id', 'user_id', 'reply_id', 'likes', 'created_at'];

    public function commentreply()
    {
        return $this->belongsTo('App\CommentReply');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }

protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    
}
