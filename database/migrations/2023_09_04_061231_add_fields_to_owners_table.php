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
        Schema::table('owners', function (Blueprint $table) {
            $table->string('print_name')->nullable();
            $table->string('print_address')->nullable();
            $table->string('print_contact')->nullable();
            $table->string('logo_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->dropColumn('print_name');
            $table->dropColumn('print_address');
            $table->dropColumn('print_contact');
            $table->dropColumn('logo_id');
        });
    }
};
