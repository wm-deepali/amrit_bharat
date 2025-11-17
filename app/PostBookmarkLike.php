<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class PostBookmarkLike extends Model
{
    //
    protected $table= "post_bookmark_likes";
    protected $fillable = ['id', 'user_id', 'post_id', 'bookmark', 'likes',  'created_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
