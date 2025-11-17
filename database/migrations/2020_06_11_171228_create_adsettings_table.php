<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adsettings', function (Blueprint $table) {
            $table->id();
            $table->string('homepageuppersidebar300x250time');
            $table->string('homepageuppersidebar300x250number');
            $table->string('homepagemiddlesidebar300x250time');
            $table->string('homepagemiddlesidebar300x250number');
            $table->string('homepagelowersidebar300x250time');
            $table->string('homepagelowersidebar300x250number');
            $table->string('homepageuppersidebar300x600time');
            $table->string('homepageuppersidebar300x600number');
            $table->string('homepagemiddlesidebar300x600time');
            $table->string('homepagemiddlesidebar300x600number');
            $table->string('homepagelowersidebar300x600time');
            $table->string('homepagelowersidebar300x600number');
            $table->string('homepageupperbanner728x90time');
            $table->string('homepageupperbanner728x90number');
            $table->string('homepagemiddlebanner728x90time');
            $table->string('homepagemiddlebanner728x90number');
            $table->string('homepagelowerbanner728x90time');
            $table->string('homepagelowerbanner728x90number');
            $table->string('categorypagesidebar300x250time');
            $table->string('categorypagesidebar300x250number');
            $table->string('categorypagesidebar300x600time');
            $table->string('categorypagesidebar300x600number');
            $table->string('postpageuppersidebar300x250time');
            $table->string('postpageuppersidebar300x250number');
            $table->string('postpagelowersidebar300x250time');
            $table->string('postpagelowersidebar300x250number');
            $table->string('postpagesidebar300x600time');
            $table->string('postpagesidebar300x600number');
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
        Schema::dropIfExists('adsettings');
    }
}
