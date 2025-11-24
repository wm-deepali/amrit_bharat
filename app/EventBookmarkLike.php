<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBookmarkLike extends Model
{
    use HasFactory;

    protected $table = 'event_bookmark_likes';

    protected $fillable = [
        'user_id',
        'event_id',
        'likes',
    ];
}

