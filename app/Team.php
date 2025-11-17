<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable=['teamcategory_id','name','designation','image','state_id','city_id','status'];

    public function state()
    {
        return $this->belongsTo('App\State');
    }
    
    public function city()
    {
        return $this->belongsTo('App\City');
    }
    
    public function teamcategory()
    {
        return $this->belongsTo('App\Teamcategory')->where('status', 'active');
    }
}
