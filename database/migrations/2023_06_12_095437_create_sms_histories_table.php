<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_user_id');
            $table->string('api')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('message')->nullable();
            $table->tinyInteger('status')->default(ACTIVE);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('error')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_histories');
    }
};
