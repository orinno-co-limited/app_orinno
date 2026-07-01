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
        Schema::create('notice_boards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('details')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->tinyInteger('property_all')->default(DEACTIVATE);
            $table->tinyInteger('unit_all')->default(DEACTIVATE);
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
        Schema::dropIfExists('notice_boards');
    }
};
