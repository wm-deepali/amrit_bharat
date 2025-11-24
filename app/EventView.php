<?php

namespace App;

use App\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventView extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'ip_address',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
