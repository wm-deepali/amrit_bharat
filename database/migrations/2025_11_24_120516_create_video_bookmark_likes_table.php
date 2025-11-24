<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('video_bookmark_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('video_id')->index();
            $table->tinyInteger('likes')->default(1); // 1 = liked, 0 = unliked
            $table->timestamps();

            // Optional: foreign keys if you want
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');

            // Prevent duplicate likes per user per video
            $table->unique(['user_id', 'video_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('video_bookmark_likes');
    }
};
