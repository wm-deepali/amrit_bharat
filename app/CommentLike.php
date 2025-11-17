<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class CommentLike extends Model
{
    //
    protected $table= "comment_likes";
    protected $fillable = ['id', 'user_id', 'comment_id', 'likes', 'created_at'];

    public function comment()
    {
        return $this->belongsTo('App\CommentLike');
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
