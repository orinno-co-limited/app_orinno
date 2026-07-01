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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('txn_id', 100)->unique();
            $table->string('payment_method', 100);
            $table->string('currency', 100);
            $table->longText('payment_details')->nullable();
            $table->dateTime('payment_time')->nullable();
            $table->enum('status', ['initiate', 'pending', 'completed', 'cancelled']);
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
        Schema::dropIfExists('transactions');
    }
};
