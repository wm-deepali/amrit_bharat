<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Post extends Model
{
    protected $fillable=[
        'user_id',
        'postnumber',
        'title',
        'slug',
        'content',
        'video',
        'image',
        'imagetag',
        'metatitle',
        'metadescription',
        'metakeyword',
        'views',
        'approvedby_id',
        'status',
        'likes',
        'user_delete_status',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User')->where('delete_status','0');
    }

    public function approvedby()
    {
        return $this->belongsTo('App\User','approvedby_id');
    }

    public function categories()
    {
        return $this->hasMany('App\Postcategory');
    }

    public function getCategoryIds()
    {
        return $this->categories()->pluck('category_id')->toArray();
    }

    public function subcategories()
    {
        return $this->hasMany('App\Postsubcategory');
    }

    public function tagData()
    {
    return $this->belongsToMany('App\Tag', 'posttags', 'post_id', 'tag_id');
    }

    public function tags()
    {
        return $this->hasMany('App\Posttag');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment')->whereNull('parent_id')->where('status','Approved');
    }

    public function postcomments()
    {
        return $this->hasMany('App\PostComment')->with('user')->where('status','Approved');
    }

    public function reply()
    {
        return $this->hasManyThrough('App\CommentReply', 'App\PostComment', 'post_id', 'comment_id', 'id', 'id');
    }
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
