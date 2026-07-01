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
        Schema::create('listing_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_user_id')->nullable();
            $table->unsignedBigInteger('listing_id');
            $table->unsignedBigInteger('file_id');
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
        Schema::dropIfExists('listing_images');
    }
};
