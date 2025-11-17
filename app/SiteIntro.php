<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteIntro extends Model
{
    //
    protected $table= "site_intro";
    protected $fillable = ['id','heading', 'image', 'short_descriptions', 'created_at', 'updated_at'];
}
