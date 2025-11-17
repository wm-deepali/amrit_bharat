<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootersettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footersettings', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable()->default(Null);
            $table->string('link')->nullable()->default(Null);
            $table->text('content')->nullable()->default(Null);
            $table->string('facebook')->nullable()->default(Null);
            $table->string('twitter')->nullable()->default(Null);
            $table->string('rss')->nullable()->default(Null);
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
        Schema::dropIfExists('footersettings');
    }
}
