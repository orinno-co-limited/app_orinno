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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->tinyText('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->tinyInteger('price_duration_type')->default(1);
            $table->string('bed_room')->nullable();
            $table->string('bath_room')->nullable();
            $table->string('kitchen_room')->nullable();
            $table->string('dining_room')->nullable();
            $table->string('living_room')->nullable();
            $table->string('storage_room')->nullable();
            $table->text('other_room')->nullable();
            $table->string('interior')->nullable();
            $table->string('type')->nullable();
            $table->string('amenities')->nullable();
            $table->string('advantage')->nullable();
            $table->longText('details')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('listings');
    }
};
