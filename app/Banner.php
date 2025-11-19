<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'image',
        'url',
        'status',
    ];

    // Optional: define relation
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
