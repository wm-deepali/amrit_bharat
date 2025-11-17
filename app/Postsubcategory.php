<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postsubcategory extends Model
{
    protected $fillable=['post_id','subcategory_id'];

    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory');
    }
}
