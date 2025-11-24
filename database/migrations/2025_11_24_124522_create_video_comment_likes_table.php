<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('video_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('video_comments')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('type')->default(0); // 1 = like, -1 = dislike, 0 = none
            $table->timestamps();

            $table->unique(['comment_id', 'user_id']); // one reaction per user per comment
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_comment_likes');
    }
};
