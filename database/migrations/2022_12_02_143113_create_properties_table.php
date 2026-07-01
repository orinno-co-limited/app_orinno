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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('property_type')->comment('1=Own, 2=Lease');
            $table->string('name');
            $table->integer('number_of_unit');
            $table->text('description')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->tinyInteger('unit_type')->comment('1=Single, 2=Multiple')->nullable();
            $table->tinyInteger('status')->comment('1=Active, 2=Inactive, 3=Cancelled, 4=Draft')->default(4)->nullable();
            $table->unsignedBigInteger('thumbnail_image_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('properties');
    }
};
