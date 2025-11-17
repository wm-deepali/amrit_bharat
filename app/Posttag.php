<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posttag extends Model
{
    protected $fillable=['post_id','tag_id'];

    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }
}
