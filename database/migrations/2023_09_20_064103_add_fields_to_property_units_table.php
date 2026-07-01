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
        Schema::table('property_units', function (Blueprint $table) {
            $table->tinyText('description')->nullable();
            $table->string('square_feet')->nullable();
            $table->string('amenities')->nullable();
            $table->string('parking')->nullable();
            $table->string('condition')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_units', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('square_feet');
            $table->dropColumn('amenities');
            $table->dropColumn('parking');
            $table->dropColumn('condition');
        });
    }
};
