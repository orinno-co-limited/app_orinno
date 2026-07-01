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
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(DEACTIVATE)->comment('1=Active,0=Disable');
            $table->tinyInteger('mode')->default(GATEWAY_MODE_SANDBOX)->comment('1=live,2=sandbox');
            $table->string('url')->nullable();
            $table->string('key')->nullable()->comment('client id, public key, key, store id, api key');
            $table->string('secret')->nullable()->comment('client secret, secret, store password, auth token');
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
        Schema::dropIfExists('gateways');
    }
};
