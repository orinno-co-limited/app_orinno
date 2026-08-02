<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sms_histories', function (Blueprint $table) {
            $table->string('channel')->default('sms')->after('phone_number');
        });
    }

    public function down()
    {
        Schema::table('sms_histories', function (Blueprint $table) {
            $table->dropColumn('channel');
        });
    }
};
