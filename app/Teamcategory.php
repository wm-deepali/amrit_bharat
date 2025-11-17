<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teamcategory extends Model
{
    protected $fillable=['name','status'];
    
    public  function teams()
    {
        return $this->hasMany('App\Team')->where('status', 'active');
    }
}
