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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('job');
            $table->unsignedBigInteger('image_id')->nullable();
            $table->integer('age')->nullable();
            $table->integer('family_member');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->tinyInteger('rent_type')->default(RENT_TYPE_MONTHLY);
            $table->tinyInteger('due_date')->nullable();
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $table->decimal('general_rent')->default(0);
            $table->decimal('security_deposit')->default(0);
            $table->decimal('late_fee')->default(0);
            $table->decimal('incident_receipt')->default(0);
            $table->tinyInteger('status')->default(TENANT_STATUS_DRAFT);
            $table->decimal('close_refund_amount')->default(0);
            $table->decimal('close_charge')->default(0);
            $table->date('close_date')->nullable();
            $table->string('close_reason')->nullable();
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
        Schema::dropIfExists('tenants');
    }
};
