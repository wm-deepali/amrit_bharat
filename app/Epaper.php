<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Epaper extends Model
{
    protected $fillable=['title','file','date','status'];
}
