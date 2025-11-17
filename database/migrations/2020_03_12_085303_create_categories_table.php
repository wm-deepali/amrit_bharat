<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('metatitle');
            $table->string('metadescription');
            $table->string('metakeyword');
            $table->enum('hassubcategory',['yes','no'])->default('no');
            $table->enum('showonheader',['yes','no'])->default('no');
            $table->enum('showonfooter',['yes','no'])->default('no');
            $table->enum('status',['active','block'])->default('active');
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
        Schema::dropIfExists('categories');
    }
}
