<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_sms')->default(true)->after('contact_number');
            $table->boolean('notify_whatsapp')->default(true)->after('notify_sms');
            $table->boolean('notify_email')->default(true)->after('notify_whatsapp');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_sms', 'notify_whatsapp', 'notify_email']);
        });
    }
};
