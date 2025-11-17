<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name','slug','metatitle','metadescription','metakeyword','hassubcategory','showonheader','showonfooter','status'];

    public function posts()
    {
        return $this->belongsToMany('App\Post','postcategories')->where('status','published');
    }

    public function videoposts()
    {
        return $this->belongsToMany('App\Post','postcategories','category_id','post_id')->where('status','published')->whereNotNull('video')->orderBy('id','DESC')->take(12);
    }

    public function subcategories()
    {
        return $this->hasMany('App\Subcategory')->where('status', 'active');
    }
}
