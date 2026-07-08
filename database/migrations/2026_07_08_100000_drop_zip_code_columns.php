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
        Schema::table('property_details', function (Blueprint $table) {
            $table->dropColumn('zip_code');
        });

        Schema::table('tenant_details', function (Blueprint $table) {
            $table->dropColumn(['previous_zip_code', 'permanent_zip_code']);
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('zip_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_details', function (Blueprint $table) {
            $table->string('zip_code')->nullable();
        });

        Schema::table('tenant_details', function (Blueprint $table) {
            $table->string('previous_zip_code')->nullable();
            $table->string('permanent_zip_code')->nullable();
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->string('zip_code')->nullable();
        });
    }
};
