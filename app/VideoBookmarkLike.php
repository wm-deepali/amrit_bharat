<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoBookmarkLike extends Model
{
    use HasFactory;

    protected $table = 'video_bookmark_likes';

    protected $fillable = [
        'user_id',
        'video_id',
        'likes', // 1 = liked, 0 = removed
    ];

    /**
     * RELATIONSHIPS
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
