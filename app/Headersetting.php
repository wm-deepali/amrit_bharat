<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Headersetting extends Model
{
    protected $fillable=['id','image','link','title','datetimeformat','facebook','twitter','youtube','instagram'];
}
