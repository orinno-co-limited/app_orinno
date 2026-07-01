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
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('unit_name');
            $table->integer('bedroom');
            $table->integer('bath');
            $table->integer('kitchen');
            $table->decimal('general_rent')->default(0)->nullable();
            $table->decimal('security_deposit')->default(0)->nullable();
            $table->decimal('late_fee')->default(0)->nullable();
            $table->decimal('incident_receipt')->default(0)->nullable();
            $table->tinyInteger('rent_type')->comment('1=monthly,2=yearly,3=custom')->nullable();
            $table->integer('monthly_due_day')->nullable();
            $table->integer('yearly_due_day')->nullable();
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $table->date('lease_payment_due_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_units');
    }
};
