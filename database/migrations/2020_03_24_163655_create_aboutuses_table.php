<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aboutuses', function (Blueprint $table) {
            $table->id();
            $table->text('content1')->nullable()->default(Null);
            $table->text('content2')->nullable()->default(Null);
            $table->text('address')->nullable()->default(Null);
            $table->string('contact1')->nullable()->default(Null);
            $table->string('contact2')->nullable()->default(Null);
            $table->string('map')->nullable()->default(Null);
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
        Schema::dropIfExists('aboutuses');
    }
}
