<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoComment extends Model
{
    protected $fillable = ['video_id', 'user_id', 'comment', 'total_likes', 'is_edit', 'status'];

    // The user who made the comment
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Reactions / likes for the comment
    public function reactions()
    {
        return $this->hasMany(VideoCommentLike::class, 'comment_id');
    }

    // The video to which this comment belongs
    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }
}
