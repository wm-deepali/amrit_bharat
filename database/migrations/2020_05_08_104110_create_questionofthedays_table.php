<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionofthedaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionofthedays', function (Blueprint $table) {
            $table->id();
            $table->longText('question');
            $table->longText('option1');
            $table->integer('option1vote')->default(0);
            $table->longText('option2');
            $table->integer('option2vote')->default(0);
            $table->longText('option3')->nullable()->default(Null);
            $table->integer('option3vote')->default(0);
            $table->longText('option4')->nullable()->default(Null);
            $table->integer('option4vote')->default(0);
            $table->longText('option5')->nullable()->default(Null);
            $table->integer('option5vote')->default(0);
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
        Schema::dropIfExists('questionofthedays');
    }
}
