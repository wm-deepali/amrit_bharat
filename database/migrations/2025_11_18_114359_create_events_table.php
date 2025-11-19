<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('slug',191)->unique();
            $table->string('short_content', 140);
            $table->text('description');

            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');

            $table->string('venue');

            // Foreign Keys
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('city_id');

            $table->enum('type', ['free', 'paid']);
            $table->decimal('price', 10, 2)->nullable();

            $table->enum('status', ['pending', 'published', 'rejected']);

            $table->json('images')->nullable();
            $table->string('default_image')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
