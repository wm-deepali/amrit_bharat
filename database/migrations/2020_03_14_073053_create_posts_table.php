<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('postnumber')->nullable()->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content')->nullable()->default(Null);
            $table->string('video')->nullable()->default(Null);
            $table->string('image')->nullable()->default(Null);
            $table->string('imagetag')->nullable()->default(Null);
            $table->string('metatitle')->nullable()->default(Null);
            $table->text('metadescription')->nullable()->default(Null);
            $table->text('metakeyword')->nullable()->default(Null);
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('approvedby_id')->nullable()->default(Null);
            $table->enum('status',['published','draft','pending'])->default('draft');
            $table->timestamps();
            $table->foreign('approvedby_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
