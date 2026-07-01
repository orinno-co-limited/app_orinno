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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->integer('max_maintainer')->default(0);
            $table->integer('max_property')->default(0);
            $table->integer('max_unit')->default(0);
            $table->integer('max_tenant')->default(0);
            $table->integer('max_invoice')->default(0);
            $table->integer('max_auto_invoice')->default(0);
            $table->tinyInteger('ticket_support')->default(0);
            $table->tinyInteger('notice_support')->default(0);
            $table->decimal('monthly_price', 8, 2)->default(0);
            $table->decimal('yearly_price', 8, 2)->default(0);
            $table->tinyInteger('status')->default(DEACTIVATE)->comment('active for 1 , deactivate for 0');
            $table->tinyInteger('is_default')->default(DEACTIVATE)->comment('default for 1 , not default for 0');
            $table->tinyInteger('is_trail')->default(DEACTIVATE)->comment('default for 1 , not default for 0');
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
        Schema::dropIfExists('packages');
    }
};
