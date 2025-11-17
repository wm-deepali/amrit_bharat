<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepagewidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homepagewidgets', function (Blueprint $table) {
            $table->id();
            $table->string('cataloguecategory');
            $table->string('catalogueposttype');
            $table->string('categorytab1');
            $table->string('categorytab1posttype');
            $table->string('categorytab2');
            $table->string('categorytab2posttype');
            $table->string('categorytab3');
            $table->string('categorytab3posttype');
            $table->string('categorytab4');
            $table->string('categorytab4posttype');
            $table->string('slidercategory');
            $table->string('sliderposttype');
            $table->string('trendingcategory');
            $table->string('trendingposttype');
            $table->string('otherwidgetcategory');
            $table->string('otherwidgetposttype');
            $table->string('mustreadcategory');
            $table->string('mustreadposttype');
            $table->string('youmaylikecategory');
            $table->string('youmaylikeposttype');
            $table->string('sidebartab1category');
            $table->string('sidebartab1posttype');
            $table->string('sidebartab2category');
            $table->string('sidebartab2posttype');
            $table->string('sidebartab3category');
            $table->string('sidebartab3posttype');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homepagewidgets');
    }
}
