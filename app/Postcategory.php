<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postcategory extends Model
{
    protected $fillable=['post_id','category_id'];

    public function category()
    {
        return $this->belongsTo('App\Category')->where('status', 'active');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
