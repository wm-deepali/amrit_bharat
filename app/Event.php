<?php

namespace App;

use App\City;
use App\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'user_id',
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

    // Cast JSON fields
    protected $casts = [
        'images' => 'array',
    ];

    // Relationship: user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relationship: State
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    // Relationship: City
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
