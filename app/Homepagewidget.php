<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homepagewidget extends Model
{
    protected $fillable=['id','cataloguecategory','catalogueposttype','categorytab1','categorytab1posttype','categorytab2','categorytab2posttype','categorytab3','categorytab3posttype','categorytab4','categorytab4posttype','slidercategory','sliderposttype','trendingcategory','trendingposttype','centercategorytab','videocategorytab','otherwidgetcategory','otherwidgetposttype','mustreadcategory','mustreadposttype','youmaylikecategory','youmaylikeposttype','lowercategorytab','sidebartab1category','sidebartab1posttype','sidebartab2category','sidebartab2posttype','sidebartab3category','sidebartab3posttype'];

    public function centercategories()
    {
        return $this->hasMany('App\Homepagewidgetcentercategory');
    }

    public function videocategories()
    {
        return $this->hasMany('App\Homepagewidgetvideocategory');
    }

    public function lowercategories()
    {
        return $this->hasMany('App\Homepagewidgetlowercategory');
    }

}
