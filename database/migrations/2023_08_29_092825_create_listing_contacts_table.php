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
        Schema::create('listing_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_user_id')->nullable();
            $table->unsignedBigInteger('listing_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('profession')->nullable();
            $table->text('details')->nullable();
            $table->text('reply')->nullable();
            $table->tinyInteger('status')->default(LISTING_CONTACT_STATUS_PENDING);
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
        Schema::dropIfExists('listing_contacts');
    }
};
