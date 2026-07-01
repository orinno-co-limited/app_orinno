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
        Schema::create('invoice_recurring_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('property_unit_id');
            $table->dateTime('start_date')->default(now());
            $table->tinyInteger('recurring_type')->default(INVOICE_RECURRING_TYPE_MONTHLY);
            $table->integer('cycle_day')->nullable();
            $table->integer('due_day_after')->default(0);
            $table->string('invoice_prefix')->nullable();
            $table->decimal('amount')->default(0);
            $table->tinyInteger('status')->default(0)->comment('0=deactivate,1=active');
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
        Schema::dropIfExists('invoice_recurring_settings');
    }
};
