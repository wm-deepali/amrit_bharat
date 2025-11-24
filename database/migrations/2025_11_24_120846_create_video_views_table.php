<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('video_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained('videos')->onDelete('cascade');
            $table->string('ip_address', 45); // IPv4/IPv6
            $table->timestamps();

            $table->unique(['video_id', 'ip_address']); // ensures one view per IP per video
        });
    }

    public function down()
    {
        Schema::dropIfExists('video_views');
    }
};
