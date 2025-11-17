<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['post_id','name','email','contact','content','status','parent_id','type'];

    public function replies(){
        return $this->hasMany('App\Comment', 'parent_id', 'id')->where('status','active');
    }

    public function childrenCategories()
    {
        return $this->hasMany('App\Comment', 'parent_id', 'id')->with('replies')->where('status','active');
    }
      
    public function parent(){
        return $this->hasOne('App\Comment', 'id', 'parent_id');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

}