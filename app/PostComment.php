<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class PostComment extends Model
{
    //
    protected $table= "post_comments";
    protected $fillable = ['id','user_id', 'post_id', 'comment', 'is_edit', 'total_likes', 'created_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    
    public function getReplyCountAttribute()
    {
        return (int)$this->commentreplies()->count();
    }
    public function commentreplies()
    {
        return $this->hasMany('App\CommentReply', 'comment_id', 'id')->with('user')->where('status', 'Approved');
    }


    public function commentlikes()
    {
        return $this->hasMany('App\CommentLike', 'comment_id', 'id');
    }


    protected static function booted () {
        static::deleting(function(PostComment $postcomment) { // before delete() method call this
             $postcomment->commentreplies()->delete();
             $postcomment->commentlikes()->delete();
             // do the rest of the cleanup...
        });
    }
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
