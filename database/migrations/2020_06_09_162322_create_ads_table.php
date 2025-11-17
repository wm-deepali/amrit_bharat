<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(Null);
            $table->string('contact')->nullable()->default(Null);
            $table->string('email')->nullable()->default(Null);
            $table->string('address')->nullable()->default(Null);
            $table->unsignedBigInteger('state_id')->nullable()->default(Null);
            $table->unsignedBigInteger('city_id')->nullable()->default(Null);
            $table->string('pincode')->nullable()->default(Null);
            $table->string('title');
            $table->string('type');
            $table->string('page');
            $table->string('position');
            $table->string('image')->nullable()->default(Null);
            $table->string('link')->nullable()->default(Null);
            $table->string('code')->nullable()->default(Null);
            $table->string('startdate');
            $table->string('enddate');
            $table->string('amount')->nullable()->default(Null);
            $table->string('paymentmethod')->nullable()->default(Null);
            $table->string('remark')->nullable()->default(Null);
            $table->string('paymentdocument')->nullable()->default(Null);
            $table->string('collectedby')->nullable()->default(Null);
            $table->string('chequedate')->nullable()->default(Null);
            $table->string('chequenumber')->nullable()->default(Null);
            $table->string('bankname')->nullable()->default(Null);
            $table->string('bankbranch')->nullable()->default(Null);
            $table->string('utrnumber')->nullable()->default(Null);
            $table->string('utrdate')->nullable()->default(Null);
            $table->string('upiid')->nullable()->default(Null);
            $table->string('upidate')->nullable()->default(Null);
            $table->string('upireferencenumber')->nullable()->default(Null);
            $table->string('wallet')->nullable()->default(Null);
            $table->string('walletdate')->nullable()->default(Null);
            $table->string('walletreferencenumber')->nullable()->default(Null);
            $table->string('transactionnumber');
            $table->enum('paymentstatus',['success','failed'])->default('success');
            $table->enum('status',['active','block'])->default('block');
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
        Schema::dropIfExists('ads');
    }
}
