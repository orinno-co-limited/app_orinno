<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_recurring_settings', function (Blueprint $table) {
            $table->tinyInteger('generation_day')->nullable()->after('cycle_day');
        });
    }

    public function down()
    {
        Schema::table('invoice_recurring_settings', function (Blueprint $table) {
            $table->dropColumn('generation_day');
        });
    }
};
