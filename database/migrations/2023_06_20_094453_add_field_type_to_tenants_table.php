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
        Schema::table('tenants', function (Blueprint $table) {
            $table->tinyInteger('security_deposit_type')->default(0)->after('security_deposit');
            $table->tinyInteger('late_fee_type')->default(0)->after('late_fee');
        });
        Schema::table('property_units', function (Blueprint $table) {
            $table->tinyInteger('security_deposit_type')->default(0)->after('security_deposit');
            $table->tinyInteger('late_fee_type')->default(0)->after('late_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('security_deposit_type');
            $table->dropColumn('late_fee_type');
        });
        Schema::table('property_units', function (Blueprint $table) {
            $table->dropColumn('security_deposit_type');
            $table->dropColumn('late_fee_type');
        });
    }
};
