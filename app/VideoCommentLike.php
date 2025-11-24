<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoCommentLike extends Model
{
    protected $fillable = ['comment_id', 'user_id', 'type'];

    public function comment()
    {
        return $this->belongsTo(VideoComment::class, 'comment_id');
    }
}
