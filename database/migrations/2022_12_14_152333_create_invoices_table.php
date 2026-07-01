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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('property_unit_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('name');
            $table->string('invoice_no')->unique();
            $table->string('month');
            $table->date('due_date');
            $table->decimal('amount')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=paid,2=overdue');
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
        Schema::dropIfExists('invoices');
    }
};
