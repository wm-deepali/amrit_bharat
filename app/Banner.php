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

    protected $appends = ['image_url']; // 👈 Add this

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 👇 Add accessor for full image URL
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // If image stored in "storage/app/public"
        return asset('storage/' . $this->image);

        // If stored directly in "public/uploads"
        // return asset('uploads/' . $this->image);
    }
}
