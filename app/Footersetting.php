<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Footersetting extends Model
{
    protected $fillable = ['id','image','link','content','facebook','twitter','rss'];
}
