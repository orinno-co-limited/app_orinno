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
        Schema::create('owner_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id');
            $table->string('name');
            $table->integer('max_maintainer')->default(0);
            $table->integer('max_property')->default(0);
            $table->integer('max_unit')->default(0);
            $table->integer('max_tenant')->default(0);
            $table->integer('max_invoice')->default(0);
            $table->integer('max_auto_invoice')->default(0);
            $table->tinyInteger('ticket_support')->default(0);
            $table->tinyInteger('notice_support')->default(0);
            $table->decimal('monthly_price', 8, 2)->default(0);
            $table->decimal('yearly_price', 8, 2)->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->tinyInteger('status')->default(DEACTIVATE);
            $table->tinyInteger('is_trail')->default(DEACTIVATE)->comment('default for 1 , not default for 0');
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
        Schema::dropIfExists('owner_packages');
    }
};
