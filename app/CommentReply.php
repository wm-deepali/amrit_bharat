<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class CommentReply extends Model
{
    //
    protected $table= "comment_replies";
    protected $fillable = ['id', 'user_id', 'comment_id', 'reply', 'total_likes', 'created_at'];

    public function comment()
    {
        return $this->belongsTo('App\PostComment');
    }

    public function reply()
    {
        return $this->belongsTo('App\ReplyLike');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function replylike()
    {
        return $this->hasMany('App\ReplyLike');
    }

    protected static function booted () {
        static::deleting(function(CommentReply $commentreply) { // before delete() method call this
             $commentreply->replylike()->delete();
             // do the rest of the cleanup...
        });
    }

    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    
}
