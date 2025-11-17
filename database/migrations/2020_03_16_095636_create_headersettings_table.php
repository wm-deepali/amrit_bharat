<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadersettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headersettings', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable()->default(Null);
            $table->string('link')->nullable()->default(Null);
            $table->string('title')->nullable()->default(Null);
            $table->string('datetimeformat');
            $table->string('facebook')->nullable()->default(Null);
            $table->string('twitter')->nullable()->default(Null);
            $table->string('youtube')->nullable()->default(Null);
            $table->string('instagram')->nullable()->default(Null);
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
        Schema::dropIfExists('headersettings');
    }
}
