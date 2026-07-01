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
        Schema::table('tenant_details', function (Blueprint $table) {
            $table->string('previous_country_id')->change();
            $table->string('previous_state_id')->change();
            $table->string('previous_city_id')->change();
            $table->string('permanent_country_id')->change();
            $table->string('permanent_state_id')->change();
            $table->string('permanent_city_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenant_details', function (Blueprint $table) {
            //
        });
    }
};
