<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_bookmark_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->tinyInteger('likes')->default(1); // 1 = liked, 0 = unliked
            $table->timestamps();

            // foreign keys
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            // prevent duplicate entries
            $table->unique(['user_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_bookmark_likes');
    }
};
