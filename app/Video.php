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

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optional: if you want to return views in a formatted way
    public function getViewsAttribute($value)
    {
        return (int) $value;
    }
}
