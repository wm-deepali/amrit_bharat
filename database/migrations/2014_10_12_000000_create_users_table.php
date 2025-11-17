<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('contact')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('gender',['male','female'])->nullable()->default(Null);
            $table->text('address')->nullable()->default(Null);
            $table->unsignedBigInteger('state_id')->nullable()->default(Null);
            $table->unsignedBigInteger('city_id')->nullable()->default(Null);
            $table->string('image')->nullable()->default(Null);
            $table->string('cv')->nullable()->default(Null);
            $table->string('role')->default('admin');
            $table->string('permission')->nullable()->default(Null);
            $table->enum('status',['approved','pending'])->default('pending');
            $table->enum('added_by',['admin','frontend'])->default('admin');
            $table->string('user_number')->nullable()->default(Null);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
