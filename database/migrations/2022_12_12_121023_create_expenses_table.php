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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('property_unit_id')->nullable();
            $table->unsignedBigInteger('expense_type_id')->nullable();
            $table->mediumText('description')->nullable();
            $table->decimal('total_amount')->default(0);
            $table->string('responsibilities')->comment('1=tenant, 2=owner');
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
        Schema::dropIfExists('expenses');
    }
};
