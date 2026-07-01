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
        Schema::create('agreement_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->string('bulk_envelope_status')->nullable();
            $table->string('envelope_id')->nullable();
            $table->string('error_details')->nullable();
            $table->string('recipient_signing_uri')->nullable();
            $table->string('recipient_signing_uri_error')->nullable();
            $table->string('status')->nullable();
            $table->string('status_date_time')->nullable();
            $table->string('uri')->nullable();
            $table->tinyInteger('is_test')->default(DEACTIVATE);
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
        Schema::dropIfExists('agreement_histories');
    }
};
