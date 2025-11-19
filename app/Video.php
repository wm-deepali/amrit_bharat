<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'short_description',
        'youtube_link',
        'detail_content',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
