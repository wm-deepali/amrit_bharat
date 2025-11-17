<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homepagewidgetcentercategory extends Model
{
    protected $fillable = ['homepagewidget_id','category_id'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
