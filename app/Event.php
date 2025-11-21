<?php

namespace App;

use App\City;
use App\State;
use App\EventCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'short_content',
        'description',
        'start_datetime',
        'end_datetime',
        'venue',
        'state_id',
        'city_id',
        'type',
        'price',
        'status',
        'images',
        'default_image',
    ];

    protected $casts = [
        // Laravel will cast array → JSON automatically
        'images' => 'array',
    ];

    // Append custom attributes to API response
    protected $appends = [
        'image_urls',
        'default_image_url',
    ];

    /*----------------------------------------------------------
    | RELATIONSHIPS
    ----------------------------------------------------------*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    /*----------------------------------------------------------
    | ACCESSOR: Full URLs for all images
    ----------------------------------------------------------*/
    public function getImageUrlsAttribute()
    {
        // If images is null or empty → return []
        if (!$this->images) {
            return [];
        }

        // If images is not an array → decode it
        $images = is_array($this->images)
            ? $this->images
            : json_decode($this->images, true);

        // If decode fails → return []
        if (!is_array($images)) {
            return [];
        }

        // Map URLs
        return collect($images)->map(function ($img) {
            return url('uploads/events/' . $img);
        })->toArray();
    }

    /*----------------------------------------------------------
    | ACCESSOR: Full URL of default image
    ----------------------------------------------------------*/
    public function getDefaultImageUrlAttribute()
    {
        if (!$this->default_image) {
            return null;
        }

        return asset('uploads/events/' . $this->default_image);
    }
}
