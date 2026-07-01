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
        Schema::create('subscription_orders', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->tinyInteger('duration_type')->default(1);
            $table->float('amount')->default(0)->nullable();
            $table->float('tax_amount')->nullable();
            $table->float('tax_percentage')->nullable();
            $table->string('system_currency')->nullable();
            $table->unsignedBigInteger('gateway_id');
            $table->string('gateway_currency')->nullable();
            $table->float('conversion_rate')->default(1)->nullable();
            $table->float('subtotal')->default(0);
            $table->float('total')->default(0)->nullable();
            $table->float('transaction_amount')->default(0)->nullable();
            $table->tinyInteger('payment_status')->default(0)->comment('0=pending, 1=paid, 2=cancelled')->nullable();
            $table->tinyInteger('bank_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('deposit_by')->nullable();
            $table->unsignedBigInteger('deposit_slip_id')->nullable();
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
        Schema::dropIfExists('subscription_orders');
    }
};
