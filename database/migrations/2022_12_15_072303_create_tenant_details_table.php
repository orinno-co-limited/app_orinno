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
        Schema::create('tenant_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('previous_address')->nullable();
            $table->unsignedBigInteger('previous_country_id')->nullable();
            $table->unsignedBigInteger('previous_state_id')->nullable();
            $table->unsignedBigInteger('previous_city_id')->nullable();
            $table->string('previous_zip_code')->nullable();
            $table->string('permanent_address')->nullable();
            $table->unsignedBigInteger('permanent_country_id')->nullable();
            $table->unsignedBigInteger('permanent_state_id')->nullable();
            $table->unsignedBigInteger('permanent_city_id')->nullable();
            $table->string('permanent_zip_code')->nullable();
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
        Schema::dropIfExists('tenant_details');
    }
};
