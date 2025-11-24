<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Video extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'short_description',
        'youtube_link',
        'detail_content',
        'status',
        'published_at', // added
        'views',        // added
    ];

    // Append custom attributes to API response
    protected $appends = [
        'total_likes',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(VideoBookmarkLike::class);
    }

    public function views()
    {
        return $this->hasMany(VideoView::class);
    }

    // Total likes (only where likes = 1)
    public function getTotalLikesAttribute()
    {
        return $this->likes()->where('likes', 1)->count();
    }

    // Total unique views (count distinct IPs)
    public function getViewsAttribute()
    {
        return $this->views()->distinct('ip_address')->count('ip_address');
    }

    public function comments()
    {
        return $this->hasMany(VideoComment::class)->with('user')->where('status', 'Approved'); // eager load user info
    }

}
