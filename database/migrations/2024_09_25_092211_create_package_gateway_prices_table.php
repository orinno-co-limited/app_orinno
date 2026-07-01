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
        Schema::create('package_gateway_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gateway_id');
            $table->unsignedBigInteger('gateway_currency_id');
            $table->unsignedBigInteger('package_id');
            $table->string('gateway');
            $table->string('monthly_price_id');
            $table->string('yearly_price_id');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('package_gateway_prices');
    }
};
